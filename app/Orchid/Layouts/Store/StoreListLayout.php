<?php

declare (strict_types = 1);

namespace App\Orchid\Layouts\Store;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class StoreListLayout extends Table {
	/**
	 * @var string
	 */
	public $data = 'stores';

	/**
	 * @return array
	 */
	public function fields(): array
	{
		return [
			TD::set('id', 'ID')
				->align(TD::ALIGN_CENTER)
				->width('100px')
				->sort()
				->link('platform.systems.stores.edit', 'id'),

			TD::set('name', __('Name'))
				->sort()
				->filter(TD::FILTER_TEXT),

			TD::set('updated_at', __('Last edit'))
				->sort(),
		];
	}
}
