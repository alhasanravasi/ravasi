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

@section('notifications')
	@php
		$withMessage = !session()->has('flash_notification');
		$resendVerificationLink = getResendVerificationLink(withMessage: $withMessage);
	@endphp
	@if (!empty($resendVerificationLink))
		<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-11 col-xxl-10 mx-auto">
			<div class="alert alert-info text-center">
				{!! $resendVerificationLink !!}
			</div>
		</div>
	@endif
@endsection

@section('content')
	<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-11 col-xxl-10 mx-auto">
		@php
			// $mbAuth = socialLogin()->isEnabled() ? ' mb-4' : ' mb-4';
			$mbAuth = ' mb-4';
		@endphp
		<div class="row d-flex justify-content-center">
			<div class="col-12 col-sm-12 col-md-12 col-lg-11 col-xl-10 col-xxl-8">
				<h3 class="fw-600{{ $mbAuth }}">{{ trans('auth.sign_in') }}</h3>
			</div>
		</div>
		
		@include('auth.login.inc.social', ['page' => 'login', 'position' => 'top'])
		
		<div class="row d-flex justify-content-center">
			<div class="col-12 col-sm-12 col-md-12 col-lg-11 col-xl-10 col-xxl-8">
				<p class="text-muted mb-4">{{ getLoginDescription() }}</p>
				
				<form id="loginForm" role="form" method="post" action="{{ url()->current() }}">
					{!! csrf_field() !!}
					@honeypot
					<input type="hidden" name="country" value="{{ config('country.code') }}">
					
					{{-- email --}}
					@php
						$emailError = (isset($errors) && $errors->has('email')) ? ' is-invalid' : '';
						$emailValue = (session()->has('email')) ? session('email') : old('email');
					@endphp
					<div class="mb-3 auth-field-item">
						<div class="row">
							@php
								$col = isPhoneAsAuthFieldEnabled() ? 'col-6' : 'col-12';
							@endphp
							<label class="form-label {{ $col }}" for="email">
								{{ trans('auth.email') }}
							</label>
							@if (isPhoneAsAuthFieldEnabled())
								<div class="col-6 text-end">
									<a href="" class="auth-field" data-auth-field="phone">{{ trans('auth.login_with_phone') }}</a>
								</div>
							@endif
						</div>
						<input id="email" name="email"
						       type="text"
						       placeholder="{{ trans('auth.email_or_username') }}"
						       class="form-control{{ $emailError }}"
						       value="{{ $emailValue }}"
						>
					</div>
					
					{{-- phone --}}
					@if (isPhoneAsAuthFieldEnabled())
						@php
							$phoneError = (isset($errors) && $errors->has('phone')) ? ' is-invalid' : '';
							$phoneValue = (session()->has('phone')) ? session('phone') : old('phone');
							$phoneCountryValue = config('country.code');
						@endphp
						<div class="mb-3 auth-field-item">
							<div class="row">
								<label class="form-label col-6" for="phone">
									{{ trans('auth.phone_number') }}
								</label>
								<div class="col-6 text-end">
									<a href="" class="auth-field" data-auth-field="email">{{ trans('auth.login_with_email') }}</a>
								</div>
							</div>
							<input id="phone" name="phone"
							       type="tel"
							       class="form-control{{ $phoneError }}"
							       value="{{ phoneE164($phoneValue, old('phone_country', $phoneCountryValue)) }}"
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
					<div class="mb-3 password-field">
						<div class="row">
							<label class="form-label col-6" for="password">{{ trans('auth.password') }}</label>
							<div class="col-6 text-end">
								<i class="fa-regular fa-eye-slash"></i>
								<a href="#"
								   class="toggle-password-link"
								   data-bs-toggle="tooltip"
								   data-bs-title="{{ trans('auth.show_password') }}"
								   data-toggle-text="true"
								>
									{{ trans('auth.show') }}
								</a>
							</div>
						</div>
						<input id="password" name="password"
						       type="password"
						       class="form-control{{ $passwordError }}"
						       placeholder="{{ trans('auth.password') }}"
						       autocomplete="new-password"
						>
					</div>
					
					<div class="row mt-4">
						<div class="col">
							<div class="form-check">
								<input id="rememberMe" name="remember" class="form-check-input" type="checkbox" value="1">
								<label class="form-check-label" for="rememberMe">{{ trans('auth.remember_me') }}</label>
							</div>
						</div>
						<div class="col text-end">
							<a href="{{ urlGen()->passwordForgot() }}">{{ trans('auth.forgot_password') }}</a>
						</div>
					</div>
					
					@include('front.layouts.inc.tools.captcha', ['noLabel' => true])
					
					{{-- Submit --}}
					<div class="d-grid my-4">
						<button type="submit" id="loginBtn" class="btn btn-primary btn-block">{{ trans('auth.log_in') }}</button>
					</div>
				
				</form>
			</div>
		</div>
		
		<p class="text-center text-muted mb-0">
			{{ trans('auth.dont_have_account') }} <a href="{{ urlGen()->signUp() }}">{{ trans('auth.create_account') }}</a>
		</p>
	
	</div>
@endsection

@section('after_scripts')
@endsection
