<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\AnalysisController;

Route::get('/invoice/print/{id}', [InvoiceController::class, 'print'])->name('invoice.print');
Route::get('/analysis/download/{id}', [AnalysisController::class, 'download'])->name('analysis.download');

