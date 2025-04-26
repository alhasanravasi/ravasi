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
	@if (session()->has('status'))
		<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-11 col-xxl-10 mx-auto">
			<div class="alert alert-success alert-dismissible">
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ t('Close') }}"></button>
				<p class="mb-0">{{ session('status') }}</p>
			</div>
		</div>
	@endif
	
	@if (session()->has('email'))
		<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-11 col-xxl-10 mx-auto">
			<div class="alert alert-danger alert-dismissible">
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ t('Close') }}"></button>
				<p class="mb-0">{{ session('email') }}</p>
			</div>
		</div>
	@endif
	
	@if (session()->has('phone'))
		<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-11 col-xxl-10 mx-auto">
			<div class="alert alert-danger alert-dismissible">
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ t('Close') }}"></button>
				<p class="mb-0">{{ session('phone') }}</p>
			</div>
		</div>
	@endif
	
	@if (session()->has('login'))
		<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-11 col-xxl-10 mx-auto">
			<div class="alert alert-danger alert-dismissible">
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ t('Close') }}"></button>
				<p class="mb-0">{{ session('login') }}</p>
			</div>
		</div>
	@endif
@endsection

@section('content')
	@if (!(isset($paddingTopExists) and $paddingTopExists))
		<div class="p-0 mt-lg-4 mt-md-3 mt-3"></div>
	@endif
	<div class="col-11 col-sm-11 col-md-10 col-lg-9 col-xl-8 mx-auto">
		<h3 class="fw-600 mb-5">{{ trans('auth.forgotten_password') }}</h3>
		
		<p class="text-muted mb-4">{{ getPasswordForgotDescription() }}</p>
		
		<form id="pwdForm" role="form" method="post" action="{{ urlGen()->passwordForgot() }}">
			{!! csrf_field() !!}
			@honeypot
			
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
				       placeholder="{{ trans('auth.email_address') }}"
				       class="form-control{{ $emailError }}"
				       value="{{ old('email') }}"
				>
				<div class="form-text">
					{{ trans('auth.forgot_password_hint_email') }}
				</div>
			</div>
			
			{{-- phone --}}
			@if (isPhoneAsAuthFieldEnabled())
				@php
					$phoneError = (isset($errors) && $errors->has('phone')) ? ' is-invalid' : '';
					$phoneCountryValue = config('country.code');
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
					       value="{{ phoneE164(old('phone'), old('phone_country', $phoneCountryValue)) }}"
					>
					<input name="phone_country" type="hidden" value="{{ old('phone_country', $phoneCountryValue) }}">
					<div class="form-text">
						{{ trans('auth.forgot_password_hint_phone') }}
					</div>
				</div>
			@endif
			
			{{-- auth_field --}}
			<input name="auth_field" type="hidden" value="{{ old('auth_field', getAuthField()) }}">
			
			@include('front.layouts.inc.tools.captcha', ['noLabel' => true])
			
			{{-- Submit --}}
			<div class="d-grid my-4">
				<button type="submit" id="pwdBtn" class="btn btn-primary btn-lg btn-block">
					{{ trans('auth.continue') }}
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
@endsection
