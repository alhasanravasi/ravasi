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

/* Prevent errors, If these variables are missing. */
if (typeof oldInputAvailable === 'undefined') {
	var oldInputAvailable = false;
}
if (typeof packageIsEnabled === 'undefined') {
	var packageIsEnabled = false;
}
var select2Language = languageCode;
if (typeof langLayout !== 'undefined' && typeof langLayout.select2 !== 'undefined') {
	select2Language = langLayout.select2;
}
if (typeof permanentPostsEnabled === 'undefined') {
	var permanentPostsEnabled = 0;
}
if (typeof postTypeId === 'undefined') {
	var postTypeId = 0;
}
if (typeof editLabel === 'undefined') {
	var editLabel = 'Edit';
}

onDocumentReady((event) => {
	
	/* Select a category */
	getCategories(siteUrl, languageCode);
	$(document).on('click', '.cat-link, #selectCats .page-link', function (e) {
		e.preventDefault(); /* Prevents submission or reloading */
		
		getCategories(siteUrl, languageCode, this);
	});
	
	/* Show the permanent listings option field */
	showPermanentPostsOption(permanentPostsEnabled, postTypeId);
	$('input[name="post_type_id"]').on('click', function () {
		postTypeId = $(this).val();
		showPermanentPostsOption(permanentPostsEnabled, postTypeId);
	});
	
});

/**
 * Get subcategories buffer and/or Append selected category
 *
 * @param siteUrl
 * @param languageCode
 * @param jsThis
 * @returns {boolean}
 */
function getCategories(siteUrl, languageCode, jsThis = null) {
	let csrfToken = $('input[name=_token]').val();
	
	/* Get Request URL */
	let url;
	
	let selectedId = $('#categoryId').val();
	let beingSelectedId;
	
	if (!isDefined(jsThis) || jsThis === null) {
		/* On page load, without click on the modal link */
		// ---
		beingSelectedId = !isEmpty(selectedId) ? selectedId : 0;
		
		/* Set the global selection URL */
		url = `${siteUrl}/browsing/categories/select`;
		
		if (!oldInputAvailable) {
			return false;
		}
		
	} else {
		/* Click on the modal link */
		// ---
		let thisEl = $(jsThis);
		
		/* Get the category selection URL */
		url = thisEl.attr('href');
		
		if (thisEl.hasClass('page-link')) {
			/* Get URL from pagination link */
			// ---
			
			/* Extract the category ID */
			beingSelectedId = 0;
			if (!isEmpty(url)) {
				beingSelectedId = urlQuery(url).getParameter('parentId') ?? 0;
			}
			
		} else {
			/* Get URL from data-selection-url */
			// ---
			
			if (thisEl.hasClass('open-selection-url')) {
				url = thisEl.data('selection-url');
			} else {
				/* Get the category ID */
				beingSelectedId = thisEl.data('id');
				beingSelectedId = !isEmpty(beingSelectedId) ? beingSelectedId : 0;
			}
			
		}
		
		/*
		 * Optimize the category selection
		 * by preventing AJAX request to append the selection
		 */
		let hasChildren = thisEl.data('has-children');
		if (isDefined(hasChildren) && (hasChildren === 0 || hasChildren === '0')) {
			let catName = thisEl.text();
			let catType = thisEl.data('type');
			let catParentId = thisEl.data('parent-id');
			let catParentUrl = urlQuery(url).setParameters({parentId: catParentId}).toString();
			
			let linkText = `<i class="fa-regular fa-pen-to-square"></i> ${editLabel}`;
			let outputHtml = catName
				+ `[ <a href="#browseCategories"
						data-bs-toggle="modal"
						class="cat-link open-selection-url"
						data-selection-url="${catParentUrl}"
					>${linkText}</a> ]`;
			
			return appendSelectedCategory(siteUrl, languageCode, beingSelectedId, catType, outputHtml);
		}
	}
	
	const payload = {
		'parentId': beingSelectedId
	};
	if (!isEmpty(selectedId)) {
		payload['selectedId'] = selectedId;
	}
	
	/* Reorder the category list */
	/* const categoryListReorder = new BsRowColumnsReorder('#modalCategoryList', {defaultColumns: 6}); */
	
	/* AJAX Call */
	let ajax = $.ajax({
		method: 'GET',
		url: url,
		data: payload,
		beforeSend: function() {
			/*
			let spinner = '<i class="spinner-border"></i>';
			$('#selectCats').addClass('text-center').html(spinner);
			*/
			
			let selectCatsEl = $('#selectCats');
			selectCatsEl.empty().addClass('py-4').busyLoad('hide');
			selectCatsEl.busyLoad('show', {
				text: langLayout.loading,
				custom: createCustomSpinnerEl(),
				background: '#fff',
				containerItemClass: 'm-5',
			});
		}
	});
	ajax.done(function (xhr) {
		let selectCatsEl = $('#selectCats');
		selectCatsEl.removeClass('py-4').busyLoad('hide');
		
		if (!isDefined(xhr.html) || !isDefined(xhr.hasChildren)) {
			return false;
		}
		
		/* Get & append the category's children */
		if (xhr.hasChildren) {
			selectCatsEl.removeClass('text-center');
			selectCatsEl.html(xhr.html);
			
			/* Apply GridReorder to the new content */
			/* categoryListReorder.addNewContainers(selectCatsEl[0]); */
		} else {
			/*
			 * Section to append default category field info
			 * or to append selected category during form loading.
			 * Not intervene when the onclick event is fired.
			 */
			if (!isDefined(xhr.category) || !isDefined(xhr.category.id) || !isDefined(xhr.category.type) || !isDefined(xhr.html)) {
				return false;
			}
			
			return appendSelectedCategory(siteUrl, languageCode, xhr.category.id, xhr.category.type, xhr.html);
		}
	});
	ajax.fail(function(xhr) {
		let message = getErrorMessageFromXhr(xhr);
		if (message !== null) {
			jsAlert(message, 'error', false, true);
			
			/* Close the Modal */
			let modalEl = document.querySelector('#browseCategories');
			if (typeof modalEl !== 'undefined' && modalEl !== null) {
				let modalObj = bootstrap.Modal.getInstance(modalEl);
				if (modalObj !== null) {
					modalObj.hide();
				}
			}
		}
	});
}

/**
 * Append the selected category to its field in the form
 *
 * @param siteUrl
 * @param languageCode
 * @param catId
 * @param catType
 * @param outputHtml
 * @returns {boolean}
 */
function appendSelectedCategory(siteUrl, languageCode, catId, catType, outputHtml) {
	if (!isDefined(catId) || !isDefined(catType) || !isDefined(outputHtml)) {
		return false;
	}
	
	try {
		/* Select the category & append it */
		$('#catsContainer').html(outputHtml);
		
		/* Save data in hidden field */
		$('#categoryId').val(catId);
		$('#categoryType').val(catType);
		
		/* Close the Modal */
		let modalEl = document.querySelector('#browseCategories');
		if (isDefined(modalEl) && modalEl !== null) {
			let modalObj = bootstrap.Modal.getInstance(modalEl);
			if (modalObj !== null) {
				modalObj.hide();
			}
		}
		
		/* Apply category's type actions & Get category's custom-fields */
		applyCategoryTypeActions('categoryType', catType, packageIsEnabled);
		getCustomFieldsByCategory(siteUrl, languageCode, catId);
	} catch (e) {
		console.log(e);
	}
	
	return false;
}

/**
 * Get the Custom Fields by Category
 *
 * @param siteUrl
 * @param languageCode
 * @param catId
 * @returns {*}
 */
function getCustomFieldsByCategory(siteUrl, languageCode, catId) {
	/* Check undefined variables */
	if (!isDefined(languageCode) || !isDefined(catId)) {
		return false;
	}
	
	/* Don't make ajax request if any category has selected. */
	if (isEmpty(catId) || catId === 0) {
		return false;
	}
	
	let csrfToken = $('input[name=_token]').val();
	
	let url = `${siteUrl}/browsing/categories/${catId}/fields`;
	
	let dataObj = {
		'_token': csrfToken,
		'languageCode': languageCode,
		'postId': isDefined(postId) ? postId : ''
	};
	if (isDefined(errors)) {
		/* console.log(errors); */
		dataObj.errors = errors;
	}
	if (isDefined(oldInput)) {
		/* console.log(oldInput); */
		dataObj.oldInput = oldInput;
	}
	
	let ajax = $.ajax({
		method: 'POST',
		url: url,
		data: dataObj,
		beforeSend: function() {
			let cfEl = $('#cfContainer');
			
			let spinner = '<i class="spinner-border"></i>';
			cfEl.addClass('text-center mb-3').html(spinner);
		}
	});
	ajax.done(function (xhr) {
		let cfEl = $('#cfContainer');
		
		/* Load Custom Fields */
		cfEl.removeClass('text-center mb-3');
		cfEl.html(xhr.customFields);
		
		/* Apply Fields Components */
		initSelect2(cfEl, languageCode);
		cfEl.find('.selecter, .large-data-selecter').select2({
			width: '100%'
		});
	});
	ajax.fail(function(xhr) {
		let message = getErrorMessageFromXhr(xhr);
		if (message !== null) {
			jsAlert(message, 'error', false);
		}
	});
	
	return catId;
}

/**
 * Apply Category Type actions (for Job offer/search & Services for example)
 *
 * @param categoryTypeFieldId
 * @param categoryTypeValue
 * @param packageIsEnabled
 */
function applyCategoryTypeActions(categoryTypeFieldId, categoryTypeValue, packageIsEnabled) {
	$('#' + categoryTypeFieldId).val(categoryTypeValue);
	
	/* Debug */
	/* console.log(categoryTypeFieldId + ': ' + categoryTypeValue); */
	
	if (categoryTypeValue === 'job-offer') {
		$('#postTypeBloc label[for="post_type_id-1"]').show();
		$('#priceBloc label[for="price"]').html(lang.salary);
		$('#priceBloc').show();
	} else if (categoryTypeValue === 'job-search') {
		$('#postTypeBloc label[for="post_type_id-2"]').hide();
		
		$('#postTypeBloc input[value="1"]').attr('checked', 'checked');
		$('#priceBloc label[for="price"]').html(lang.salary);
		$('#priceBloc').show();
	} else if (categoryTypeValue === 'not-salable') {
		$('#priceBloc').hide();
		
		$('#postTypeBloc label[for="post_type_id-2"]').show();
	} else {
		$('#postTypeBloc label[for="post_type_id-2"]').show();
		$('#priceBloc label[for="price"]').html(lang.price);
		$('#priceBloc').show();
	}
	
	$('#nextStepBtn').html(lang.nextStepBtnLabel.next);
}

function initSelect2(selectElementObj, languageCode) {
	selectElementObj.find('.selecter').select2({
		language: select2Language,
		dropdownAutoWidth: 'true',
		minimumResultsForSearch: Infinity /* Hiding the search box */
	});
	
	selectElementObj.find('.large-data-selecter').select2({
		language: select2Language,
		dropdownAutoWidth: 'true'
	});
}

/**
 * Show the permanent listings option field
 *
 * @param permanentPostsEnabled
 * @param postTypeId
 * @returns {boolean}
 */
function showPermanentPostsOption(permanentPostsEnabled, postTypeId)
{
	if (permanentPostsEnabled === '0' || permanentPostsEnabled === 0) {
		$('#isPermanentBox').empty();
		return false;
	}
	if (permanentPostsEnabled === '1' || permanentPostsEnabled === 1) {
		if (postTypeId === '1' || postTypeId === 1) {
			$('#isPermanentBox').removeClass('hide');
		} else {
			$('#isPermanentBox').addClass('hide');
			$('#isPermanent').prop('checked', false);
		}
	}
	if (permanentPostsEnabled === '2' || permanentPostsEnabled === 2) {
		if (postTypeId === '2' || postTypeId === 2) {
			$('#isPermanentBox').removeClass('hide');
		} else {
			$('#isPermanentBox').addClass('hide');
			$('#isPermanent').prop('checked', false);
		}
	}
	if (permanentPostsEnabled === '3' || permanentPostsEnabled === 3) {
		let isPermanentField = $('#isPermanent');
		if (isPermanentField.length) {
			if (postTypeId === '2' || postTypeId === 2) {
				isPermanentField.val('1');
			} else {
				isPermanentField.val('0');
			}
		}
	}
	if (permanentPostsEnabled === '4' || permanentPostsEnabled === 4) {
		$('#isPermanentBox').removeClass('hide');
	}
}
