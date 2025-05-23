<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectsController;
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

Route::get('/',[HomeController::class,'index'])->name('home');
Route::get('/projects',[ProjectsController::class,'index'])->name('projects');

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('/register',[UserAccountController::class,'registration'])->name('account.registration');
    Route::post('/process-register',[UserAccountController::class,'processRegistration'])->name('account.processRegistration');
    Route::get('/login',[UserAccountController::class,'login'])->name('account.login');
    Route::post('/authenticate',[UserAccountController::class,'authenticate'])->name('account.authenticate');
});