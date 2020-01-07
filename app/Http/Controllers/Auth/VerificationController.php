<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;

class VerificationController extends Controller {
	/*
		    |--------------------------------------------------------------------------
		    | Email Verification Controller
		    |--------------------------------------------------------------------------
		    |
		    | This controller is responsible for handling email verification for any
		    | user that recently registered with the application. Emails may also
		    | be re-sent if the user didn't receive the original email message.
		    |
	*/

	use VerifiesEmails;

	/**
	 * Where to redirect users after verification.
	 *
	 * @var string
	 */
	protected $redirectTo = '/verified';

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		//	$this->middleware('auth');
		$this->middleware('signed')->only('verify');
		$this->middleware('throttle:6,1')->only('verify', 'resend');

	}

	public function verify(Request $request) {
		$user = User::find($request->route('id'));

		if (!$user) {
			throw new AuthorizationException;
		}

		if ($user->hasVerifiedEmail()) {
			return redirect($this->redirectPath());
		}

		if ($user->markEmailAsVerified()) {
			event(new Verified($user));
		}

		return redirect($this->redirectPath())->with('verified', true);
	}

}
