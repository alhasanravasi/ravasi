@extends('admin.layouts.master')

@section('header')
	<div class="row page-titles">
		<div class="col-md-5 col-12 align-self-center">
			<h3 class="mb-0">
				{{ trans('admin.dashboard') }}
				<small>
					{{ trans('admin.first_page_you_see', [
						'appName' => config('app.name'),
						'appVersion' => env('APP_VERSION', config('version.app'))
					]) }}
				</small>
			</h3>
		</div>
		<div class="col-md-7 col-12 align-self-center d-none d-md-flex justify-content-end">
			<ol class="breadcrumb mb-0 p-0 bg-transparent">
				<li class="breadcrumb-item"><a href="{{ urlGen()->adminUrl() }}">{{ config('app.name') }}</a></li>
				<li class="breadcrumb-item active d-flex align-items-center">{{ trans('admin.dashboard') }}</li>
			</ol>
		</div>
	</div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
        
			{{-- Small Boxes (Stats Boxes) --}}
			@include('admin.dashboard.stats-boxes')
	
			<div class="row">
				
				{{-- Posts Chart --}}
				@includeIf('admin.dashboard.charts.' . $chartsType['provider'] . '.' . $chartsType['type'] . '.latest-posts')
				
				{{-- Users Chart --}}
				@includeIf('admin.dashboard.charts.' . $chartsType['provider'] . '.' . $chartsType['type'] . '.latest-users')
				
				{{-- Latest Listings --}}
				@include('admin.dashboard.latest-posts')
				
				{{-- Latest Users --}}
				@include('admin.dashboard.latest-users')
				
				{{-- Posts Chart (ChartJs) --}}
				@include('admin.dashboard.charts.chartjs.pie.posts-per-country')
				
				{{-- Posts Chart (ChartJs) --}}
				@include('admin.dashboard.charts.chartjs.pie.users-per-country')
				
			</div>
			
        </div>
    </div>
@endsection

{{-- Define blade stacks so css and js can be pushed from the fields to these sections. --}}
@section('after_styles')
	<link rel="stylesheet" href="{{ asset('assets/plugins/morris/0.5.1/morris.css') }}">
	
	<!-- DASHBOARD LIST CONTENT - dashboard_styles stack -->
	@stack('dashboard_styles')
	
	<style>
		/* Bootstrap tooltip need to be in single line */
		{{-- B4 (xs: < 576px | sm: >= 576px | md >= 768px | lg >= 992px | xl: >= 1200px) --}}
		@media (min-width: 992px) {
			.tooltip-inner {
				white-space: nowrap;
				max-width: none;
			}
		}
	</style>
@endsection

@section('after_scripts')
	<script src="{{ asset('assets/plugins/raphael/raphael.min.js') }}"></script>
	<script src="{{ asset('assets/plugins/morris/morris.min.js') }}"></script>
	<script src="{{ asset('assets/plugins/chartjs/2.7.2/Chart.js') }}"></script>
	
	<!-- DASHBOARD LIST CONTENT - dashboard_scripts stack -->
	@stack('dashboard_scripts')
@endsection
