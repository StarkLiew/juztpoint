<?php

declare (strict_types = 1);

namespace App\Orchid;

use App\Orchid\Composers\MainMenuComposer;
use App\Orchid\Composers\SystemMenuComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;

class PlatformProvider extends ServiceProvider {
	/**
	 * Boot the application events.
	 *
	 * @param Dashboard $dashboard
	 */
	public function boot(Dashboard $dashboard) {
		View::composer('platform::dashboard', MainMenuComposer::class);
		View::composer('platform::systems', SystemMenuComposer::class);

		$dashboard
		//->registerPermissions($this->registerPermissionsMain())
		->registerPermissions($this->registerPermissionsSystems());

		$dashboard->registerGlobalSearch([
			//...Models
		]);
	}

	/**
	 * @return ItemPermission
	 */
	protected function registerPermissionsMain(): ItemPermission {
		return ItemPermission::group(__('Main'))
			->addPermission('platform.index', __('Main'))
			->addPermission('platform.systems', __('Systems'));
	}

	/**
	 * @return ItemPermission
	 */
	protected function registerPermissionsSystems(): ItemPermission {
		return ItemPermission::group(__('Systems'))
			->addPermission('platform.systems.roles', __('Roles'))
			->addPermission('platform.systems.users', __('Users'));
	}

}
