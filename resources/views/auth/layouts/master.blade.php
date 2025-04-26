@php
	$htmlLang = getLangTag(config('app.locale'));
	$htmlDir = (config('lang.direction') == 'rtl') ? ' dir="rtl"' : '';
	$htmlTheme = isDarkModeEnabledForCurrentUser() ? ' theme="dark"' : '';
	
	// Logo
	$logoFactoryUrl = config('larapen.media.logo-factory');
	$logoUrl = '';
	try {
        if (is_link(public_path('storage'))) {
			$logoDarkUrl = config('settings.app.logo_dark_url', $logoFactoryUrl);
			$logoLightUrl = config('settings.app.logo_light_url', $logoFactoryUrl);
			$logoUrl = $logoLightUrl;
		}
    } catch (\Throwable $e) {}
    $logoUrl = !empty($logoUrl) ? $logoUrl : $logoFactoryUrl;
	$logoWidth = (int)config('settings.upload.img_resize_logo_width', 200);
	$logoHeight = (int)config('settings.upload.img_resize_logo_height', 45);
	$logoWidth = \Illuminate\Support\Number::clamp($logoWidth, min: 150, max: 250);
	$logoHeight = \Illuminate\Support\Number::clamp($logoWidth, min: 40, max: 60);
	$logoCssSize = "max-width:{$logoWidth}px; max-height:{$logoHeight}px; width:auto; height:auto;";
    $appName = config('app.name', 'SiteName');
    $logoLabel = config('settings.app.name', $appName);
	$logoAlt = strtolower($logoLabel);
	
	// Hero Background Image
	$heroBgStyle = '';
    try {
        if (is_link(public_path('storage'))) {
            $bgImgUrl = config('settings.auth.hero_image_url');
            $heroBgStyle = 'background-image:url(' . $bgImgUrl . ');';
        }
    } catch (\Throwable $e) {}
@endphp
<!DOCTYPE html>
<html lang="{{ $htmlLang }}"{!! $htmlDir . $htmlTheme !!}>
<head>
	<meta charset="{{ config('larapen.core.charset', 'utf-8') }}"/>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">
	<link href="{{ config('settings.app.favicon_url') }}" rel="icon"/>
	<title>{!! MetaTag::get('title') !!}</title>
	{!! MetaTag::tag('description') !!}{!! MetaTag::tag('keywords') !!}
	<link rel="canonical" href="{{ request()->fullUrl() }}"/>
	
	{{-- Specify a default target for all hyperlinks and forms on the page --}}
	<base target="_top"/>
	
	@yield('before_styles')
	
	{{-- App CSS files (Handled by Mix) --}}
	@if (config('lang.direction') == 'rtl')
		<link href="https://fonts.googleapis.com/css?family=Cairo|Changa" rel="stylesheet">
		<link href="{{ url(mix('dist/auth/styles.rtl.css')) }}" rel="stylesheet">
	@else
		<link href="{{ url(mix('dist/auth/styles.css')) }}" rel="stylesheet">
	@endif
	
	{{-- Generated CSS from Settings (Handled by FileController) --}}
	@php
		$skinQs = request()->filled('skin') ? '?skin=' . request()->query('skin') : null;
		$styleCssUrl = url('auth/common/css/skin.css') . $skinQs . getPictureVersion(!empty($skinQs));
	@endphp
	<link href="{{ $styleCssUrl }}" rel="stylesheet">
	
	@yield('after_styles')
	
	@include('front.common.js.document')
	
	@yield('captcha_head')
	@section('recaptcha_head')
		@php
			$captcha = config('settings.security.captcha');
			$reCaptchaVersion = config('recaptcha.version', 'v2');
			$isReCaptchaEnabled = (
				$captcha == 'recaptcha'
				&& !empty(config('recaptcha.site_key'))
				&& !empty(config('recaptcha.secret_key'))
				&& in_array($reCaptchaVersion, ['v2', 'v3'])
			);
		@endphp
		@if ($isReCaptchaEnabled)
			<style>
				.is-invalid .g-recaptcha iframe,
				.has-error .g-recaptcha iframe {
					border: 1px solid #f85359;
				}
			</style>
			@if ($reCaptchaVersion == 'v3')
				<script type="text/javascript">
					function myCustomValidation(token) {
						/* read HTTP status */
						/* console.log(token); */
						let gRecaptchaResponseEl = $('#gRecaptchaResponse');
						if (gRecaptchaResponseEl.length) {
							gRecaptchaResponseEl.val(token);
						}
					}
				</script>
				{!! recaptchaApiV3JsScriptTag([
					'action' 		    => request()->path(),
					'custom_validation' => 'myCustomValidation'
				]) !!}
			@else
				{!! recaptchaApiJsScriptTag() !!}
			@endif
		@endif
	@show
</head>
<body>

{{-- Preloader --}}
{{--
<div class="preloader">
	<div class="lds-ellipsis">
		<div></div>
		<div></div>
		<div></div>
		<div></div>
	</div>
</div>
--}}

<div id="main-wrapper" class="auth-login-register">
	<div class="container-fluid px-0">
		<div class="row g-0 min-vh-100">
			
			{{-- Welcome Text --}}
			<div class="col-md-6">
				<div class="hero-wrap d-flex align-items-start h-100">
					<div class="hero-mask opacity-8 bg-primary"></div>
					<div class="hero-bg hero-bg-scroll" style="{!! $heroBgStyle !!}"></div>
					<div class="hero-content w-100 min-vh-100 d-flex flex-column">
						<div class="row g-0">
							<div class="col-11 col-sm-10 col-md-10 col-lg-9 mx-auto">
								<div class="logo mt-5 mb-5 mb-md-0">
									<a class="d-flex" href="{{ url('/') }}" title="{!! $logoLabel !!}">
										<img src="{{ $logoUrl }}"
										     alt="{{ $logoAlt }}"
										     data-bs-placement="bottom"
										     data-bs-toggle="tooltip"
										     title="{!! $logoLabel !!}"
										     style="{!! $logoCssSize !!}"
										>
									</a>
								</div>
							</div>
						</div>
						<div class="row g-0 my-auto">
							<div class="col-11 col-sm-10 col-md-10 col-lg-9 mx-auto">
								@php
									$defaultCoverTitle = trans('auth.default_cover_title', ['appName' => config('app.name')]);
									$defaultCoverDescription = trans('auth.default_cover_description');
								@endphp
								<h1 class="text-11 text-white mb-4">
									{!! $coverTitle ?? $defaultCoverTitle !!}
								</h1>
								<p class="text-4 text-white lh-base mb-5">
									{!! $coverDescription ?? $defaultCoverDescription !!}
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			{{-- Login Form --}}
			<div class="col-md-6 d-flex">
				<div class="container my-auto py-5">
					<div class="row g-0">
						
						@php
							$hasNotifications = (
								(isset($errors) && $errors->any())
								|| session()->has('flash_notification')
								|| session()->has('resendEmailVerificationData')
								|| session()->has('resendPhoneVerificationData')
								|| session()->has('status')
								|| session()->has('email')
								|| session()->has('phone')
								|| session()->has('login')
								|| session()->has('code')
							);
						@endphp
						
						@if (isset($errors) && $errors->any())
							<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-11 col-xxl-10 mx-auto">
								<div class="alert alert-danger">
									@if (request()->segment(2) == 'register')
										<h5><strong>{{ trans('auth.validation_errors_title') }}</strong></h5>
									@endif
									<ul class="list-unstyled mb-0">
										@foreach ($errors->all() as $error)
											<li><i class="bi bi-check"></i> {!! $error !!}</li>
										@endforeach
									</ul>
								</div>
							</div>
						@endif
						
						@if (session()->has('flash_notification'))
							<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-11 col-xxl-10 mx-auto">
								@include('flash::message')
							</div>
						@endif
						
						@yield('notifications')
						
						@if ($hasNotifications)
							<div class="col-12 mx-auto mb-4">&nbsp;</div>
						@endif
						
						@yield('content')
						
						@include('auth.layouts.partials.select-language')
					
					</div>
				</div>
			</div>
		
		</div>
	</div>
</div>

@section('modal')
@show
@include('front.layouts.inc.modal.countries', ['modalSize' => 'modal-xl'])

@include('front.common.js.init')

<script>
	var countryCode = '{{ config('country.code', 0)  }}';
	
	{{-- Dark Mode --}}
	var isSettingsAppDarkModeEnabled = {{ isSettingsAppDarkModeEnabled() ? 'true' : 'false' }};
	var isDarkModeEnabledForCurrentUser = {{ isDarkModeEnabledForCurrentUser() ? 'true' : 'false' }};
	var isDarkModeEnabledForCurrentDevice = {{ isDarkModeEnabledForCurrentDevice() ? 'true' : 'false' }};
	
	{{-- The app's default auth field --}}
	var defaultAuthField = '{{ old('auth_field', getAuthField()) }}';
	var phoneCountry = '{{ config('country.code') }}';
</script>

@yield('before_scripts')

{{-- Toggle Password Visibility --}}
@include('auth.layouts.js.translations')

{{-- Country List for Intl Tel Input --}}
<script src="{{ url('auth/common/js/intl-tel-input/countries.js') . getPictureVersion() }}"></script>

{{-- App JS files (Handled by Mix) --}}
<script src="{{ url(mix('dist/auth/scripts.js')) }}"></script>

{{-- Select2 Locale File --}}
@php
	$select2LangFilePath = 'assets/plugins/select2/js/i18n/' . config('app.locale') . '.js';
@endphp
@if (file_exists(public_path($select2LangFilePath)))
	<script src="{{ url()->asset($select2LangFilePath) }}"></script>
@endif

<script>
	onDocumentReady((event) => {
		{{-- Searchable Select Boxes --}}
		let largeDataSelect2Params = {
			width: '100%',
			dropdownAutoWidth: 'true'
		};
		{{-- Simple Select Boxes --}}
		let select2Params = {...largeDataSelect2Params};
		{{-- Hiding the search box --}}
		select2Params.minimumResultsForSearch = Infinity;
		
		if (typeof langLayout !== 'undefined' && typeof langLayout.select2 !== 'undefined') {
			select2Params.language = langLayout.select2;
			largeDataSelect2Params.language = langLayout.select2;
		}
		
		$('.selecter').select2(select2Params);
		$('.large-data-selecter').select2(largeDataSelect2Params);
	});
</script>

@yield('after_scripts')
@yield('captcha_footer')
</body>
</html>
