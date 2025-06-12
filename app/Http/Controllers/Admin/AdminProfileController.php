<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AdminProfileController extends Controller
{
    public function update(Request $request)
    {
        try {
            /** @var \App\Models\Admin $admin */
            $admin = Auth::guard('admin')->user();

            if (!$admin) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Not authenticated'
                ], 401);
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:admin,email,' . $admin->admin_id . ',admin_id',
                'phone' => 'nullable|string|max:20',
                'username' => 'nullable|string|max:255', 
            ]);

           
            if (isset($validated['name'])) {
                $nameParts = explode(' ', $validated['name'], 2);
                $admin->f_name = $nameParts[0];
                $admin->l_name = isset($nameParts[1]) ? $nameParts[1] : '';
            }

            $admin->email = $validated['email'];
            
            if (isset($validated['phone'])) {
                $admin->phone = $validated['phone'];
            }

            $admin->save();

            return response()->json([
                'success' => true, 
                'message' => 'Profile updated successfully'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Admin profile update error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the profile. Please try again.'
            ], 500);
        }
    }
}