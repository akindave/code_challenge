<?php

namespace App\Repositories\Eloquent;

use App\Models\Request;
use App\Repositories\Contracts\RequestRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class RequestRepository implements RequestRepositoryInterface
{
    public function __construct(private Request $model) {}

    public function findById(int $id): ?Request
    {
        return $this->model->with(['user', 'department', 'actions.approver'])->find($id);
    }

    public function getAll(): Collection
    {
        return $this->model->with(['user', 'department'])->get();
    }

    public function getPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with(['user', 'department'])->paginate($perPage);
    }

    public function create(array $data): Request
    {
        return $this->model->create($data);
    }

    public function update(Request $request, array $data): Request
    {
        $request->update($data);
        return $request;
    }

    public function delete(Request $request): bool
    {
        return $request->delete();
    }

    public function getPendingRequestsForUser(int $userId): Collection
    {
        return $this->model->whereHas('department.approvers', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->where('status', Request::STATUS_PENDING)
        ->with(['user', 'department'])
        ->get();
    }

    public function getRequestApprovalHistory(int $requestId): Collection
    {
        return $this->model->with(['actions.approver'])->findOrFail($requestId)->actions;
    }
}