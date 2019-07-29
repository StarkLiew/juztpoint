<?php

declare (strict_types = 1);

namespace App\Orchid\Screens\Settings\Company;

use App\Models\Setting;
use App\Orchid\Layouts\Company\CompanyEditLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class CompanyEditScreen extends Screen {
	/**
	 * Display header name.
	 *
	 * @var string
	 */
	public $name = 'Company';

	/**
	 * Display header description.
	 *
	 * @var string
	 */
	public $description = 'Edit My Company Information';

	/**
	 * Query data.
	 *
	 * @param \Orchid\Platform\Models\User $user
	 *
	 * @return array
	 */
	public function query(): array
	{
		$company = new Setting;
		// $company->setTable('user_33_settings');
		$data = $company->where('type', '=', 'company')->first();

		return [
			'company' => $data,
		];

	}

	/**
	 * @throws \Throwable
	 *
	 * @return array
	 */
	public function layout(): array
	{
		return [
			CompanyEditLayout::class,
		];
	}

	/**
	 * Button commands.
	 *
	 * @return Link[]
	 */
	public function commandBar(): array
	{
		return [

			Link::name(__('Save'))
				->icon('icon-check')
				->method('save'),
		];
	}

	/**
	 * @param \Orchid\Platform\Models\User $user
	 * @param \Illuminate\Http\Request     $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function save(Request $request) {
		/* permissions = $request->get('permissions', []);
			$roles = $request->input('user.roles', []);

			foreach ($permissions as $key => $value) {
				unset($permissions[$key]);
				$permissions[base64_decode($key)] = $value;
			}

			$user
				->fill($request->get('user'))
				->replaceRoles($roles)
				->fill([
					'permissions' => $permissions,
				])
				->save();

		*/
		Alert::info(__('Company was saved'));
	}

}
