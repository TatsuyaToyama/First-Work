<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\VerificationController;

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


Route::middleware(['verified'])->group(function(){
    Route::get('/', [AttendanceController::class, 'index'])->name('records.index');
    Route::get('/dashboard', [AttendanceController::class,'dashboard'])->name('dashboard');



    Route::get('/record', [AttendanceController::class,'record']);
    Route::post('/record', [AttendanceController::class,'record']);
    Route::get('/attendance', [AttendanceController::class,'attendance']) ->name('attendance.date');
    Route::post('/attendance', [AttendanceController::class,'attendance']) ->name('attendance.date');
    Route::get('/attendance/submit', [AttendanceController::class,'attendance']);
    Route::post('/attendance/submit', [AttendanceController::class,'attendance']);


    Route::get('/user', [AttendanceController::class,'user']);
    Route::get('/user/result', [AttendanceController::class,'Search']);
    Route::post('/user/result', [AttendanceController::class,'Search']);
});


Route::middleware(['auth:sanctum',config('jetstream.auth_session'),'verified'])
->post('/', [AttendanceController::class,'index']);

Auth::routes(['verify' => true]);


// Route::get('/email/verify', 'VerificationController@show')
//     ->middleware('auth')
//     ->name('verification.notice');

// Route::get('/email/verify/{id}/{hash}', 'VerificationController@verify')
//     ->middleware(['auth', 'signed'])
//     ->name('verification.verify');

// Route::post('/email/verification-notification', 'VerificationController@send')
//     ->middleware(['auth', 'throttle:6,1'])
//     ->name('verification.send');




// group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');
// });

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
