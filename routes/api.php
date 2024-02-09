<?php

use Vibraniuum\Pamtechoga\Http\Controllers\Api\BranchController;
use Vibraniuum\Pamtechoga\Http\Controllers\Api\DashboardController;
use Vibraniuum\Pamtechoga\Http\Controllers\Api\DeviceController;
use Vibraniuum\Pamtechoga\Http\Controllers\Api\NewsController;
use Vibraniuum\Pamtechoga\Http\Controllers\Api\OrderController;
use Vibraniuum\Pamtechoga\Http\Controllers\Api\OrganizationController;
use Vibraniuum\Pamtechoga\Http\Controllers\Api\PaymentController;
use Vibraniuum\Pamtechoga\Http\Controllers\Api\ProductController;
use Vibraniuum\Pamtechoga\Http\Controllers\Api\ReviewsController;
use Vibraniuum\Pamtechoga\Http\Controllers\Api\SupportMessageController;
use Vibraniuum\Pamtechoga\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;
use Vibraniuum\Pamtechoga\Http\Controllers\Api\AuthController;

Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);

Route::apiResource('users', UserController::class)->middleware('auth:sanctum');
Route::post('/device-token', [DeviceController::class, 'saveDeviceToken'])->middleware('auth:sanctum');
Route::apiResource('organizations', OrganizationController::class)->middleware('auth:sanctum');
Route::apiResource('branches', BranchController::class)->middleware('auth:sanctum');
Route::apiResource('orders', OrderController::class)->middleware('auth:sanctum');
Route::get('all-orders', [OrderController::class, 'allOrders'])->middleware('auth:sanctum');
Route::apiResource('payments', PaymentController::class)->middleware('auth:sanctum');
Route::get('/breakdown', [PaymentController::class, 'breakdown'])->middleware('auth:sanctum');
Route::get('/payment-details', [PaymentController::class, 'paymentDetails'])->middleware('auth:sanctum');
Route::apiResource('products', ProductController::class)->middleware('auth:sanctum');
Route::apiResource('support-messages', SupportMessageController::class)->middleware('auth:sanctum');
Route::apiResource('reviews', ReviewsController::class)->middleware('auth:sanctum');
Route::get('/order-reviews', [ReviewsController::class, 'orderReviews'])->middleware('auth:sanctum');

Route::get('/fuel-prices', [DashboardController::class, 'fuelPrices'])->middleware('auth:sanctum');
Route::get('/zones-and-stations', [DashboardController::class, 'zonesAndStations'])->middleware('auth:sanctum');

Route::get('/dashboard/stats', [DashboardController::class, 'dashboard'])->middleware('auth:sanctum');

Route::get('/news', [NewsController::class, 'index'])->middleware('auth:sanctum');

