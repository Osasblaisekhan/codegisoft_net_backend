<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private function isAdmin()
    {
        $user = Auth::user();
        return $user && $user->role === 'admin';
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!$this->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only admins can create users.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'password' => 'nullable|string|min:6',
            'role' => 'nullable|string',
            'avatar' => 'nullable',
            'phone' => 'nullable',
            'courses' => 'nullable',
            'department' => 'nullable|string|max:255',
            'status' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $userData = $request->all();
        
        if (isset($userData['password']) && !empty($userData['password'])) {
            $userData['password'] = Hash::make($userData['password']);
        } else {
            unset($userData['password']);
        }

        $user = User::create($userData);

        $message = "
            <div style='font-family: Arial, sans-serif; padding: 20px; background-color: #f9f9f9; border-radius: 8px;'>
                <h1 style='color: #4CAF50;'>🎉 Welcome Codegisoft-Net, {$user->name}! 🎉</h1>
                <p style='font-size: 16px;'>Your account has been created successfully.</p>
                <p style='font-size: 16px;'>You can log in to your account using the following credentials:</p>
                <p style='font-weight: bold;'>📧 <strong>Email:</strong> {$user->email}</p>
                <p style='font-weight: bold;'>🔑 <strong>Password:</strong> {$request->password}</p>
                <p style='font-weight: bold;'>👤 <strong>Role:</strong> {$user->role}</p>
                <p style='font-size: 16px; color: #555;'>Please remember to change your password after your first login for security.</p>
                <p style='font-size: 16px;'>Happy exploring! 🌟</p>
                <footer style='margin-top: 20px;'>
                    <p style='font-size: 14px; color: #777;'>Best regards,<br>Your Company Name</p>
                </footer>
            </div>
        ";
        NotificationService::send($user->email, $message, 'Account Created');

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!$this->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only admins can update users.'
            ], 403);
        }

        $user = User::find($id);
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'password' => 'nullable|string|min:6',
            'role' => 'nullable|string',
            'avatar' => 'nullable',
            'phone' => 'nullable',
            'courses' => 'nullable',
            'department' => 'nullable|string|max:255',
            'status' => 'nullable|string',
        ]);

        \Log::info('Update request data: ' . json_encode($request->all()));

        if ($validator->fails()) {
            \Log::info('Validation errors: ' . json_encode($validator->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $userData = $request->all();
        
        if (isset($userData['password']) && !empty($userData['password'])) {
            $userData['password'] = Hash::make($userData['password']);
        } else {
            unset($userData['password']);
        }

        $user->update(array_filter($userData, function($value) {
            return $value !== null && $value !== '';
        }));

        $message = "<h1>Your Account Has Been Updated</h1><p>Hello {$user->name}, your account information has been updated successfully.</p>";
        NotificationService::send($user->email, $message, 'Account Updated');

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => $user
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!$this->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only admins can delete users.'
            ], 403);
        }

        $user = User::find($id);
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $userEmail = $user->email;
        $userName = $user->name;
        $user->delete();

        $message = "<h1>Your Account Has Been Deleted</h1><p>Hello {$userName}, your account has been deleted successfully.</p>";
        NotificationService::send($userEmail, $message, 'Account Deleted');

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }
}
