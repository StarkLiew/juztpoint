<?php

namespace App\Http\Middleware;

use Closure;

class ImageInterceptor {
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next) {
		//    $request->files->all()
		//$str = mb_convert_encoding($str, "UTF-8", "Windows-1252");
		/* $files = $request->files->all();

		foreach (array_flatten($request->files->all()) as $file) {
			$size = $file->getClientSize(); // size in bytes!

			$onemb = pow(1024, 2); // https://stackoverflow.com/a/2510446/6056864

			if ($size > $onemb) {
				abort(Response::HTTP_UNPROCESSABLE_ENTITY);
			}
		} */

		return $next($request);
	}
}