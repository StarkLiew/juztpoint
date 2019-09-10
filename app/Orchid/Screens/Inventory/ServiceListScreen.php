<?php

declare (strict_types = 1);

namespace App\Orchid\Screens\Inventory;

use App\Models\Product;
use App\Orchid\Layouts\Inventory\ServiceEditLayout;
use App\Orchid\Layouts\Inventory\ServiceListLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Layout;
use Orchid\Screen\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class ServiceListScreen extends Screen {
	/**
	 * Display header name.
	 *
	 * @var string
	 */
	public $name = 'Service';

	/**
	 * Display header description.
	 *
	 * @var string
	 */
	public $description = 'All registered services';

	/**
	 * @var string
	 */
	public $permission = 'platform.services';

	/**
	 * Query data.
	 *
	 * @return array
	 */
	public function query(): array
	{

		return [
			'services' => Product::filters()
				->where('type', '=', 'service')
				->defaultSort('name', 'desc')
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
				->link(route('platform.services.create')),
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
			ServiceListLayout::class,

			Layout::modals([
				'oneAsyncModal' => [
					ServiceEditLayout::class,
				],
			])->async('asyncGetService'),
		];
	}

	/**
	 * @param Product $service
	 *
	 * @return array
	 */
	public function asyncGetService(Product $service): array
	{
		return [
			'service' => $service,
		];
	}

	/**
	 * @param Product $service
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function saveProduct(Product $service, Request $request) {
		$service->fill($request->get('service'));
		$service->save();

		Alert::info(__('Service was saved.'));

		return back();
	}
}
