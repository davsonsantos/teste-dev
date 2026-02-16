<?php

namespace App\Models;

use App\Mail\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class User extends Authenticatable implements Auditable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, AuditableTrait, SoftDeletes;

    protected $appends = ['avatar'];


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
            'is_active' => 'boolean',
            'enable_2fa' => 'boolean',
        ];
    }

    public function sendPasswordResetNotification($token): void
    {
        // Enviamos o Mailable personalizado passando o token e o usuário
        \Illuminate\Support\Facades\Mail::to($this->email)->send(
            new ResetPasswordNotification($token, $this)
        );
    }

    public function getAvatarAttribute()
    {
        if (! array_key_exists('avatar_path', $this->attributes)) {
            return null;
        }

        if ($this->avatar_path && Storage::disk('public')->exists($this->avatar_path)) {
            return asset('storage/' . $this->avatar_path);
        }
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn($word) => Str::substr($word, 0, 1))
            ->implode('');
    }


    public function transformAudit(array $data): array
    {
        if (session()->has('impersonator_user_id')) {
            $data['impersonator_id'] = session()->get('impersonator_user_id');
        }

        return $data;
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}
