<?php

namespace App\Http\Middleware;

use Closure;
use App\Repositories\Facades\LogFacade;

class LogRequests {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
      $log = LogFacade::logRequest($request);
      $start = microtime(true);
      $response = $next($request);
      LogFacade::update($log->id,['run_time' => microtime(true) - $start]);
      return $response;
    }
}
