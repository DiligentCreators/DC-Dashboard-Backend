<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UsernameRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // username is required
        if (empty($value)) {
            $fail("The {$attribute} is required.");

            return;
        }

        // Username must be between 3 and 65 characters
        if (strlen($value) < 3 || strlen($value) > 65) {
            $fail("The {$attribute} must be between 3 and 65 characters.");

            return;
        }

        // Username is unique
        $user = User::where('username', $value)->first();
        if ($user) {
            $fail("The {$value} is already registered.");

            return;
        }
    }
}
