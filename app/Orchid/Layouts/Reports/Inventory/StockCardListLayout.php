<?php

declare (strict_types = 1);

namespace App\Orchid\Layouts\Reports\Inventory;

use App\Models\Item;
use Carbon\Carbon;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class StockCardListLayout extends Table {
	/**
	 * @var string
	 */
	public $data = 'products';
	public $runningTotal = 0;

	/**
	 * @return array
	 */
	public function fields(): array
	{
		return [
			TD::set('product.name', 'Name')
				->sort(),
			TD::set('trxn_date', __('Date'))
				->render(function (Item $item) {
					return Carbon::parse($item->trxn_date)->format('d/m/Y');

				}),
			TD::set('type', 'Description')
				->sort(),

			TD::set('items.qty', __('Qty'))
				->render(function (Item $item) {
					return number_format((float) $item->qty, 1, '.', '');

				})
				->align(TD::ALIGN_RIGHT),

			TD::set('items.qty', __('Balance'))
				->render(function (Item $item) {
					$this->runningTotal += (float) $item->qty;
					return number_format($this->runningTotal, 1, '.', '');

				})
				->align(TD::ALIGN_RIGHT),
		];
	}
}
