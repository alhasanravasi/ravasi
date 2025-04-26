@php
	$userStats ??= [];
	
	$countPendingApprovalPosts = (int)data_get($userStats, 'posts.pendingApproval', 0);
	$countArchivedPosts = (int)data_get($userStats, 'posts.archived', 0);
	$countPosts = (int)data_get($userStats, 'posts.published', 0);
	// $countPosts = $countPosts + $countPendingApprovalPosts + $countArchivedPosts;
	$postsVisits = (int)data_get($userStats, 'posts.visits', 0);
	$countFavoritePosts = (int)data_get($userStats, 'posts.favourite', 0);
	$countThreads = (int)data_get($userStats, 'threads.all', 0);
@endphp
<div class="card card-default">
	<div class="card-header">
		<h4 class="card-title">
			{{ t('account_stats') }}
		</h4>
	</div>
	<div class="card-body">
		
		<div class="container px-0 text-center">
			<div class="row g-3">
				
				<div class="col-6">
					<div class="inner-box default-inner-box m-0">
						{{-- Listings Stats --}}
						<div class="row">
							<div class="col-12">
								<a href="{{ url(urlGen()->getAccountBasePath() . '/posts/list') }}">
									<i class="fa-solid fa-bullhorn ln-shadow rounded-circle"></i>
								</a>
							</div>
							<div class="col-12">
								<a href="{{ url(urlGen()->getAccountBasePath() . '/posts/list') }}">
									{{ \App\Helpers\Common\Num::short($countPosts) }}
									{{ trans_choice('global.count_active_posts', getPlural($countPosts), [], config('app.locale')) }}
								</a>
							</div>
						</div>
					</div>
				</div>
				
				<div class="col-6">
					<div class="inner-box default-inner-box m-0">
						{{-- Traffic Stats --}}
						<div class="row">
							<div class="col-12">
								<a href="{{ url(urlGen()->getAccountBasePath() . '/posts/list') }}">
									<i class="fa-regular fa-eye ln-shadow rounded-circle"></i>
								</a>
							</div>
							<div class="col-12">
								<a href="{{ url(urlGen()->getAccountBasePath() . '/posts/list') }}">
									{{ \App\Helpers\Common\Num::short($postsVisits) }}
									{{ trans_choice('global.count_visits', getPlural($postsVisits), [], config('app.locale')) }}
								</a>
							</div>
						</div>
					</div>
				</div>
				
				<div class="col-6">
					<div class="inner-box default-inner-box m-0">
						{{-- Favorites Stats --}}
						<div class="row">
							<div class="col-12">
								<a href="{{ url(urlGen()->getAccountBasePath() . '/saved-posts') }}">
									<i class="fa-solid fa-bookmark ln-shadow rounded-circle"></i>
								</a>
							</div>
							<div class="col-12">
								<a href="{{ url(urlGen()->getAccountBasePath() . '/saved-posts') }}">
									{{ \App\Helpers\Common\Num::short($countFavoritePosts) }}
									{{ trans_choice('global.count_favorites', getPlural($countFavoritePosts), [], config('app.locale')) }}
								</a>
							</div>
						</div>
					</div>
				</div>
				
				<div class="col-6">
					<div class="inner-box default-inner-box m-0">
						{{-- Threads Stats --}}
						<div class="row">
							<div class="col-12">
								<a href="{{ url(urlGen()->getAccountBasePath() . '/messages') }}">
									<i class="fa-solid fa-envelope ln-shadow rounded-circle"></i>
								</a>
							</div>
							<div class="col-12">
								<a href="{{ url(urlGen()->getAccountBasePath() . '/messages') }}">
									{{ \App\Helpers\Common\Num::short($countThreads) }}
									{{ trans_choice('global.count_mails', getPlural($countThreads), [], config('app.locale')) }}
								</a>
							</div>
						</div>
					</div>
				</div>
				
				<!---->
				
				<div class="col-6">
					<div class="inner-box default-inner-box m-0">
						{{-- Pending Approval Stats --}}
						<div class="row">
							<div class="col-12">
								<a href="{{ url(urlGen()->getAccountBasePath() . '/posts/pending-approval') }}">
									<i class="fa-solid fa-hourglass-half ln-shadow rounded-circle"></i>
								</a>
							</div>
							<div class="col-12">
								<a href="{{ url(urlGen()->getAccountBasePath() . '/posts/pending-approval') }}">
									{{ \App\Helpers\Common\Num::short($countPendingApprovalPosts) }}
									{{ trans_choice('global.count_pending_approval_posts', getPlural($countPendingApprovalPosts), [], config('app.locale')) }}
								</a>
							</div>
						</div>
					</div>
				</div>
				
				<div class="col-6">
					<div class="inner-box default-inner-box m-0">
						{{-- Archived Posts Stats --}}
						<div class="row">
							<div class="col-12">
								<a href="{{ url(urlGen()->getAccountBasePath() . '/posts/archived') }}">
									<i class="fa-solid fa-calendar-xmark ln-shadow rounded-circle"></i>
								</a>
							</div>
							<div class="col-12">
								<a href="{{ url(urlGen()->getAccountBasePath() . '/posts/archived') }}">
									{{ \App\Helpers\Common\Num::short($countArchivedPosts) }}
									{{ trans_choice('global.count_archived_posts', getPlural($countArchivedPosts), [], config('app.locale')) }}
								</a>
							</div>
						</div>
					</div>
				</div>
				
			</div>
		
		</div>
	
	</div>
</div>
