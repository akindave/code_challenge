<?php

namespace App\Http\Controllers\Api\Approver;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Approver\StoreApproverRequest;
use App\Http\Requests\Api\Request\UpdateApproverRequest;
use App\Http\Resources\ApproverResource;
use App\Models\Department;
use App\Models\DepartmentApprover;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class ApproverController extends Controller
{
    public function index(Department $department): AnonymousResourceCollection
    {
    
        return ApproverResource::collection(
            $department->approvers()
                ->with(['user', 'approvalLevel'])
                ->get()
        );
    }

    public function store(StoreApproverRequest $request, Department $department): JsonResponse
    {

        $approver = $department->approvers()->create($request->validated());

        return response()->json([
            'message' => 'Approver added successfully',
            'approver' => new ApproverResource($approver->load(['user', 'approvalLevel'])),
        ], Response::HTTP_CREATED);
    }

    public function update(
        UpdateApproverRequest $request, 
        Department $department,
        DepartmentApprover $approver
    ): JsonResponse {
        
        $approver->update($request->validated());

        return response()->json([
            'message' => 'Approver updated successfully',
            'approver' => new ApproverResource($approver->load(['user', 'approvalLevel'])),
        ]);
    }

    public function destroy(Department $department, DepartmentApprover $approver): JsonResponse
    {

        $approver->delete();

        return response()->json([
            'message' => 'Approver removed successfully'
        ]);
    }
}