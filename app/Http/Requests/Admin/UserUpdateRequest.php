<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('user');

        return [
            'username' => 'nullable|string|unique:users,username,'.$userId.',id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$userId.',id',
            'password' => 'nullable|string|min:8|confirmed',
            'is_admin' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
            'is_suspended' => 'sometimes|boolean',
            'role' => 'sometimes|string|exists:roles,name',
        ];
    }
}
