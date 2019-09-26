<?php

namespace App\Scopes;

use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope {
	/**
	 * Apply the scope to a given Eloquent query builder.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $builder
	 * @param  \Illuminate\Database\Eloquent\Model  $model
	 * @return void
	 */
	public function apply(Builder $builder, Model $model) {

		$authUser = Auth::user();
		$tenant_id = $authUser->id;

		if (!empty($authUser->tenant)) {
			$tenant_id = $authUser->tenant;
		}
		$builder->where('tenant', '=', $tenant_id)->orWhere('id', '=', $tenant_id);

	}
}