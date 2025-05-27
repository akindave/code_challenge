<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RequestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'current_level' => $this->current_level,
            'user' => $this->whenLoaded('user', fn() => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ]),
            'department' => $this->whenLoaded('department', fn() => [
                'id' => $this->department->id,
                'name' => $this->department->name,
            ]),
            'actions' => $this->whenLoaded('actions', fn() => 
                $this->actions->map(function ($action) {
                    return [
                        'id' => $action->id,
                        'action' => $action->action,
                        'action_label' => $action->getActionLabel(),
                        'comments' => $action->comments,
                        'approver' => [
                            'id' => $action->approver->id,
                            'name' => $action->approver->name,
                        ],
                        'created_at' => $action->created_at->toDateTimeString(),
                    ];
                })
            ),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}