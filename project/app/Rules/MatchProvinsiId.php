<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;

class MatchProvinsiId implements Rule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    protected $provinsiId;

    public function __construct($provinsiId)
    {
        $this->provinsiId = $provinsiId;
    }

    public function passes($attribute, $value)
    {
        return substr($value, 0, 2) === $this->provinsiId;
    }

    public function message()
    {
        return 'The :attribute must start with the first two digits of the Provinsi Kode.';
    }
}
