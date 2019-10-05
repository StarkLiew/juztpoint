<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller {
	/**
	 * Create user
	 *
	 * @param  [string] name
	 * @param  [string] email
	 * @param  [string] password
	 * @param  [string] password_confirmation
	 * @return [string] message
	 */
	public function signup(Request $request) {
		$request->validate([
			'name' => 'required|string',
			'email' => 'required|string|email|unique:users',
			'password' => 'required|string|confirmed',
		]);
		$user = new User([
			'name' => $request->name,
			'email' => $request->email,
			'password' => bcrypt($request->password),
			'permissions' => '{"platform.systems.announcement":"1","platform.systems.attachment":"1","platform.systems.commissions":"1","platform.systems.company":"1","platform.systems.payments":"1","platform.systems.roles":"1","platform.systems.stores":"1","platform.systems.taxes":"1","platform.systems.users":"1","platform.categories":"1","platform.customers":"1","platform.index":"1","platform.products":"1","platform.services":"1","platform.systems.index":"1","platform.systems":"1","platform.vendors":"1","platform.sales":"1","platform.inventory":"1","platform.receives":"1","platform.reports":"1"}',
		]);

		$user->save();
		return response()->json([
			'message' => 'Successfully created user!',
		], 201);
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

			if (array_key_exists('fingerprint', $terminal['properties'])) {
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
}