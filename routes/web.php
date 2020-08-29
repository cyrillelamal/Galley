<?php

use Illuminate\Support\Facades\Route;

// The SPA handler.
Route::view('/{path?}', 'spa')->where('path', '.*');
