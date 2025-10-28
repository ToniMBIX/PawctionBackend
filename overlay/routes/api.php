<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuctionController,BidController,OrderController,PaymentController,PaymentProController};

Route::get('/health', fn() => ['ok'=>true]);

Route::get('/auctions', [AuctionController::class, 'index']);
Route::get('/auctions/{id}', [AuctionController::class, 'show']);
Route::post('/auctions/{id}/bids', [BidController::class, 'store']);
Route::post('/orders', [OrderController::class, 'store']);

Route::post('/payments/stripe/checkout', [PaymentController::class, 'createStripeCheckout']);
Route::post('/payments/stripe/webhook', [PaymentController::class, 'stripeWebhook']);

Route::post('/payments/paypal/create', [PaymentProController::class, 'createPayPal']);
Route::post('/payments/paypal/capture', [PaymentProController::class, 'capturePayPal']);
