<?php

namespace Vibraniuum\Pamtechoga\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Vibraniuum\Pamtechoga\Models\Branch;
use Vibraniuum\Pamtechoga\Models\Organization;
use Vibraniuum\Pamtechoga\Models\OrganizationUser;

/**
 * @group Branch management
 *
 * APIs for managing branches
 */
class BranchController extends Controller
{
    /**
     * Branches
     *
     * List of all branches belonging to the organization of the authenticated user
     *
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
    public function index(): JsonResponse
    {
        $user = auth()->user();

        $organizationUser = OrganizationUser::where('user_id', $user->id)->first();

        $organization = $organizationUser->organization;

        $branches = $organization->branches;

        return response()->json([
            'status' => true,
            'organization' => $organization,
        ]);
    }


    /**
     * Show Branch
     *
     * See details of a given branch
     *
     * @urlParam id integer required The ID of the branch.
     * @response 200 scenario="Updated successfully"
     * {
    "status": true,
    "branch": {
    "id": 1,
    "organization_id": 1,
    "address": "East-West Road, Rivers State, Nigeria",
    "meta": null,
    "created_at": "2023-05-20T14:08:58.000000Z",
    "updated_at": "2023-05-22T23:18:00.000000Z"
    }
    }
     * @responseField status The status of this API.
     * @responseField branch details of updated branch.
     */
    public function show(int $id): JsonResponse
    {


        $branch = Branch::where('id', $id)->first();

        return response()->json([
            'status' => true,
            'branch' => $branch,
        ]);
    }

    /**
     * Update Branch
     *
     * Make changes to a given branch
     *
     * @urlParam id integer required The ID of the branch.
     * @response 200 scenario="Updated successfully"
     * {
    "status": true,
    "message": "Branch Updated successfully!",
    "branch": {
    "id": 1,
    "organization_id": 1,
    "address": "East-West Road, Rivers State, Nigeria",
    "meta": null,
    "created_at": "2023-05-20T14:08:58.000000Z",
    "updated_at": "2023-05-22T23:18:00.000000Z"
    }
    }
     * @responseField status The status of this API.
     * @responseField branch details of updated branch.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $validateData = Validator::make($request->all(),
            [
                'address' => 'nullable',
            ]);

            if($validateData->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateData->errors()
                ], 401);
            }

            $branch = Branch::find($id);

            $branch->update($request->all()); // check why $request->all() is not working...

            return response()->json([
                'status' => true,
                'message' => "Branch Updated successfully!",
                'branch' => $branch
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validateData = Validator::make($request->all(),
                [
                    'address' => 'required',
                ]);

            if($validateData->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateData->errors()
                ], 401);
            }

            $user = auth()->user();

            $organizationUser = OrganizationUser::where('user_id', $user->id)->first();

            $organization = $organizationUser->organization;

            $branch = Branch::create([
                'organization_id' => $organization->id,
                ...$request->all()
            ]);

            $branchResource = Branch::find($branch->id);

            return response()->json([
                'status' => true,
                'message' => "Branch Created successfully!",
                'data' => $branchResource
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

}
