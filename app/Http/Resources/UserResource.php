<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\User;
use DateTimeInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use LogicException;

final class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $user = $this->resource;

        if (! $user instanceof User) {
            throw new LogicException('Resource pengguna tidak valid.');
        }

        return [
            'id' => $user->id,
            'username' => $user->username,
            'role' => $user->role,
            'created_at' => self::formatTimestamp($user->getAttribute('created_at')),
            'updated_at' => self::formatTimestamp($user->getAttribute('updated_at')),
        ];
    }

    private static function formatTimestamp(mixed $value): ?string
    {
        return $value instanceof DateTimeInterface ? $value->format(DateTimeInterface::ATOM) : null;
    }
}
