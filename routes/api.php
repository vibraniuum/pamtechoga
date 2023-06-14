<?php

use Vibraniuum\Pamtechoga\Http\Controllers\Api\BranchController;
use Vibraniuum\Pamtechoga\Http\Controllers\Api\OrderController;
use Vibraniuum\Pamtechoga\Http\Controllers\Api\OrganizationController;
use Vibraniuum\Pamtechoga\Http\Controllers\Api\PaymentController;
use Vibraniuum\Pamtechoga\Http\Controllers\Api\ProductController;
use Vibraniuum\Pamtechoga\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;
use Vibraniuum\Pamtechoga\Http\Controllers\Api\AuthController;

Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);

Route::apiResource('users', UserController::class)->middleware('auth:sanctum');
Route::apiResource('organizations', OrganizationController::class)->middleware('auth:sanctum');
Route::apiResource('branches', BranchController::class)->middleware('auth:sanctum');
Route::apiResource('orders', OrderController::class)->middleware('auth:sanctum');
Route::apiResource('payments', PaymentController::class)->middleware('auth:sanctum');
Route::apiResource('products', ProductController::class)->middleware('auth:sanctum');

