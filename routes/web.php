<?php

use App\Http\Controllers\Admin\ImpersonateController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EAD\CoursesController;
use App\Http\Controllers\EAD\EnrollmentsController;
use App\Http\Controllers\EAD\SubjectsController;
use App\Http\Controllers\Studant\DashboardController as StudantDashboardController;
use App\Http\Controllers\User\InvateController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\UsersController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);

    Route::get('two-factor', [LoginController::class, 'twoFactor'])->name('two-factor');
    Route::post('two-factor', [LoginController::class, 'twoFactorStore'])->name('two-factor.post');


    Route::get('register', [RegisterController::class, 'create'])->name('register');
    Route::post('register', [RegisterController::class, 'store']);
    Route::post('register/teste', [RegisterController::class, 'testeModal'])->name('register.teste');

    Route::get('forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');

    Route::get('reset-password/{token}', [ResetPasswordController::class, 'show'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'update'])->name('password.update');
    Route::get('/register-invited', [App\Http\Controllers\User\InvateController::class, 'registerInvited'])
        ->name('register.invited')
        ->middleware('signed');
});;
Route::middleware(['auth', 'verified'])->group(function () {

    // Logout
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
    // Dashboard Principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::patch('/profile/information', [ProfileController::class, 'updateInformation'])->name('profile.information.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::patch('/profile/2fa', [ProfileController::class, 'update2FA'])->name('profile.2fa.update');

    //ROUTES USERS
    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
    Route::post('/users', [UsersController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UsersController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UsersController::class, 'destroy'])->name('users.destroy')->withTrashed();
    Route::post('/user/invite', [InvateController::class, 'invite'])->name('users.invite');
    Route::post('/impersonate/take/{user}', [ImpersonateController::class, 'take'])->name('admin.impersonate.take');

    //ROUTES ENVIRONMENTS
    Route::get('/enrollments', [EnrollmentsController::class, 'index'])->name('enrollments.index');
    Route::post('/enrollments', [EnrollmentsController::class, 'store'])->name('enrollments.store');
    Route::put('/enrollments/{enrollment}', [EnrollmentsController::class, 'update'])->name('enrollments.update');
    Route::delete('/enrollments/{enrollment}', [EnrollmentsController::class, 'destroy'])->name('enrollments.destroy');

    //ROUTES TEACHERS
    Route::get('/courses', [CoursesController::class, 'index'])->name('course.index');
    Route::post('/courses', [CoursesController::class, 'store'])->name('course.store');
    Route::put('/courses/{course}', [CoursesController::class, 'update'])->name('course.update');
    Route::delete('/courses/{course}', [CoursesController::class, 'destroy'])->name('course.destroy')->withTrashed();

    //ROUTE SUBJECTS
    Route::get('/subjects', [SubjectsController::class, 'index'])->name('subject.index');
    Route::post('/subjects', [SubjectsController::class, 'store'])->name('subject.store');
    Route::put('/subjects/{subject}', [SubjectsController::class, 'update'])->name('subject.update');
    Route::delete('/subjects/{subject}', [SubjectsController::class, 'destroy'])->name('subject.destroy');

    //ROUTE STUDANT
    Route::get('/painel', [StudantDashboardController::class, 'index'])->name('student.dashboard');
    Route::get('/painel/profile', [StudantDashboardController::class, 'profile'])->name('student.profile');
});

Route::post('/impersonate/leave', [ImpersonateController::class, 'leave'])->name('admin.impersonate.leave');
