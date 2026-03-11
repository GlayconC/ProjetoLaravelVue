<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CpfCnpj implements ValidationRule
{

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = preg_replace('/[^0-9]/', '', $value);

        if (strlen($value) === 11) {
            if (!$this->validarCpf($value)) {
                $fail('Deve ser um CPF válido.');
            }
        } elseif (strlen($value) === 14) {
            if (!$this->validarCnpj($value)) {
                $fail('Deve ser um CNPJ válido.');
            }
        } else {
            $fail('O campo deve ser um CPF ou CNPJ válido.');
        }
    }

    private function validarCpf($cpf): bool {
        if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            $sum = 0;
            for ($i = 0; $i < $t; $i++) {
                $sum += $cpf[$i] * ($t + 1 - $i);
            }
            $remainder = (10 * $sum) % 11 % 10;
            if ($cpf[$t] != $remainder) {
                return false;
            }
        }

        return true;
    }

    private function validarCnpj(string $cnpj): bool
    {
        if (strlen($cnpj) !== 14 || preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }

        $multipliers1 = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $multipliers2 = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        for ($t = 0; $t < 2; $t++) {
            $multipliers = $t === 0 ? $multipliers1 : $multipliers2;
            $sum = 0;
            for ($i = 0; $i < count($multipliers); $i++) {
                $sum += $cnpj[$i] * $multipliers[$i];
            }
            $remainder = $sum % 11 < 2 ? 0 : 11 - ($sum % 11);
            if ($cnpj[count($multipliers)] != $remainder) {
                return false;
            }
        }

        return true;
    }

}
