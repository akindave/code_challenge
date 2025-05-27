<?php

namespace App\Http\Controllers\Api\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Request\ApproveRequestRequest;
use App\Http\Requests\Api\Request\CreateRequestRequest;
use App\Http\Requests\Api\Request\RejectRequestRequest;
use App\Http\Resources\Request\RequestResource;
use App\Models\Request;
use App\Services\ApprovalService;
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
        $requests = Request::with(['user', 'department', 'actions.approver'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate();

        return RequestResource::collection($requests);
    }

    public function store(CreateRequestRequest $request): JsonResponse
    {
        $requestModel = Request::create([
            'user_id' => auth()->id(),
            'department_id' => $request->department_id,
            'title' => $request->title,
            'description' => $request->description,
            'status' => Request::STATUS_PENDING,
            'current_level' => 1, // Start with first approval level
        ]);

        return response()->json(
            new RequestResource($requestModel->load(['user', 'department'])),
            Response::HTTP_CREATED
        );
    }

    public function show(Request $request): JsonResponse
    {
        $this->authorize('view', $request);

        return response()->json(
            new RequestResource($request->load(['user', 'department', 'actions.approver']))
        );
    }

    public function pending(): AnonymousResourceCollection
    {
        $requests = Request::whereHas('department.approvers', function ($query) {
            $query->where('user_id', auth()->id());
        })
        ->where('status', Request::STATUS_PENDING)
        ->with(['user', 'department'])
        ->latest()
        ->paginate();

        return RequestResource::collection($requests);
    }

    public function approve(ApproveRequestRequest $request, Request $requestModel): JsonResponse
    {
        $this->authorize('approve', $requestModel);

        $requestModel = $this->approvalService->approveRequest(
            $requestModel,
            auth()->user(),
            $request->comments
        );

        return response()->json(
            new RequestResource($requestModel->load(['user', 'department', 'actions.approver']))
        );
    }

    public function reject(RejectRequestRequest $request, Request $requestModel): JsonResponse
    {
        $this->authorize('approve', $requestModel);

        $requestModel = $this->approvalService->rejectRequest(
            $requestModel,
            auth()->user(),
            $request->comments
        );

        return response()->json(
            new RequestResource($requestModel->load(['user', 'department', 'actions.approver']))
        );
    }
}