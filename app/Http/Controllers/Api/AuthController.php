<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'name' => 'required|string|max:255',
            'role' => 'required|in:student,mentor,admin',
            'avatar' => 'nullable|url',
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $userData = $request->all();
        $userData['password'] = Hash::make($userData['password']);
        $userData['status'] = $userData['status'] ?? 'active';

        $user = User::create($userData);

        $token = $user->createToken('auth-token')->plainTextToken;

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
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
            'role' => 'required|in:student,mentor,admin',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        if ($user->role !== $request->role) {
            return response()->json([
                'success' => false,
                'message' => 'Role mismatch'
            ], 403);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout successful'
        ]);
    }

    public function getUsers(Request $request)
    {
        $query = User::query();

        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->get()->makeHidden(['password', 'remember_token']);
        
        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }
}
