<?php

namespace Vibraniuum\Pamtechoga\Http\Controllers\Api;

use Helix\Lego\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Vibraniuum\Pamtechoga\Models\News;

class NewsController extends Controller
{
    /**
     * Fetch resource in storage.
     *
     * @return JsonResponse
     */
    public function index()
    {
        try
        {
            $news = News::orderBy('created_at', 'desc')->paginate(50);

            return response()->json([
                'status' => true,
                'message' => 'News retrieved successfully.',
                'data' => $news
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

}
