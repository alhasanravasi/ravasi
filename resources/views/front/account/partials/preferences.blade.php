@php
	$authUserIsAdmin ??= false;
@endphp
<div class="col-12">
	<div class="card card-default">
		<div class="card-header">
			<h4 class="card-title">
				{{ t('Settings') }}
			</h4>
		</div>
		<div class="card-body">
			<form name="settings"
			      class="form-horizontal"
			      role="form"
			      method="POST"
			      action="{{ urlGen()->accountPreferences() }}"
			      enctype="multipart/form-data"
			>
				{!! csrf_field() !!}
				<input name="_method" type="hidden" value="PUT">
				<input name="panel" type="hidden" value="settings">
				
				<input name="user_id" type="hidden" value="{{ $authUser->getAuthIdentifier() }}">
				
				@if (config('settings.listing_page.activation_facebook_comments') && config('services.facebook.client_id'))
					{{-- disable_comments --}}
					<div class="row mb-3">
						<label class="col-md-3 col-form-label"></label>
						<div class="col-md-9">
							<div class="form-check pt-2">
								<input id="disableComments" name="disable_comments"
								       class="form-check-input"
								       value="1"
								       type="checkbox" @checked($authUser->disable_comments == 1)
								>
								<label class="form-check-label" for="disable_comments" style="font-weight: normal;">
									{{ t('disable_comments_on_listings') }}
								</label>
							</div>
						</div>
					</div>
				@endif
				
				@if ($authUser->accept_terms != 1)
					{{-- accept_terms --}}
					@php
						$acceptTermsError = (isset($errors) && $errors->has('accept_terms')) ? ' is-invalid' : '';
					@endphp
					<div class="row mb-1 required">
						<label class="col-md-3 col-form-label"></label>
						<div class="col-md-9">
							<div class="form-check">
								<input name="accept_terms" id="acceptTerms"
								       class="form-check-input{{ $acceptTermsError }}"
								       value="1"
								       type="checkbox" @checked(old('accept_terms', $authUser->accept_terms) == '1')
								>
								<label class="form-check-label" for="acceptTerms" style="font-weight: normal;">
									{!! t('accept_terms_label', ['attributes' => getUrlPageByType('terms')]) !!}
								</label>
							</div>
							<div style="clear:both"></div>
						</div>
					</div>
					
					<input type="hidden" name="user_accept_terms" value="{{ (int)$authUser->accept_terms }}">
				@endif
				
				{{-- accept_marketing_offers --}}
				@php
					$acceptMarketingOffersError = (isset($errors) && $errors->has('accept_marketing_offers'))
						? ' is-invalid' : '';
				@endphp
				<div class="row mb-3 required">
					<label class="col-md-3 col-form-label"></label>
					<div class="col-md-9">
						<div class="form-check">
							<input name="accept_marketing_offers" id="acceptMarketingOffers"
							       class="form-check-input{{ $acceptMarketingOffersError }}"
							       value="1"
							       type="checkbox"
									@checked(old('accept_marketing_offers', $authUser->accept_marketing_offers) == '1')
							>
							<label class="form-check-label" for="acceptMarketingOffers" style="font-weight: normal;">
								{!! t('accept_marketing_offers_label') !!}
							</label>
						</div>
						<div style="clear:both"></div>
					</div>
				</div>
				
				{{-- time_zone --}}
				@php
					$timeZoneError = (isset($errors) && $errors->has('time_zone')) ? ' is-invalid' : '';
				@endphp
				<div class="row mb-4 required">
					<label class="col-md-3 col-form-label{{ $timeZoneError }}" for="time_zone">
						{{ t('preferred_time_zone_label') }}
					</label>
					<div class="col-md-9 col-lg-8 col-xl-6">
						<select name="time_zone" class="form-control large-data-selecter{{ $timeZoneError }}">
							<option value="" @selected(empty(old('time_zone')))>
								{{ t('select_a_time_zone') }}
							</option>
							@php
								$tz = !empty($authUser->time_zone) ? $authUser->time_zone : '';
							@endphp
							@foreach (\App\Helpers\Common\Date::getTimeZones() as $key => $item)
								<option value="{{ $key }}" @selected(old('time_zone', $tz) == $key)>
									{{ $item }}
								</option>
							@endforeach
						</select>
						<div class="form-text text-muted">
							@if ($authUserIsAdmin)
								{!! t('admin_preferred_time_zone_info', [
										'frontTz' => config('country.time_zone'),
										'country' => config('country.name'),
										'adminTz' => config('app.timezone'),
									]) !!}
							@else
								{!! t('preferred_time_zone_info', [
									'frontTz' => config('country.time_zone'),
									'country' => config('country.name'),
								]) !!}
							@endif
						</div>
					</div>
				</div>
				
				{{-- button --}}
				<div class="row">
					<div class="offset-md-3 col-md-9">
						<button type="submit" class="btn btn-primary">{{ t('Update') }}</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
