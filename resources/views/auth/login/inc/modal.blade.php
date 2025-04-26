<div class="modal fade" id="quickLogin" tabindex="-1" aria-labelledby="quickLoginLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			
			<div class="modal-header px-3">
				<h4 class="modal-title fs-5 fw-bold" id="quickLoginLabel">
					<i class="fa-solid fa-right-to-bracket"></i> {{ trans('auth.log_in') }}
				</h4>
				
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ t('Close') }}"></button>
			</div>
			
			<form role="form" method="POST" action="{{ urlGen()->signIn() }}">
				<div class="modal-body">
					<div class="row">
						<div class="col-12">
							
							{!! csrf_field() !!}
							<input type="hidden" name="language_code" value="{{ config('app.locale') }}">
							
							@if (isset($errors) && $errors->any() && old('quickLoginForm')=='1')
								<div class="alert alert-danger alert-dismissible">
									<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ t('Close') }}"></button>
									<ul class="list list-check">
										@foreach($errors->all() as $error)
											<li>{!! $error !!}</li>
										@endforeach
									</ul>
								</div>
							@endif
							
							@include('auth.login.inc.social', ['socialCol' => 12, 'page' => 'modal'])
							@php
								$mtAuth = !socialLogin()->isEnabled() ? ' mt-3' : '';
							@endphp
							
							
							{{-- email --}}
							@php
								$emailError = (isset($errors) && $errors->has('email')) ? ' is-invalid' : '';
								$emailValue = (session()->has('email')) ? session('email') : old('email');
							@endphp
							<div class="mb-3 auth-field-item{{ $mtAuth }}">
								<div class="row">
									@php
										$col = isPhoneAsAuthFieldEnabled() ? 'col-6' : 'col-12';
									@endphp
									<label class="form-label {{ $col }} m-0 py-2 text-left" for="email">
										{{ trans('auth.email') }}:
									</label>
									@if (isPhoneAsAuthFieldEnabled())
										<div class="col-6 py-2 text-right">
											<a href="" class="auth-field" data-auth-field="phone">
												{{ trans('auth.login_with_phone') }}
											</a>
										</div>
									@endif
								</div>
								<div class="input-group">
									<span class="input-group-text"><i class="bi bi-person"></i></span>
									<input id="mEmail" name="email"
										   type="text"
										   placeholder="{{ trans('auth.email_or_username') }}"
										   class="form-control{{ $emailError }}"
										   value="{{ $emailValue }}"
									>
								</div>
							</div>
							
							{{-- phone --}}
							@if (isPhoneAsAuthFieldEnabled())
								@php
									$phoneError = (isset($errors) && $errors->has('phone')) ? ' is-invalid' : '';
									$phoneValue = (session()->has('phone')) ? session('phone') : old('phone');
									$phoneCountryValue = config('country.code');
								@endphp
								<div class="mb-3 auth-field-item{{ $mtAuth }}">
									<div class="row">
										<label class="form-label col-6 m-0 py-2 text-left" for="phone">
											{{ trans('auth.phone_number') }}:
										</label>
										<div class="col-6 py-2 text-right">
											<a href="" class="auth-field" data-auth-field="email">
												{{ trans('auth.login_with_email') }}
											</a>
										</div>
									</div>
									<input id="mPhone" name="phone"
										   type="tel"
										   class="form-control m-phone{{ $phoneError }}"
										   value="{{ phoneE164($phoneValue, old('phone_country', $phoneCountryValue)) }}"
									>
									<input name="phone_country" type="hidden" value="{{ old('phone_country', $phoneCountryValue) }}">
								</div>
							@endif
							
							{{-- auth_field --}}
							<input name="auth_field" type="hidden" value="{{ old('auth_field', getAuthField()) }}">
							
							{{-- password --}}
							@php
								$passwordError = (isset($errors) && $errors->has('password')) ? ' is-invalid' : '';
							@endphp
							<div class="mb-3 password-field">
								<label for="password" class="control-label">{{ trans('auth.password') }}</label>
								<div class="input-group required toggle-password-wrapper">
									<span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
									<input id="mPassword" name="password"
										   type="password"
										   class="form-control{{ $passwordError }}"
										   placeholder="{{ trans('auth.password') }}"
										   autocomplete="new-password"
									>
									<span class="input-group-text">
										<a class="toggle-password-link" href="#">
											<i class="fa-regular fa-eye-slash"></i>
										</a>
									</span>
								</div>
							</div>
							
							{{-- remember --}}
							@php
								$rememberError = (isset($errors) && $errors->has('remember')) ? ' is-invalid' : '';
							@endphp
							<div class="mb-3">
								<label class="checkbox form-check-label float-start mt-2" for="rememberMe2" style="font-weight: normal;">
									<input type="checkbox"
									       value="1"
									       name="remember_me"
									       id="rememberMe2"
									       class="{{ $rememberError }}"
									> {{ trans('auth.remember_me') }}
								</label>
								<p class="float-end mt-2">
									<a href="{{ urlGen()->passwordForgot() }}">
										{{ trans('auth.forgot_password') }}
									</a> / <a href="{{ urlGen()->signUp() }}">{{ trans('auth.create_account') }}</a>
								</p>
								<div style=" clear:both"></div>
							</div>
							
							@include('front.layouts.inc.tools.captcha', ['label' => true])
							
							<input type="hidden" name="quickLoginForm" value="1">
							
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary float-end">{{ trans('auth.log_in') }}</button>
					<button type="button" class="btn btn-default" data-bs-dismiss="modal">{{ t('Cancel') }}</button>
				</div>
			</form>
			
		</div>
	</div>
</div>
