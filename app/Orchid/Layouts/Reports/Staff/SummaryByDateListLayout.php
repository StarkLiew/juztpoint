<?php

declare (strict_types = 1);

namespace App\Orchid\Layouts\Reports\Staff;

use App\Models\Item;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class SummaryByDateListLayout extends Table {
	/**
	 * @var string
	 */
	public $data = 'dates';

	/**
	 * @return array
	 */
	public function fields(): array
	{
		return [
			TD::set('trxn_date', 'Date')
				->width('120px')
				->sort(),
			TD::set('user.name', 'Name')
				->width('120px')
				->sort(),
			TD::set('total_amount', __('Amount'))
				->render(function (Item $item) {
					return number_format((float) $item->total_amount, 2, '.', '');

				})
				->align(TD::ALIGN_RIGHT),

		];
	}
}
