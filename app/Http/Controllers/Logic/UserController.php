<?php
namespace App\Http\Controllers\Logic;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {
	private static $salt = '$2y$10$IOwZagskN7CqpFG0zlTiv';
	public function __construct() {

		$this->ERRORS = [
			100 => 'No valid ID is provided.',
			101 => 'Invalid option.',
			102 => 'Input Validation Fail.',
		];

		$this->errors = [];
		$this->append = false;
		$this->noCheck = [];

	}
	public function login(Request $request) {
		if ($request->has('email') && $request->has('password')) {
			$user = User::where("email", "=", $request->input('email'))
				->where("level", "<>", "0")
				->first();

			if ($user && Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {

				$token = str_random(60);
				$user->api_token = $token;
				$user->save();

				unset($user->email);
				return response()->json(['api_token' => $token, 'user' => $user]);
			} else {

				return response()->json(['msg' => "Incorrect email or password!"]);
			}
		} else {

			return response()->json(['msg' => "Please enter email and password."]);
		}
	}
	public function register(Request $request) {
		$this->append = true;

		if (!$this->validateInput($request)) {
			echo "Validate: " . json_encode($this->errors);
			return response()->json(['status' => "fail"]);
		}

		$user = new User;
		$user->name = $request->input('name');
		$user->companyname = $request->input('companyname');
		if ($request->has('tenant')) {
			$user->tenant = $request->input('tenant');
		}

		$user->password = Hash::make($request->input('password'));
		$user->email = $request->input('email');
		if ($request->has('pin')) {
			$user->pin = $request->input('pin');
		}

		$user->api_token = str_random(60);
		$user->level = 0;

		$this->save($user, $request);

	}

	public function create(Request $request) {

		$this->validate($request, [
			'name' => 'required|min:1|max:255',
			'email' => 'email|unique:users',
		]);

		$form = $request->all();
		$data = new User($form);
		$tmp = str_random(8);
		$data->password = bcrypt($tmp);

		if ($data->save()) {
			if (!empty($form->role_id)) {
				$data->roles()->attach($form->role_id);
			}

			$data->load('roles');
			return response()->json(['msg' => 'Saved', 'data' => $data], 200);
		} else {
			return response()->json(['msg' => 'Fail', 'data' => []], 400);
		}

	}
	public function info() {

		return Auth::user();

	}

	public static function all(Request $request) {
		$option = ['by' => 'name', 'order' => 'asc', 'paging' => 25, 'page' => 1];
		$selectedgroup = '';
		$filtername = '';
		$parent = '';
		$modal = '';
		$title = 'Users';

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

		$model = \App\Models\User::orderBy($option['by'], $option['order']);
		if ($filtername !== '') {
			$model = $model->where('name', 'like', '%' . $filtername . '%');
		}

		$result = $model->paginate($option['paging']);

		return response()->json(compact('title', 'modal', 'parent', 'option', 'selectedgroup', 'filtername', 'result'));
	}

	public function detail($id, Request $request) {
		$roles = Role::all();
		$options = compact('roles');

		if ($id === 'new') {
			$result = [];
			return response()->json(compact('result', 'options'), 200);
		}

		if (!empty($id)) {

			$result = User::where("id", "=", $id)->with('roles')->first();
			$result->role_id = $result->roles[0]->id;
			return response()->json(compact('result', 'options'), 200);
		} else {
			return response()->json(['msg' => 'Not found'], 401);
		}

	}

	public function update($id, Request $request) {

		$data = User::find($id);
		if (!$data) {
			return response()->json(['msg' => 'Not found'], 400);
		}

		$this->validate($request, [
			'name' => 'required|min:1|max:255',
			'email' => 'email',
		]);
		$form = $request->all();

		if ($data->update($form)) {
			$data->detachRoles($data->roles);
			if (!empty($form['role_id'])) {
				$data->attachRole($form['role_id']);
			}

			$data->load('roles');
			return response()->json(['msg' => 'Saved', 'data' => $data], 200);
		} else {
			return response()->json(['msg' => 'Fail', 'data' => []], 400);
		}

	}

	public function changePassword(Request $request) {

		$this->validate($request, [
			'current_password' => 'required',
			'password' => 'required|
               min:6|
               regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{8}.*$/|
               confirmed',

		]);

		if ($request->has('current_password')) {
			if (!Auth::attempt(['email' => Auth::user()->email, 'password' => $request->input('current_password')])) {
				return response()->json(['msg' => 'Unauthorised'], 401);
			}
		}

		$data = Auth::user();
		if (!$data) {
			return response()->json(['msg' => 'Not found'], 400);
		}

		$form = $request->all();
		$data->password = bcrypt($form['password']);

		if ($data->update()) {
			return response()->json(['msg' => 'Saved', 'data' => $data], 200);
		} else {
			return response()->json(['msg' => 'Fail', 'data' => []], 400);
		}

	}

	public function trash($id, Request $request) {

		if ($request->has('password')) {
			if (!Auth::attempt(['email' => Auth::user()->email, 'password' => $request->input('password')])) {
				return redirect()->back();
			}
		}

		if (!empty($id)) {
			$data = User::find($id);
			if ($data !== null) {
				$data->delete();
				return redirect($this->redirect);
			} else {
				return redirect($this->redirect);
			}
		} else {
			return redirect($this->redirect);
		}
	}

	public function destroy(Request $request) {

		if ($request->has('password')) {
			if ($this->isCurrentUser($request->input('password'))) {
				$affectedRows = User::onlyTrashed()->forceDelete();
				if ($affectedRows) {
					return response()->json(['msg' => $affectedRows . " affected and unrecoverable."]);
				}

			} else {
				return response()->json(['msg' => "Fail to validate user."]);

			}

		}

	}

	private function validateInput($request) {
		$this->errors = [];

		if ($request->has('name')) {
			$name = trim($request->input('name'));
			if (strlen($name) < 3) {
				$this->errors['name'] = ["msg" => "Name length too short"];
			}

		} else {
			$this->errors['name'] = ["msg" => "Name is required"];
		}

		if ($request->has('password')) {
			$password = trim($request->input('password'));
			if (strlen($password) < 8) {
				$this->errors['password'] = ["msg" => "Password length too short"];

			}
		} else {
			if ($this->append) {
				$this->errors['password'] = ["msg" => "Password cannot be empty"];
			}

		}
		if ($request->has('pin')) {
			$pin = trim($request->input('pin'));
			if (strlen($pin) !== 4) {
				return "Pin length too short";
			}

			if (!preg_match("/[0-9][0-9][0-9][0-9]/", $pin)) {
				$this->errors['pin'] = ["msg" => "Pin must contain 4 digit only."];

			}
			if (!in_array('pin', $this->noCheck)) {
				if (User::where('pin', "=", $pin)->first()) {
					$this->errors['pin'] = ["msg" => "Someone has use this pin (" . $pin . "). Choose another combination from 0000 to 9999"];
				}
			}
		}
		if ($request->has('email')) {
			$email = $request->input('email');
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$this->errors['email'] = ["msg" => "Email should be name@example.com"];

			}
			if (!in_array('email', $this->noCheck)) {
				if (User::withTrashed()->where('email', $email)->first()) {
					$this->errors['email'] = ["msg" => "This email (" . $email . ") is already registered"];
				}
			}
		} else {
			$this->errors['email'] = ["msg" => "Valid email is required"];
		}
		if (count($this->errors) > 0) {
			return false;
		}

		return true;
	}

	public static function isCurrentUser($password) {

		$user = User::where("email", "=", Auth::user()->email)
			->where("password", "=", sha1(self::$salt . $password))
			->where("level", "=", 1)
			->first();
		if ($user) {
			return true;
		}

		return false;
	}

}
