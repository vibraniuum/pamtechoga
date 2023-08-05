<?php

namespace Vibraniuum\Pamtechoga\Http\Controllers\Api;

use Helix\Lego\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Vibraniuum\Pamtechoga\Models\Order;
use Vibraniuum\Pamtechoga\Models\OrganizationUser;
use Vibraniuum\Pamtechoga\Models\Review;
use Vibraniuum\Pamtechoga\Models\SupportMessage;

class ReviewsController extends Controller
{

    public function index()
    {
        $user = auth()->user();

        $userOrganization = OrganizationUser::where('user_id', $user->id)->first();

        $organization = $userOrganization->organization;

        $messages = Review::where('organization_id', $organization->id)->orderBy('created_at', 'desc')->with('user', 'organization')->paginate(20);

        return response()->json([
            'status' => true,
            'message' => 'Reviews retrieved successfully',
            'data' => $messages
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        try {
            //Validated
            $validateMessage = Validator::make($request->all(),
                [
                    'message' => 'required',
                    'rating' => 'required',
                    'order_id' => 'required',
                ]);

            if($validateMessage->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateMessage->errors()
                ], 401);
            }

            $user = auth()->user();

            $organizationUser = OrganizationUser::where('user_id', $user->id)->first();

            $organization = $organizationUser->organization;

            $order = Order::where('id', $request->all()['order_id'])->first();

            if(is_null($order)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Order specified not found.'
                ], 404);
            } else {
                // check if a review already exists for the order
                $existingReview = Review::where('order_id', $order->id)->first();
                if(!is_null($existingReview)) {
                    return response()->json([
                        'status' => false,
                        'message' => "A review for this order already exists. You can only review an order once."
                    ], 500);
                } else if($order->status != 'DELIVERED') {
                    return response()->json([
                        'status' => false,
                        'message' => "You can only submit a review for delivered orders."
                    ], 500);
                }
            }

            $review = Review::create([
                'user_id' => $user->id,
                'organization_id' => $organization->id,
                ...$request->all()
            ]);

            $reviewFromDB = Review::where('id', $review->id)->with('user', 'organization')->first();

            return response()->json([
                'status' => true,
                'message' => 'Review Created Successfully',
                'data' => $reviewFromDB
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
