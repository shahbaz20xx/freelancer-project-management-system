<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserAccountController;
use Illuminate\Support\Facades\Route;
use TCG\Voyager\Facades\Voyager;

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

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/projects', [ProjectsController::class, 'index'])->name('projects');
Route::get('/projects/detail/{id}', [ProjectsController::class, 'projectDetail'])->name('projectDetail');
Route::post('/apply-project', [ProjectsController::class, 'applyProject'])->name('applyProject');


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});


Route::group(['prefix' => 'account'], function () {

    Route::group(['middleware' => 'guest'], function () {
        Route::get('/register', [UserAccountController::class, 'registration'])->name('account.registration');
        Route::post('/process-register', [UserAccountController::class, 'processRegistration'])->name('account.processRegistration');
        Route::get('/login', [UserAccountController::class, 'login'])->name('account.login');
        Route::post('/authenticate', [UserAccountController::class, 'authenticate'])->name('account.authenticate');
    });

    Route::group(['middleware' => 'auth'], function () {
        Route::get('/create-project', [UserAccountController::class, 'createProject'])->name('account.createProject');
        Route::post('/upload-project', [UserAccountController::class, 'uploadProject'])->name('account.uploadProject');

        Route::get('/profile', [UserAccountController::class, 'profile'])->name('account.profile');
        Route::get('/logout', [UserAccountController::class, 'logout'])->name('account.logout');
        Route::put('/update-profile', [UserAccountController::class, 'updateProfile'])->name('account.updateProfile');
        Route::post('/update-profile-img', [UserAccountController::class, 'updateProfileImg'])->name('account.updateProfileImg');
        Route::post('/change-password', [UserAccountController::class, 'changePassword'])->name('account.changePassword');

        Route::get('/my-projects', [UserAccountController::class, 'myProjects'])->name('account.myProjects');
        Route::get('/my-projects-applications', [UserAccountController::class, 'myProjectApplications'])->name('account.myProjectApplications');
        Route::post('/update-application-status', [ProjectsController::class, 'updateApplicationStatus'])->name('updateApplicationStatus');
        Route::post('/delete-project', [UserAccountController::class, 'deleteProject'])->name('account.deleteProject');
        //test
        Route::get('/edit-project/edit/{projectId}', [UserAccountController::class, 'editProject'])->name('account.editProject');
        Route::post('/update-project/{projectId}', [UserAccountController::class, 'updateProject'])->name('account.updateProject');

        Route::post('/generate-invoice', [InvoiceController::class, 'generateInvoice'])->name('generateInvoice');
        //update request-release action logic
        Route::post('/invoices/request-release', [InvoiceController::class, 'requestRelease'])->name('invoices.requestRelease');
        //test
        Route::post('/invoices/generate-for-task', [InvoiceController::class, 'generateInvoiceForTask'])->name('invoices.generateForTask');
        
        //test
        Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
        Route::post('/tasks/mark-complete', [TaskController::class, 'markComplete'])->name('tasks.markComplete');
    });
});
