<?php

declare (strict_types = 1);

namespace App\Orchid\Screens\Settings\Tax;

use App\Models\Setting;
use App\Orchid\Layouts\Tax\TaxEditLayout;
use Auth;
use Illuminate\Http\Request;
use Orchid\Screen\Layout;
use Orchid\Screen\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class TaxEditScreen extends Screen {
	/**
	 * Display header name.
	 *
	 * @var string
	 */
	public $name = 'Tax';

	/**
	 * @var bool
	 */
	private $exist = false;

	/**
	 * Display header description.
	 *
	 * @var string
	 */
	public $description = 'All registered taxes';

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
	public function query(Setting $tax): array
	{

		$this->exist = $tax->exists;

		return [
			'tax' => $tax,
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
			TaxEditLayout::class,
		];
	}

	/**
	 * @param \Orchid\Platform\Models\User $user
	 * @param \Illuminate\Http\Request     $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function save(Setting $tax, Request $request) {
		$tax->type = 'tax';
		$tax->user_id = Auth::id();
		$input = $request->get('tax');
		$tax->properties = $input['properties'];
		$tax
			->fill($request->get('tax'))
			->save();

		Alert::info(__('Tax was saved'));

		return redirect()->route('platform.systems.taxes');
	}

	/**
	 * @param User $user
	 *
	 * @throws \Exception
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function remove(Setting $tax) {
		$tax->delete();

		Alert::info(__('Tax was removed'));

		return redirect()->route('platform.systems.taxes');
	}

}
