<?php

namespace App\Http\Controllers;
use App\Models\Document;
use App\Models\Setting;
use Input;

class MailController extends Controller {

	public function sendReceipt(Request $request) {
		$id = Input::get("id");
		$to = Input::get("to");
		$name = Input::get("name");

		$value = Document::with(['items', 'store', 'teller', 'customer', 'payments'])
			->withTrashed()
			->where('type', 'receipt')
			->where('id', $id)->first();

		$company = Setting::where('type', 'company')->first();

		$data = array('name' => $name, 'value' => $value, 'header' => ['company' => $company, 'store' => $value['store']]);

		return view('mail.receipt', $data);

		/* Mail::send('mail.receipt', $data, function ($message) {
			$message->to($to, $name)->subject('Receipt');
			$message->from('sales@test.com', $company['name']);
		}); */
	}
}
