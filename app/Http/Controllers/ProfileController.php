<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

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
        $user = auth()->user();

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
                Rule::unique('users')->ignore($user->id),
            ],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
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
        | Store file safely
        |--------------------------------------------------------------------------
        */

        $file = $request->file('resume');

        $extension = $file->getClientOriginalExtension();
        $safeBase = preg_replace('/[^A-Za-z0-9_\-]/', '_', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $fileName = time() . '_' . $safeBase . '.' . $extension;

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