@php
	$passwordTips = getPasswordTips();
@endphp
<div class="col-12">
	<div class="card card-default">
		<div class="card-header">
			<h4 class="card-title">
				{{ trans('auth.change_password') }}
			</h4>
		</div>
		<div class="card-body">
			<form name="passwordForm"
			      class="form-horizontal"
			      role="form"
			      method="POST"
			      action="{{ urlGen()->accountSecurityPassword() }}"
			>
				@csrf
				@method('PUT')
				
				<input name="user_id" type="hidden" value="{{ $authUser->getAuthIdentifier() }}">
				
				{{-- current_password --}}
				@php
					$currentPasswordError = (isset($errors) && $errors->has('current_password')) ? ' is-invalid' : '';
				@endphp
				<div class="row mb-2">
					<label for="current_password" class="col-md-3 col-form-label{{ $currentPasswordError }}">
						{{ trans('auth.current_password') }}
					</label>
					<div class="col-md-9 col-lg-8 col-xl-6">
						<input id="currentPassword" name="current_password"
						       type="password"
						       class="form-control{{ $currentPasswordError }}"
						       placeholder="{{ trans('auth.current_password') }}"
						       autocomplete="new-password"
						>
					</div>
				</div>
				
				{{-- new_password --}}
				@php
					$newPasswordError = (isset($errors) && $errors->has('new_password')) ? ' is-invalid' : '';
				@endphp
				<div class="row mb-2">
					<label for="new_password" class="col-md-3 col-form-label{{ $newPasswordError }}">
						{{ trans('auth.new_password') }}
					</label>
					<div class="col-md-9 col-lg-8 col-xl-6">
						<input id="newPassword" name="new_password"
						       type="password"
						       class="form-control{{ $newPasswordError }}"
						       placeholder="{{ trans('auth.new_password') }}"
						       autocomplete="new-password"
						>
						@if (!empty($passwordTips))
							<div class="form-text text-muted mt-2">
								@foreach($passwordTips as $tip)
									<span class="d-block"><i class="bi bi-check2"></i> {{ $tip }}</span>
								@endforeach
							</div>
						@endif
					</div>
				</div>
				
				{{-- new_password_confirmation --}}
				@php
					$newPasswordError = (isset($errors) && $errors->has('new_password')) ? ' is-invalid' : '';
				@endphp
				<div class="row mb-3">
					<label for="new_password_confirmation" class="col-md-3 col-form-label{{ $newPasswordError }}">
						{{ trans('auth.confirm_new_password') }}
					</label>
					<div class="col-md-9 col-lg-8 col-xl-6">
						<input id="newPasswordConfirmation" name="new_password_confirmation"
						       type="password"
						       class="form-control{{ $newPasswordError }}"
						       placeholder="{{ trans('auth.confirm_new_password') }}"
						>
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
