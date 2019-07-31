<?php

declare (strict_types = 1);

namespace App\Orchid\Layouts\Commission;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CommissionListLayout extends Table {
	/**
	 * @var string
	 */
	public $data = 'commissions';

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
				->link('platform.systems.commissions.edit', 'id'),

			TD::set('name', __('Name'))
				->sort()
				->filter(TD::FILTER_TEXT),

			TD::set('updated_at', __('Last edit'))
				->sort(),
		];
	}
}
