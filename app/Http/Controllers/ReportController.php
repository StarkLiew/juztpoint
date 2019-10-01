<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class ReportController extends Controller {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		//$this->middleware('auth');
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Contracts\Support\Renderable
	 */
	public function index($report, Request $request) {

		$class = '\\App\\Http\\Controllers\\Reports\\' . $report;
		return $class::show($request);
	}

}
