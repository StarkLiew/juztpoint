<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller {
	/*
		    |--------------------------------------------------------------------------
		    | Register Controller
		    |--------------------------------------------------------------------------
		    |
		    | This controller handles the registration of new users as well as their
		    | validation and creation. By default this controller uses a trait to
		    | provide this functionality without requiring any additional code.
		    |
	*/

	use RegistersUsers;

	/**
	 * Where to redirect users after registration.
	 *
	 * @var string
	 */
	protected $redirectTo = '/backoffice';

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->middleware('guest');
	}

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator(array $data) {
		return Validator::make($data, [
			'name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
			'companyname' => ['required', 'string'],
			'password' => ['required', 'string', 'min:8', 'confirmed'],
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return \App\User
	 */
	protected function create(array $data) {
		$user = new User;
		$user->company_name = $data['companyname'];
		$user->name = $data['name'];
		$user->email = $data['email'];
		$user->password = Hash::make($data['password']);
		$user->level = 0;

		/* $user->permissions = json_encode(Array('platform.systems.roles' => false, 'platform.systems.users' => false, 'platform.systems.attachment' => false, 'platform.systems.announcement' => false, 'platform.index' => true, 'platform.systems' => false, 'platform.systems.index' => true)); */

		$user->permissions = json_decode('{"platform.systems.announcement":"1","platform.systems.attachment":"1","platform.systems.commissions":"1","platform.systems.company":"1","platform.systems.payments":"1","platform.systems.roles":"1","platform.systems.stores":"1","platform.systems.taxes":"1","platform.systems.users":"1","platform.categories":"1","platform.customers":"1","platform.index":"1","platform.products":"1","platform.services":"1","platform.systems.index":"1","platform.systems":"1","platform.vendors":"1"}');

		$user->save();

		return $user;
	}
}
