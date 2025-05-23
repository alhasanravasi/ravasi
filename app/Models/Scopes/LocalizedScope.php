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

namespace App\Models\Scopes;

use App\Models\Permission;
use App\Models\Post;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class LocalizedScope implements Scope
{
	/**
	 * Apply the scope to a given Eloquent query builder.
	 *
	 * @param \Illuminate\Database\Eloquent\Builder $builder
	 * @param \Illuminate\Database\Eloquent\Model $model
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function apply(Builder $builder, Model $model): Builder
	{
		if (empty(config('country.code'))) {
			return $builder;
		}
		
		// Apply this scope when the Domain Mapping plugin is installed.
		// And when the session is NOT shared.
		// And, apply it from the Admin panel only.
		if (config('plugins.domainmapping.installed')) {
			if (!config('settings.domainmapping.share_session')) {
				if (request()->segment(1) == urlGen()->adminUri()) {
					// 'countries' table filter
					if ($model->getTable() == 'countries') {
						return $builder->where('code', '=', config('country.code'));
					}
					
					// Tables with 'country_code' column filter
					if (Schema::hasColumn($model->getTable(), 'country_code')) {
						if ($model->getTable() == 'users') {
							
							if (Permission::checkDefaultPermissions()) {
								$builder->permission(Permission::getStaffPermissions())
									->orWhere('country_code', '=', config('country.code'));
							}
							
						} else {
							$builder->where('country_code', '=', config('country.code'));
						}
						
						return $builder;
					}
					
					// Tables with 'post' relation filter
					if (in_array($model->getTable(), ['pictures', 'reviews'])) {
						return $builder->has('post');
					}
					
					// Tables with 'payable' relation filter
					if ($model->getTable() == 'payments') {
						return $builder->hasMorph('payable', Post::class);
					}
				}
			}
		}
		
		return $builder;
	}
}
