<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $user = auth()->user();

        $user->load([
            'resumes'
        ]);

        return view(
            'profile.index',
            compact('user')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Profile
    |--------------------------------------------------------------------------
    */

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
            ],
        ]);


        $user = auth()->user();

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);


        return back()->with(
            'success',
            'Profile updated successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Upload Resume
    |--------------------------------------------------------------------------
    */

    public function uploadResume(Request $request)
    {
        $request->validate([
            'resume' => [
                'required',
                'file',
                'mimes:pdf,doc,docx',
                'max:5120',
            ],
        ]);


        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | Store file
        |--------------------------------------------------------------------------
        */

        $file = $request->file('resume');

        $fileName =
            time() . '_' .
            $file->getClientOriginalName();


        $path = $file->storeAs(
            'resumes',
            $fileName,
            'public'
        );


        /*
        |--------------------------------------------------------------------------
        | Save database record
        |--------------------------------------------------------------------------
        */

        $user->resumes()->create([
            'file_name' =>
                $file->getClientOriginalName(),

            'file_path' =>
                $path,
        ]);


        return back()->with(
            'success',
            'Resume uploaded successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Resume
    |--------------------------------------------------------------------------
    */

    public function deleteResume(
        int $resume
    ) {

        $user = auth()->user();

        $resumeModel = $user->resumes()->findOrFail(
            $resume
        );


        /*
        |--------------------------------------------------------------------------
        | Delete physical file
        |--------------------------------------------------------------------------
        */

        if (
            Storage::disk('public')->exists(
                $resumeModel->file_path
            )
        ) {
            Storage::disk('public')->delete(
                $resumeModel->file_path
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Delete database record
        |--------------------------------------------------------------------------
        */

        $resumeModel->delete();


        return back()->with(
            'success',
            'Resume deleted successfully.'
        );
    }
}