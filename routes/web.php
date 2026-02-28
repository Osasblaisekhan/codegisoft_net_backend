<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Codegisoft API',
        'version' => '1.0',
        'endpoints' => [
            'GET /api/users',
            'POST /api/users',
            'GET /api/users/{id}',
            'PUT /api/users/{id}',
            'DELETE /api/users/{id}',
        ]
    ]);
});
