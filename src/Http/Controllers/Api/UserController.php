<?php

namespace Vibraniuum\Pamtechoga\Http\Controllers\Api;

use Helix\Lego\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Vibraniuum\Pamtechoga\Models\Organization;
use Vibraniuum\Pamtechoga\Models\OrganizationUser;
use Vibraniuum\Pamtechoga\Models\Payment;

/**
 * @group User management
 *
 * APIs for managing users
 */
class UserController extends Controller
{

    public function index()
    {
        $user = auth()->user();

        $userOrganization = OrganizationUser::where('user_id', $user->id)->first();

        $organization = $userOrganization->organization;

        $usersBelongingToMyOrganization = OrganizationUser::where('organization_id', $organization->id)->with('user')->get();

        return response()->json([
            'status' => true,
            'users' => $usersBelongingToMyOrganization
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            $validateUser = Validator::make($request->all(),
                [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email',
                ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = auth()->user();

            $organizationUser = OrganizationUser::where('user_id', $user->id)->first();

            $organization = $organizationUser->organization;

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('11111111') // 8 ones
            ]);

            OrganizationUser::create([
                'organization_id' => $organization->id,
                'user_id' => $user->id,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'data' => $user
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

//    /**
//     * Display the specified resource.
//     *
//     * @return \Illuminate\Http\Response
//     */
    public function show()
    {
        $user = auth()->user();

        $organizationUser = OrganizationUser::where('user_id', $user->id)->first();

        $user = collect($organizationUser?->user)->except(['deleted_at', 'email_verified_at']);

        $organization = $organizationUser?->organization;
        $branches = $organization->branches;

        return response()->json([
            'status' => true,
            'data' => $organizationUser?->user ? $user : null,
            'organization' => $organizationUser?->organization
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        try{
            $authenicatedUser = auth()->user();

            $user = User::where('id', $authenicatedUser->id)->first();

            // check if other users already have this email address

            $user->update($request->all());

            return response()->json([
                'status' => true,
                'message' => "User Updated successfully!",
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return response()->json([
            'status' => true,
            'message' => "Post Deleted successfully!",
        ], 200);
    }
}
