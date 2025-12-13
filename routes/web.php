<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FeedbackLinkController;
use App\Http\Controllers\VerifyController;

use Illuminate\Support\Facades\Route;



Route::get('/send-link/{requestid}', [ApiController::class, 'sendLink'])
     ->name('send.link');
Route::get('/', [ApiController::class, 'index'])->name('blogs.index');
Route::get('/form', [ApiController::class, 'openForm'])->name('staff.form')->middleware('signed');



   


