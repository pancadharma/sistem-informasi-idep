<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MatchKabupatenID implements Rule
{
    protected $kabupatenID;

    public function __construct($kabupatenID)
    {
        $this->kabupatenID = $kabupatenID;
    }

    public function passes($attribute, $value)
    {
        return substr($value, 0, 5) === $this->kabupatenID;
    }

    public function message()
    {
        return "The :attribute must start with the first five digits of the " . __("cruds.kabupaten.title") . " " .__("cruds.kabupaten.kode");
    }
}
