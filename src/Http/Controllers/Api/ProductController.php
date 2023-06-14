<?php

namespace Vibraniuum\Pamtechoga\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Vibraniuum\Pamtechoga\Models\Product;

/**
 * @group Order management
 *
 * APIs for managing orders
 */
class ProductController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {

        $products = Product::get();

        return response()->json([
            'status' => true,
            'data' => $products,
        ]);
    }
}
