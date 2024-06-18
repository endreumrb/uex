<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCPF implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Remove caracteres não numéricos do CPF
        $cpf = preg_replace('/\D/', '', $value);

        // Verifica se o CPF possui 11 dígitos
        if (strlen($cpf) !== 11) {
            $fail('O campo :attribute deve conter 11 dígitos.');
            return;
        }

        // Verifica se todos os dígitos são iguais (CPF inválido)
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            $fail('O campo :attribute não é um CPF válido.');
            return;
        }

        // Calcula o primeiro dígito verificador
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += (int)$cpf[$i] * (10 - $i);
        }
        $digit1 = 11 - ($sum % 11);
        if ($digit1 >= 10) {
            $digit1 = 0;
        }

        // Calcula o segundo dígito verificador
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += (int)$cpf[$i] * (11 - $i);
        }
        $sum += $digit1 * 2;
        $digit2 = 11 - ($sum % 11);
        if ($digit2 >= 10) {
            $digit2 = 0;
        }

        // Verifica se os dígitos verificadores estão corretos
        if ($cpf[9] != $digit1 || $cpf[10] != $digit2) {
            $fail('O campo :attribute não é um CPF válido.');
        }
    }
}