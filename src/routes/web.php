<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\RequestController;
use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth')->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/start', [AttendanceController::class, 'start'])->name('attendance.start');
    Route::post('/attendance/clock-out', [AttendanceController::class, 'clockOut'])->name('attendance.clockOut');
    Route::post('/attendance/break-start', [AttendanceController::class, 'startBreak'])->name('attendance.break.start');
    Route::post('/attendance/break-end', [AttendanceController::class, 'endBreak'])->name('attendance.break.end');
    Route::get('/attendances', [AttendanceController::class, 'monthly'])->name('attendance.monthly');
    Route::get('/attendances/{attendance}', [AttendanceController::class, 'show'])->name('attendance.show');
    Route::post('/attendances/{attendance}/request-edit', [AttendanceController::class, 'requestEdit'])->name('attendance.request.edit');
    Route::get('/my-requests', [AttendanceController::class, 'requestList'])->name('attendance.requests');
    Route::get('/requests/{request}', [RequestController::class, 'show'])->name('request.show');

});

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.post');
});

Route::middleware(['auth', 'can:admin'])->group(function () {
    Route::get('/requests', [RequestController::class, 'index'])->name('requests.index');
    Route::get('/requests/{request}', [RequestController::class, 'show'])->name('requests.show');
    Route::post('/requests/{request}/approve', [RequestController::class, 'approve'])->name('requests.approve');
});
