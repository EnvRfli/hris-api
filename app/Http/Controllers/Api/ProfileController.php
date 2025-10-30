<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EmployeeProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Get current user profile
     */
    public function show(Request $request)
    {
        $user = $request->user()->load([
            'employeeProfile.department',
            'employeeProfile.position',
            'employeeProfile.workShift',
            'employeeProfile.manager',
            'roles'
        ]);

        return response()->json([
            'user' => $user
        ]);
    }

    /**
     * Update current user profile
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            // User basic info
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            
            // Employee profile info (yang boleh user update sendiri)
            'phone' => ['nullable', 'string', 'max:20'],
            'birth_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:male,female'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'province' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            
            // Bank info
            'bank_name' => ['nullable', 'string', 'max:100'],
            'bank_account_number' => ['nullable', 'string', 'max:50'],
            'bank_account_name' => ['nullable', 'string', 'max:255'],
            
            // Emergency contact
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
            'emergency_contact_relation' => ['nullable', 'string', 'max:50'],
        ]);

        // Update user table (name, email)
        if (isset($validated['name']) || isset($validated['email'])) {
            $user->update([
                'name' => $validated['name'] ?? $user->name,
                'email' => $validated['email'] ?? $user->email,
            ]);
        }

        // Update employee profile
        $profileData = collect($validated)->except(['name', 'email'])->toArray();
        
        if (!empty($profileData)) {
            if ($user->employeeProfile) {
                $user->employeeProfile->update($profileData);
            } else {
                // Jika belum ada employee profile, buat baru
                EmployeeProfile::create(array_merge($profileData, [
                    'user_id' => $user->id,
                    'employee_id' => 'EMP' . str_pad($user->id, 6, '0', STR_PAD_LEFT), // Auto generate
                ]));
            }
        }

        // Reload with relationships
        $user->load([
            'employeeProfile.department',
            'employeeProfile.position',
            'employeeProfile.workShift',
            'employeeProfile.manager',
            'roles'
        ]);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $request->user();

        // Verify current password
        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect'
            ], 422);
        }

        // Update password
        $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        return response()->json([
            'message' => 'Password updated successfully'
        ]);
    }

    /**
     * Upload profile photo
     */
    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'], // Max 2MB
        ]);

        $user = $request->user();

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->employeeProfile && $user->employeeProfile->photo) {
                $oldPhotoPath = storage_path('app/public/' . $user->employeeProfile->photo);
                if (file_exists($oldPhotoPath)) {
                    unlink($oldPhotoPath);
                }
            }

            // Store new photo
            $path = $request->file('photo')->store('profile-photos', 'public');

            // Update employee profile
            if ($user->employeeProfile) {
                $user->employeeProfile->update(['photo' => $path]);
            } else {
                EmployeeProfile::create([
                    'user_id' => $user->id,
                    'employee_id' => 'EMP' . str_pad($user->id, 6, '0', STR_PAD_LEFT),
                    'photo' => $path,
                ]);
            }

            $user->load('employeeProfile');

            return response()->json([
                'message' => 'Photo uploaded successfully',
                'photo_url' => asset('storage/' . $path)
            ]);
        }

        return response()->json([
            'message' => 'No photo uploaded'
        ], 422);
    }
}
