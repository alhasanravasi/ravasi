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

use App\Http\Controllers\Web\Front\Browsing\Category\CategoryController as BrowsingCategoryController;
use App\Http\Controllers\Web\Front\Browsing\Location\AutoCompleteController;
use App\Http\Controllers\Web\Front\Browsing\Location\ModalController;
use App\Http\Controllers\Web\Front\Browsing\Location\SelectBoxController;
use App\Http\Controllers\Web\Front\CountriesController;
use App\Http\Controllers\Web\Front\FileController;
use App\Http\Controllers\Web\Front\HomeController;
use App\Http\Controllers\Web\Front\Locale\LocaleController;
use App\Http\Controllers\Web\Front\Page\CmsController;
use App\Http\Controllers\Web\Front\Page\ContactController;
use App\Http\Controllers\Web\Front\Page\PricingController;
use App\Http\Controllers\Web\Front\Post\CreateOrEdit\MultiSteps\Create\FinishController as CreateFinishController;
use App\Http\Controllers\Web\Front\Post\CreateOrEdit\MultiSteps\Create\PaymentController as CreatePaymentController;
use App\Http\Controllers\Web\Front\Post\CreateOrEdit\MultiSteps\Create\PhotoController as CreatePhotoController;
use App\Http\Controllers\Web\Front\Post\CreateOrEdit\MultiSteps\Create\PostController as CreatePostController;
use App\Http\Controllers\Web\Front\Post\CreateOrEdit\MultiSteps\Edit\PaymentController as EditPaymentController;
use App\Http\Controllers\Web\Front\Post\CreateOrEdit\MultiSteps\Edit\PhotoController as EditPhotoController;
use App\Http\Controllers\Web\Front\Post\CreateOrEdit\MultiSteps\Edit\PostController as EditPostController;
use App\Http\Controllers\Web\Front\Post\CreateOrEdit\SingleStep\CreateController as SingleCreateController;
use App\Http\Controllers\Web\Front\Post\CreateOrEdit\SingleStep\EditController as SingleEditController;
use App\Http\Controllers\Web\Front\Post\ReportController;
use App\Http\Controllers\Web\Front\Post\Show\ShowController;
use App\Http\Controllers\Web\Front\Search\CategoryController;
use App\Http\Controllers\Web\Front\Search\CityController;
use App\Http\Controllers\Web\Front\Search\SearchController;
use App\Http\Controllers\Web\Front\Search\TagController;
use App\Http\Controllers\Web\Front\Search\UserController;
use App\Http\Controllers\Web\Front\SitemapController;
use App\Http\Controllers\Web\Front\SitemapsController;
use Illuminate\Support\Facades\Route;

$isDomainmappingAvailable = (plugin_exists('domainmapping') && plugin_installed_file_exists('domainmapping'));

// ACCOUNT
$accountBasePath = urlGen()->getAccountBasePath();
Route::namespace('Account')->prefix($accountBasePath)->group(__DIR__ . '/front/account.php');

// Select Language
Route::namespace('Locale')
	->group(function ($router) {
		Route::get('locale/{code}', [LocaleController::class, 'setLocale']);
	});

// FILES
Route::controller(FileController::class)
	->prefix('common')
	->group(function ($router) {
		Route::get('file', 'watchMediaContent');
		Route::get('js/fileinput/locales/{code}.js', 'bootstrapFileinputLocales');
		Route::get('js/intl-tel-input/countries.js', 'intlTelInputData');
		Route::get('css/style.css', 'cssStyle');
	});

if (!$isDomainmappingAvailable) {
	// SITEMAPS (XML)
	Route::get('sitemaps.xml', [SitemapsController::class, 'getAllCountriesSitemapIndex']);
}

// Impersonate (As admin user, login as another user)
Route::middleware(['auth'])
	->group(function ($router) {
		Route::impersonate();
	});


// HOMEPAGE
if (!doesCountriesPageCanBeHomepage()) {
	Route::get('/', [HomeController::class, 'index']);
	Route::get(dynamicRoute('routes.countries'), CountriesController::class);
} else {
	Route::get('/', CountriesController::class);
}


// POSTS
Route::namespace('Post')
	->group(function ($router) {
		$router->pattern('id', '[0-9]+');
		
		$hidPrefix = config('larapen.core.hashableIdPrefix');
		if (is_string($hidPrefix) && !empty($hidPrefix)) {
			$router->pattern('hashableId', '([0-9]+)?(' . $hidPrefix . '[a-z0-9A-Z]{11})?');
		} else {
			$router->pattern('hashableId', '([0-9]+)?([a-z0-9A-Z]{11})?');
		}
		
		// $router->pattern('slug', '.*');
		$bannedSlugs = regexSimilarRoutesPrefixes();
		if (!empty($bannedSlugs)) {
			/*
			 * NOTE:
			 * '^(?!companies|users)$' : Don't match 'companies' or 'users'
			 * '^(?=.*)$'              : Match any character
			 * '^((?!\/).)*$'          : Match any character, but don't match string with '/'
			 */
			$router->pattern('slug', '^(?!' . implode('|', $bannedSlugs) . ')(?=.*)((?!\/).)*$');
		} else {
			$router->pattern('slug', '^(?=.*)((?!\/).)*$');
		}
		
		// SingleStep Listing creation
		Route::namespace('CreateOrEdit\SingleStep')
			->controller(SingleCreateController::class)
			->group(function ($router) {
				Route::get('create', 'showForm');
				Route::post('create', 'postForm');
				Route::get('create/finish', 'finish');
				
				// Payment Gateway Success & Cancel
				Route::get('create/payment/success', 'paymentConfirmation');
				Route::get('create/payment/cancel', 'paymentCancel');
				Route::post('create/payment/success', 'paymentConfirmation');
			});
		
		// MultiSteps Listing creation
		Route::namespace('CreateOrEdit\MultiSteps')
			->group(function ($router) {
				Route::controller(CreatePostController::class)
					->group(function ($router) {
						Route::get('posts/create', 'showForm');
						Route::post('posts/create', 'postForm');
					});
				
				Route::controller(CreatePhotoController::class)
					->group(function ($router) {
						Route::get('posts/create/photos', 'showForm');
						Route::post('posts/create/photos', 'postForm');
						Route::post('posts/create/photos/{photoId}/delete', 'removePicture');
						Route::post('posts/create/photos/reorder', 'reorderPictures');
					});
				
				Route::controller(CreatePaymentController::class)
					->group(function ($router) {
						Route::get('posts/create/payment', 'showForm');
						Route::post('posts/create/payment', 'postForm');
						
						// Payment Gateway Success & Cancel
						Route::get('posts/create/payment/success', 'paymentConfirmation');
						Route::post('posts/create/payment/success', 'paymentConfirmation');
						Route::get('posts/create/payment/cancel', 'paymentCancel');
					});
				
				Route::post('posts/create/finish', CreateFinishController::class);
				Route::get('posts/create/finish', CreateFinishController::class);
			});
		
		Route::middleware(['auth'])
			->group(function ($router) {
				$router->pattern('id', '[0-9]+');
				
				// SingleStep Listing edition
				Route::namespace('CreateOrEdit\SingleStep')
					->controller(SingleEditController::class)
					->group(function ($router) {
						Route::get('edit/{id}', 'showForm');
						Route::put('edit/{id}', 'postForm');
						
						// Payment Gateway Success & Cancel
						Route::get('edit/{id}/payment/success', 'paymentConfirmation');
						Route::get('edit/{id}/payment/cancel', 'paymentCancel');
						Route::post('edit/{id}/payment/success', 'paymentConfirmation');
					});
				
				// MultiSteps Listing Edition
				Route::namespace('CreateOrEdit\MultiSteps')
					->group(function ($router) {
						Route::controller(EditPostController::class)
							->group(function ($router) {
								Route::get('posts/{id}/details', 'showForm');
								Route::put('posts/{id}/details', 'postForm');
							});
						
						Route::controller(EditPhotoController::class)
							->group(function ($router) {
								Route::get('posts/{id}/photos', 'showForm');
								Route::post('posts/{id}/photos', 'postForm');
								Route::post('posts/{id}/photos/{photoId}/delete', 'delete');
								Route::post('posts/{id}/photos/reorder', 'reorder');
							});
						
						Route::controller(EditPaymentController::class)
							->group(function ($router) {
								Route::get('posts/{id}/payment', 'showForm');
								Route::post('posts/{id}/payment', 'postForm');
								
								// Payment Gateway Success & Cancel
								Route::get('posts/{id}/payment/success', 'paymentConfirmation');
								Route::post('posts/{id}/payment/success', 'paymentConfirmation');
								Route::get('posts/{id}/payment/cancel', 'paymentCancel');
							});
					});
			});
		
		// Post's Details
		Route::controller(ShowController::class)
			->group(function ($router) {
				$router->pattern('id', '[0-9]+');
				Route::get(dynamicRoute('routes.post'), 'index');
				Route::post('posts/{id}/phone', 'getPhone');
			});
		
		// Send report abuse
		Route::controller(ReportController::class)
			->group(function ($router) {
				Route::get('posts/{hashableId}/report', 'showReportForm');
				Route::post('posts/{hashableId}/report', 'sendReport');
			});
	});


// BROWSING
Route::namespace('Browsing')
	->prefix('browsing')
	->group(function ($router) {
		// Categories
		Route::controller(BrowsingCategoryController::class)
			->group(function ($router) {
				$router->pattern('id', '[0-9]+');
				Route::post('categories/select', 'getCategoriesHtml'); // To remove!
				Route::get('categories/select', 'getCategoriesHtml');
				Route::post('categories/{id}/fields', 'getCustomFieldsHtml');
			});
		
		// Location
		Route::namespace('Location')
			->group(function ($router) {
				$router->pattern('countryCode', getCountryCodeRoutePattern());
				Route::post('countries/{countryCode}/cities/autocomplete', AutoCompleteController::class);
				Route::controller(SelectBoxController::class)
					->group(function ($router) {
						$router->pattern('id', '[0-9]+');
						Route::get('countries/{countryCode}/admins/{adminType}', 'getAdmins');
						Route::get('countries/{countryCode}/admins/{adminType}/{adminCode}/cities', 'getCities');
						Route::get('countries/{countryCode}/cities/{id}', 'getSelectedCity');
					});
				Route::controller(ModalController::class)
					->group(function ($router) {
						Route::post('locations/{countryCode}/admins/{adminType}', 'getAdmins');
						Route::post('locations/{countryCode}/admins/{adminType}/{adminCode}/cities', 'getCities');
						Route::post('locations/{countryCode}/cities', 'getCities');
					});
			});
	});


// FEEDS
Route::feeds();


if (!$isDomainmappingAvailable) {
	// SITEMAPS (XML)
	Route::controller(SitemapsController::class)
		->group(function ($router) {
			$router->pattern('countryCode', getCountryCodeRoutePattern());
			Route::get('{countryCode}/sitemaps.xml', 'getSitemapIndexByCountry');
			Route::get('{countryCode}/sitemaps/pages.xml', 'getPagesSitemapByCountry');
			Route::get('{countryCode}/sitemaps/categories.xml', 'getCategoriesSitemapByCountry');
			Route::get('{countryCode}/sitemaps/cities.xml', 'getCitiesSitemapByCountry');
			Route::get('{countryCode}/sitemaps/posts.xml', 'getListingsSitemapByCountry');
		});
}


// PAGES
Route::namespace('Page')
	->group(function ($router) {
		Route::get(dynamicRoute('routes.pricing'), [PricingController::class, 'index']);
		Route::get(dynamicRoute('routes.pageBySlug'), [CmsController::class, 'index']);
		Route::controller(ContactController::class)
			->group(function ($router) {
				Route::get(dynamicRoute('routes.contact'), 'showForm');
				Route::post(dynamicRoute('routes.contact'), 'postForm');
			});
	});

// SITEMAP (HTML)
Route::get(dynamicRoute('routes.sitemap'), SitemapController::class);

// SEARCH
Route::namespace('Search')
	->group(function ($router) {
		$router->pattern('id', '[0-9]+');
		$router->pattern('username', '[a-zA-Z0-9]+');
		Route::get(dynamicRoute('routes.search'), [SearchController::class, 'index']);
		Route::get(dynamicRoute('routes.searchPostsByUserId'), [UserController::class, 'index']);
		Route::get(dynamicRoute('routes.searchPostsByUsername'), [UserController::class, 'profile']);
		Route::get(dynamicRoute('routes.searchPostsByTag'), [TagController::class, 'index']);
		Route::get(dynamicRoute('routes.searchPostsByCity'), [CityController::class, 'index']);
		Route::get(dynamicRoute('routes.searchPostsBySubCat'), [CategoryController::class, 'index']);
		Route::get(dynamicRoute('routes.searchPostsByCat'), [CategoryController::class, 'index']);
	});
