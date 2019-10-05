<?php

declare (strict_types = 1);

namespace App\Orchid\Screens\Settings\Commission;

use App\Models\Setting;
use App\Orchid\Layouts\Commission\CommissionEditLayout;
use Auth;
use Illuminate\Http\Request;
use Orchid\Screen\Layout;
use Orchid\Screen\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class CommissionEditScreen extends Screen {
	/**
	 * Display header name.
	 *
	 * @var string
	 */
	public $name = 'Commission';

	/**
	 * @var bool
	 */
	private $exist = false;

	/**
	 * Display header description.
	 *
	 * @var string
	 */
	public $description = 'All registered commissions';

	/**
	 * @var string
	 */
	public $permission = 'platform.systems';

	/**
	 * Query data.
	 *
	 * @param \Orchid\Platform\Models\User $user
	 *
	 * @return array
	 */
	public function query(Setting $commission): array
	{

		$this->exist = $commission->exists;

		return [
			'commission' => $commission,
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

			Link::name(__('Remove'))
				->icon('icon-trash')
				->method('remove')
				->canSee($this->exist),
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
			CommissionEditLayout::class,
		];
	}

	/**
	 * @param \Orchid\Platform\Models\User $user
	 * @param \Illuminate\Http\Request     $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function save(Setting $commission, Request $request) {
		$commission->type = 'commission';
		$commission->user_id = Auth::id();
		$input = $request->get('commission');
		$commission->properties = $input['properties'];
		$commission
			->fill($request->get('commission'))
			->save();

		Alert::info(__('Commission was saved'));

		return redirect()->route('platform.systems.commissions');
	}

	/**
	 * @param User $user
	 *
	 * @throws \Exception
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function remove(Setting $commission) {
		$commission->delete();

		Alert::info(__('Commission was removed'));

		return redirect()->route('platform.systems.commissions');
	}

}
