<?php

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\YoutubeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('/login', [ApiAuthController::class, 'login']);
Route::post('/register', [ApiAuthController::class, 'register']);
Route::post('get_youtube_qualities', [YoutubeController::class, 'get_youtube_qualities']);
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/get_active_user', [ApiAuthController::class, 'get_active_user']);
    Route::post('/update', [ApiAuthController::class, 'update']);
    Route::post('/deleteAccount', [ApiAuthController::class, 'delete_account']);
    Route::get('/getInstructorsData', [ApiController::class, 'getInstructorsData']);
    Route::get('/getModulesData/{ins_id}', [ApiController::class, 'getModulesData']);
    Route::get('/getCoursesByInstructorModule/insId/{ins_id}/moduleId/{module_id}', [ApiController::class, 'getCoursesByInstructorModule']);
    Route::get('/getCoursesByInstructor/insId/{ins_id}', [ApiController::class, 'getCoursesByInstructor']);
    Route::get('/getCoursesByModule/moduleId/{module_id}', [ApiController::class, 'getCoursesByModule']);
    Route::get('/getModules', [ApiController::class, 'getModules']);
    Route::get('/getCourses', [ApiController::class, 'getCourses']);
    Route::get('/getCourse/{id}', [ApiController::class, 'getCourse']);
    Route::get('/getEnrolledCourses', [ApiController::class, 'getEnrolledCourses']);
    Route::get('/search', [ApiController::class, 'search']);
    
});
