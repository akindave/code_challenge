<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalAction extends Model
{
    use HasFactory;

    public const ACTION_APPROVE = 'approve';
    public const ACTION_REJECT = 'reject';

    protected $fillable = [
        'request_id',
        'approver_id',
        'action',
        'comments',
    ];

    protected $casts = [
        'action' => 'string',
    ];

    /**
     * Relationship with the Request model
     */
    public function request(): BelongsTo
    {
        return $this->belongsTo(Request::class);
    }

    /**
     * Relationship with the User model (approver)
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    /**
     * Scope for approval actions
     */
    public function scopeApprovals($query)
    {
        return $query->where('action', self::ACTION_APPROVE);
    }

    /**
     * Scope for rejection actions
     */
    public function scopeRejections($query)
    {
        return $query->where('action', self::ACTION_REJECT);
    }

    /**
     * Check if action is approval
     */
    public function isApproval(): bool
    {
        return $this->action === self::ACTION_APPROVE;
    }

    /**
     * Check if action is rejection
     */
    public function isRejection(): bool
    {
        return $this->action === self::ACTION_REJECT;
    }

    /**
     * Get human-readable action name
     */
    public function getActionLabel(): string
    {
        return match ($this->action) {
            self::ACTION_APPROVE => 'Approved',
            self::ACTION_REJECT => 'Rejected',
            default => ucfirst($this->action),
        };
    }

    /**
     * Get all available actions
     */
    public static function getAvailableActions(): array
    {
        return [
            self::ACTION_APPROVE,
            self::ACTION_REJECT,
        ];
    }

    /**
     * Get action label for select options
     */
    public static function getActionOptions(): array
    {
        return [
            self::ACTION_APPROVE => 'Approve',
            self::ACTION_REJECT => 'Reject',
        ];
    }
}