<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class TicketObserver
{
    public function created(Ticket $ticket): void
    {
        ActivityLog::record(
            $ticket->id,
            Auth::id(),
            'ticket_created',
            "Ticket #{$ticket->ticket_number} was created."
        );
    }

    public function updated(Ticket $ticket): void
    {
        $ignoredFields = ['updated_at', 'created_at'];

        $changedFields = array_diff(array_keys($ticket->getDirty()), $ignoredFields);

        foreach ($changedFields as $field) {
            $old = $ticket->getOriginal($field);
            $new = $ticket->getAttribute($field);

            [$action, $description] = $this->buildLogMessage($field, $old, $new, $ticket);

            ActivityLog::record(
                $ticket->id,
                Auth::id(),
                $action,
                $description,
                $field,
                $old,
                $new
            );
        }
    }

    public function deleted(Ticket $ticket): void
    {
        ActivityLog::record(
            $ticket->id,
            Auth::id(),
            'ticket_deleted',
            "Ticket #{$ticket->ticket_number} was deleted."
        );
    }

    protected function buildLogMessage(string $field, $old, $new, Ticket $ticket): array
    {
        switch ($field) {
            case 'status':
                return ['status_changed', "Status changed from '{$old}' to '{$new}'."];

            case 'priority':
                return ['priority_changed', "Priority changed from '{$old}' to '{$new}'."];

            case 'assigned_to':
                $oldName = optional(\App\Models\User::find($old))->name ?? 'Unassigned';
                $newName = optional(\App\Models\User::find($new))->name ?? 'Unassigned';
                return ['assigned', "Ticket reassigned from {$oldName} to {$newName}."];

            case 'department_id':
                $oldDept = optional(\App\Models\Department::find($old))->name ?? 'N/A';
                $newDept = optional(\App\Models\Department::find($new))->name ?? 'N/A';
                return ['department_changed', "Department changed from {$oldDept} to {$newDept}."];

            default:
                return ['field_updated', "Field '{$field}' changed from '{$old}' to '{$new}'."];
        }
    }
}