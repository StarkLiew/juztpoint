<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController {
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	protected $menus = ['users', 'sales', 'customers', 'suppliers', 'services', 'products', 'stores', 'commission_rates', 'service_categories', 'product_categories', 'tax_rates', 'payment_types', 'referral_sources', 'cancellation_reasons', 'discount_types', 'appointments', 'appointment_details'];
	protected $configurations = ['companies', 'config_bookings', 'config_staff_notifications', 'config_notifications', 'config_sales'];

	public function gotoList() {

		$url = url()->current();
		$this->redirect = "";

		$paths = explode('/', parse_url($url, PHP_URL_PATH));

		foreach ($paths as $path) {

			if (in_array($path, $this->menus)) {
				$this->redirect = '/dashboard/' . $path;
			}

			if (in_array($path, $this->configurations)) {
				$this->redirect = '/configurations';
			}

		}

		return redirect($this->redirect);

	}

	public function callback($request, $data = []) {

		$this->redirect = "";
		$url = url()->current();
		$isApi = false;
		$matches = [];
		if (strpos($url, 'api')) {
			$isApi = true;
		}

		$paths = explode('/', parse_url($url, PHP_URL_PATH));

		foreach ($paths as $path) {

			if (in_array($path, $this->menus)) {
				$this->redirect = '/dashboard/' . $path;
			}

			if (in_array($path, $this->configurations)) {
				$this->redirect = '/configurations';
			}

		}

		if (!$isApi) {

			if (count($this->errors) > 0) {
				if ($this->append) {
					return redirect()->back()->withInput($request->all())->withErrors($this->errors);
				} else {
					return redirect()->back()->withErrors($this->errors);
				}

			} else {
				return redirect()->to($this->redirect)->send();
			}
		}

		if (count($this->errors) > 0) {
			return response()->json($this->errors);
		}

		return response()->json($data);

	}

	public function save($collection, $request) {

		try {

			if (!$collection->save()) {

				$this->errors['500'] = ['msg' => 'Changes not save', 'status' => 'fail'];
				return response()->json($this->errors);

			}
			$this->errors = [];

			$this->errors['200'] = ['msg' => 'Data is saved', 'status' => 'success'];
			return response()->json($this->errors['200']);

		} catch (\Exception $e) {

			if ($e->getCode() === '23000') {
				$this->errors['500'] = ['msg' => 'This email already registered.'];
			} else {
				$this->errors['500'] = ['msg' => $e->getMessage()];
			}
			return $this->callback($collection);

		}
	}

	public function isSystemDefault() {

	}

	public static $_mongoIdFromTimestampCounter = 0;

	public static function createObjectId() {
		// Build Binary Id
		$timestamp = strtotime('today');
		$binaryTimestamp = pack('N', $timestamp); // unsigned long
		$machineId = substr(md5(gethostname()), 0, 3); // 3 bit machine identifier
		//$binaryPID = pack('n', posix_getpid()); // unsigned short
		$counter = substr(pack('N', self::$_mongoIdFromTimestampCounter++), 1, 3); // Counter
		$binaryId = "{$binaryTimestamp}{$machineId}{$machineId}{$counter}";
		// Convert to ASCII
		$id = '';
		for ($i = 0; $i < 12; $i++) {
			$id .= sprintf("%02X", ord($binaryId[$i]));
		}

		// Return Mongo ID
		return $id;
	}

}
