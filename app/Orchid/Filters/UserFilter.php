<?php

declare (strict_types = 1);

namespace App\Orchid\Filters;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;

class UserFilter extends Filter {
	/**
	 * @var array
	 */
	public $parameters = [
		'user',
	];

	/**
	 * @return string
	 */
	public function name(): string {
		return __('Staff');
	}

	/**
	 * @param Builder $builder
	 *
	 * @return Builder
	 */
	public function run(Builder $builder): Builder {

		return $builder->whereHas('user', function (Builder $query) {
			$query->where('user_id', $this->request->get('user'));
		});
	}

	/**
	 * @return Field[]
	 */
	public function display(): array
	{
		return [
			Select::make('user')
				->fromModel(User::class, 'name', 'id')
				->empty()
				->value($this->request->get('user'))
				->title(__('Staff')),
		];
	}
}
