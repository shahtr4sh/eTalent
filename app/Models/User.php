<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Filament\Panel;

class User extends Authenticatable
{

    public function canAccessPanel(Panel $panel): bool
    {
        // Allow semua user authenticated masuk panel "app"
        if ($panel->getId() === 'app') {
            return true;
        }

        // Untuk panel admin, anda juga mahu allow semua:
        if ($panel->getId() === 'admin') {
            return true;
        }

        return false;
    }

    public function pemohon()
    {
        return $this->hasOne(\App\Models\Pemohon::class);
    }

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
        ];
    }

    public function promotionApplications()
    {
        return $this->hasMany(\App\Models\PromotionApplication::class, 'staff_id', 'staff_id');
    }

}
