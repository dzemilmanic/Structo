<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

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
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        // Define all possible validation rules
        $rules = [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'specialization' => ['sometimes', 'nullable', 'string', 'max:255'],
            'bio' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:20'],
            'location' => ['sometimes', 'nullable', 'string', 'max:255'],
            'photo' => ['sometimes', 'nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];

        // Only validate the fields that are present in the request
        $fieldsToValidate = [];
        foreach ($rules as $field => $rule) {
            if ($request->has($field) || $request->hasFile($field)) {
                $fieldsToValidate[$field] = $rule;
            }
        }

        $validated = $request->validate($fieldsToValidate);

        // Handle photo upload to AWS S3
        if ($request->hasFile('photo')) {
            try {
                Log::info('Starting photo upload process');
                
                // Test S3 connection first
                try {
                    Storage::disk('profile_photos')->exists('test');
                    Log::info('S3 connection test successful');
                } catch (\Exception $e) {
                    Log::error('S3 connection failed: ' . $e->getMessage());
                    return Redirect::route('profile.edit')->with('error', 'S3 connection failed. Please check your AWS credentials.');
                }
                
                // Delete old photo from S3 if exists
                if ($user->photo) {
                    try {
                        $oldKey = $this->getPhotoKeyFromUrl($user->photo);
                        if ($oldKey) {
                            Storage::disk('profile_photos')->delete($oldKey);
                            Log::info('Old photo deleted: ' . $oldKey);
                        }
                    } catch (\Exception $e) {
                        Log::warning('Failed to delete old photo: ' . $e->getMessage());
                    }
                }
                
                // Generate unique filename
                $file = $request->file('photo');
                $filename = 'user_' . $user->id . '_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
                
                Log::info('Uploading file: ' . $filename);
                
                // Store new photo on S3
                $path = Storage::disk('profile_photos')->putFileAs('', $file, $filename);
                
                if ($path) {
                    // Get the full S3 URL
                    $photoUrl = Storage::disk('profile_photos')->url($path);
                    $user->photo = $photoUrl;
                    Log::info('Photo uploaded successfully: ' . $photoUrl);
                } else {
                    Log::error('Failed to upload photo - putFileAs returned false');
                    return Redirect::route('profile.edit')->with('error', 'Failed to upload photo to S3');
                }
                
            } catch (\Exception $e) {
                Log::error('S3 Photo Upload Error: ' . $e->getMessage());
                Log::error('Stack trace: ' . $e->getTraceAsString());
                return Redirect::route('profile.edit')->with('error', 'Failed to upload photo: ' . $e->getMessage());
            }
        }

        // Update each field individually to ensure it's saved
        foreach ($validated as $field => $value) {
            if ($field !== 'photo') { // Photo is handled above
                $user->$field = $value;
            }
        }

        // Handle email verification reset
        if (isset($validated['email']) && $user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Save the user
        $saved = $user->save();

        if ($saved) {
            return Redirect::route('profile.edit')->with('status', 'profile-updated');
        } else {
            return Redirect::route('profile.edit')->with('error', 'Failed to update profile');
        }
    }

    /**
     * Extract the S3 key from the full URL
     */
    private function getPhotoKeyFromUrl($url)
    {
        if (!$url) {
            return null;
        }
        
        // If it's a full S3 URL, extract the key
        if (str_contains($url, 'amazonaws.com') || str_contains($url, 's3.')) {
            $parts = parse_url($url);
            $path = ltrim($parts['path'], '/');
            
            // Remove bucket name from path if present (for path-style URLs)
            $bucketName = env('AWS_BUCKET');
            if (str_starts_with($path, $bucketName . '/')) {
                $path = substr($path, strlen($bucketName) + 1);
            }
            
            // Remove profile-photos prefix if present (since we set root in config)
            if (str_starts_with($path, 'profile-photos/')) {
                $path = substr($path, strlen('profile-photos/'));
            }
            
            return $path;
        }
        
        // If it's just a filename, return as is
        return basename($url);
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Delete user's photo from S3 if exists
        if ($user->photo) {
            try {
                $key = $this->getPhotoKeyFromUrl($user->photo);
                if ($key) {
                    Storage::disk('profile_photos')->delete($key);
                }
            } catch (\Exception $e) {
                Log::error('Failed to delete photo from S3: ' . $e->getMessage());
            }
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}