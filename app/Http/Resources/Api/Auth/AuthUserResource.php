<?php

namespace App\Http\Resources\Api\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Generate Token
        $token = $this->createToken($this->email)->plainTextToken;

        return [
            'id' => $this->id,
            'username' => $this->username,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'is_active' => $this->is_active,
            'is_suspended' => $this->is_suspended,
            'is_locked' => $this->is_locked,
            'is_admin' => $this->is_admin,
            'created_at' => $this->created_at,
            'type' => 'Bearer',
            'token' => $token,
        ];
    }
}
