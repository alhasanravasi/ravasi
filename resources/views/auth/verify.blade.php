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
	$entityMetadataKey = request()->segment(3);
	$entityId ??= 0;
	
	// Get resend OTP code URL
	$resendVerificationData = getResendVerificationDataFromSession();
	$fieldHiddenValue = $resendVerificationData['fieldHiddenValue'] ?? '*********';
	$resendUrl = $resendVerificationData['resendUrl'] ?? null;
	$resendUrl = ($entityMetadataKey != 'password') ? $resendUrl : null;
@endphp

@section('notifications')
	@if (session()->has('code'))
		<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-11 col-xxl-10 mx-auto">
			<div class="alert alert-danger">
				<p>{{ session('code') }}</p>
			</div>
		</div>
	@endif
@endsection

@section('content')
	<div class="col-11 col-sm-11 col-md-10 col-lg-9 col-xl-8 mx-auto">
		<h3 class="fw-600 mb-4">{{ trans('auth.otp_validation') }}</h3>
		
		<p class="text-muted mb-4">{!! getOtpValidationDescription($fieldHiddenValue) !!}</p>
		
		<form id="tokenForm" role="form" method="post" action="{{ url(getRequestPath('.*/verify/.*')) }}">
			{!! csrf_field() !!}
			@honeypot
			
			{{-- code --}}
			@php
				$codeError = (isset($errors) && $errors->has('code')) ? ' is-invalid' : '';
			@endphp
			<div class="mb-3">
				<label for="code" class="col-form-label">{{ trans('auth.code') }}:</label>
				<input id="code" name="code"
				       type="text"
				       placeholder="{{ trans('auth.enter_verification_code') }}"
				       class="form-control{{ $codeError }}"
				       value="{{ old('code') }}"
				       autocomplete="one-time-code"
				>
			</div>
			
			<div class="d-grid my-4">
				<button type="submit" id="tokenBtn" class="btn btn-primary btn-lg btn-block">
					{{ trans('auth.verify') }}
				</button>
			</div>
		</form>
		
		@if (!empty($resendUrl))
			<p class="text-center text-muted">
				{{ trans('auth.not_received_code') }} <a href="{{ $resendUrl }}">{{ trans('auth.resend_it') }}</a>
			</p>
		@endif
		
		<p class="text-center text-muted">
			<a href="{{ urlGen()->signIn() }}">{{ trans('auth.back_to_login') }}</a>
		</p>
	</div>
@endsection

@section('after_scripts')
@endsection
