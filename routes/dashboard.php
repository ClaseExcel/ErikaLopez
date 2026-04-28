<?php

use Illuminate\Support\Facades\Route;

Route::resource('dashboard', 'DashboardController')->only(['index']);