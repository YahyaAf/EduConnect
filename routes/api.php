<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\TagController;
use App\Http\Controllers\V1\RoleController;
use App\Http\Controllers\V1\UserController;
use App\Http\Controllers\V1\CourseController;
use App\Http\Controllers\V1\MentorController;
use App\Http\Controllers\V1\ProfileController;
use App\Http\Controllers\V1\StudentController;
use App\Http\Controllers\V1\CategoryController;
use App\Http\Controllers\V1\EnrollmentController;
use App\Http\Controllers\V1\PermissionController;
use App\Http\Controllers\V1\StatisticsController;


Route::prefix('v1')->group(function () {
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });    
    Route::post('register', [UserController::class, 'register']);
    Route::post('login', [UserController::class, 'login']);
    Route::post('logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
    Route::middleware('auth:sanctum')->post('/refresh-token', [UserController::class, 'refreshToken']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/profile', [ProfileController::class, 'show']);
        Route::post('/profile/update', [ProfileController::class, 'update']);
        Route::delete('/profile', [ProfileController::class, 'destroy']);
        
        Route::apiResource('permissions', PermissionController::class);

        Route::post('courses/{id}/enroll', [EnrollmentController::class, 'enroll']);
        Route::get('courses/{id}/enrollments', [EnrollmentController::class, 'listEnrollments']);
        Route::put('enrollments/{id}', [EnrollmentController::class, 'updateStatus']);
        Route::delete('enrollments/{id}', [EnrollmentController::class, 'destroy']);

        Route::apiResource('roles', RoleController::class);
        Route::post('roles/{roleId}/permissions', [RoleController::class, 'assignPermissions']);
        Route::post('roles/{roleId}/revoke-permissions', [RoleController::class, 'revokePermissions']);

        Route::get('categories', [CategoryController::class, 'index']);
        Route::post('categories', [CategoryController::class, 'store']);
        Route::get('categories/{id}', [CategoryController::class, 'show']);
        Route::put('categories/{id}', [CategoryController::class, 'update']);
        Route::delete('categories/{id}', [CategoryController::class, 'destroy']);

        Route::get('tags', [TagController::class, 'index']);
        Route::post('tags', [TagController::class, 'store']);
        Route::post('tags/multiple', [TagController::class, 'storeMultiple']); 
        Route::get('tags/{id}', [TagController::class, 'show']);
        Route::put('tags/{id}', [TagController::class, 'update']);
        Route::delete('tags/{id}', [TagController::class, 'destroy']);

        Route::get('courses', [CourseController::class, 'index']);
        Route::post('courses', [CourseController::class, 'store']);
        Route::get('courses/{id}', [CourseController::class, 'show']);
        Route::put('courses/{id}', [CourseController::class, 'update']);
        Route::delete('courses/{id}', [CourseController::class, 'destroy']);

        Route::prefix('stats')->group(function () {
            Route::get('/courses', [StatisticsController::class, 'getCoursesStats']);
            Route::get('/categories', [StatisticsController::class, 'getCategoriesStats']);
            Route::get('/tags', [StatisticsController::class, 'getTagsStats']);
        });

        Route::prefix('students')->group(function () {
            Route::get('/courses', [StudentController::class, 'getCourses']);
            Route::get('/progress', [StudentController::class, 'getProgress']);
        
        });
        Route::prefix('mentor')->group(function () {
            Route::get('/courses', [MentorController::class, 'getCreatedCourses']);
            Route::get('/enrolled-students', [MentorController::class, 'getEnrolledStudentsCount']);
            Route::get('/performance-stats', [MentorController::class, 'getPerformanceStats']);

        });

        
    });
});

