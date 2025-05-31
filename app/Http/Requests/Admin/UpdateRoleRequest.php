<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

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
        $roleId = $this->route('id'); // assuming you are passing role ID in route

        return [
            'name' => 'required|string|unique:roles,name,'.$roleId,
            'guard_name' => ['required', 'in:web'],
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ];

    }
}
