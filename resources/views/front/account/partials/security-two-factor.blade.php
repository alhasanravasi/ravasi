@if (isTwoFactorEnabled())
	<div class="col-12">
		<div class="card card-default">
			<div class="card-header">
				<h4 class="card-title">
					{{ trans('auth.two_factor_settings_title') }}
				</h4>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-12">
						<p class="text-muted">{!! trans('auth.two_factor_settings_info') !!}</p>
					</div>
				</div>
				
				<form name="2faForm" class="form-horizontal" role="form" method="POST" action="{{ urlGen()->accountSecurityTwoFactor() }}">
					@csrf
					@method('PUT')
					
					<input name="user_id" type="hidden" value="{{ $authUser->getAuthIdentifier() }}">
					
					{{-- two_factor_enabled --}}
					@php
						$twoFactorEnabledError = (isset($errors) && $errors->has('two_factor_enabled')) ? ' is-invalid' : '';
						$twoFactorEnabled = $authUser->two_factor_enabled ?? null;
						$twoFactorEnabledOld = old('two_factor_enabled', $twoFactorEnabled);
					@endphp
					<div class="row mb-2">
						<label class="col-md-3 col-form-label{{ $twoFactorEnabledError }}">2FA Status</label>
						<div class="col-md-9 d-flex align-items-center">
							<div class="form-check form-switch" style="min-height: initial;">
								<input class="form-check-input{{ $twoFactorEnabledError }}"
								       type="checkbox"
								       name="two_factor_enabled"
								       id="twoFactorSwitch"
								       value="1" @checked($twoFactorEnabledOld == '1')
								>
								<label class="form-check-label{{ $twoFactorEnabledError }} mb-0" for="twoFactorSwitch">
									{{ ($twoFactorEnabledOld == '1') ? trans('auth.two_factor_enabled') : trans('auth.two_factor_disabled') }}
								</label>
							</div>
						</div>
					</div>
					
					{{-- two_factor_method --}}
					@php
						$twoFactorMethodError = (isset($errors) && $errors->has('two_factor_method')) ? ' is-invalid' : '';
						$twoFactorMethod = $authUser->two_factor_method ?? null;
					@endphp
					<div id="2faOptions" class="{{ $twoFactorEnabled ? '' : 'd-none' }}">
						<div class="row mb-2">
							<label class="col-md-3 col-form-label{{ $twoFactorMethodError }} text-md-right">2FA Method</label>
							<div class="col-md-9 d-flex align-items-center">
								@if (isTwoFactorEnabled('email'))
									<div class="form-check form-check-inline" style="min-height: initial;">
										<input class="form-check-input{{ $twoFactorMethodError }}"
										       type="radio"
										       name="two_factor_method"
										       id="methodEmail"
										       value="email" @checked(old('two_factor_method', $twoFactorMethod) == 'email')
										>
										<label class="form-check-label{{ $twoFactorMethodError }} mb-0" for="methodEmail">Email</label>
									</div>
								@endif
								@if (isTwoFactorEnabled('sms'))
									<div class="form-check form-check-inline" style="min-height: initial;">
										<input class="form-check-input{{ $twoFactorMethodError }}"
										       type="radio"
										       name="two_factor_method"
										       id="methodSms"
										       value="sms" @checked(old('two_factor_method', $twoFactorMethod) == 'sms')
												{{ ($twoFactorMethod === 'sms') ? 'checked' : '' }}
										>
										<label class="form-check-label{{ $twoFactorMethodError }} mb-0" for="methodSms">SMS</label>
									</div>
								@endif
							</div>
						</div>
						
						{{-- phone --}}
						@if (isTwoFactorEnabled('sms'))
							@php
								$phoneError = (isset($errors) && $errors->has('phone')) ? ' is-invalid' : '';
								$phoneValue = $authUser->phone ?? null;
								$phoneCountryValue = $authUser->phone_country ?? config('country.code');
								$phoneValue = phoneE164($phoneValue, $phoneCountryValue);
								$phoneValueOld = phoneE164(old('phone', $phoneValue), old('phone_country', $phoneCountryValue));
								
								$twoFactorMethod = $authUser->two_factor_method ?? null;
								$phoneCanBeShown = ($twoFactorMethod === 'sms' && empty($phoneValue));
							@endphp
							<div id="phoneField" class="{{ $phoneCanBeShown ? '' : 'd-none' }}">
								<div class="row mb-3">
									<label for="phone_number" class="col-md-3 col-form-label{{ $phoneError }} text-md-right">
										{{ trans('auth.phone_number') }}
										@if (getAuthField() == 'phone')
											<sup>*</sup>
										@endif
									</label>
									<div class="col-md-9 col-lg-8 col-xl-6">
										<input id="phone"
										       type="tel"
										       class="form-control{{ $phoneError }}"
										       name="phone"
										       value="{{ $phoneValueOld }}"
										>
										<input name="phone_country" type="hidden" value="{{ old('phone_country', $phoneCountryValue) }}">
									</div>
								</div>
							</div>
						@endif
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
@endif

@section('after_scripts')
	@parent
	<script>
		/* 2FA translations */
		const lang2fa = {
			enabled: "{{ trans('auth.two_factor_enabled') }}",
			disabled: "{{ trans('auth.two_factor_disabled') }}",
		};
		
		onDocumentReady((event) => {
			
			/* Handle 2FA switch toggle */
			const twoFactorSwitchEl = document.getElementById('twoFactorSwitch');
			const twoFactorOptionsEl = document.getElementById('2faOptions');
			
			if (twoFactorSwitchEl && twoFactorOptionsEl) {
				const twoFactorLabel = twoFactorSwitchEl.nextElementSibling;
				twoFactorSwitchEl.addEventListener('change', function () {
					twoFactorOptionsEl.classList.toggle('d-none', !this.checked);
					twoFactorLabel.textContent = this.checked ? lang2fa.enabled : lang2fa.disabled;
				});
			}
			
			/* Handle 2FA method radio buttons */
			const twoFactorMethodEls = document.querySelectorAll('input[name="two_factor_method"]');
			const phoneFieldEl = document.getElementById('phone');
			if (twoFactorMethodEls.length > 0 && phoneFieldEl) {
				twoFactorMethodEls.forEach(input => {
					input.addEventListener('change', function () {
						if (!isEmpty(phoneFieldEl.value)) {
							return false;
						}
						
						phoneFieldEl.classList.toggle('d-none', this.value !== 'sms');
					});
				});
			}
			
		});
	</script>
@endsection
