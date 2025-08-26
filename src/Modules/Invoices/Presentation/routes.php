<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Invoices\Presentation\Http\InvoiceController;
use Ramsey\Uuid\Validator\GenericValidator;

Route::pattern('action', '^[a-zA-Z]+$');
Route::pattern('reference', (new GenericValidator)->getPattern());


Route::prefix('invoices')->group(function () {
    Route::get('/', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::post('/store', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/search/{id}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::post('/send/{id}', [InvoiceController::class, 'send'])->name('invoices.send');
});
