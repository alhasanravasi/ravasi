{{--
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
--}}
@extends('auth.layouts.master')

@php
	$passwordReset ??= [];
	$passwordTips = getPasswordTips(withCommon: true);
	
	$authField = request()->query('field');
@endphp
@section('content')
	@if (!(isset($paddingTopExists) && $paddingTopExists))
		<div class="p-0 mt-lg-4 mt-md-3 mt-3"></div>
	@endif
	<div class="col-11 col-sm-11 col-md-10 col-lg-9 col-xl-8 mx-auto">
		<h3 class="fw-600 mb-5">{{ trans('auth.reset_password') }}</h3>
		
		<p class="text-muted mb-4">{{ getResetPasswordDescription() }}</p>
		
		<form method="post" action="{{ urlGen()->passwordReset() }}">
			{!! csrf_field() !!}
			@honeypot
			<input type="hidden" name="token" value="{{ $token }}">
			
			{{-- email --}}
			@php
				$emailError = (isset($errors) && $errors->has('email')) ? ' is-invalid' : '';
			@endphp
			<div class="mb-3 auth-field-item">
				<div class="row">
					@php
						$col = isPhoneAsAuthFieldEnabled() ? 'col-6' : 'col-12';
					@endphp
					<label class="form-label {{ $col }} text-start" for="email">
						{{ trans('auth.email') }}
					</label>
					@if (isPhoneAsAuthFieldEnabled())
						<div class="col-6 text-end">
							<a href="" class="auth-field" data-auth-field="phone">{{ trans('auth.use_phone') }}</a>
						</div>
					@endif
				</div>
				<input id="email"
				       name="email"
				       type="text"
				       data-valid-type="email"
				       class="form-control{{ $emailError }}"
				       value="{{ old('email', data_get($passwordReset, 'email')) }}"
				       placeholder="{{ trans('auth.email_address') }}"
				>
			</div>
			
			{{-- phone --}}
			@if (isPhoneAsAuthFieldEnabled())
				@php
					$phoneError = (isset($errors) && $errors->has('phone')) ? ' is-invalid' : '';
					$phoneValue = data_get($passwordReset, 'phone');
					$phoneCountryValue = data_get($passwordReset, 'phone_country', config('country.code'));
					$phoneValue = phoneE164($phoneValue, $phoneCountryValue);
					$phoneValueOld = phoneE164(old('phone', $phoneValue), old('phone_country', $phoneCountryValue));
				@endphp
				<div class="mb-3 auth-field-item">
					<div class="row">
						<label class="form-label col-6 text-start" for="phone">
							{{ trans('auth.phone_number') }}
						</label>
						<div class="col-6 text-end">
							<a href="" class="auth-field" data-auth-field="email">{{ trans('auth.use_email') }}</a>
						</div>
					</div>
					<input id="phone"
					       name="phone"
					       type="tel"
					       class="form-control{{ $phoneError }}"
					       value="{{ $phoneValueOld }}"
					>
					<input name="phone_country" type="hidden" value="{{ old('phone_country', $phoneCountryValue) }}">
				</div>
			@endif
			
			{{-- auth_field --}}
			<input name="auth_field" type="hidden" value="{{ old('auth_field', getAuthField()) }}">
			
			{{-- password --}}
			@php
				$passwordError = (isset($errors) && $errors->has('password')) ? ' is-invalid' : '';
			@endphp
			<div class="mb-3 required">
				<label class="form-label" for="password">{{ trans('auth.new_password') }}</label>
				<input type="password"
				       name="password"
				       placeholder=""
				       class="form-control email{{ $passwordError }}"
				       autocomplete="new-password"
				>
				@if (!empty($passwordTips))
					<div class="form-text text-muted mt-2">
						@foreach($passwordTips as $tip)
							<span class="d-block"><i class="bi bi-check2"></i> {{ $tip }}</span>
						@endforeach
					</div>
				@endif
			</div>
			
			{{-- password_confirmation --}}
			@php
				$passwordError = (isset($errors) && $errors->has('password')) ? ' is-invalid' : '';
			@endphp
			<div class="mb-3 required">
				<label class="form-label" for="password_confirmation">
					{{ trans('auth.confirm_new_password') }}
				</label>
				<input type="password"
				       name="password_confirmation"
				       placeholder=""
				       class="form-control email{{ $passwordError }}"
				>
			</div>
			
			@include('front.layouts.inc.tools.captcha', ['noLabel' => true])
			
			{{-- Submit --}}
			<div class="d-grid my-4">
				<button type="submit" class="btn btn-primary btn-lg btn-block">
					{{ trans('auth.reset_password') }}
				</button>
			</div>
		</form>
		
		<p class="text-center text-muted">
			<a href="{{ urlGen()->signIn() }}">{{ trans('auth.back_to_login') }}</a>
		</p>
		<p class="text-center text-muted">
			{{ trans('auth.dont_have_account') }} <a href="{{ urlGen()->signUp() }}">{{ trans('auth.create_account') }}</a>
		</p>
	</div>
@endsection

@section('after_scripts')
	<script>
		defaultAuthField = '{{ old('auth_field', $authField) }}';
		phoneCountry = '{{ old('phone_country', ($phoneCountryValue ?? '')) }}';
	</script>
@endsection
