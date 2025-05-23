<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
* @method static static OptionOne()
* @method static static OptionTwo()
* @method static static OptionThree()
*/
final class TargetProgressRisk extends Enum
{
	const UNSET     = 'unset';
	const NONE      = 'none';
	const MEDIUM    = 'medium';
	const HIGH      = 'high';
	
	public static function getDescription($value): string
	{
		return __("enums.target_progress_risk.{$value}");
	}
	public function text(): string
	{
		return __("enums.target_progress_risk.{$this->value}");
	}
}
