<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController,AuctionController};

Route::get('/health', [AuctionController::class, 'health']);

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

Route::get('/auctions', [AuctionController::class, 'index']);
Route::get('/auctions/{auction}', [AuctionController::class, 'show']);

Route::middleware('auth:sanctum')->group(function() {
    Route::post('/auctions/{auction}/bids', [AuctionController::class, 'bid']);
    Route::post('/auctions/{auction}/favorite', [AuctionController::class, 'favorite']);
    Route::get('/me/favorites', [AuctionController::class, 'myFavorites']);
    Route::post('/auctions/{auction}/checkout', [AuctionController::class, 'checkout']);
});
