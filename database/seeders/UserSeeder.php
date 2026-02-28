<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'email' => 'alex.johnson@student.codegisoft.com',
                'password' => 'student123',
                'name' => 'Alex Johnson',
                'role' => 'student',
                'avatar' => 'https://images.pexels.com/photos/1239291/pexels-photo-1239291.jpeg?auto=compress&cs=tinysrgb&w=400',
                'phone' => '+237 650 123 456',
                'courses' => ['React Native', 'Full Stack Development'],
                'status' => 'active'
            ],
            [
                'email' => 'sarah.chen@student.codegisoft.com',
                'password' => 'student123',
                'name' => 'Sarah Chen',
                'role' => 'student',
                'avatar' => 'https://images.pexels.com/photos/774909/pexels-photo-774909.jpeg?auto=compress&cs=tinysrgb&w=400',
                'phone' => '+237 650 234 567',
                'courses' => ['React Native', 'UI/UX Design'],
                'status' => 'active'
            ],
            [
                'email' => 'mike.rodriguez@mentor.codegisoft.com',
                'password' => 'mentor123',
                'name' => 'Mike Rodriguez',
                'role' => 'mentor',
                'avatar' => 'https://images.pexels.com/photos/1222271/pexels-photo-1222271.jpeg?auto=compress&cs=tinysrgb&w=400',
                'phone' => '+237 670 345 678',
                'department' => 'Mobile Development',
                'courses' => ['React Native', 'Flutter', 'iOS Development'],
                'status' => 'active'
            ],
            [
                'email' => 'lisa.wang@mentor.codegisoft.com',
                'password' => 'mentor123',
                'name' => 'Lisa Wang',
                'role' => 'mentor',
                'avatar' => 'https://images.pexels.com/photos/1181519/pexels-photo-1181519.jpeg?auto=compress&cs=tinysrgb&w=400',
                'phone' => '+237 670 456 789',
                'department' => 'Full Stack Development',
                'courses' => ['React', 'Node.js', 'MongoDB'],
                'status' => 'active'
            ],
            [
                'email' => 'admin@codegisoft.com',
                'password' => 'admin123',
                'name' => 'David Administrator',
                'role' => 'admin',
                'avatar' => 'https://images.pexels.com/photos/1043471/pexels-photo-1043471.jpeg?auto=compress&cs=tinysrgb&w=400',
                'phone' => '+237 690 567 890',
                'department' => 'System Administration',
                'status' => 'active'
            ],
            [
                'email' => 'superadmin@codegisoft.com',
                'password' => 'superadmin123',
                'name' => 'Maria Super Admin',
                'role' => 'admin',
                'avatar' => 'https://images.pexels.com/photos/1065084/pexels-photo-1065084.jpeg?auto=compress&cs=tinysrgb&w=400',
                'phone' => '+237 690 678 901',
                'department' => 'System Administration',
                'status' => 'active'
            ]
        ];

        foreach ($users as $userData) {
            User::create([
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
                'name' => $userData['name'],
                'role' => $userData['role'],
                'avatar' => $userData['avatar'],
                'phone' => $userData['phone'],
                'courses' => $userData['courses'] ?? null,
                'department' => $userData['department'] ?? null,
                'status' => $userData['status'],
            ]);
        }
    }
}
