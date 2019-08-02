<?php

declare (strict_types = 1);

namespace App\Orchid\Screens\Inventory;

use App\Models\Product;
use App\Orchid\Layouts\Inventory\ProductEditLayout;
use App\Orchid\Layouts\Inventory\ProductRightEditLayout;
use Auth;
use Illuminate\Http\Request;
use Orchid\Screen\Layout;
use Orchid\Screen\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class ProductEditScreen extends Screen {
	/**
	 * Display header name.
	 *
	 * @var string
	 */
	public $name = 'Product';

	/**
	 * @var bool
	 */
	private $exist = false;

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
	 * @param \App\Models\Product $product
	 *
	 * @return array
	 */
	public function query(Product $product): array
	{

		$this->exist = $product->exists;

		return [
			'product' => $product,
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

			Link::name(__('Save'))
				->icon('icon-check')
				->method('save'),

			Link::name(__('Remove'))
				->icon('icon-trash')
				->method('remove')
				->canSee($this->exist),
		];
	}

	/**
	 * @throws \Throwable
	 *
	 * @return array
	 */
	public function layout(): array
	{
		return [
			Layout::columns([
				ProductEditLayout::class,
				ProductRightEditLayout::class,
			]),

		];
	}

	/**
	 * @param \App\Models\Account $account
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function save(Product $product, Request $request) {
		$product->user_id = Auth::id();
		$input = $request->get('product');
		if (array_key_exists('properties', $input)) {
			$product->properties = $input['properties'];
		}

		$product
			->fill($request->get('product'))
			->save();

		Alert::info(__('Vendor was saved'));

		return redirect()->route('platform.products');
	}

	/**
	 * @param Product $product
	 *d
	 * @throws \Exception
	 *
	 * @return \Illuminate\Http\RedirectResponsed
	 */
	public function remove(Product $product) {
		$product->delete();

		Alert::info(__('Product was removed'));

		return redirect()->route('platform.products');
	}

}
