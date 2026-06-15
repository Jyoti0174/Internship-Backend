<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'status'      => $this->status,
            'priority'    => $this->priority,

            'user' => $this->whenLoaded('user', function () {
                return [
                    'id'    => $this->user->id,
                    'name'  => $this->user->name,
                    'email' => $this->user->email,
                ];
            }),

            'assigned_to' => $this->whenLoaded('assignedTo', function () {
                return $this->assignedTo ? [
                    'id'   => $this->assignedTo->id,
                    'name' => $this->assignedTo->name,
                ] : null;
            }),

            'department' => $this->whenLoaded('department', function () {
                return $this->department ? [
                    'id'   => $this->department->id,
                    'name' => $this->department->name,
                ] : null;
            }),

            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}