<?php
namespace App\Http\Controllers\Logic;
use App\Events\AppointmentMake;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Model\UserController as UC;
use App\Models\Appointment;
use Auth;
use broadcast;
use DB;
use Illuminate\Http\Request;

class AppointmentController extends Controller {

	public function __construct() {

	}

	public function create(Request $request) {

		$this->validate($request, [
			'customer_id' => 'required',
			'appoint_datetime' => 'required|date',
		]);

		$data = new Appointment($request->all());
		$data->user_id = Auth::user()->id;
		DB::beginTransaction();
		try {

			$data->save();

			if ($request->has('details')) {
				$inputs = $request->input('details');
				$data->details()->createMany($inputs);
			}

			$data->load('customer', 'details', 'user');
			$data->details->load('user', 'product');

			DB::commit();

			return response()->json(['msg' => 'Saved', 'data' => $data], 200);

		} catch (\PDOException $e) {
			DB::rollback();
			return response()->json(['msg' => 'Fail', 'data' => []], 400);
		}

	}

	public function all(Request $request) {

		$option = ['by' => 'appoint_datetime', 'order' => 'desc', 'paging' => 31, 'page' => 1];
		$selectedgroup = '';
		$filtername = '';
		$parent = '';
		$modal = '';
		$status = '';
		$title = 'Appointments';

		if ($request->has('page')) {
			$option['page'] = $request->input('page');
		}

		if ($request->has('paging')) {
			$option['paging'] = $request->input('paging');
		}

		if ($request->has('by')) {
			$option['by'] = $request->input('by');
		}

		if ($request->has('order')) {
			$option['order'] = $request->input('order');
		}

		if ($request->has('filtername')) {
			$filtername = $request->input('filtername');
		}

		if ($request->has('group')) {
			$selectedgroup = $request->input('group');
		}

		if ($request->has('status')) {
			$status = $request->input('status');
		}

		$model = Appointment::orderBy($option['by'], $option['order']);

		//if($filtername !== '') $model = $model->where('id', 'like', '%'.$filtername.'%');
		if ($status !== '') {
			$model = $model->where('status', $status);
		}

		$model->with(['details' => function ($query) {$query->with(['product', 'user', 'assistant']);}, 'customer', 'user']);
		$result = $model->paginate($option['paging']);

		return response()->json(compact('title', 'modal', 'parent', 'option', 'selectedgroup', 'filtername', 'result', 'options', 'dates'), 200);
	}

	public function uninvoices(Request $request) {

		$option = ['by' => 'appoint_datetime', 'order' => 'desc', 'paging' => 25, 'page' => 1];
		$selectedgroup = '';
		$filtername = '';
		$parent = '';
		$modal = '';
		$status = '';
		$title = 'Uninvoiced appointment';

		if ($request->has('page')) {
			$option['page'] = $request->input('page');
		}

		if ($request->has('by')) {
			$option['by'] = $request->input('by');
		}

		if ($request->has('order')) {
			$option['order'] = $request->input('order');
		}

		if ($request->has('filtername')) {
			$filtername = $request->input('filtername');
		}

		if ($request->has('group')) {
			$selectedgroup = $request->input('group');
		}

		if ($request->has('status')) {
			$selectedgroup = $request->input('status');
		}

		//$model = Appointment::orderBy($option['by'], $option['order']);

		$appointmentIds = \App\Models\InvoiceDetail::whereNotNull('appointment_id')->pluck('appointment_id')->all();

		//if($filtername !== '') $model = $model->where('id', 'like', '%'.$filtername.'%');
		$model = Appointment::where('status', 4)->whereNotIn('id', $appointmentIds);
		$model->with(['details' => function ($query) {$query->with(['product', 'user', 'assistant']);}, 'customer', 'user']);
		$result = $model->orderBy('appoint_datetime')->paginate($option['paging']);

		return response()->json(compact('title', 'modal', 'parent', 'option', 'selectedgroup', 'filtername', 'result', 'options', 'dates'), 200);
	}

	public function detail($id, Request $request) {

		if (!empty($id)) {

			$users = \App\Models\User::all();
			$services = \App\Models\Price::whereHas('product', function ($query) {$query->where('service', true);})->with(['product' => function ($query) {$query->with('category')->where('service', true);}])->get();

			$options = compact('users', 'services');
			$dates = ['appoint_datetime'];

			if ($id === 'new') {
				$result = [];
				return response()->json(compact('result', 'id', 'options'), 200);
			}

			$result = Appointment::where("id", "=", $id)->with(['customer', 'user', 'details' => function ($query) {$query->with(['product', 'user', 'assistant'])->orderBy('line');}])->first();

			return response()->json(compact('result', 'id', 'options', 'dates'), 200, [], JSON_NUMERIC_CHECK);
		} else {
			return response()->json(['msg' => 'Not found'], 401);
		}

	}

	public function update($id, Request $request) {

		$data = Appointment::find($id);
		if (!$data) {
			return response()->json(['msg' => 'Not found'], 400);
		}

		$this->validate($request, [
			'customer_id' => 'required',
			'appoint_datetime' => 'required|date',
		]);

		DB::beginTransaction();
		try {

			if ($request->has('details')) {
				$inputs = $request->input('details');
				$data->details()->forceDelete();
				$data->details()->createMany($inputs);
			}

			$data->touch();
			$data->update($request->all());
			$data->load('customer', 'details', 'user');
			$data->details->load('user', 'product');
			DB::commit();

//             if( $request->input('status') !== $data->status){
			$message = [
				0 => $data->customer->name . ' has confirmed an appointment with you on ' . $data->appoint_datetime,
				1 => 'Your appointment with ' . $data->customer->name . ' has arrived.',
				2 => 'Your appointment with ' . $data->customer->name . ' has started.',
				3 => 'Your appointment with ' . $data->customer->name . ' is no show.',
				4 => 'Your appointment with ' . $data->customer->name . ' completed.',
			];
			broadcast(new AppointmentMake(Auth::user()->name, $message[$data->status]))->toOthers();
			//      }
			return response()->json(['msg' => 'Saved', 'data' => $data], 200);

		} catch (\PDOException $e) {
			DB::rollback();
			return response()->json(['msg' => 'Fail', 'data' => $data, 'err' => $e], 400);
		}
	}

	public function trash($id, Request $request) {

		if ($request->has('password')) {
			if (!Auth::attempt(['email' => Auth::user()->email, 'password' => $request->input('password')])) {
				return response()->json(['msg' => 'Unauthorised'], 401);
			}
		} else {
			return response()->json(['msg' => 'Bad Request'], 400);
		}

		if (!empty($id)) {
			$invoiced = \App\Models\InvoiceDetail::where('appointment_id', $id)->count();

			if ($invoiced) {
				return response()->json(['msg' => 'Cannot delete, this appointment is invoiced.'], 401);
			}

			$data = Appointment::find($id);
			if ($data !== null) {

				if ($data->delete() && $data->details()->delete()) {
					return response()->json(['msg' => 'Deleted'], 200);
				} else {
					return response()->json(['msg' => 'Internal Error'], 500);
				}
			} else {
				return response()->json(['msg' => 'Not found'], 401);
			}
		} else {
			return response()->json(['msg' => 'Bad Request'], 400);
		}
	}

	public function destroy(Request $request) {

		if ($request->has('password')) {
			if (UC::isCurrentUser($request->input('password'))) {
				$affectedRows = Appointment::onlyTrashed()->forceDelete();
				if ($affectedRows) {
					return response()->json(['msg' => $affectedRows . " affected and unrecoverable."]);
				}

			} else {
				return response()->json(['msg' => "Fail to validate user."]);
			}

		}
	}

}