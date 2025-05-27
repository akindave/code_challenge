<?php

namespace App\Services;

use App\Models\Request;
use App\Models\ApprovalAction;
use App\Models\User;
use App\Notifications\RequestApprovedNotification;
use App\Notifications\RequestRejectedNotification;
use App\Repositories\Contracts\RequestRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ApprovalService
{
    public function __construct(
        private RequestRepositoryInterface $requestRepository
    ) {}

    public function approveRequest(Request $request, User $approver, ?string $comments): Request
    {
        return DB::transaction(function () use ($request, $approver, $comments) {
            // Record approval action
            ApprovalAction::create([
                'request_id' => $request->id,
                'approver_id' => $approver->id,
                'action' => ApprovalAction::ACTION_APPROVE,
                'comments' => $comments,
            ]);

            // Check if there are more levels to approve
            $nextApprovers = $request->getNextApprovers();

            if ($nextApprovers) {
                $request->update([
                    'current_level' => $nextApprovers->approval_level_id,
                ]);
                
                // Notify next approver
                Notification::send($nextApprovers->user, new RequestApprovedNotification($request));
            } else {
                $request->update([
                    'status' => Request::STATUS_APPROVED,
                ]);
                
                // Notify requester
                Notification::send($request->user, new RequestApprovedNotification($request));
            }

            return $request->fresh();
        });
    }

    public function rejectRequest(Request $request, User $approver, ?string $comments): Request
    {
        return DB::transaction(function () use ($request, $approver, $comments) {
            // Record rejection action
            ApprovalAction::create([
                'request_id' => $request->id,
                'approver_id' => $approver->id,
                'action' => ApprovalAction::ACTION_REJECT,
                'comments' => $comments,
            ]);

            // Update request status
            $request->update([
                'status' => Request::STATUS_REJECTED,
            ]);

            // Notify requester
            Notification::send($request->user, new RequestRejectedNotification($request));

            return $request->fresh();
        });
    }
}