<?php

declare (strict_types = 1);

namespace App\Orchid\Screens\Settings\Terminal;

use App\Models\Setting;
use App\Orchid\Layouts\Terminal\TerminalEditLayout;
use App\Orchid\Layouts\Terminal\TerminalListLayout;
use Illuminate\Http\Request;
use Orchid\Platform\Models\User;
use Orchid\Screen\Layout;
use Orchid\Screen\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class TerminalListScreen extends Screen {
	/**
	 * Display header name.
	 *
	 * @var string
	 */
	public $name = 'Terminal';

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
	 * @return array
	 */
	public function query(): array
	{

		return [
			'terminals' => Setting::filters()
				->where('type', '=', 'terminal')
				->defaultSort('id', 'desc')
				->paginate(),
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
			Link::name(__('Add'))
				->icon('icon-plus')
				->link(route('platform.systems.terminals.create')),
		];
	}

	/**
	 * Views.
	 *
	 * @return Layout[]
	 */
	public function layout(): array
	{
		return [
			TerminalListLayout::class,

			Layout::modals([
				'oneAsyncModal' => [
					TerminalEditLayout::class,
				],
			])->async('asyncGetTerminal'),
		];
	}

	/**
	 * @param User $user
	 *
	 * @return array
	 */
	public function asyncGetTerminal(Setting $terminal): array
	{
		return [
			'terminal' => $terminal,
		];
	}

	/**
	 * @param User    $user
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function saveStore(Setting $tax, Request $request) {
		$tax->fill($request->get('tax'));
		$tax->type = 'tax';
		$tax->save();

		Alert::info(__('Tax was saved.'));

		return back();
	}
}
