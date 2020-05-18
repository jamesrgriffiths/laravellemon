<?php

namespace App\Repositories\Classes;

use Auth;
use App\Log;
use App\Repositories\Repository;
use App\Repositories\Interfaces\LogRepositoryInterface;

class LogRepository extends Repository implements LogRepositoryInterface {

  // Needed for base Repository class usaage
  protected $modelClassName = 'App\Log';

  // Get distinct classes
  public function getClasses() {
    return Log::select('type','class')->orderBy('type','ASC')->orderBy('class','ASC')->distinct()->get();
  }

  // Get distinct IPs
  public function getIPs() {
    return Log::select('ip_address')->orderBy('ip_address','ASC')->distinct()->get();
  }

  // Get distinct types
  public function getTypes() {
    return Log::select('type')->orderBy('type','ASC')->distinct()->get();
  }

  // Log an exception
  public function logException($request, $exception) {
    return $this::store([
      'type' => 'error',
      'user_id' => Auth::check()?Auth::id():null,
      'ip_address' => $request->ip(),
      'device' => $request->header('User-Agent'),
      'url' => $request->url(),
      'class' => get_class($exception),
      'message' => $exception->getMessage(),
      'trace' => $exception,
    ]);
  }

  // Log a request
  public function logRequest($request) {
    return $this::store([
      'type' => 'request',
      'user_id' => Auth::check()?Auth::id():null,
      'ip_address' => $request->ip(),
      'device' => $request->header('User-Agent'),
      'url' => $request->url(),
      'class' => 'HTTP Request',
      'message' => 'HTTP Request',
      'trace' => $request,
    ]);
  }

}
