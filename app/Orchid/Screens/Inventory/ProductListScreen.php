<?php

declare (strict_types = 1);

namespace App\Orchid\Screens\Inventory;

use App\Models\Product;
use App\Orchid\Layouts\Inventory\ProductEditLayout;
use App\Orchid\Layouts\Inventory\ProductListLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Layout;
use Orchid\Screen\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class ProductListScreen extends Screen {
	/**
	 * Display header name.
	 *
	 * @var string
	 */
	public $name = 'Product';

	/**
	 * Display header description.
	 *
	 * @var string
	 */
	public $description = 'All registered products';

	/**
	 * @var string
	 */
	public $permission = 'platform.products';

	/**
	 * Query data.
	 *
	 * @return array
	 */
	public function query(): array
	{

		return [
			'products' => Product::filters()
				->where('type', '=', 'product')
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
				->link(route('platform.products.create')),
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
			ProductListLayout::class,

			Layout::modals([
				'oneAsyncModal' => [
					ProductEditLayout::class,
				],
			])->async('asyncGetProduct'),
		];
	}

	/**
	 * @param Product $product
	 *
	 * @return array
	 */
	public function asyncGetProduct(Product $product): array
	{
		return [
			'product' => $product,
		];
	}

	/**
	 * @param Product $product
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function saveProduct(Product $product, Request $request) {
		$product->fill($request->get('product'));
		$product->save();

		Alert::info(__('Product was saved.'));

		return back();
	}
}
