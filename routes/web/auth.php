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

use App\Http\Controllers\Web\Auth\ForgotPasswordController;
use App\Http\Controllers\Web\Auth\LoginController;
use App\Http\Controllers\Web\Auth\RegisterController;
use App\Http\Controllers\Web\Auth\ResetPasswordController;
use App\Http\Controllers\Web\Auth\SocialController;
use App\Http\Controllers\Web\Auth\ToolsController;
use App\Http\Controllers\Web\Auth\TwoFactorController;
use App\Http\Controllers\Web\Auth\VerificationController;
use Illuminate\Support\Facades\Route;

// AUTH
Route::middleware(['guest', 'no.http.cache'])
	->group(function ($router) {
		// Registration Routes...
		Route::controller(RegisterController::class)
			->group(function ($router) {
				Route::get('register', 'showRegistrationForm')->name('auth.register.form');
				Route::post('register', 'register')->name('auth.register');
				Route::get('register/finished', 'finished')->name('auth.register.finished');
			});
		
		// Authentication Routes...
		Route::controller(LoginController::class)
			->group(function ($router) {
				Route::get('login', 'showLoginForm')->name('auth.login.form');
				Route::post('login', 'login')->name('auth.login');
			});
		
		// Password Forgot Routes...
		Route::controller(ForgotPasswordController::class)
			->group(function ($router) {
				Route::get('password/forgot', 'showForgotForm')->name('auth.forgot.password.form');
				Route::post('password/forgot', 'sendResetLinkOrCode')->name('auth.forgot.password');
			});
		
		Route::controller(ResetPasswordController::class)
			->group(function ($router) {
				/*
				 * Reset Password using Link (a part of the core routes)
				 * Show token form when the {token?} variable is empty
				 * - Natively the {token} variable was required.
				 * - $token is saved as hidden field in the reset password form to be used for security
				 */
				Route::get('password/reset/{token?}', 'showResetForm')->name('auth.reset.password.form');
				Route::post('password/reset', 'reset')->name('auth.reset.password');
			});
		
		// Social Authentication
		// Old routes:
		// auth/{provider}
		// auth/{provider}/callback
		Route::controller(SocialController::class)
			->group(function ($router) {
				$router->pattern('provider', 'facebook|linkedin|twitter-oauth-2|twitter|google');
				Route::get('connect/{provider}', 'redirectToProvider')->name('auth.social.connect');
				Route::get('connect/{provider}/callback', 'handleProviderCallback')->name('auth.social.connect.callback');
			});
	});

// Two-Factor Authentication (2FA)
Route::controller(TwoFactorController::class)
	->group(function ($router) {
		Route::get('two-factor/verify', 'showForm')->name('auth.2fa.verify.form');
		Route::post('two-factor/verify', 'verify')->name('auth.2fa.verify.submit');
		Route::get('two-factor/resend', 'resendCode')->name('auth.2fa.resend');
	});

// Logout
Route::get('logout', [LoginController::class, 'logout']);

// VERIFICATION
Route::controller(VerificationController::class)
	->prefix('verify')
	->group(function ($router) {
		// Email Address or Phone Number verification
		// ---
		// Important: Make sure that the 'entityMetadataKey' possible values match with
		// $entitiesMetadata key in the 'app/Services/Auth/Traits/VerificationTrait.php' file
		// Note: No support for email or SMS resending for the password forgot feature,
		//       since user can effortlessly re-do the action.
		$router->pattern('entityMetadataKey', 'users|posts|password');
		$router->pattern('entityMetadataKeyForReSend', 'users|posts');
		$router->pattern('field', 'email|phone');
		$router->pattern('token', '.*');
		$router->pattern('entityId', '[0-9]+');
		
		Route::get('{entityMetadataKey}/{entityId}/resend/email', 'resendEmailVerification')->name('auth.verify.resend.link');
		Route::get('{entityMetadataKey}/{entityId}/resend/sms', 'resendPhoneVerification')->name('auth.verify.resend.code');
		Route::get('{entityMetadataKey}/{field}/{token?}', 'verifyOrShowOtpVerificationForm')->name('auth.verify.verifyEntityOrShowOtpForm');
		Route::post('{entityMetadataKey}/{field}/{token?}', 'postOtpVerificationForm')->name('auth.verify.submitOtpForm');
	});

// TOOLS/FILES
Route::controller(ToolsController::class)
	->prefix('common')
	->group(function ($router) {
		Route::get('js/intl-tel-input/countries.js', 'intlTelInputData');
		Route::get('css/skin.css', 'skinCss');
	});
