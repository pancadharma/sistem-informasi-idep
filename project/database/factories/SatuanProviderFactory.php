<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Provider\Base;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SatuanProviderFactory extends Base
{

    protected static $units = [
        'Kilogram', 'Gram', 'Liter', 'Milliliter', 'Meter', 'Centimeter', 'Millimeter', 'Inch', 'Foot', 'Yard', 'Piece', 'Dozen'
    ];

    public function unitName()
    {
        return static::randomElement(static::$units);
    }
}
