<?php

namespace App\Observers;

use App\Models\AdminActivityLog;
use Illuminate\Database\Eloquent\Model;

class AdminActivityObserver
{
    public function created(Model $record): void
    {
        $this->record($record, 'created', array_keys($record->getAttributes()));
    }

    public function updated(Model $record): void
    {
        $this->record($record, 'updated', array_keys($record->getChanges()));
    }

    public function deleted(Model $record): void
    {
        $this->record($record, 'deleted', []);
    }

    private function record(Model $record, string $action, array $fields): void
    {
        $user = auth()->user();
        if (! $user?->is_admin) {
            return;
        }

        $sensitiveFields = ['password', 'remember_token', 'created_at', 'updated_at'];
        $changedFields = array_values(array_diff($fields, $sensitiveFields));
        if (in_array('email', $changedFields, true)) {
            $changedFields = array_values(array_diff($changedFields, ['email']));
            $changedFields[] = 'email (updated)';
        }

        AdminActivityLog::create([
            'user_id' => $user->id,
            'action' => $action,
            'module' => $record->getTable(),
            'subject_type' => $record::class,
            'subject_id' => (string) $record->getKey(),
            'changed_fields' => $changedFields,
            'created_at' => now(),
        ]);
    }
}
