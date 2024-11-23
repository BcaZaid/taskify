<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Models\Document;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Validate and fill user data
        $request->user()->fill($request->validated());

        // If the email is changed, mark it as not verified
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        // Save the user data
        $request->user()->save();

        // Redirect back to the profile edit page with a success message
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the user's profile picture.
     */
    public function updatePicture(Request $request)
    {
        // Validate the uploaded picture
        $request->validate([
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Ensure the image is within the size limit
        ]);

        $user = auth()->user();

        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if it exists and it's not the default image
            if ($user->profile_picture && !str_contains($user->profile_picture, 'images/default-profile.png')) {
                Storage::delete('public/' . $user->profile_picture); // Ensure the path is correct with 'public' disk prefix
            }

            // Store new profile picture
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');

            // Update user's profile picture path
            $user->profile_picture = $path;
        }

        // Save the updated user data
        $user->save();

        // Redirect back with a success message
        return redirect()->back()->with('profile_success', 'Profile picture updated successfully!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Validate the password
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Log out the user
        Auth::logout();

        // Delete the user account
        $user->delete();

        // Invalidate and regenerate session token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to the home page
        return Redirect::to('/');
    }

    /**
     * Update user's documents.
     */
    public function updateDocuments(Request $request)
    {
        // Validate the uploaded documents
        $request->validate([
            'documents.*' => 'required|file|mimes:jpeg,png,jpg,gif|max:2048', // Updated validation for images
        ]);

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                // Generate a unique file name and store the document
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('documents', $fileName, 'public');

                // Save document details in the database
                Document::create([
                    'user_id' => Auth::id(),
                    'file_path' => $filePath,
                ]);
            }
        }

        // Redirect back with a success message
        return redirect()->route('profile.edit')->with('success', 'Documents uploaded successfully!');
    }
    // ProfileController.php
    public function deletePicture($id)
    {
        $document = Document::findOrFail($id);
        if ($document->user_id === auth()->id()) {
            Storage::disk('public')->delete($document->file_path);
            $document->delete();
            return redirect()->route('profile.edit')->with('success', 'Image deleted successfully!');
        }
        return redirect()->route('profile.edit')->with('error', 'You are not authorized to delete this image.');
    }
}
