<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StrongPassword implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (strlen($value) < 12) {
            $fail('The password must be at least 12 characters long.');
            return;
        }

        if (!preg_match('/[a-z]/', $value)) {
            $fail('The password must contain at least one lowercase letter.');
            return;
        }

        if (!preg_match('/[A-Z]/', $value)) {
            $fail('The password must contain at least one uppercase letter.');
            return;
        }

        if (!preg_match('/[0-9]/', $value)) {
            $fail('The password must contain at least one number.');
            return;
        }

        if (!preg_match('/[!@#$%&*]/', $value)) {
            $fail('The password must contain at least one special character: ! @ # $ % & *');
            return;
        }
    }
}
