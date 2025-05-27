<?php

namespace App\Repositories\Contracts;

use App\Models\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface RequestRepositoryInterface
{
    public function findById(int $id): ?Request;
    
    public function getAll(): Collection;
    
    public function getPaginated(int $perPage = 15): LengthAwarePaginator;
    
    public function create(array $data): Request;
    
    public function update(Request $request, array $data): Request;
    
    public function delete(Request $request): bool;
    
    public function getPendingRequestsForUser(int $userId): Collection;
    
    public function getRequestApprovalHistory(int $requestId): Collection;
}