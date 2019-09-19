<?php

declare (strict_types = 1);

namespace App\Orchid\Screens\Inventory;

use App\Models\Item;
use App\Models\Product;
use App\Orchid\Layouts\Inventory\ProductEditLayout;
use App\Orchid\Layouts\Inventory\ProductRightEditLayout;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\Select;
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
			Layout::rows([
				Select::make('product.properties.color')
					->options([
						'blue' => 'Blue',
						'red' => 'Red',
						'pink' => 'Pink',
						'green' => 'Green',
						'purple' => 'Purple',
						'indigo' => 'Indigo',
						'orange' => 'Orange',
					])
					->empty('blue')
					->title('Color')
					->help('Point-of-Sale Button Color'),
			]),

			Layout::rows([
				Cropper::make('product.properties.thumbnail')
					->width(180)
					->height(180)
					->target('relativeUrl'),
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

		DB::beginTransaction();

		try {
			$input = $request->get('product');

			$product->user_id = Auth::id();

			if (array_key_exists('properties', $input)) {

				$properties = $input['properties'];

				if (array_key_exists('thumbnail', $properties)) {

					$image = $properties['thumbnail'];
					if (!strpos($image, 'base64')) {
						if (!is_null($image)) {
							$ext = substr($image, -3);

							$content = File::get(public_path() . $image);
							$input['properties']['thumbnail'] = 'data:image/' . $ext . ';base64, ' . base64_encode($content);
							$product->attachment()->delete();
							File::delete(public_path() . $image);

						}
					}

				}

			}

			$product
				->fill($input)
				->fill(['type' => 'product'])
				->save();

			$open = Item::where('item_id', $product->id)->where('type', 'open')->first();

			if (!$open) {
				$open = new Item();
			}

			$open->line = 1;
			$open->item_id = $product->id;
			$open->qty = $product->properties['qty'];
			$open->trxn_id = 1;
			$open->tax_id = 1;
			$open->type = 'open';
			$open->discount = '{}';
			$open->refund_qty = 0;
			$open->tax_amount = 0.00;
			$open->discount_amount = 0.00;
			$open->refund_amount = 0.00;
			$open->total_amount = 0.00;
			$open->user_id = $product->user_id;
			$open->save();

			DB::commit();

			Alert::info(__('Product was saved'));

			return redirect()->route('platform.products');

		} catch (\Illuminate\Database\QueryException $e) {
			DB::rollback();
			Alert::info(__($e->errorInfo[2]));
			return redirect()->back();

		}
	}

	/**
	 * @param Product $product
	 *d
	 * @throws \Exception
	 *
	 * @return \Illuminate\Http\RedirectResponsed
	 */
	public function remove(Product $product) {

		$found = Item::where('item_id', $product->id)->first();

		if ($found) {
			Alert::info(__('Product cannot be remove'));
			return redirect()->back();
		}
		$product->delete();

		Alert::info(__('Product was removed'));

		return redirect()->route('platform.products');
	}

}
