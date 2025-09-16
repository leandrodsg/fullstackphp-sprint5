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
        if (strlen($value) < 10) {
            $fail('A senha deve ter pelo menos 10 caracteres.');
            return;
        }

        if (!preg_match('/[a-z]/', $value)) {
            $fail('A senha deve conter pelo menos uma letra minúscula.');
            return;
        }

        if (!preg_match('/[A-Z]/', $value)) {
            $fail('A senha deve conter pelo menos uma letra maiúscula.');
            return;
        }

        if (!preg_match('/[0-9]/', $value)) {
            $fail('A senha deve conter pelo menos um número.');
            return;
        }

        if (!preg_match('/[!@#$%&*]/', $value)) {
            $fail('A senha deve conter pelo menos um caractere especial: ! @ # $ % & *');
            return;
        }
    }
}
