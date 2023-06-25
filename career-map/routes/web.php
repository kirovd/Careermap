<?php

use App\Http\Controllers\JobController;
use Illuminate\Support\Facades\Route;

Route::get('/', [JobController::class, 'create'])->name('create-job');
Route::post('/jobs', [JobController::class, 'store'])->name('jobs.store');
Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{id}', [JobController::class, 'show'])->name('jobs.show');
Route::post('/jobs/{id}/accept', [JobController::class, 'accept'])->name('jobs.accept');



