<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Candidate Profile
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $user = auth()->user();
        $user->load(['resumes']);

        return view('profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
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

        return back()->with('success', 'Profile updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Admin Profile
    |--------------------------------------------------------------------------
    */

    public function adminProfile()
    {
        $user = auth()->user();
        $user->load(['employee']);

        return view('admin.profile.index', compact('user'));
    }

    public function adminUpdate(Request $request)
    {
        return $this->update($request);
    }

    /*
    |--------------------------------------------------------------------------
    | HR Profile
    |--------------------------------------------------------------------------
    */

    public function hrProfile()
    {
        $user = auth()->user();
        $user->load(['employee']);

        return view('hr.profile.index', compact('user'));
    }

    public function hrUpdate(Request $request)
    {
        return $this->update($request);
    }

    /*
    |--------------------------------------------------------------------------
    | TR Profile
    |--------------------------------------------------------------------------
    */

    public function trProfile()
    {
        $user = auth()->user();
        $user->load(['employee']);

        return view('tr.profile.index', compact('user'));
    }

    public function trUpdate(Request $request)
    {
        return $this->update($request);
    }

    /*
    |--------------------------------------------------------------------------
    | Upload & Delete Resume
    |--------------------------------------------------------------------------
    */

    public function uploadResume(Request $request)
    {
        $request->validate([
            'resume' => 'required|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $user = auth()->user();
        $file = $request->file('resume');

        $extension = $file->getClientOriginalExtension();
        $safeBase = preg_replace('/[^A-Za-z0-9_\-]/', '_', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $fileName = time() . '_' . $safeBase . '.' . $extension;

        $path = $file->storeAs('resumes', $fileName, 'public');

        $user->resumes()->create([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
        ]);

        return back()->with('success', 'Resume uploaded successfully.');
    }

    public function deleteResume(int $resume)
    {
        $user = auth()->user();
        $resumeModel = $user->resumes()->findOrFail($resume);

        if (Storage::disk('public')->exists($resumeModel->file_path)) {
            Storage::disk('public')->delete($resumeModel->file_path);
        }

        $resumeModel->delete();

        return back()->with('success', 'Resume deleted successfully.');
    }
}