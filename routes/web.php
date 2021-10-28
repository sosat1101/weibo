<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaticPagesController;
use App\Http\Controllers\UserController;
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

Route::get('/',[StaticPagesController::class, 'home'])->name('home');

Route::get('/help',[StaticPagesController::class, 'help'])->name('help');

Route::get('/about',[StaticPagesController::class, 'about']);

Route::resource('users','UserController');

Route::get('login',[\App\Http\Controllers\SessionController::class,'create'])->name('login');

Route::post('login',[\App\Http\Controllers\SessionController::class,'store'])->name('login');

Route::delete('logout',[\App\Http\Controllers\SessionController::class,'destroy'])->name('logout');

Route::get('signup/confirm/{token}', [UserController::class, 'confirmEmail'])->name('confirm_email');

Route::get('password/reset-form', [\App\Http\Controllers\PasswordController::class, 'resetForm'])->name('password.resetForm');
Route::post('password/send-reset-link-email', [\App\Http\Controllers\PasswordController::class, 'sendResetLinkEmail'])->name('password.sendResetLinkEmail');
Route::get('password/reset/{token}', [\App\Http\Controllers\PasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/update', [\App\Http\Controllers\PasswordController::class, 'update'])->name('password.update');

Route::resource('status', 'StatusController', ['only' => ['store', 'destroy']]);

Route::get('users/{user}/following', [UserController::class, 'following'])->name('users.following');
Route::get('users/{user}/follower', [UserController::class, 'follower'])->name('users.follower');

Route::post('users/followers/{user}', [\App\Http\Controllers\FollowersController::class, 'store'])->name('followers.store');
Route::delete('users/followers/{user}', [\App\Http\Controllers\FollowersController::class, 'destroy'])->name('followers.destroy');


