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
 * settings.security.option
 */

class SecuritySetting
{
	public static function getValues($value, $disk)
	{
		if (empty($value)) {
			
			$value['honeypot_enabled'] = '1';
			$value['honeypot_name_field_name'] = 'entity_field';
			$value['honeypot_valid_from_field_name'] = 'valid_field';
			$value['honeypot_amount_of_seconds'] = '3';
			$value['honeypot_respond_to_spam_with'] = 'blank_page';
			
			$value['captcha_delay'] = '1000';
			$value['recaptcha_version'] = 'v2';
			
		} else {
			
			if (!array_key_exists('honeypot_enabled', $value)) {
				$value['honeypot_enabled'] = '1';
			}
			if (!array_key_exists('honeypot_name_field_name', $value)) {
				$value['honeypot_name_field_name'] = 'entity_field';
			}
			if (!array_key_exists('honeypot_valid_from_field_name', $value)) {
				$value['honeypot_valid_from_field_name'] = 'valid_field';
			}
			if (!array_key_exists('honeypot_amount_of_seconds', $value)) {
				$value['honeypot_amount_of_seconds'] = '3';
			}
			if (!array_key_exists('honeypot_respond_to_spam_with', $value)) {
				$value['honeypot_respond_to_spam_with'] = 'blank_page';
			}
			
			if (!array_key_exists('captcha_delay', $value)) {
				$value['captcha_delay'] = '1000';
			}
			if (!array_key_exists('recaptcha_version', $value)) {
				$value['recaptcha_version'] = 'v2';
			}
			
			// Get reCAPTCHA old config values
			if (isset($value['recaptcha_public_key'])) {
				$value['recaptcha_v2_site_key'] = $value['recaptcha_public_key'];
			}
			if (isset($value['recaptcha_private_key'])) {
				$value['recaptcha_v2_secret_key'] = $value['recaptcha_private_key'];
			}
			
		}
		
		return $value;
	}
	
	public static function setValues($value, $setting)
	{
		return $value;
	}
	
	public static function getFields($diskName): array
	{
		$fields = [
			[
				'name'  => 'csrf_protection_sep',
				'type'  => 'custom_html',
				'value' => trans('admin.csrf_protection_title'),
			],
			[
				'name'    => 'csrf_protection',
				'label'   => trans('admin.csrf_protection_label'),
				'type'    => 'checkbox_switch',
				'wrapper' => [
					'class' => 'col-md-12',
				],
				'hint'    => trans('admin.csrf_protection_hint'),
			],
			
			[
				'name'  => 'honeypot_title',
				'type'  => 'custom_html',
				'value' => trans('admin.honeypot_title'),
			],
			[
				'name'    => 'honeypot_enabled',
				'label'   => trans('admin.honeypot_enabled_label'),
				'type'    => 'checkbox_switch',
				'wrapper' => [
					'class' => 'col-md-12',
				],
				'hint'    => trans('admin.honeypot_enabled_hint'),
			],
			[
				'name'    => 'honeypot_name_field_name',
				'label'   => trans('admin.honeypot_name_field_name_label'),
				'type'    => 'text',
				'hint'    => trans('admin.honeypot_name_field_name_hint'),
				'wrapper' => [
					'class' => 'col-md-6 honeypot-el',
				],
			],
			[
				'name'    => 'honeypot_respond_to_spam_with',
				'label'   => trans('admin.honeypot_respond_to_spam_with_label'),
				'type'    => 'select2_from_array',
				'options' => [
					'blank_page'     => 'Blank Page',
					'http_error_500' => 'HTTP Error 500',
				],
				'wrapper' => [
					'class' => 'col-md-6 honeypot-el',
				],
				'hint'    => trans('admin.honeypot_respond_to_spam_with_hint'),
			],
			[
				'name'    => 'honeypot_separator_1',
				'type'    => 'custom_html',
				'value'   => '<div style="clear: both;"></div>',
				'wrapper' => [
					'class' => 'col-md-12 extended honeypot-el',
				],
			],
			[
				'name'    => 'honeypot_randomize_name_field_name',
				'label'   => trans('admin.honeypot_randomize_name_field_name_label'),
				'type'    => 'checkbox_switch',
				'hint'    => trans('admin.honeypot_randomize_name_field_name_hint'),
				'wrapper' => [
					'class' => 'col-md-6 honeypot-el',
				],
			],
			[
				'name'    => 'honeypot_valid_from_timestamp',
				'label'   => trans('admin.honeypot_valid_from_timestamp_label'),
				'type'    => 'checkbox_switch',
				'hint'    => trans('admin.honeypot_valid_from_timestamp_hint'),
				'wrapper' => [
					'class' => 'col-md-6 honeypot-el',
				],
			],
			[
				'name'    => 'honeypot_valid_from_field_name',
				'label'   => trans('admin.honeypot_valid_from_field_name_label'),
				'type'    => 'text',
				'hint'    => trans('admin.honeypot_valid_from_field_name_hint'),
				'wrapper' => [
					'class' => 'col-md-6 honeypot-el honeypot-timestamp-el',
				],
			],
			[
				'name'       => 'honeypot_amount_of_seconds',
				'label'      => trans('admin.honeypot_amount_of_seconds_label'),
				'type'       => 'number',
				'attributes' => [
					'min'  => 1,
					'max'  => 3600,
					'step' => 1,
				],
				'hint'       => trans('admin.honeypot_amount_of_seconds_hint'),
				'wrapper'    => [
					'class' => 'col-md-6 honeypot-el honeypot-timestamp-el',
				],
			],
			
			[
				'name'  => 'captcha_title',
				'type'  => 'custom_html',
				'value' => trans('admin.captcha_title'),
			],
			[
				'name'    => 'captcha',
				'label'   => trans('admin.captcha_label'),
				'type'    => 'select2_from_array',
				'options' => [
					''          => 'Disabled',
					'default'   => 'Simple Captcha (Default)',
					'math'      => 'Simple Captcha (Math)',
					'flat'      => 'Simple Captcha (Flat)',
					'mini'      => 'Simple Captcha (Mini)',
					'inverse'   => 'Simple Captcha (Inverse)',
					'custom'    => 'Simple Captcha (Custom)',
					'recaptcha' => 'Google reCAPTCHA',
				],
				'wrapper' => [
					'class' => 'col-md-6',
				],
				'hint'    => trans('admin.captcha_hint'),
			],
			[
				'name'    => 'captcha_delay',
				'label'   => trans('admin.captcha_delay_label'),
				'type'    => 'select2_from_array',
				'options' => [
					1000 => '1000ms',
					1100 => '1100ms',
					1200 => '1200ms',
					1300 => '1300ms',
					1400 => '1400ms',
					1500 => '1500ms',
					1600 => '1600ms',
					1700 => '1700ms',
					1800 => '1800ms',
					1900 => '1900ms',
					2000 => '2000ms',
					2500 => '2500ms',
					3000 => '3000ms',
				],
				'wrapper' => [
					'class' => 'col-md-6 s-captcha',
				],
				'hint'    => trans('admin.captcha_delay_hint'),
			],
			[
				'name'    => 'captcha_custom',
				'type'    => 'custom_html',
				'value'   => trans('admin.captcha_custom'),
				'wrapper' => [
					'class' => 'col-md-12 s-captcha s-captcha-custom',
				],
			],
			[
				'name'    => 'captcha_custom_info',
				'type'    => 'custom_html',
				'value'   => trans('admin.captcha_custom_info'),
				'wrapper' => [
					'class' => 'col-md-12 s-captcha s-captcha-custom',
				],
			],
			[
				'name'       => 'captcha_width',
				'label'      => trans('admin.captcha_width_label', ['max' => 300]),
				'type'       => 'number',
				'attributes' => [
					'min'  => 100,
					'max'  => 300,
					'step' => 1,
				],
				'wrapper'    => [
					'class' => 'col-md-3 s-captcha s-captcha-custom',
				],
			],
			[
				'name'       => 'captcha_height',
				'label'      => trans('admin.captcha_height_label', ['max' => 150]),
				'type'       => 'number',
				'attributes' => [
					'min'  => 30,
					'max'  => 150,
					'step' => 1,
				],
				'wrapper'    => [
					'class' => 'col-md-3 s-captcha s-captcha-custom',
				],
			],
			[
				'name'       => 'captcha_length',
				'label'      => trans('admin.captcha_length_label', ['max' => 8]),
				'type'       => 'number',
				'attributes' => [
					'min'  => 3,
					'max'  => 8,
					'step' => 1,
				],
				'wrapper'    => [
					'class' => 'col-md-3 s-captcha s-captcha-custom',
				],
			],
			[
				'name'       => 'captcha_quality',
				'label'      => trans('admin.captcha_quality_label', ['max' => 100]),
				'type'       => 'number',
				'attributes' => [
					'min'  => 1,
					'max'  => 100,
					'step' => 1,
				],
				'wrapper'    => [
					'class' => 'col-md-3 s-captcha s-captcha-custom',
				],
			],
			[
				'name'    => 'captcha_bgImage',
				'label'   => trans('admin.captcha_bgImage_label'),
				'type'    => 'checkbox_switch',
				'wrapper' => [
					'class' => 'col-md-12 s-captcha s-captcha-custom',
				],
				'hint'    => trans('admin.captcha_bgImage_hint'),
			],
			[
				'name'       => 'captcha_bgColor',
				'label'      => trans('admin.captcha_bgColor_label'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '',
				],
				'wrapper'    => [
					'class' => 'col-md-6 s-captcha s-captcha-custom',
				],
			],
			[
				'name'       => 'captcha_lines',
				'label'      => trans('admin.captcha_lines_label', ['max' => 20]),
				'type'       => 'number',
				'attributes' => [
					'min'  => 0,
					'max'  => 20,
					'step' => 1,
				],
				'wrapper'    => [
					'class' => 'col-md-3 s-captcha s-captcha-custom',
				],
			],
			[
				'name'       => 'captcha_angle',
				'label'      => trans('admin.captcha_angle_label', ['max' => 180]),
				'type'       => 'number',
				'attributes' => [
					'min'  => 0,
					'max'  => 180,
					'step' => 1,
				],
				'wrapper'    => [
					'class' => 'col-md-3 s-captcha s-captcha-custom',
				],
			],
			[
				'name'       => 'captcha_sharpen',
				'label'      => trans('admin.captcha_sharpen_label', ['max' => 20]),
				'type'       => 'number',
				'attributes' => [
					'min'  => 0,
					'max'  => 20,
					'step' => 1,
				],
				'wrapper'    => [
					'class' => 'col-md-3 s-captcha s-captcha-custom',
				],
			],
			[
				'name'       => 'captcha_blur',
				'label'      => trans('admin.captcha_blur_label', ['max' => 20]),
				'type'       => 'number',
				'attributes' => [
					'min'  => 0,
					'max'  => 20,
					'step' => 1,
				],
				'wrapper'    => [
					'class' => 'col-md-3 s-captcha s-captcha-custom',
				],
			],
			[
				'name'       => 'captcha_contrast',
				'label'      => trans('admin.captcha_contrast_label', ['max' => 50]),
				'type'       => 'number',
				'attributes' => [
					'min'  => -50,
					'max'  => 50,
					'step' => 1,
				],
				'wrapper'    => [
					'class' => 'col-md-3 s-captcha s-captcha-custom',
				],
			],
			[
				'name'       => 'captcha_expire',
				'label'      => trans('admin.captcha_expire_label'),
				'type'       => 'number',
				'attributes' => [
					'min'  => 0,
					'step' => 1,
				],
				'wrapper'    => [
					'class' => 'col-md-3 s-captcha s-captcha-custom',
				],
			],
			[
				'name'    => 'captcha_math',
				'label'   => trans('admin.captcha_math_label'),
				'type'    => 'checkbox_switch',
				'wrapper' => [
					'class' => 'col-md-6 s-captcha s-captcha-custom',
				],
				'hint'    => trans('admin.captcha_math_hint'),
			],
			[
				'name'    => 'captcha_encrypt',
				'label'   => trans('admin.captcha_encrypt_label'),
				'type'    => 'checkbox_switch',
				'wrapper' => [
					'class' => 'col-md-6 s-captcha s-captcha-custom',
				],
				'hint'    => trans('admin.captcha_encrypt_hint'),
			],
			[
				'name'    => 'captcha_sensitive',
				'label'   => trans('admin.captcha_sensitive_label'),
				'type'    => 'checkbox_switch',
				'wrapper' => [
					'class' => 'col-md-6 s-captcha s-captcha-custom',
				],
				'hint'    => trans('admin.captcha_sensitive_hint'),
			],
			[
				'name'    => 'captcha_invert',
				'label'   => trans('admin.captcha_invert_label'),
				'type'    => 'checkbox_switch',
				'wrapper' => [
					'class' => 'col-md-6 s-captcha s-captcha-custom',
				],
				'hint'    => trans('admin.captcha_invert_hint'),
			],
			
			// ==========
			
			[
				'name'    => 'recaptcha_sep_info',
				'type'    => 'custom_html',
				'value'   => trans('admin.recaptcha_sep_info_value'),
				'wrapper' => [
					'class' => 'col-md-12 recaptcha',
				],
			],
			[
				'name'    => 'recaptcha_version',
				'label'   => trans('admin.recaptcha_version_label'),
				'type'    => 'select2_from_array',
				'options' => [
					'v2' => 'v2 (Checkbox)',
					'v3' => 'v3',
				],
				'hint'    => trans('admin.recaptcha_version_hint'),
				'wrapper' => [
					'class' => 'col-md-6 recaptcha',
				],
			],
			[
				'name'    => 'separator_clear_recaptcha',
				'type'    => 'custom_html',
				'value'   => '<div style="clear: both;"></div>',
				'wrapper' => [
					'class' => 'col-md-12 recaptcha',
				],
			],
			[
				'name'    => 'recaptcha_v2_site_key',
				'label'   => trans('admin.recaptcha_v2_site_key_label'),
				'type'    => 'text',
				'wrapper' => [
					'class' => 'col-md-6 recaptcha recaptcha-v2',
				],
			],
			[
				'name'    => 'recaptcha_v2_secret_key',
				'label'   => trans('admin.recaptcha_v2_secret_key_label'),
				'type'    => 'text',
				'wrapper' => [
					'class' => 'col-md-6 recaptcha recaptcha-v2',
				],
			],
			[
				'name'    => 'recaptcha_v3_site_key',
				'label'   => trans('admin.recaptcha_v3_site_key_label'),
				'type'    => 'text',
				'wrapper' => [
					'class' => 'col-md-6 recaptcha recaptcha-v3',
				],
			],
			[
				'name'    => 'recaptcha_v3_secret_key',
				'label'   => trans('admin.recaptcha_v3_secret_key_label'),
				'type'    => 'text',
				'wrapper' => [
					'class' => 'col-md-6 recaptcha recaptcha-v3',
				],
			],
			[
				'name'    => 'recaptcha_skip_ips',
				'label'   => trans('admin.recaptcha_skip_ips_label'),
				'type'    => 'textarea',
				'hint'    => trans('admin.recaptcha_skip_ips_hint'),
				'wrapper' => [
					'class' => 'col-md-6 recaptcha',
				],
			],
			
			// ==========
		];
		
		return addOptionsGroupJavaScript(__NAMESPACE__, __CLASS__, $fields);
	}
}
