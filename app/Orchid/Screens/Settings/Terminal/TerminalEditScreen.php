<?php

declare (strict_types = 1);

namespace App\Orchid\Screens\Settings\Terminal;

use App\Models\Setting;
use App\Orchid\Layouts\Terminal\TerminalEditLayout;
use Auth;
use Illuminate\Http\Request;
use Orchid\Screen\Layout;
use Orchid\Screen\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class TerminalEditScreen extends Screen {
	/**
	 * Display header name.
	 *
	 * @var string
	 */
	public $name = 'Terminal';

	/**
	 * @var bool
	 */
	private $exist = false;

	/**
	 * Display header description.
	 *
	 * @var string
	 */
	public $description = 'All registered terminals';

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
	public function query(Setting $terminal): array
	{

		$this->exist = $terminal->exists;

		return [
			'terminal' => $terminal,
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
			TerminalEditLayout::class,
		];
	}

	/**
	 * @param \Orchid\Platform\Models\User $user
	 * @param \Illuminate\Http\Request     $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function save(Setting $setting, Request $request) {
		$setting->type = 'terminal';
		$setting->user_id = Auth::id();
		$input = $request->get('terminal');

		if (!array_key_exists('device_id', $input['properties'])) {

			$input['properties']['device_id'] = uniqid();

		}

		$setting->properties = $input['properties'];

		$setting
			->fill($input)
			->save();

		Alert::info(__('Terminal was saved'));

		return redirect()->route('platform.systems.terminals');
	}

	/**
	 * @param User $user
	 *
	 * @throws \Exception
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function remove(Setting $terminal) {
		$tax->delete();

		Alert::info(__('Terminal was removed'));

		return redirect()->route('platform.systems.terminals');
	}

}
