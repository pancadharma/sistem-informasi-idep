<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MatchKecamatanID implements Rule
{
    protected $kecamatanID;

    public function __construct($kecamatanID)
    {
        $this->kecamatanID = $kecamatanID;
    }

    public function passes($attribute, $value)
    {
        return substr($value, 0, 8) === $this->kecamatanID;
    }

    public function message()
    {
        return "The :attribute must start with of the " . __("cruds.kecamatan.title") . " " .__("cruds.kecamatan.kode") ." followed by remain ".__("cruds.desa.title") ." ".__("cruds.desa.form.kode");
    }
}
