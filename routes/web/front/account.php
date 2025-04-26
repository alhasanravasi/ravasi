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

use App\Http\Controllers\Web\Front\Account\ClosingController;
use App\Http\Controllers\Web\Front\Account\LinkedAccountsController;
use App\Http\Controllers\Web\Front\Account\MessagesController;
use App\Http\Controllers\Web\Front\Account\OverviewController;
use App\Http\Controllers\Web\Front\Account\PostsController;
use App\Http\Controllers\Web\Front\Account\PreferencesController;
use App\Http\Controllers\Web\Front\Account\ProfileController;
use App\Http\Controllers\Web\Front\Account\SavedPostsController;
use App\Http\Controllers\Web\Front\Account\SavedSearchesController;
use App\Http\Controllers\Web\Front\Account\SecurityController;
use App\Http\Controllers\Web\Front\Account\SubscriptionController;
use App\Http\Controllers\Web\Front\Account\TransactionsController;
use Illuminate\Support\Facades\Route;

$accountMiddlewares = ['auth', 'twoFactor', 'banned.user', 'no.http.cache'];

Route::middleware($accountMiddlewares)
	->group(function ($router) {
		$disableImpersonation = ['impersonate.protect'];
		
		// Users
		Route::get('overview', [OverviewController::class, 'index']);
		
		Route::controller(ProfileController::class)
			->prefix('profile')
			->group(function ($router) use ($disableImpersonation) {
				Route::get('/', 'index');
				Route::middleware($disableImpersonation)
					->group(function ($router) {
						Route::put('/', 'updateDetails');
						Route::put('photo', 'updatePhoto');
						Route::put('photo/delete', 'deletePhoto');
					});
			});
		
		Route::prefix('security')
			->group(function ($router) use ($disableImpersonation) {
				Route::get('/', [SecurityController::class, 'index']); // Page with forms
				Route::put('password', [SecurityController::class, 'changePassword'])->middleware($disableImpersonation);
				Route::put('two-factor', [SecurityController::class, 'setupTwoFactor'])->middleware($disableImpersonation);
			});
		
		Route::controller(PreferencesController::class)
			->group(function ($router) use ($disableImpersonation) {
				Route::get('preferences', 'index');
				Route::middleware($disableImpersonation)
					->group(function ($router) {
						Route::put('preferences', 'updatePreferences');
						Route::post('dark-mode', 'setDarkMode');
					});
			});
		
		Route::controller(LinkedAccountsController::class)
			->prefix('linked-accounts')
			->group(function ($router) use ($disableImpersonation) {
				Route::get('/', 'index');
				Route::get('{provider}/disconnect', 'disconnect')->middleware($disableImpersonation);
			});
		
		Route::controller(ClosingController::class)
			->group(function ($router) use ($disableImpersonation) {
				Route::get('closing', 'showForm');
				Route::post('closing', 'postForm')->middleware($disableImpersonation);
			});
		
		// Subscription
		Route::controller(SubscriptionController::class)
			->group(function ($router) {
				$router->pattern('id', '[0-9]+');
				
				Route::get('subscription', 'showForm');
				Route::post('subscription', 'postForm');
				
				// Payment Gateway Success & Cancel
				Route::get('{id}/payment/success', 'paymentConfirmation');
				Route::post('{id}/payment/success', 'paymentConfirmation');
				Route::get('{id}/payment/cancel', 'paymentCancel');
			});
		
		// Transactions
		Route::namespace('Transactions')
			->prefix('transactions')
			->group(function ($router) {
				Route::get('promotion', [TransactionsController::class, 'index']);
				Route::get('subscription', [TransactionsController::class, 'index']);
			});
	});

Route::middleware($accountMiddlewares)
	->group(function ($router) {
		// Posts
		Route::controller(PostsController::class)
			->prefix('posts')
			->group(function ($router) {
				$router->pattern('id', '[0-9]+');
				
				// Activated Posts
				Route::get('list', 'onlinePosts');
				Route::get('list/{id}/offline', 'takePostOffline');
				Route::get('list/{id}/delete', 'destroy');
				Route::post('list/delete', 'destroy');
				
				// Archived Posts
				Route::get('archived', 'archivedPosts');
				Route::get('archived/{id}/repost', 'repostPost');
				Route::get('archived/{id}/delete', 'destroy');
				Route::post('archived/delete', 'destroy');
				
				// Pending Approval Posts
				Route::get('pending-approval', 'pendingApprovalPosts');
				Route::get('pending-approval/{id}/delete', 'destroy');
				Route::post('pending-approval/delete', 'destroy');
			});
		
		// Saved Posts
		Route::controller(SavedPostsController::class)
			->prefix('saved-posts')
			->group(function ($router) {
				$router->pattern('id', '[0-9]+');
				
				Route::post('toggle', 'toggle');
				Route::get('/', 'index');
				Route::get('{id}/delete', 'destroy');
				Route::post('delete', 'destroy');
			});
		
		// Saved Searches
		Route::controller(SavedSearchesController::class)
			->prefix('saved-searches')
			->group(function ($router) {
				$router->pattern('id', '[0-9]+');
				
				Route::post('store', 'store');
				Route::get('/', 'index');
				Route::get('{id}', 'show');
				Route::get('{id}/delete', 'destroy');
				Route::post('delete', 'destroy');
			});
	});

// Messenger
// Contact Post's Author
Route::post('messages/posts/{id}', [MessagesController::class, 'store']);

// Messenger Chat
Route::middleware($accountMiddlewares)
	->group(function ($router) {
		Route::controller(MessagesController::class)
			->prefix('messages')
			->group(function ($router) {
				$router->pattern('id', '[0-9]+');
				
				Route::post('check-new', 'checkNew');
				Route::get('/', 'index');
				Route::post('/', 'store');
				Route::get('{id}', 'show');
				Route::put('{id}', 'update');
				Route::get('{id}/actions', 'actions');
				Route::post('actions', 'actions');
				Route::get('{id}/delete', 'destroy');
				Route::post('delete', 'destroy');
			});
	});
