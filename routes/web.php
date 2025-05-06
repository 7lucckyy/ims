<?php

use App\Http\Controllers\POPdfController;
use App\Http\Controllers\PRPdfController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin/login')->name('login');

Route::middleware('signed')->group(function () {

    Route::get('purchaseRequest/{purchaseRequest}/pdf', PRPdfController::class)->name('purchaseRequest.pdf');
    Route::get('purchaseOrder/{purchaseOrder}/pdf', POPdfController::class)->name('purchaseOrder.pdf');
});
