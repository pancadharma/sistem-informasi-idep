<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
* @method static static OptionOne()
* @method static static OptionTwo()
* @method static static OptionThree()
*/
final class TargetProgressStatus extends Enum
{
	const UNSET             = 'unset';
	const TO_BE_CONDUCTED   = 'to_be_conducted';
	const COMPLETED         = 'completed';
	const ONGOING           = 'ongoing';
	
	public static function getDescription($value): string
	{
		return __("enums.target_progress_status.{$value}");
	}
	public function text(): string
	{
		return __("enums.target_progress_status.{$this->value}");
	}
}
