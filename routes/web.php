<?php

use App\Http\Controllers\InvoicePdfController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Invoice PDF Routes
Route::get('/invoice/{invoice}/pdf', [InvoicePdfController::class, 'download'])->name('invoice.pdf.download');
Route::get('/invoice/{invoice}/pdf/preview', [InvoicePdfController::class, 'stream'])->name('invoice.pdf.preview');
