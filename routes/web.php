<?php

use Illuminate\Support\Facades\Route;

use App\Models\Client;
use App\Models\OpticalDistribution;

Route::get('/', function () {
    $totalClient = Client::count();
    $totalOD = OpticalDistribution::count();
    return view('dashboard', [
        'totalClient' => $totalClient,
        'totalOD' => $totalOD,
        'logs' => []
    ]);
});

Route::get('/traffic', function () {
    return redirect('/');
});
// routes/web.php
use App\Http\Controllers\OpticalDistributionController;
Route::resource('optical_distribution', OpticalDistributionController::class);
use App\Http\Controllers\LineController;

Route::resource('line', LineController::class);
use App\Http\Controllers\MapController;

Route::get('/map/view', [MapController::class, 'view'])->name('map.view');
use App\Http\Controllers\ClientController;

Route::resource('client', ClientController::class);
