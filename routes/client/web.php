<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\client\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController\client;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\ProfileController;
use App\Http\Service\VNPayService;
use Illuminate\Support\Facades\Route;

// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified',
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('admin.dashboard.guest_dashboard');
//     })->name('dashboard');
// });
Route::get('/', function () {
    return view('client.index');
})->name('home');

Route::get('admin', function () {
    if (Auth::check() && Auth::user()->isAdmin()) {
        return view('admin.admin')->with('msg', 'Chào mừng bạn quay lại');
    } else {
        return back()->with('error', 'Bạn không được cấp quyền truy cập.');
    }
})->middleware(['auth', 'verified'])->name('admin-index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/checkout/{id}', [HomeController::class, 'checkout'])->name('checkout');
    Route::post('/paybooking/{id}', [CartController::class, 'payBooking'])->name('payBooking');
    Route::match(['get', 'post'], '/callBackVNPay/{id}', [VNPayService::class, 'callBackVNPay'])->name('call.back.vnpay');
});
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search-suggestions', [HomeController::class, 'searchSuggestions'])->name('seacrh');
Route::get('/competition-detail/{id}', [HomeController::class, 'competitionDetail'])->name('competition.detail');
Route::get('/matchSeat/{id}', [HomeController::class, 'matchSeat'])->name('matches.seat');
Route::get('/seat-plan/{id}', [HomeController::class, 'seatPlan'])->name('seat.plan');
Route::get('login', [HomeController::class, 'create'])->name('login');
Route::get('/dashboard-guest', [DashboardController::class, 'index'])->name('dashboard.guest');
Route::post('/order-guest/{id}', [DashboardController::class, 'orderGuest'])->name('order.guest');
Route::post('/potential', [HomeController::class, 'potential'])->name('potential');

