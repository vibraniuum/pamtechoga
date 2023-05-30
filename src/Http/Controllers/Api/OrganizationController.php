<?php

namespace Vibraniuum\Pamtechoga\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Vibraniuum\Pamtechoga\Models\Organization;
use Vibraniuum\Pamtechoga\Models\OrganizationUser;

/**
 * @group Organization management
 *
 * APIs for managing organizations
 */
class OrganizationController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @response 200 scenario="Retrieved successfully"
     * {
    "status": true,
    "organization": {
    "id": 1,
    "name": "WHITE MAN",
    "slug": "white-man",
    "phone": "0801112324",
    "email": "whiteman@gmail.com",
    "meta": null,
    "created_at": "2023-05-20T14:08:32.000000Z",
    "updated_at": "2023-05-22T23:01:20.000000Z",
    "branches": [
    {
    "id": 1,
    "organization_id": 1,
    "address": "East-West Road, Rivers State, Nigeria",
    "meta": null,
    "created_at": "2023-05-20T14:08:58.000000Z",
    "updated_at": "2023-05-22T23:18:00.000000Z"
    },
    {
    "id": 2,
    "organization_id": 1,
    "address": "OWERRI ROAD, TRAILER PARK",
    "meta": null,
    "created_at": "2023-05-20T14:09:29.000000Z",
    "updated_at": "2023-05-20T14:09:29.000000Z"
    }
    ]
    }
    }
     * @responseField status The status of this API.
     * @responseField organization details and Map of branches assigned to authenticated user's organization.
     */
    public function show(): JsonResponse
    {
        $user = auth()->user();

        $organizationUser = OrganizationUser::where('user_id', $user->id)->first();

        $organization = $organizationUser->organization;
        $branches = $organization->branches;

        return response()->json([
            'status' => true,
            'organization' => $organization
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $validateData = Validator::make($request->all(),
            [
                'name' => 'nullable',
                'email' => 'nullable',
                'phone' => 'nullable'
            ]);

            if($validateData->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateData->errors()
                ], 401);
            }

            $organization = Organization::find($id);

            $organization->update($request->all());

            return response()->json([
                'status' => true,
                'message' => "Organization Updated successfully!",
                'organization' => $organization
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
