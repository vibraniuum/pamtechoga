<?php

namespace Vibraniuum\Pamtechoga\Http\Controllers\Api;

use Helix\Lego\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Vibraniuum\Pamtechoga\Models\OrganizationUser;
use Vibraniuum\Pamtechoga\Models\SupportMessage;

class SupportMessageController extends Controller
{

    public function index()
    {
        $user = auth()->user();

        $userOrganization = OrganizationUser::where('user_id', $user->id)->first();

        $organization = $userOrganization->organization;

        $messages = SupportMessage::where('organization_id', $organization->id)->orderBy('created_at', 'desc')->with('user', 'organization')->paginate(20);

        return response()->json([
            'status' => true,
            'message' => 'Messages retrieved successfully',
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
                    'user_id' => 'required',
                    'message' => 'required',
                    'message_type' => 'required',
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

            $message = SupportMessage::create([
                'organization_id' => $organization->id,
                ...$request->all()
            ]);

            $messageFromDB = SupportMessage::where('id', $message->id)->with('user', 'organization')->first();

            return response()->json([
                'status' => true,
                'message' => 'Message Created Successfully',
                'data' => $messageFromDB
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
