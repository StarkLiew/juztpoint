<?php

declare (strict_types = 1);

namespace App\Orchid\Layouts\Reports;

use App\Models\Item;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class TotalLayout extends Table {
	/**
	 * @var string
	 */
	public $data = 'total';

	/**
	 * @return array
	 */
	public function fields(): array
	{
		return [

			TD::set('total_amount', __('Amount'))
				->render(function (Item $item) {
					return number_format((float) $item->total_amount, 2, '.', '');

				})
				->align(TD::ALIGN_RIGHT),

		];
	}
}
