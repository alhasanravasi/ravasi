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

@section('content')
	@include('front.common.spacer')
	<div class="main-container">
		<div class="container">
			<div class="row">
				
				@include('front.post.inc.notification')
				
				<div class="col-xl-12 page-content">
					
					@if (session()->has('message'))
						<div class="inner-box">
							<div class="row">
								<div class="col-12">
									<div class="alert alert-success pgray alert-lg mb-0" role="alert">
										<h2 class="no-padding mb20">
											<i class="fa-regular fa-circle-check"></i> {{ t('congratulations') }}
										</h2>
										<p class="mb-0">
											{{ session('message') }} <a href="{{ url('/') }}">{{ t('Homepage') }}</a>
										</p>
									</div>
								</div>
							</div>
						</div>
					@endif
					
				</div>
			</div>
		</div>
	</div>
@endsection
@php
	if (!session()->has('resendEmailVerificationData') && !session()->has('resendPhoneVerificationData')) {
		if (session()->has('message')) {
			session()->forget('message');
		}
	}
@endphp
