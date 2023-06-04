<?php

namespace Vibraniuum\Pamtechoga\Http\Controllers\Api;

use Helix\Lego\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Vibraniuum\Pamtechoga\Models\OrganizationUser;

/**
 * @group User management
 *
 * APIs for managing users
 */
class UserController extends Controller
{


    public function index()
    {
        $users = User::all();

        return response()->json([
            'status' => true,
            'users' => $users
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {
//        $post = User::create($request->all());
//
//        return response()->json([
//            'status' => true,
//            'message' => "Post Created successfully!",
//            'post' => $post
//        ], 200);
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
                'user' => $user
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
