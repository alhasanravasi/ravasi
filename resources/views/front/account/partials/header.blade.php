@php
	$headerTitle ??= t('overview');
	$userName = $authUser->name ?? '--';
	$userPhotoUrl = $authUser->photo_url ?? config('larapen.media.avatar');
@endphp
<div class="inner-box default-inner-box">
	<div class="row d-flex align-items-center">
		<div class="col-lg-8 col-md-12 d-flex justify-content-star flex-column justify-content-center">
			<h3 class="p-0">
				{!! $headerTitle !!}
			</h3>
			<div>{!! Breadcrumb::render() !!}</div>
		</div>
		<div class="col-lg-4 col-md-12 d-flex justify-content-lg-end hidden-md">
			<h3 class="p-0">
				{{ $userName }}&nbsp;
				<img id="userImg" class="userImg rounded-circle" src="{{ $userPhotoUrl }}" alt="user">
			</h3>
		</div>
	</div>
</div>
