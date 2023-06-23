<?php

namespace Vibraniuum\Pamtechoga\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Vibraniuum\Pamtechoga\Models\Branch;
use Vibraniuum\Pamtechoga\Models\Order;
use Vibraniuum\Pamtechoga\Models\Organization;
use Vibraniuum\Pamtechoga\Models\OrganizationUser;
use Vibraniuum\Pamtechoga\Models\Payment;
use Vibraniuum\Pamtechoga\Models\PaymentDetail;

/**
 * @group Order management
 *
 * APIs for managing orders
 */
class PaymentController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();

        $userOrganization = OrganizationUser::where('user_id', $user->id)->first();

        $organization = $userOrganization->organization;

        $all_time = (bool)$request->query('all_time');
        $status = $request->query('status');

        if(!$all_time) {
            $startDate = Carbon::createFromFormat('Y-m-d', $request->query('start_date'))->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $request->query('end_date'))->endOfDay();

            $payments = Payment::where('organization_id', $organization->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', $status)
                ->orderBy('created_at', 'desc')
                ->with('organization')
                ->paginate(50);
        } else {
            $payments = Payment::where('organization_id', $organization->id)
                ->where('status', $status)
                ->orderBy('created_at', 'desc')
                ->with('organization')
                ->paginate(50);
        }


        return response()->json([
            'status' => true,
            'data' => $payments,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @return JsonResponse
     */
    public function paymentDetails(): JsonResponse
    {
        $paymentDetails = PaymentDetail::orderBy('created_at', 'desc')->first();

        return response()->json([
            'status' => true,
            'data' => $paymentDetails,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @return JsonResponse
     */
    public function show(): JsonResponse
    {
        $user = auth()->user();

        $organizationUser = OrganizationUser::where('user_id', $user->id)->first();

        $organization = $organizationUser->organization;
        $branches = $organization->branches;

        $organization['branches'] = $branches;

        return response()->json([
            'status' => true,
            'organization' => $organization
        ]);
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
                    'amount' => 'required',
                    'payment_date' => 'required',
                    'reference_photo' => 'nullable',
                    'reference_description' => 'nullable',
                ]);

            if($validateData->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateData->errors()
                ], 401);
            }

            $user = auth()->user();

            $userOrganization = OrganizationUser::where('user_id', $user->id)->first();

            $organization = $userOrganization->organization;

            $payment = Payment::create([
                'type' => 'DEBT',
                'user_id' => $user->id,
                'organization_id' => $organization->id,
                ...$request->all()
            ]);

            $paymentResource = Payment::find($payment->id)->first();

            return response()->json([
                'status' => true,
                'message' => "Payment created successfully!",
                'data' => $paymentResource
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
     * @param  int  $id
     * @return JsonResponse
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

            $branch->update($request->all());

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
}
