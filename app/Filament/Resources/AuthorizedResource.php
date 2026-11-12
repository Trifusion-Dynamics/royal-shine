<?php

namespace App\Filament\Resources;

use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;

abstract class AuthorizedResource extends Resource
{
    protected static string $permissionModule;

    protected static bool $readOnly = false;

    public static function canViewAny(): bool
    {
        return auth()->user()?->canAccessModule(static::$permissionModule) ?? false;
    }

    public static function canCreate(): bool
    {
        return ! static::$readOnly && static::canViewAny();
    }

    public static function canEdit(Model $record): bool
    {
        return static::canCreate();
    }

    public static function canDelete(Model $record): bool
    {
        return static::canCreate();
    }

    public static function canDeleteAny(): bool
    {
        return static::canCreate();
    }
}
