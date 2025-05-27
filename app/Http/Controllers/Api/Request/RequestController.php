<?php

namespace App\Http\Controllers\Api\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Request\ApproveRequestRequest;
use App\Http\Requests\Api\Request\CreateRequestRequest;
use App\Http\Requests\Api\Request\RejectRequestRequest;
use App\Http\Resources\RequestResource;
use App\Models\Request as RequestModel;
use App\Services\ApprovalService;
use GuzzleHttp\Psr7\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class RequestController extends Controller
{
    public function __construct(
        private ApprovalService $approvalService
    ) {}

    public function index(): AnonymousResourceCollection
    {
        return RequestResource::collection(
            RequestModel::where('user_id', auth()->id())
                ->with(['user', 'department', 'actions.approver'])
                ->latest()
                ->paginate()
        );
    }

    public function pending(): AnonymousResourceCollection
    {
        return RequestResource::collection(
            RequestModel::whereHas('department.approvers', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->where('status', RequestModel::STATUS_PENDING)
            ->with(['user', 'department'])
            ->latest()
            ->paginate()
        );
    }

    public function store(CreateRequestRequest $request): JsonResponse
    {
        $requestModel = RequestModel::create([
            'user_id' => auth()->id(),
            'department_id' => $request->department_id,
            'title' => $request->title,
            'description' => $request->description,
            'status' => RequestModel::STATUS_PENDING,
            'current_level' => 1,
        ]);

        return response()->json([
            'message' => 'Request created successfully',
            'request' => new RequestResource($requestModel),
        ], Response::HTTP_CREATED);
    }

    public function show(RequestModel $request): JsonResponse
    {
       
        return response()->json(
            new RequestResource($request->load(['user', 'department', 'actions.approver']))
        );
    }

    public function approve(ApproveRequestRequest $request, $requestModel)
    {
        $requestModel = RequestModel::where('id',$requestModel)->first();
        $requestModel = $this->approvalService->approveRequest(
            $requestModel,
            auth()->user(),
            $request->comments
        );

        return response()->json([
            'message' => 'Request approved successfully',
            'request' => new RequestResource($requestModel),
        ]);
    }

    public function reject(RejectRequestRequest $request, RequestModel $requestModel): JsonResponse
    {
       
        $requestModel = $this->approvalService->rejectRequest(
            $requestModel,
            auth()->user(),
            $request->comments
        );

        return response()->json([
            'message' => 'Request rejected successfully',
            'request' => new RequestResource($requestModel),
        ]);
    }

    public function history(RequestModel $request): JsonResponse
    {
        return response()->json(
            RequestResource::collection($request->actions)
        );
    }
}