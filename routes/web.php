<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Routes for GET requests. They are all guarded by appropriate middlewares, based on the logic behind authentication

// Guest middleware: If someone wants to login through the 'home' page, or register through the 'register' page, they must be guests of the website (unauthenticated users). If a user is actually logged in, they shouldn't be able to access the home and register routes. Thus, when they try to hit these routes, they are redirected to the HelloWorld route, which is the main route for authenticated users.

Route::get('/', function () {
    return view('home');
})->middleware('guest');

Route::get('/register', function () {
    return view('register');
})->middleware('guest');

// Auth middleware: If someone is not authenticated, they shouldn't be able to access the HelloWorld route, which is the main route for authenticated users. If they try to hit it, they will be redirected to the home route, so that they can login first.

Route::get('/HelloWorld', function () {
    return view('helloworld');
})->name('helloworld')->middleware('auth');

// Routes for the authentication POST requests. Their functions fall under the AuthController.

// The login route is passed through a Laravel middleware called throttle. This limits the amount of post requests that are sent to this route in a time unit. In this case, it limits the login attempts of a browser session to 5 in one minute. If a sixth login is attempted, a 429 status is sent on the page with the message "Too many requests".
Route::post('/', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
