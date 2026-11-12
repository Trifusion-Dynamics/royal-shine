<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($this->getRecord()->isLastSuperAdmin() && $data['role'] !== 'super_admin') {
            throw ValidationException::withMessages([
                'data.role' => 'The last super admin cannot be demoted.',
            ]);
        }

        $data['is_admin'] = true;

        return $data;
    }
}
