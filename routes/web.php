<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InsController;
use App\Http\Controllers\ManageCoursesController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\CoursesControllerView;
use App\Http\Controllers\MyCoursesController;
use App\Http\Controllers\SearchController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['auth', 'checkIp']], function () {
    Route::get('/', [UserController::class, 'index'])->name('home');
    Route::get('/courses', [CoursesControllerView::class, 'courses'])->name('courses');
    Route::get('/myCourses', [MyCoursesController::class, 'courses'])->name('own-courses');
    Route::get('/search', [SearchController::class, 'search'])->name('search');


    Route::get('course-view/{course_id}', [UserController::class, 'courseView']);    



    Route::get('course-page/{id}', [UserController::class, 'coursePage'])->name('course-page');
    Route::get('settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('update-info', [AdminController::class, 'updateUser'])->name('update-info');
    Route::post('remove-image', [AdminController::class, 'removeImage'])->name('remove-image');

    Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'dashboard']], function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('add-courses', [CoursesController::class, 'index'])->name('add-course');
        Route::post('create-course', [CoursesController::class, 'store'])->name('create-course');
        Route::get('courses-page', [ManageCoursesController::class, 'index'])->name('courses-page');
        Route::get('edit-course/{course_id}', [ManageCoursesController::class, 'editCourse']);
        Route::post('course-update/{course_id}', [ManageCoursesController::class, 'update'])->name('course-update');
        Route::post('create-section}', [ManageCoursesController::class, 'createSections'])->name('create-section');
        Route::post('create-lesson}', [ManageCoursesController::class, 'createLessons'])->name('create-lesson');
        Route::post('delete-section/{id}', [ManageCoursesController::class, 'deleteSection'])->name('delete-section');
        Route::post('delete-lesson/{id}', [ManageCoursesController::class, 'deleteLesson'])->name('delete-lesson');
        Route::post('update-section/{id}', [ManageCoursesController::class, 'updateSection'])->name('update-section');
        Route::post('update-lesson/{id}', [ManageCoursesController::class, 'updateLesson'])->name('update-lesson');
        Route::post('archiveCourse', [ManageCoursesController::class, 'archiveCourse'])->name('archiveCourse');
        Route::post('changeModulePrice', [ModuleController::class, 'changeModulePrice'])->name('changeModulePrice');
        Route::get('/manage-modules',[ModuleController::class,'manage_modules'])->name('instructorModules');
        Route::get('/rateCourse/{course_id}/{rate}',[ManageCoursesController::class,'rateCourse'])->name('rateCourse');
        Route::post('add_auth_user', [ModuleController::class, 'add_auth_user'])->name('add_auth_user');
        Route::post('delete_auth_user', [ModuleController::class, 'delete_auth_user'])->name('delete_auth_user');
        
        Route::group(['middleware' => ['auth', 'admin']], function () {
            Route::resource('module', ModuleController::class);
            Route::get('enrollment-history', [AdminController::class, 'historyEnroll'])->name('enrollment-history');
            Route::get('add-student', [AdminController::class, 'addStudent'])->name('add-student');
            Route::post('enrollment', [AdminController::class, 'enrollmentStudent'])->name('enrollment');
            Route::get('delete-user/{id}', [AdminController::class, 'deleteUserCourse'])->name('delete-user');
            Route::get('edit-status/{id}', [AdminController::class, 'changeStatus'])->name('edit-status');
            Route::get('instructors', [InsController::class, 'index'])->name('instructors');
            Route::post('update_user_role', [InsController::class, 'update_user_role'])->name('update_user_role');
            Route::post('linkCourseModule',[ManageCoursesController::class,'linkCourseModule'])->name('linkCourseModule');
            Route::get('deleteCourse/{id}',[ManageCoursesController::class,'deleteCourse'])->name('deleteCourse');
            Route::get('deleteModule/{id}',[ModuleController::class,'deleteModule'])->name('deleteModule');
            Route::post('archiveModule',[ModuleController::class,'archiveModule'])->name('archiveModule');
            Route::get('orderLesons', [ManageCoursesController::class, 'orderLesons']);
        });
        Route::group(['middleware' => ['auth', 'instructor']], function () {
            //        Route::get('settings', [AdminController::class, 'settings'])->name('settings');
        });
    });
});
