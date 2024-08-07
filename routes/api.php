<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TradeController;
use App\Http\Controllers\UserInfoController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PasswordUpdateController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Auth\PasswordResetLinkController;

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

Route::middleware('auth:sanctum')->group(function () {

    // Email verification notice route
    Route::get('/email/verify', function () {
        return response()->json(['message' => 'Email verification required']);
    })->name('verification.notice');

    // Email verification route
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return response()->json(['message' => 'Email verified successfully']);
    })->middleware(['signed'])->name('verification.verify');

    // Resend email verification route
    Route::post('/email/resend', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return response()->json(['message' => 'Verification link sent']);
    })->name('verification.send');
});

Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [LoginController::class, 'login']);

Route::get('/verified', function () {
    return view('verified'); 
})->name('verified');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->name('password.email');

Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->name('password.reset');
Route::post('/password-change', [PasswordUpdateController::class, 'reset'])->name('reset.password.post');
Route::get('/resetsuccess', function () {
    return view('password-reset-success'); 
})->name('resetsuccess');


    
Route::post('/user-info/{userId}', [UserInfoController::class, 'store'])->name('user.info.store');
Route::post('place-trade/{userId}', [TradeController::class, 'placeTrade']);
Route::post('transactions/{userId}', [TransactionController::class, 'store']);
Route::post('/upload-documents/{id}', [UserInfoController::class, 'uploadDocuments']);
Route::post('transaction-withdraw/{userId}', [TransactionController::class, 'withdraw']);

Route::get('/admin/users', [AdminController::class, 'getAllUsers']);
Route::post('/admin/login', [AdminController::class, 'adminLogin']);
Route::post('/admin/users/{user_id}', [AdminController::class, 'updateUser']);
