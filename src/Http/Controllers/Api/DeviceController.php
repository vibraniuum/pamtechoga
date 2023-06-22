<?php

namespace Vibraniuum\Pamtechoga\Http\Controllers\Api;

use Helix\Lego\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Google\Auth\ApplicationDefaultCredentials;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Google\Auth\CredentialsLoader;
use Vibraniuum\Pamtechoga\Models\DeviceToken;
use Vibraniuum\Pamtechoga\Models\OrganizationUser;

class DeviceController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function saveDeviceToken(Request $request)
    {
        try
        {
            //Validated
            $validateUser = Validator::make($request->all(),
                [
                    'device_token' => 'required',
                ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = auth()->user();

            //save device-token to database
            $userOrganization = OrganizationUser::where('user_id', $user->id)->first();

            $organization = $userOrganization->organization;

            // update device token
            $userDevice = DeviceToken::where('user_id', $user->id)->first();

            if(is_null($userDevice)) {
                $userDevice = DeviceToken::create([
                    'user_id' => $user->id,
                    'device_token' => $request->device_token,
                    'organization_id' => $organization->id
                ]);
            } else {
                $userDevice->update([
                    'device_token' => $request->device_token
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Device token saved successfully.',
                'data' => $userDevice
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

}
