<?php

use App\Http\Controllers\API\ListingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return response()->json([
        'success' => true,
        'message' => 'Detail logi user',
        'data' => $request->user()
    ]);
});

// fungsi index yang di ambil dari route api listing controller 
Route::resource('listing', ListingController::class)->only(['index']);
require __DIR__ . '/auth.php';
