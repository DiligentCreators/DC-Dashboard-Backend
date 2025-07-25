<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreStaffUserRequest extends FormRequest
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
    protected function prepareForValidation()
    {
        $this->merge([
            'is_admin' => $this->input('is_admin', 1),

        ]);
    }

    public function rules(): array
    {
        return [

            'email_verified_at' => 'nullable|in:yes,no',
            'username' => 'nullable|string|unique:users,username',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'is_admin' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
            'is_suspended' => 'sometimes|boolean',
        ];
    }
}
