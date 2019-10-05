<?php

declare (strict_types = 1);

namespace App\Orchid\Screens\Settings\Payment;

use App\Models\Setting;
use App\Orchid\Layouts\Payment\PaymentEditLayout;
use App\Orchid\Layouts\Payment\PaymentListLayout;
use Illuminate\Http\Request;
use Orchid\Platform\Models\User;
use Orchid\Screen\Layout;
use Orchid\Screen\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class PaymentListScreen extends Screen {
	/**
	 * Display header name.
	 *
	 * @var string
	 */
	public $name = 'Payment';

	/**
	 * Display header description.
	 *
	 * @var string
	 */
	public $description = 'All registered payments';

	/**
	 * @var string
	 */
	public $permission = 'platform.systems';

	/**
	 * Query data.
	 *
	 * @return array
	 */
	public function query(): array
	{

		return [
			'payments' => Setting::filters()
				->where('type', '=', 'payment')
				->defaultSort('id', 'desc')
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
				->link(route('platform.systems.payments.create')),
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
			PaymentListLayout::class,

			Layout::modals([
				'oneAsyncModal' => [
					PaymentEditLayout::class,
				],
			])->async('asyncGetTax'),
		];
	}

	/**
	 * @param User $user
	 *
	 * @return array
	 */
	public function asyncGetPayment(Setting $payment): array
	{
		return [
			'payment' => $payment,
		];
	}

	/**
	 * @param User    $user
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function savePayment(Setting $payment, Request $request) {
		$payment->fill($request->get('payment'));
		$payment->type = 'payment';
		$payment->save();

		Alert::info(__('Payment was saved.'));

		return back();
	}
}
