<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class CategoryComplaint extends Enum
{
	const Appreciation = 'appreciation';
	const Category_1   = 'category_1';
	const Category_2   = 'category_2';
	const Category_3   = 'category_3';
	const Category_4   = 'category_4';
	const Category_5   = 'category_5';
	const Category_6   = 'category_6';
	
	public static function getDescription($value): string
	{
		return __("enums.category_complaint.{$value}");
	}	public function text(): string
	{
		return __("enums.category_complaint.{$this->value}");
	}
}
