<?php

declare (strict_types = 1);

namespace App\Orchid\Layouts\Inventory;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ProductListLayout extends Table {
	/**
	 * @var string
	 */
	public $data = 'products';

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
				->link('platform.products.edit', 'id'),

			TD::set('name', __('Name'))
				->sort()
				->filter(TD::FILTER_TEXT),

			TD::set('sku', __('SKU'))
				->sort()
				->filter(TD::FILTER_TEXT),

			TD::set('type', __('Type'))
				->sort()
				->filter(TD::FILTER_TEXT),

			TD::set('category', __('Category'))
				->sort()
				->filter(TD::FILTER_TEXT),

			TD::set('status', __('Status'))
				->sort()
				->filter(TD::FILTER_TEXT),

			TD::set('updated_at', __('Last edit'))
				->sort(),
		];
	}
}
