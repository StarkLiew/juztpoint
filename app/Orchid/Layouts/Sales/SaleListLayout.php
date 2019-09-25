<?php

declare (strict_types = 1);

namespace App\Orchid\Layouts\Sales;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class SaleListLayout extends Table {
	/**
	 * @var string
	 */
	public $data = 'sales';

	/**
	 * @return array
	 */
	public function fields(): array
	{
		return [
			TD::set('reference', 'Reference')
				->align(TD::ALIGN_CENTER)
				->width('100px')
				->sort()
				->filter(TD::FILTER_TEXT),
			TD::set('date', __('Date'))
				->sort()
				->filter(TD::FILTER_TEXT),
			TD::set('account.name', __('Customer'))
				->sort()
				->filter(TD::FILTER_TEXT),
			TD::set('terminal.store.name', __('Store'))
				->sort()
				->filter(TD::FILTER_TEXT),
			TD::set('terminal.name', __('Terminal'))
				->sort()
				->filter(TD::FILTER_TEXT),

			TD::set('user.name', __('User'))
				->sort()
				->filter(TD::FILTER_TEXT),

		];
	}
}
