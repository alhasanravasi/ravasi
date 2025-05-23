<?php
/*
 * LaraClassifier - Classified Ads Web Application
 * Copyright (c) BeDigit. All Rights Reserved
 *
 * Website: https://laraclassifier.com
 * Author: Mayeul Akpovi (BeDigit - https://bedigit.com)
 *
 * LICENSE
 * -------
 * This software is provided under a license agreement and may only be used or copied
 * in accordance with its terms, including the inclusion of the above copyright notice.
 * As this software is sold exclusively on CodeCanyon,
 * please review the full license details here: https://codecanyon.net/licenses/standard
 */

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class LocaleOfCountryRule implements ValidationRule
{
	public ?string $countryCode = null;
	
	public function __construct(?string $countryCode)
	{
		$this->countryCode = $countryCode;
	}
	
	/**
	 * Run the validation rule.
	 */
	public function validate(string $attribute, mixed $value, Closure $fail): void
	{
		if (!$this->passes($attribute, $value)) {
			$fail(trans('validation.locale_of_country_rule'));
		}
	}
	
	/**
	 * Determine if the validation rule passes.
	 * Check the Locale related to the Country Code.
	 *
	 * @param string $attribute
	 * @param mixed $value
	 * @return bool
	 */
	public function passes(string $attribute, mixed $value): bool
	{
		$value = getAsString($value);
		$locales = getLocalesWithName();
		
		$filtered = collect($locales)
			->filter(function ($name, $locale) {
				return str_ends_with($locale, '_' . $this->countryCode);
			});
		
		if ($filtered->isNotEmpty()) {
			return str_ends_with($value, '_' . $this->countryCode);
		}
		
		return isset($locales[$value]);
	}
}
