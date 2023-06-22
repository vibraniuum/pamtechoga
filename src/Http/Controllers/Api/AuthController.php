<?php

namespace Vibraniuum\Pamtechoga\Http\Controllers\Api;

use Helix\Lego\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Vibraniuum\Pamtechoga\Models\DeviceToken;
use Vibraniuum\Pamtechoga\Models\Organization;
use Vibraniuum\Pamtechoga\Models\OrganizationUser;

class AuthController extends Controller
{

    /**
     * Create User
     * @param Request $request
     * @return JsonResponse
     */
    public function createUser(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make($request->all(),
                [
                    'organization_id' => 'required',
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required'
                ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $organization = Organization::find($request->organization_id);

            if(is_null($organization)) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => 'Organization does not match with our record.'
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            OrganizationUser::create([
                'organization_id' => $organization->id,
                'user_id' => $user->id,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken,
                'data' => $user
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Login The User
     * @bodyParam email required string required The email of the user. Example: user@email.com
     * @bodyParam password required string The password of the user account.
     * @response 200 scenario="Login Successful"
     * {
    "status": true,
    "message": "User Logged In Successfully",
    "token": "x|XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX",
    "data": {
    "id": 10,
    "name": "Somiari Lucky",
    "email": "somiari.lucky@gmail.com",
    "email_verified_at": null,
    "created_at": "2023-05-21T15:45:34.000000Z",
    "updated_at": "2023-05-27T14:49:16.000000Z",
    "deleted_at": null
    }
    }
     * @param Request $request
     * @return JsonResponse
     */
    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required',
                ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken,
                'data' => $user
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

}
