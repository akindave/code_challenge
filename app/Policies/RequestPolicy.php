<?php

namespace App\Policies;

use App\Models\Request;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RequestPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Request $request): bool
    {
        return $request->user_id === $user->id || 
               $request->department->approvers()->where('user_id', $user->id)->exists();
    }

    public function approve(User $user, Request $request): bool
    {
        return $request->department->approvers()
            ->where('user_id', $user->id)
            ->where('approval_level_id', $request->current_level)
            ->exists();
    }

    public function delete(User $user, Request $request): bool
    {
        return $request->user_id === $user->id && 
               $request->status === Request::STATUS_PENDING;
    }
}