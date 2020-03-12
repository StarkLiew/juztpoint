<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {

	public function __construct(HasherContract $hasher) {
		$this->hasher = $hasher;
	}

	/**
	 * Create user
	 *
	 * @param  [string] name
	 * @param  [string] email
	 * @param  [string] password
	 * @param  [string] password_confirmation
	 * @return [string] message
	 */
	public function register(Request $request) {
		if (!env('REGISTER_NEW_ACCOUNT', true)) {
			return response()->json([
				'message' => 'Register new account have been lock.',
			], 401);
		}

		$request->validate([
			'name' => 'required|string',
			'companyname' => 'required|string',
			'email' => 'required|string|email',
			'password' => 'required|string',
			'password_confirmation' => 'required|string',
		]);
		$data = request(['name', 'companyname', 'email', 'password', 'password_confirmation']);

		$user = new User;
		$user->company_name = $data['companyname'];
		$user->name = $data['name'];
		$user->email = $data['email'];
		$user->pin = '0123'; //Default pin to login to App
		$user->password = Hash::make($data['password']);
		$user->level = 0;

		$user->properties = json_decode('{"role":"MGR","backoffice":"1"}');
		try {
			$user->save();

			return response()->json([
				'message' => 'Successfully created account!',
			], 201);
		} catch (\PDOException $e) {
			return response()->json([
				'message' => 'Registration new account unsuccessful. Account may already exist.',
			], 401);
		}

	}

	/**
	 * Login user and create token
	 *
	 * @param  [string] email
	 * @param  [string] password
	 * @param  [boolean] remember_me
	 * @return [string] access_token
	 * @return [string] token_type
	 * @return [string] expires_at
	 */
	public function machine(Request $request) {
		$request->validate([
			'email' => 'required|string|email',
			'password' => 'required|string',
			'device_id' => 'required|string',
			'fingerprint' => 'required|string',
		]);
		$credentials = request(['email', 'password']);
		$device = request(['device_id', 'fingerprint']);

		if (!Auth::attempt($credentials)) {
			return response()->json([
				'message' => 'Unauthorized',
			], 401);
		}

		$terminal = Setting::where('type', 'terminal')->whereJsonContains('properties->device_id', $device['device_id'])->first();
		$store = Setting::where('id', $terminal['properties']['store_id'])->first();
		if (!$terminal) {
			return response()->json([
				'message' => 'Unauthorized. Invalid device id',
			], 401);
		} else {

			if (array_key_exists('fingerprint', $terminal['properties']) && $device['fingerprint'] !== '') {

				// compare fingerprint
				$result = [];

				$fingerprinted = json_decode($device['fingerprint']);
				$existfingerprint = $terminal['properties']['fingerprint'];

				foreach ((array) $fingerprinted as $value) {
					$hasKey = false;
					foreach ((array) $existfingerprint as $rvalue) {
						$value = (array) $value;
						$rvalue = (array) $rvalue;

						if ($value['key'] === $rvalue['key']) {
							$hasKey = true;
							if ($value['value'] !== $rvalue['value']) {
								$result[$value['key']] = $value['value'];
							}

						}
					}
					if (!$hasKey) {
						$result[$value['key']] = null;
					}

				}

				foreach ((array) $existfingerprint as $value) {
					$hasKey = false;
					foreach ((array) $fingerprinted as $rvalue) {
						$value = (array) $value;
						$rvalue = (array) $rvalue;
						if ($value['key'] === $rvalue['key']) {
							$hasKey = true;
							if ($value['value'] !== $rvalue['value']) {
								$result[$value['key']] = $value['value'];
							}
						}

					}
					if (!$hasKey) {
						$result[$value['key']] = null;
					}
				}
				$existfingerprintCount = count($existfingerprint);
				$resultCount = count($result);
				$score = ($existfingerprintCount - $resultCount) / $existfingerprintCount * 100;

				if ($score > 89) {

					return response()->json([
						'message' => 'Unauthorized. Device not regonized',
					], 401);
				}

			} else {
				$properties = $terminal['properties'];
				$properties['fingerprint'] = json_decode($device['fingerprint']);
				$terminal['properties'] = $properties;
				$terminal->save();
			}
		}

		$user = $request->user();
		$tokenResult = $user->createToken('Personal Access Token');
		$token = $tokenResult->token;
		$token->save();

		return response()->json([
			'user' => $user,
			'terminal' => $terminal,
			'store' => $store,
			'token' => $tokenResult->accessToken,
			'token_type' => 'Bearer',
			'expires_at' => Carbon::parse(
				$tokenResult->token->expires_at
			)->toDateTimeString(),
		]);
	}

	/**
	 * Login user and create token
	 *
	 * @param  [string] email
	 * @param  [string] password
	 * @param  [boolean] remember_me
	 * @return [string] access_token
	 * @return [string] token_type
	 * @return [string] expires_at
	 */
	public function login(Request $request) {
		$request->validate([
			'email' => 'required|string|email',
			'password' => 'required|string',
			'remember_me' => 'boolean',
		]);
		$credentials = request(['email', 'password']);
		if (!Auth::attempt($credentials)) {
			return response()->json([
				'message' => 'Unauthorized',
			], 401);
		}

		$user = $request->user();
		$tokenResult = $user->createToken('Personal Access Token');
		$token = $tokenResult->token;
		if ($request->remember_me) {
			$token->expires_at = Carbon::now()->addWeeks(1);
		}

		$token->save();

		return response()->json([
			'user' => $user,
			'verified' => $user->verified,
			'token' => $tokenResult->accessToken,
			'token_type' => 'Bearer',
			'expires_at' => Carbon::parse(
				$tokenResult->token->expires_at
			)->toDateTimeString(),
		]);
	}

	/**
	 * Logout user (Revoke the token)
	 *
	 * @return [string] message
	 */
	public function logout(Request $request) {
		$request->user()->token()->revoke();
		return response()->json([
			'message' => 'Successfully logged out',
		]);
	}

	/**
	 * Get the authenticated User
	 *
	 * @return [json] user object
	 */
	public function user(Request $request) {
		return response()->json(['user' => $request->user()]);
	}

	public function validPasswordResetToken(Request $request) {
		$valid = true;
		$reset = DB::table('password_resets')
			->where('email', '=', $request->email)
			->where('created_at', '>', Carbon::now()->subHours(2))
			->first();

		if (!$reset) {
			$valid = false;
		} else {
			if (!$this->hasher->check($request->token, $reset->token)) {
				$valid = false;
			}
		}

		return response()->json([
			'valid' => $valid,
		]);
	}

	public function resendVerification() {

		$auth = Auth::user();

		$tid = $auth->id;
		if ($auth->tenant) {
			$tid = $auth->tenant;
		}
		$user = User::find($tid);
		$user->sendEmailVerificationNotification();
	}
}