<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\NewAccessToken;

final class User extends Authenticatable
{
    public const string API_TOKEN_NAME = 'api-token';

    public const array API_TOKEN_ABILITIES = [
        'api:access',
    ];

    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'role',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function createApiToken(): NewAccessToken
    {
        return $this->createToken(
            self::API_TOKEN_NAME,
            self::API_TOKEN_ABILITIES,
            now()->addMinutes((int) config('sanctum.expiration', 1440)),
        );
    }
}
