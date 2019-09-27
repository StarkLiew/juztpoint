<?php
namespace App\Helpers;
use Auth;

class TenantTable {

	public static function parse($name) {
		if (Auth::hasUser()) {
			$authUser = Auth::user();

			$tenant_id = $authUser->id;

			if (!empty($authUser->tenant)) {
				$tenant_id = $authUser->tenant;
			}
			return 'user_' . $tenant_id . '_' . $name;
		}
		return '';
	}

}