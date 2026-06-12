<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketWebController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('tickets', TicketWebController::class);