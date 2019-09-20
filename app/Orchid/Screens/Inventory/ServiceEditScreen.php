<?php

declare (strict_types = 1);

namespace App\Orchid\Screens\Inventory;

use App\Models\Item;
use App\Models\Product;
use App\Orchid\Layouts\Inventory\ServiceEditLayout;
use App\Orchid\Layouts\Inventory\ServiceRightEditLayout;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layout;
use Orchid\Screen\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class ServiceEditScreen extends Screen {
	/**
	 * Display header name.
	 *
	 * @var string
	 */
	public $name = 'Service';

	/**
	 * @var bool
	 */
	private $exist = false;

	/**
	 * Display header description.
	 *
	 * @var string
	 */
	public $description = 'All registered services';

	/**
	 * @var string
	 */
	public $permission = 'platform.services';

	/**
	 * Query data.
	 *
	 * @param \App\Models\Product $service
	 *
	 * @return array
	 */
	public function query(Product $service): array
	{

		$this->exist = $service->exists;

		return [
			'service' => $service,
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
				ServiceEditLayout::class,
				ServiceRightEditLayout::class,
			]),

			Layout::columns([
				Layout::rows([
					Select::make('service.properties.color')
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
					Cropper::make('service.properties.thumbnail')
						->width(180)
						->height(180)
						->target('relativeUrl'),
				]),
			]),

		];
	}

	/**
	 * @param \App\Models\Account $account
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function save(Product $service, Request $request) {

		try {
			$input = $request->get('service');

			$service->user_id = Auth::id();

			if (array_key_exists('properties', $input)) {

				$properties = $input['properties'];

				if (array_key_exists('thumbnail', $properties)) {

					$image = $properties['thumbnail'];

					if (!is_null($image)) {
						$ext = substr($image, -3);

						$content = File::get(public_path() . $image);
						$input['properties']['thumbnail'] = 'data:image/' . $ext . ';base64, ' . base64_encode($content);
						$product->attachment()->delete();
						File::delete(public_path() . $image);
					}

				}

			}

			$service
				->fill($input)
				->fill(['type' => 'service'])
				->save();

			Alert::info(__('Service was saved'));

			return redirect()->route('platform.services');

		} catch (\Illuminate\Database\QueryException $e) {
			Alert::info(__($e->errorInfo[2]));
			return redirect()->back();

		}
	}

	/**
	 * @param Product $service
	 *d
	 * @throws \Exception
	 *
	 * @return \Illuminate\Http\RedirectResponsed
	 */
	public function remove(Product $service) {

		$found = Item::where('item_id', $service->id)->first();

		if ($found) {
			Alert::info(__('Service cannot be remove'));
			return redirect()->back();
		}
		$product->delete();

		Alert::info(__('Service was removed'));

		return redirect()->route('platform.services');
	}

}
