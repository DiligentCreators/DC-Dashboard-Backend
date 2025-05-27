<?php

namespace App\Http\Requests\Admin;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
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
    public function rules()
    {

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique(Role::class)->ignore($this->role),
            ],
            'guard_name' => ['nullable', 'in:web,sanctum'],
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ];

    }
}
