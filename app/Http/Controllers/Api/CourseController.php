<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    private function isAdmin()
    {
        $user = Auth::user();
        return $user && $user->role === 'admin';
    }

    private function isMentor()
    {
        $user = Auth::user();
        return $user && $user->role === 'mentor';
    }

    public function index()
    {
        $courses = Course::all();
        return response()->json([
            'success' => true,
            'data' => $courses
        ]);
    }

    public function store(Request $request)
    {
        if (!$this->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only admins can create courses.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'instructors' => 'nullable|array',
            'instructors.*' => 'integer|exists:users,id',
            'duration' => 'required|string|max:100',
            'level' => 'nullable|in:Beginner,Intermediate,Advanced',
            'price' => 'required|numeric|min:0',
            'rating' => 'nullable|numeric|min:0|max:5',
            'students' => 'nullable|array',
            'students.*' => 'integer|exists:users,id',
            'description' => 'required|string',
            'image' => 'nullable|url',
            'topics' => 'nullable|array',
            'topics.*' => 'string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $course = Course::create($request->all());

        $instructorEmails = User::whereIn('id', $request->input('instructors', []))->pluck('email');
        foreach ($instructorEmails as $email) {
            $message = "<h1>Course Assigned</h1><p>You have been assigned as an instructor for the course: {$course->title}</p>";
            NotificationService::send($email, $message, 'Course Assignment');
        }

        return response()->json([
            'success' => true,
            'message' => 'Course created successfully',
            'data' => $course
        ], 201);
    }

    public function show(string $id)
    {
        $course = Course::find($id);
        
        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $course
        ]);
    }

    public function update(Request $request, string $id)
    {
        if (!$this->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only admins can update courses.'
            ], 403);
        }

        $course = Course::find($id);
        
        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'instructors' => 'sometimes|nullable|array',
            'instructors.*' => 'integer|exists:users,id',
            'duration' => 'sometimes|required|string|max:100',
            'level' => 'sometimes|nullable|in:Beginner,Intermediate,Advanced',
            'price' => 'sometimes|required|numeric|min:0',
            'rating' => 'sometimes|nullable|numeric|min:0|max:5',
            'students' => 'sometimes|nullable|array',
            'students.*' => 'integer|exists:users,id',
            'description' => 'sometimes|required|string',
            'image' => 'sometimes|nullable|url',
            'topics' => 'sometimes|nullable|array',
            'topics.*' => 'string',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $course->update($request->all());

        $instructorEmails = User::whereIn('id', $request->input('instructors', []))->pluck('email');
        foreach ($instructorEmails as $email) {
            $message = "<h1>Course Updated</h1><p>The course '{$course->title}' has been updated.</p>";
            NotificationService::send($email, $message, 'Course Updated');
        }

        return response()->json([
            'success' => true,
            'message' => 'Course updated successfully',
            'data' => $course
        ]);
    }

    public function destroy(string $id)
    {
        if (!$this->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only admins can delete courses.'
            ], 403);
        }

        $course = Course::find($id);
        
        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }

        $courseTitle = $course->title;
        $course->delete();

        return response()->json([
            'success' => true,
            'message' => 'Course deleted successfully'
        ]);
    }
}
