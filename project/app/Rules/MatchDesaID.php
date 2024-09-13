<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MatchDesaID implements ValidationRule
{

    protected string $KodeDesa;

    public function __construct($KodeDesa) {
        $this->KodeDesa = $KodeDesa;
    }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (substr($value, 0, 13) !== $this->KodeDesa) {
            $fail("The $attribute must start with the first 13 characters of the village code.");
        }
    }
    public function message(): string
    {
        return __("The :attribute must start with the the first 13 characters of :desa_title :desa_code followed by 2 remaining :dusun_title :dusun_code", [
            'desa_title' => __("cruds.desa.title"),
            'desa_code' => __("cruds.desa.kode"),
            'dusun_title' => __("cruds.dusun.title"),
            'dusun_code' => __("cruds.dusun.form.kode"),
        ]);
    }
}
