<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use App\Helpers\Helper;
use App\Repositories\Facades\LogFacade;
use App\Repositories\Facades\UserFacade;
use App\Repositories\Facades\UserTypeFacade;

class LogController extends Controller {

  // View Logs
  public function index(Request $request) {
    $type = $request->type ? $request->type : 0;
    $user = $request->user ? $request->user : 0;
    $ip = $request->ip ? $request->ip : 0;
    $page = $request->page ? $request->page : 1;

    $filter_conditions = [];
    if($type !== 0) $filter_conditions['type'] = strtolower($type);
    if($user !== 0 && $user !== -1) $filter_conditions['user_id'] = $user;
    if($user == -1) $filter_conditions['user_id'] = NULL;
    if($ip !== 0) $filter_conditions['ip_address'] = $ip;

    $logs = $filter_conditions ? LogFacade::wherePaginated($filter_conditions,'id','DESC',50) : LogFacade::getAllPaginated('id','DESC',25);

    foreach($logs as $log) {
      $log->type = ucfirst($log->type);
      $log->class = substr($log->class,strrpos($log->class,"\\"));
      $log->user = $log->user; // This enables access to the relationship with vue

      $log->user_type_name = "";
      if($log->user) {
        if($log->user->is_admin) {
          $log->user_type_name = "ADMIN";
        } else if($log->user->userType) {
          $log->user_type_name = strtoupper($log->user->userType->name);
        }
      }

      $agent = new Agent();
      $agent->setUserAgent($log->device);
      $log->device_cleaned = $agent->browser() . " on " . $agent->platform();
    }

    $types[] = (object)["id" => 0, "name" => 'TYPE: ALL'];
    $types[] = (object)["id" => 'request', "name" => 'REQUESTS'];
    $types[] = (object)["id" => 'error', "name" => 'ERRORS'];

    $users[] = (object)["id" => 0, "name" => 'USER: ALL'];
    $users[] = (object)["id" => -1, "name" => 'USER: NONE'];
    foreach(UserFacade::getAll('name') as $obj_user) { $users[] = (object)["id" => $obj_user->id, "name" => 'USER: '.strtoupper($obj_user->name)]; }

    $ips[] = (object)["id" => 0, "name" => 'IP: ALL'];
    foreach(LogFacade::getIPs() as $obj_ip) { $ips[] = (object)["id" => $obj_ip->ip_address, "name" => strtoupper($obj_ip->ip_address)]; }

    $data = [
      "type" => $type,
      "user" => $user,
      "ip" => $ip,

      "types" => $types,
      "users" => $users,
      "ips" => $ips,

      "logs" => $logs,

      "page" => $page,
      "pages" => Helper::getVisiblePages($logs->lastPage(),$page,3),
    ];

    return $request->vue ? $data : view('layouts.app')->with($data);
  }

  // Delete a log or logs
  public function destroy(Request $request, $id) {
    $current_log = LogFacade::find($id);
    if($request->type == 'delete') {
      LogFacade::delete($id);
    } elseif($request->type == 'delete_class') {
      $logs = LogFacade::where(['class' => $current_log->class, 'message' => $current_log->message, 'url' => $current_log->url],'id','ASC');
      foreach($logs as $log) {
        LogFacade::delete($log->id);
      }
    } elseif($request->type == 'delete_ip') {
      $logs = LogFacade::where(['ip_address' => $current_log->ip_address],'id','ASC');
      foreach($logs as $log) {
        LogFacade::delete($log->id);
      }
    }
    return 1;
  }

}
