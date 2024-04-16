<?php

use App\Modules\Transaction\Controllers\CreateTransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('transaction', CreateTransactionController::class)->name('transaction.create');
