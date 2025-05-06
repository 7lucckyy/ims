<?php

use App\Http\Controllers\Api\Callback\AttendanceCollector;
use Illuminate\Support\Facades\Route;

Route::post('/collector', [AttendanceCollector::class, 'collect']);
