<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use App\Helpers\Helper;
use App\Repositories\Facades\LogFacade;
use App\Repositories\Facades\UserFacade;
use App\Repositories\Facades\UserTypeFacade;
use App\Repositories\Facades\VariableFacade;

class LogController extends Controller {

  // View Logs
  public function index(Request $request) {
    $page = $request->page ? $request->page : 1;

    // filters
    $filter_type = isset($request->filter_type) ? $request->filter_type : 0;
    $filter_user = isset($request->filter_user) ? $request->filter_user : 0;
    $filter_ip = isset($request->filter_ip) ? $request->filter_ip : 0;
    $filter_conditions = [];
    if($filter_type) { $filter_conditions['type'] = $filter_type; }
    if($filter_user != 0 && $filter_user != -1) { $filter_conditions['user_id'] = $filter_user; }
    if($filter_user == -1) { $filter_conditions['user_id::null'] = ''; }
    if($filter_ip) { $filter_conditions['ip_address'] = $filter_ip; }

    $logs = $filter_conditions ? LogFacade::wherePaginated($filter_conditions,'id','DESC',25) : LogFacade::getAllPaginated('id','DESC',25);

    foreach($logs as $log) {
      $log->type = ucfirst($log->type);
      $log->class = substr($log->class,strrpos($log->class,"\\"));
      $log->user = $log->user; // This enables access to the relationship with vue

      $log->user_type_name = "";
      if($log->user) {
        if($log->user->is_admin) {
          $log->user_type_name = "Admin";
        } else if($log->user->userType) {
          $log->user_type_name = $log->user->userType->name;
        }
      }

      $log->organization_name = $log->user && $log->user->organization_id ? $log->user->organization->name : '';

      $agent = new Agent();
      $agent->setUserAgent($log->device);
      $log->device_cleaned = $agent->browser() . " on " . $agent->platform();
    }

    $ips = LogFacade::getIPs();
    $return_ips = [];
    foreach($ips as $ip) {
      array_push($return_ips,['id'=>$ip->ip_address, 'name'=>$ip->ip_address]);
    }

    $data = [
      "filter_type" => $filter_type,
      "filter_user" => $filter_user,
      "filter_ip" => $filter_ip,

      "users" => UserFacade::getAll('name'),
      "ips" => $return_ips,

      "logs" => $logs,

      "page" => $page,
      "pages" => Helper::getVisiblePages($logs->lastPage(),$page,3)
    ];

    return $request->vue ? $data : view('layouts.app')->with($data);
  }

  // Delete a log or logs
  public function destroy(Request $request, $id) {
    return LogFacade::delete($id);
  }

}
