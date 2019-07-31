<?php

declare (strict_types = 1);

namespace App\Orchid\Screens\Settings\Payment;

use App\Models\Setting;
use App\Orchid\Layouts\Payment\PaymentEditLayout;
use Auth;
use Illuminate\Http\Request;
use Orchid\Screen\Layout;
use Orchid\Screen\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class PaymentEditScreen extends Screen {
	/**
	 * Display header name.
	 *
	 * @var string
	 */
	public $name = 'Payment';

	/**
	 * @var bool
	 */
	private $exist = false;

	/**
	 * Display header description.
	 *
	 * @var string
	 */
	public $description = 'All registered payments';

	/**
	 * @var string
	 */
	public $permission = 'platform.systems.payments';

	/**
	 * Query data.
	 *
	 * @param \Orchid\Platform\Models\User $user
	 *
	 * @return array
	 */
	public function query(Setting $payment): array
	{

		$this->exist = $payment->exists;

		return [
			'payment' => $payment,
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
			PaymentEditLayout::class,
		];
	}

	/**
	 * @param \Orchid\Platform\Models\User $user
	 * @param \Illuminate\Http\Request     $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function save(Setting $payment, Request $request) {
		$payment->type = 'payment';
		$payment->user_id = Auth::id();
		$input = $request->get('payment');

		$payment
			->fill($request->get('payment'))
			->save();

		Alert::info(__('Payment was saved'));

		return redirect()->route('platform.systems.payments');
	}

	/**
	 * @param User $user
	 *
	 * @throws \Exception
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function remove(Setting $payment) {
		$payment->delete();

		Alert::info(__('Payment was removed'));

		return redirect()->route('platform.systems.payments');
	}

}
