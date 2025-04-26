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

namespace App\Models\Setting;

/*
 * settings.auth.option
 */

use App\Helpers\Common\Files\Upload;
use App\Helpers\Common\TimeRangeGenerator;

class AuthSetting
{
	public static function passedValidation($request)
	{
		$mediaOpPath = 'larapen.media.resize.namedOptions';
		$params = [
			[
				'attribute' => 'hero_image_path',
				'destPath'  => 'app/auth',
				'width'     => (int)config($mediaOpPath . '.bg-body.width', 2500),
				'height'    => (int)config($mediaOpPath . '.bg-body.height', 2500),
				'ratio'     => config($mediaOpPath . '.bg-body.ratio', '1'),
				'upsize'    => config($mediaOpPath . '.bg-body.upsize', '0'),
				'filename'  => 'auth-hero-image-',
			],
		];
		
		foreach ($params as $param) {
			$file = $request->hasFile($param['attribute'])
				? $request->file($param['attribute'])
				: $request->input($param['attribute']);
			
			$request->request->set($param['attribute'], Upload::image($file, $param['destPath'], $param));
		}
		
		return $request;
	}
	
	public static function getValues($value, $disk)
	{
		$defaultHeroImagePath = config('larapen.media.auth_hero_image');
		
		if (empty($value)) {
			
			$value['open_login_in_modal'] = '1';
			$value['login_max_attempts'] = '5';
			$value['login_decay_minutes'] = '15';
			
			$value['password_min_length'] = '6';
			$value['password_max_length'] = '30';
			$value['password_letters_required'] = '0';
			$value['password_mixedCase_required'] = '0';
			$value['password_numbers_required'] = '0';
			$value['password_symbols_required'] = '0';
			$value['password_uncompromised_required'] = '0';
			$value['password_uncompromised_threshold'] = '0';
			
			$value['email_validator_rfc'] = '1';
			$value['email_validator_strict'] = '0';
			$value['email_validator_dns'] = '0';
			$value['email_validator_spoof'] = '0';
			$value['email_validator_filter'] = '0';
			
			$value['otp_length'] = 4;
			$value['otp_expire_time_seconds'] = 60 * 5;
			$value['otp_cooldown_seconds'] = 60;
			$value['otp_max_attempts'] = 3;
			$value['otp_decay_minutes'] = 60;
			$value['max_login_lockout_attempts'] = 0;
			$value['max_resend_lockout_attempts'] = 15;
			$value['lockout_duration_minutes'] = 1440;
			
			$value['hero_image_path'] = $defaultHeroImagePath;
			
		} else {
			
			if (!array_key_exists('open_login_in_modal', $value)) {
				$value['open_login_in_modal'] = '1';
			}
			if (!array_key_exists('login_max_attempts', $value)) {
				$value['login_max_attempts'] = '5';
			}
			if (!array_key_exists('login_decay_minutes', $value)) {
				$value['login_decay_minutes'] = '15';
			}
			
			if (!array_key_exists('password_min_length', $value)) {
				$value['password_min_length'] = '6';
			}
			if (!array_key_exists('password_max_length', $value)) {
				$value['password_max_length'] = '30';
			}
			if (!array_key_exists('password_letters_required', $value)) {
				$value['password_letters_required'] = '0';
			}
			if (!array_key_exists('password_mixedCase_required', $value)) {
				$value['password_mixedCase_required'] = '0';
			}
			if (!array_key_exists('password_numbers_required', $value)) {
				$value['password_numbers_required'] = '0';
			}
			if (!array_key_exists('password_symbols_required', $value)) {
				$value['password_symbols_required'] = '0';
			}
			if (!array_key_exists('password_uncompromised_required', $value)) {
				$value['password_uncompromised_required'] = '0';
			}
			if (!array_key_exists('password_uncompromised_threshold', $value)) {
				$value['password_uncompromised_threshold'] = '0';
			}
			
			if (!array_key_exists('email_validator_rfc', $value)) {
				$value['email_validator_rfc'] = '1';
			}
			if (!array_key_exists('email_validator_strict', $value)) {
				$value['email_validator_strict'] = '0';
			}
			if (!array_key_exists('email_validator_dns', $value)) {
				$value['email_validator_dns'] = '0';
			}
			if (!array_key_exists('email_validator_spoof', $value)) {
				$value['email_validator_spoof'] = '0';
			}
			if (!array_key_exists('email_validator_filter', $value)) {
				$value['email_validator_filter'] = '0';
			}
			
			if (!array_key_exists('otp_length', $value)) {
				$value['otp_length'] = 4;
			}
			if (!array_key_exists('otp_expire_time_seconds', $value)) {
				$value['otp_expire_time_seconds'] = 60 * 5;
			}
			if (!array_key_exists('otp_cooldown_seconds', $value)) {
				$value['otp_cooldown_seconds'] = 60;
			}
			if (!array_key_exists('otp_max_attempts', $value)) {
				$value['otp_max_attempts'] = 3;
			}
			if (!array_key_exists('otp_decay_minutes', $value)) {
				$value['otp_decay_minutes'] = 60;
			}
			if (!array_key_exists('max_login_lockout_attempts', $value)) {
				$value['max_login_lockout_attempts'] = 0;
			}
			if (!array_key_exists('max_resend_lockout_attempts', $value)) {
				$value['max_resend_lockout_attempts'] = 15;
			}
			if (!array_key_exists('lockout_duration_minutes', $value)) {
				$value['lockout_duration_minutes'] = 1440;
			}
			
			$heroImageKey = 'hero_image_path';
			foreach ($value as $key => $item) {
				if ($key == $heroImageKey) {
					if (empty($value[$heroImageKey]) || !$disk->exists($value[$heroImageKey])) {
						$value[$key] = $defaultHeroImagePath;
					}
				}
			}
			
			// Required keys & values
			// If $value exists and these keys don't exist, then set their default values
			if (!array_key_exists($heroImageKey, $value)) {
				$value[$heroImageKey] = $defaultHeroImagePath;
			}
			
		}
		
		// Append files URLs
		// hero_image_url
		$heroImage = $value['hero_image_path'] ?? $value['login_bg_image'] ?? $defaultHeroImagePath;
		$value['hero_image_url'] = thumbService($heroImage, false)->resize('bg-body')->url();
		
		return $value;
	}
	
	public static function setValues($value, $setting)
	{
		return $value;
	}
	
	public static function getFields($diskName): array
	{
		$fields = [];
		
		// Login Options
		$fields = array_merge($fields, [
			[
				'name'  => 'limiting_login_attempts_title',
				'type'  => 'custom_html',
				'value' => trans('admin.auth_limiting_login_attempts_title'),
			],
			[
				'name'  => 'limiting_login_attempts_info',
				'type'  => 'custom_html',
				'value' => trans('admin.card_light', ['content' => trans('admin.auth_limiting_login_attempts_info')]),
			],
			[
				'name'    => 'login_max_attempts',
				'label'   => trans('admin.login_max_attempts_label'),
				'type'    => 'select2_from_array',
				'options' => self::getMaxAttempts(30),
				'hint'    => trans('admin.login_max_attempts_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'login_decay_minutes',
				'label'   => trans('admin.login_decay_label'),
				'type'    => 'select2_from_array',
				'options' => self::getDecayRangeInMinutes(),
				'hint'    => trans('admin.login_decay_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
		]);
		
		// Password Validation Options
		$fields = array_merge($fields, [
			[
				'name'  => 'password_validator_title',
				'type'  => 'custom_html',
				'value' => trans('admin.password_validator_title_value'),
			],
			[
				'name'       => 'password_min_length',
				'label'      => trans('admin.password_min_length_label'),
				'type'       => 'number',
				'attributes' => [
					'min'  => 0,
					'step' => 1,
					'max'  => 100,
				],
				'hint'       => trans('admin.password_min_length_hint'),
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'password_max_length',
				'label'      => trans('admin.password_max_length_label'),
				'type'       => 'number',
				'attributes' => [
					'min'  => 0,
					'step' => 1,
					'max'  => 100,
				],
				'hint'       => trans('admin.password_max_length_hint'),
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'password_letters_required',
				'label'   => trans('admin.password_letters_required_label'),
				'type'    => 'checkbox_switch',
				'hint'    => trans('admin.password_letters_required_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'password_mixedCase_required',
				'label'   => trans('admin.password_mixedCase_required_label'),
				'type'    => 'checkbox_switch',
				'hint'    => trans('admin.password_mixedCase_required_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'password_numbers_required',
				'label'   => trans('admin.password_numbers_required_label'),
				'type'    => 'checkbox_switch',
				'hint'    => trans('admin.password_numbers_required_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'password_symbols_required',
				'label'   => trans('admin.password_symbols_required_label'),
				'type'    => 'checkbox_switch',
				'hint'    => trans('admin.password_symbols_required_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'password_uncompromised_required',
				'label'   => trans('admin.password_uncompromised_required_label'),
				'type'    => 'checkbox_switch',
				'hint'    => trans('admin.password_uncompromised_required_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'password_uncompromised_threshold',
				'label'      => trans('admin.password_uncompromised_threshold_label'),
				'type'       => 'number',
				'attributes' => [
					'min'  => 0,
					'step' => 1,
					'max'  => 10,
				],
				'hint'       => trans('admin.password_uncompromised_threshold_hint'),
				'wrapper'    => [
					'class' => 'col-md-6 mt-4',
				],
			],
		]);
		
		// Email Address Validation Options
		$fields = array_merge($fields, [
			[
				'name'  => 'email_validator_title',
				'type'  => 'custom_html',
				'value' => trans('admin.email_validator_title_value'),
			],
			[
				'name'    => 'email_validator_rfc',
				'label'   => trans('admin.email_validator_rfc_label'),
				'type'    => 'checkbox_switch',
				'hint'    => trans('admin.email_validator_rfc_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'email_validator_strict',
				'label'   => trans('admin.email_validator_strict_label'),
				'type'    => 'checkbox_switch',
				'hint'    => trans('admin.email_validator_strict_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'email_validator_dns',
				'label'   => trans('admin.email_validator_dns_label'),
				'type'    => 'checkbox_switch',
				'hint'    => trans('admin.email_validator_dns_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'email_validator_spoof',
				'label'   => trans('admin.email_validator_spoof_label'),
				'type'    => 'checkbox_switch',
				'hint'    => trans('admin.email_validator_spoof_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'email_validator_filter',
				'label'   => trans('admin.email_validator_filter_label'),
				'type'    => 'checkbox_switch',
				'hint'    => trans('admin.email_validator_filter_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
		]);
		
		// Two-Factor Authentication
		$fields = array_merge($fields, [
			[
				'name'  => '2fa_title',
				'type'  => 'custom_html',
				'value' => trans('admin.2fa_title'),
			],
			[
				'name'  => '2fa_info',
				'type'  => 'custom_html',
				'value' => trans('admin.card_light', ['content' => trans('admin.2fa_info')]),
			],
			[
				'name'    => '2fa_with_mail',
				'label'   => trans('admin.2fa_with_mail_label'),
				'type'    => 'checkbox_switch',
				'wrapper' => [
					'class' => 'col-md-6',
				],
				'hint'    => trans('admin.2fa_with_mail_hint', ['settingUrl' => urlGen()->adminUrl('settings/find/mail')]),
			],
			[
				'name'    => '2fa_with_sms',
				'label'   => trans('admin.2fa_with_sms_label'),
				'type'    => 'checkbox_switch',
				'wrapper' => [
					'class' => 'col-md-6',
				],
				'hint'    => trans('admin.2fa_with_sms_hint', ['settingUrl' => urlGen()->adminUrl('settings/find/sms')]),
			],
			[
				'name'    => 'require_2fa_challenge_on_enable',
				'label'   => trans('admin.require_2fa_challenge_on_enable_label'),
				'type'    => 'checkbox_switch',
				'wrapper' => [
					'class' => 'col-md-6',
				],
				'hint'    => trans('admin.require_2fa_challenge_on_enable_hint'),
				'newline' => true,
			],
			[
				'name'    => 'otp_length',
				'label'   => trans('admin.otp_length_label'),
				'type'    => 'select2_from_array',
				'options' => self::getOptLengths(),
				'hint'    => trans('admin.otp_length_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
				'newline' => true,
			],
			[
				'name'    => 'otp_expire_time_seconds',
				'label'   => trans('admin.otp_expire_time_label'),
				'type'    => 'select2_from_array',
				'options' => self::getOtpExpireTimeRangeInSeconds(),
				'hint'    => trans('admin.otp_expire_time_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'otp_cooldown_seconds',
				'label'   => trans('admin.otp_cooldown_label'),
				'type'    => 'select2_from_array',
				'options' => self::getOtpCooldownInSeconds(),
				'hint'    => trans('admin.otp_cooldown_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'otp_max_attempts',
				'label'   => trans('admin.otp_max_attempts_label'),
				'type'    => 'select2_from_array',
				'options' => self::getMaxAttempts(),
				'hint'    => trans('admin.otp_max_attempts_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'otp_decay_minutes',
				'label'   => trans('admin.otp_decay_label'),
				'type'    => 'select2_from_array',
				'options' => self::getDecayRangeInMinutes(),
				'hint'    => trans('admin.otp_decay_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
		]);
		
		// Auto-Lockout Options
		$fields = array_merge($fields, [
			[
				'name'  => 'auth_lockout_title',
				'type'  => 'custom_html',
				'value' => trans('admin.auth_lockout_title'),
			],
			[
				'name'  => 'auth_lockout_info',
				'type'  => 'custom_html',
				'value' => trans('admin.card_light', ['content' => trans('admin.auth_lockout_info')]),
			],
			[
				'name'    => 'max_login_lockout_attempts',
				'label'   => trans('admin.max_login_lockout_attempts_label'),
				'type'    => 'select2_from_array',
				'options' => self::getMaxAttempts(100),
				'hint'    => trans('admin.max_login_lockout_attempts_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'max_resend_lockout_attempts',
				'label'   => trans('admin.max_resend_lockout_attempts_label'),
				'type'    => 'select2_from_array',
				'options' => self::getMaxAttempts(100),
				'hint'    => trans('admin.max_resend_lockout_attempts_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'lockout_duration_minutes',
				'label'   => trans('admin.lockout_duration_label'),
				'type'    => 'select2_from_array',
				'options' => self::getLockoutDurationTimeRangeInMinutes(),
				'hint'    => trans('admin.lockout_duration_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
		]);
		
		// User's Suspension & ban
		$fields = array_merge($fields, [
			[
				'name'  => 'user_suspension_and_ban_title',
				'type'  => 'custom_html',
				'value' => trans('admin.user_suspension_and_ban_title'),
			],
			[
				'name'    => 'send_notification_on_user_suspension',
				'label'   => trans('admin.send_notification_on_user_suspension_label'),
				'type'    => 'select2_from_array',
				'options' => [
					'none'        => trans('admin.send_notification_on_user_suspension_ban_option_0'),
					'send'        => trans('admin.send_notification_on_user_suspension_ban_option_1'),
					'forceToSend' => trans('admin.send_notification_on_user_suspension_ban_option_2'),
				],
				'hint'    => trans('admin.send_notification_on_user_suspension_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'send_notification_on_user_ban',
				'label'   => trans('admin.send_notification_on_user_ban_label'),
				'type'    => 'select2_from_array',
				'options' => [
					'none'        => trans('admin.send_notification_on_user_suspension_ban_option_0'),
					'send'        => trans('admin.send_notification_on_user_suspension_ban_option_1'),
					'forceToSend' => trans('admin.send_notification_on_user_suspension_ban_option_2'),
				],
				'hint'    => trans('admin.send_notification_on_user_ban_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
		]);
		
		// Auth Other Options
		$fields = array_merge($fields, [
			[
				'name'  => 'other_options_title',
				'type'  => 'custom_html',
				'value' => trans('admin.auth_other_options_title'),
			],
			[
				'name'  => 'open_login_in_modal',
				'label' => trans('admin.open_login_in_modal_label'),
				'type'  => 'checkbox_switch',
				'hint'  => trans('admin.open_login_in_modal_hint'),
			],
			[
				'name'    => 'hero_image_path',
				'label'   => trans('admin.auth_hero_image_label'),
				'type'    => 'image',
				'upload'  => true,
				'disk'    => $diskName,
				'default' => config('larapen.media.auth_hero_image'),
				'hint'    => trans('admin.auth_hero_image_hint'),
				'wrapper' => [
					'class' => 'col-md-12',
				],
				'newline' => true,
			],
		]);
		
		return addOptionsGroupJavaScript(__NAMESPACE__, __CLASS__, $fields);
	}
	
	/**
	 * @param int $max
	 * @return array
	 */
	private static function getMaxAttempts(int $max = 30): array
	{
		$array = [];
		for ($i = 0; $i <= $max; $i++) {
			$array[$i] = ($i == 0) ? trans('admin.disabled') : $i;
		}
		
		return $array;
	}
	
	/**
	 * @return array
	 */
	private static function getDecayRangeInMinutes(): array
	{
		return TimeRangeGenerator::generateRange(
			startUnit: 'minute',
			endUnit: 'day',
			limits: [
				'day' => 1,
			],
			keyUnit: 'minute'
		);
	}
	
	/**
	 * @return array
	 */
	private static function getOtpExpireTimeRangeInSeconds(): array
	{
		return TimeRangeGenerator::generateRange(
			startUnit: 'minute',
			endUnit: 'week',
			limits: [
				'week' => 1,
			],
			keyUnit: 'second'
		);
	}
	
	/**
	 * @return array
	 */
	private static function getOtpCooldownInSeconds(): array
	{
		return TimeRangeGenerator::generateRange(
			startUnit: 'second',
			endUnit: 'day',
			limits: [
				'day' => 1,
			],
			keyUnit: 'second'
		);
	}
	
	/**
	 * @return array
	 */
	private static function getOptLengths(): array
	{
		$array = [];
		for ($i = 4; $i <= 8; $i++) {
			$array[$i] = $i;
		}
		
		return $array;
	}
	
	/**
	 * @return array
	 */
	private static function getLockoutDurationTimeRangeInMinutes(): array
	{
		return TimeRangeGenerator::generateRange(
			startUnit: 'minute',
			limits: [
				'year' => 1,
			],
			keyUnit: 'minute'
		);
	}
}
