@php
	$catDisplayType ??= 'c_bigIcon_list';
	
	$apiResult ??= [];
	$totalCategories = (int)data_get($apiResult, 'meta.total', 0);
	$areCategoriesPageable = (!empty(data_get($apiResult, 'links.prev')) || !empty(data_get($apiResult, 'links.next')));
	
	$categories ??= [];
	$category ??= null;
	$hasChildren ??= false;
	$selectedId ??= 0; /* The selected category ID */
	
	$selectionUrl = url('browsing/categories/select');
@endphp
@if (!$hasChildren)
	
	{{-- To append in the form (will replace the category field) --}}
	
	@if (!empty($category))
		@php
			$_catId = data_get($category, 'id');
			$_catName = data_get($category, 'name');
		@endphp
		@if (!empty(data_get($category, 'children')))
			@php
				$_catSelectionUrl = urlQuery($selectionUrl)->setParameters(['parentId' => $_catId])->toString();
			@endphp
			<a href="#browseCategories"
			   data-bs-toggle="modal"
			   class="cat-link open-selection-url"
			   data-selection-url="{{ $_catSelectionUrl }}"
			>
				{{ $_catName }}
			</a>
		@else
			@php
				$_catParentId = data_get($category, 'parent.id', 0);
				$_catSelectionUrl = urlQuery($selectionUrl)->setParameters(['parentId' => $_catParentId])->toString();
			@endphp
			{{ $_catName }}&nbsp;
			[ <a href="#browseCategories"
				 data-bs-toggle="modal"
				 class="cat-link open-selection-url"
				 data-selection-url="{{ $_catSelectionUrl }}"
			><i class="fa-regular fa-pen-to-square"></i> {{ t('Edit') }}</a> ]
		@endif
	@else
		<a href="#browseCategories"
		   data-bs-toggle="modal"
		   class="cat-link open-selection-url"
		   data-selection-url="{{ $selectionUrl }}"
		>
			{{ t('select_a_category') }}
		</a>
	@endif
	
@else
	
	{{-- To append in the modal (will replace the modal content) --}}

	@if (!empty($category))
		@php
			$_parentId = data_get($category, 'parent.id', 0);
			$_url = urlQuery($selectionUrl)->setParameters(['parentId' => $_parentId])->toString();
			$_id = data_get($category, 'id');
			$_name = data_get($category, 'name');
		@endphp
		<p>
			<a href="{!! $_url !!}" class="btn btn-sm btn-success cat-link">
				<i class="fa-solid fa-reply"></i> {{ t('go_to_parent_categories') }}
			</a>&nbsp;
			<strong>{{ $_name }}</strong>
		</p>
		<div style="clear:both"></div>
	@endif
	
	@if (!empty($categories))
		<div class="col-12 content-box layout-section">
			@if ($catDisplayType == 'c_picture_list')
				
				<div id="modalCategoryList" class="row row-cols-lg-6 row-cols-md-4 row-cols-sm-3 row-cols-2 row-featured row-featured-category">
					@foreach($categories as $key => $cat)
						@php
							$_id = data_get($cat, 'id');
							$_hasChildren = (!empty(data_get($cat, 'children'))) ? 1 : 0;
							$_parentId = data_get($cat, 'parent.id', 0);
							$_hasLink = ($_id != $selectedId || $_hasChildren == 1);
							$_type = data_get($cat, 'type');
							$_imageUrl = data_get($cat, 'image_url');
							$_name = data_get($cat, 'name');
							$_url = urlQuery($selectionUrl)->setParameters(['parentId' => $_id])->toString();
						@endphp
						<div class="col f-category">
							@if ($_hasLink)
								<a href="{!! $_url !!}" class="cat-link"
								   data-parent-id="{{ $_parentId }}"
								   data-id="{{ $_id }}"
								   data-has-children="{{ $_hasChildren }}"
								   data-type="{{ $_type }}"
								>
							@endif
							<img src="{{ $_imageUrl }}" class="lazyload img-fluid" alt="{{ $_name }}">
							<h6 class="{{ !$_hasLink ? 'text-secondary' : '' }}">
								{{ $_name }}
							</h6>
							@if ($_hasLink)
								</a>
							@endif
						</div>
					@endforeach
				</div>
			
			@elseif ($catDisplayType == 'c_bigIcon_list')
			
				<div id="modalCategoryList" class="row row-cols-lg-6 row-cols-md-4 row-cols-sm-3 row-cols-2 row-featured row-featured-category">
					@foreach($categories as $key => $cat)
						@php
							$_id = data_get($cat, 'id');
							$_hasChildren = (!empty(data_get($cat, 'children'))) ? 1 : 0;
							$_parentId = data_get($cat, 'parent.id', 0);
							$_hasLink = ($_id != $selectedId || $_hasChildren == 1);
							$_type = data_get($cat, 'type');
							$_iconClass = data_get($cat, 'icon_class');
							$_name = data_get($cat, 'name');
							$_url = urlQuery($selectionUrl)->setParameters(['parentId' => $_id])->toString();
						@endphp
						<div class="col f-category">
							@if ($_hasLink)
								<a href="{!! $_url !!}" class="cat-link"
								   data-parent-id="{{ $_parentId }}"
								   data-id="{{ $_id }}"
								   data-has-children="{{ $_hasChildren }}"
								   data-type="{{ $_type }}"
								>
							@endif
								@if (in_array(config('settings.listings_list.show_category_icon'), [2, 6, 7, 8]))
									<i class="{{ $_iconClass ?? 'fa-solid fa-folder' }}"></i>
								@endif
								<h6 class="{{ !$_hasLink ? 'text-secondary' : '' }}">
									{{ $_name }}
								</h6>
							@if ($_hasLink)
								</a>
							@endif
						</div>
					@endforeach
				</div>
				
			@else
				
				@php
					$isShowingCategoryIconEnabled = in_array(config('settings.listings_list.show_category_icon'), [2, 6, 7, 8]);
					
					$listTab = ['c_border_list' => 'list-border'];
					$catListClass = (isset($listTab[$catDisplayType])) ? 'list ' . $listTab[$catDisplayType] : 'list';
					$catListClass = !empty($catListClass) ? ' ' . $catListClass : '';
				@endphp
				<div class="list-categories">
					<ul id="modalCategoryList" class="row row-cols-lg-3 row-cols-md-2 row-cols-sm-1 row-cols-1{{ $catListClass }} my-4">
						@foreach ($categories as $key => $cat)
							@php
								$_itemClass = (count($categories) == $key + 1) ? ' cat-list-border' : '';
								
								$_catId = data_get($cat, 'id', 0);
								$_catIconClass = $isShowingCategoryIconEnabled ? data_get($cat, 'icon_class', 'fa-solid fa-check') : '';
								$_catIcon = !empty($_catIconClass) ? '<i class="' . $_catIconClass . '"></i> ' : '';
								$_catName = data_get($cat, 'name', '--');
								$_catType = data_get($cat, 'type');
								
								$_hasChildren = !empty(data_get($cat, 'children')) ? 1 : 0;
								$_parentId = data_get($cat, 'parent.id', 0);
								$_hasLink = ($_catId != $selectedId || $_hasChildren == 1);
								$_hasLinkClass = !$_hasLink ? ' text-secondary fw-bold' : '';
								
								$_url = urlQuery($selectionUrl)->setParameters(['parentId' => $_catId])->toString();
							@endphp
							<li class="col cat-list{{ $_itemClass . $_hasLinkClass }} mb-0 px-4">
								<span>
									{!! $_catIcon !!}
									@if ($_hasLink)
										<a href="{!! $_url !!}" class="cat-link"
										   data-parent-id="{{ $_parentId }}"
										   data-id="{{ $_catId }}"
										   data-has-children="{{ $_hasChildren }}"
										   data-type="{{ $_catType }}"
										>
									@endif
										{{ $_catName }}
									@if ($_hasLink)
										</a>
									@endif
								</span>
							</li>
						@endforeach
					</ul>
				</div>
			
			@endif
		</div>
		@if ($totalCategories > 0 && $areCategoriesPageable)
			<br>
			@include('vendor.pagination.api.bootstrap-4')
		@endif
	@else
		{{ $apiMessage ?? t('no_categories_found') }}
	@endif
@endif

@section('before_scripts')
	@parent
@endsection
