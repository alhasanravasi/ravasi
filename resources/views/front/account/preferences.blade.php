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
@extends('front.layouts.master')

@php
	$authUser ??= auth()->user();
	$lastLoginAtFormatted = \App\Helpers\Common\Date::format($authUser->last_login_at, 'datetime');
@endphp
@section('content')
	@include('front.common.spacer')
	<div class="main-container">
		<div class="container">
			<div class="row">
				<div class="col-md-3 page-sidebar">
					@include('front.account.partials.sidebar')
				</div>
				
				<div class="col-md-9 page-content">
					
					@include('flash::message')
					
					@if (isset($errors) && $errors->any())
						<div class="alert alert-danger alert-dismissible">
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ t('Close') }}"></button>
							<h5><strong>{{ t('validation_errors_title') }}</strong></h5>
							<ul class="list list-check">
								@foreach ($errors->all() as $error)
									<li>{!! $error !!}</li>
								@endforeach
							</ul>
						</div>
					@endif
					
					@include('front.account.partials.header', [
						'headerTitle' => '<i class="bi bi-sliders"></i> ' . trans('auth.preferences')
					])
					
					<div class="inner-box default-inner-box" style="overflow: visible;">
						<div class="row">
							<div class="col-8"></div>
							<div class="col-4 d-flex align-items-center justify-content-end">
								@if (config('settings.app.dark_mode') == '1')
									@php
										$themeSwitcherActive = isDarkModeEnabledForCurrentUser() ? ' active' : '';
									@endphp
									<label class="theme-switcher theme-switcher-left-right{{ $themeSwitcherActive }}"
									       data-csrf-token="{{ csrf_token() }}"
									       data-user-id="{{ $authUser->id }}"
									>
										<span class="theme-switcher-label"
										      data-on="{{ t('dark_mode_on') }}"
										      data-off="{{ t('dark_mode_off') }}"
										></span>
										<span class="theme-switcher-handle"></span>
									</label>
								@endif
							</div>
						</div>
						
						<div class="row gy-3">
							@include('front.account.partials.preferences')
						</div>
					
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('after_styles')
@endsection

@section('after_scripts')
@endsection
