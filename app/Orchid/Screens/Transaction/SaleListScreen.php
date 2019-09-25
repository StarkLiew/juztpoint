<?php

declare (strict_types = 1);

namespace App\Orchid\Screens\Transaction;

use App\Models\Document;
use App\Orchid\Layouts\Sales\SaleListLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Layout;
use Orchid\Screen\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class SaleListScreen extends Screen {
	/**
	 * Display header name.
	 * @var string
	 */
	public $name = 'Sales';

	/**
	 * Display header description.
	 *
	 * @var string
	 */
	public $description = 'All sales';

	/**
	 * @var string
	 */
	public $permission = 'platform.sales';

	/**
	 * Query data.
	 *
	 * @return array
	 */
	public function query(): array
	{

		return [
			'sales' => Document::filters()
				->with(['account', 'user', 'terminal' => function ($terminal) {$terminal->with('store');}])
				->where('type', '=', 'receipt')
				->defaultSort('date', 'desc')
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
			SaleListLayout::class,
		];
	}

	/**
	 * @param Product $service
	 *
	 * @return array
	 */
	public function asyncGetSale(Product $sale): array
	{
		return [
			'sale' => $sale,
		];
	}

	/**
	 * @param Product $sale
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function saveProduct(Product $sale, Request $request) {
		$sale->fill($request->get('sale'));
		$sale->save();

		Alert::info(__('Sale was saved.'));

		return back();
	}
}
