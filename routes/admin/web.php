<?php

use App\Http\Controllers\admin\ClubController;
use App\Http\Controllers\admin\MatchController;
use App\Http\Controllers\admin\CompetitionController;
use App\Http\Controllers\admin\SeatController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware('check.is.admin')->name('admin.')->group(function () {
    Route::match(['get', 'post'], 'update-matches', [MatchController::class, 'updateMatch'])->name('update-matches');
    Route::delete('matches/destroy/{id}', [MatchController::class, 'destroy'])->name('matches.destroy');
    Route::post('matches/force-delete/{id}', [MatchController::class, 'forceDelete'])->name('matches.force.delete');
    Route::post('matches/restore/{id}', [MatchController::class, 'restore'])->name('matches.restore');
    Route::post('matches/update/{id}', [MatchController::class, 'update'])->name('matches.update');
    Route::post('matches/slug/', [MatchController::class, 'createSlug'])->name('matches.createSlug');
    Route::get('matches/detail/{id}', [MatchController::class, 'detail'])->name('matches.detail');
    Route::get('matches/restore-match', [MatchController::class, 'restoreMatch'])->name('matches.restorematch');
});
Route::prefix('admin')->middleware('check.is.admin')->name('admin.')->group(function () {
    // Route::get('competition', [CompetitionController::class, 'index'])->name('index');
    Route::match(['get', 'post'], 'update-competiton', [CompetitionController::class, 'updateCompetition'])->name('update-competition');
    Route::delete('competition/destroy/{id}', [CompetitionController::class, 'destroy'])->name('competition.destroy');
    Route::post('competition/force-delete/{id}', [CompetitionController::class, 'forceDelete'])->name('competition.force.delete');
    Route::post('competition/restore/{id}', [CompetitionController::class, 'restore'])->name('competition.restore');
    Route::post('competition/update/{id}', [CompetitionController::class, 'update'])->name('competition.update');
    Route::post('competition/slug/', [CompetitionController::class, 'createSlug'])->name('competition.createSlug');
    Route::get('competition/detail/{id}', [CompetitionController::class, 'detail'])->name('competition.detail');
});
Route::prefix('admin')->middleware('check.is.admin')->name('admin.')->group(function () {
    Route::get('seat/index', [SeatController::class, 'index'])->name('seat.index');
    Route::post('seat/store', [SeatController::class, 'store'])->name('seat.store');
    Route::post('seat/force-delete/{id}', [SeatController::class, 'forceDelete'])->name('seat.force.delete');
    Route::get('club/index',[ClubController::class,'index'])->name('club.index');
    Route::match(['get', 'post'],'club/index/filter/{competitionId?}', [ClubController::class, 'filter'])->name('club.filter');
    Route::get('/dashboard/admin', [DashboardController::class, 'indexAdmin'])->name('dashboard.Admin');
    

});
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
 