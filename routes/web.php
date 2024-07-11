<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    // Registration Form
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register.form');

    //Login Form
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login.form');

    //Registration and Login
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    // Role-based routes
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/admin/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');
    });

    Route::middleware(['auth', 'role:supplier'])->group(function () {
        Route::get('/supplier/dashboard', function () {
            return view('supplier.dashboard');
        })->name('supplier.dashboard');
    });

    Route::middleware(['auth', 'role:user'])->group(function () {
        Route::get('/user/dashboard', function () {
            return view('user.dashboard');
        })->name('user.dashboard');
    });