<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_admin && in_array($this->role, [
            'super_admin',
            'admin',
            'content_manager',
            'enquiry_manager',
        ], true);
    }

    public function canAccessModule(string $module): bool
    {
        return match ($this->role) {
            'super_admin' => true,
            'admin' => in_array($module, [
                'dashboard',
                'products',
                'categories',
                'collections',
                'banners',
                'gallery',
                'pages',
                'testimonials',
                'enquiries',
                'customers',
                'settings',
            ], true),
            'content_manager' => in_array($module, [
                'dashboard',
                'products',
                'categories',
                'collections',
                'banners',
                'gallery',
                'pages',
                'testimonials',
            ], true),
            'enquiry_manager' => in_array($module, [
                'dashboard',
                'enquiries',
                'customers',
            ], true),
            default => false,
        };
    }

    public function isLastSuperAdmin(): bool
    {
        return $this->is_admin
            && $this->role === 'super_admin'
            && static::query()->where('is_admin', true)->where('role', 'super_admin')->count() <= 1;
    }
}
