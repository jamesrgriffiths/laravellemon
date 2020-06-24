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

    // Special Data
    $users = UserFacade::getAll('name');
    $ips = LogFacade::getDistinctFields('ip_address');

    // Filters
    $filters = [];
    $type_options = [['id'=>0,'name'=>'All Types'],['id'=>'request','name'=>'Requests'],['id'=>'error','name'=>'Errors']];
    $user_options = [['id'=>0,'name'=>'All Users'],['id'=>-1,'name'=>'No User']];
    foreach($users as $user) { array_push($user_options,['id'=>$user->id,'name'=>$user->name]); }
    $ip_options = [['id'=>0,'name'=>'All IPs']];
    foreach($ips as $ip) { array_push($ip_options,['id'=>$ip->ip_address,'name'=>$ip->ip_address]); }

    // Filter Conditions
    $filter_conditions = [];
    $filter_type = $request->has('filter_type') ? $request->filter_type : 0;
    $filter_user = $request->has('filter_user') ? $request->filter_user : 0;
    $filter_ip = $request->has('filter_ip') ? $request->filter_ip : 0;
    if($filter_type) { $filter_conditions['type'] = $filter_type; }
    if($filter_user != 0) {
      if($filter_user == -1) {
        $filter_conditions['user_id::null'] = '';
      } else {
        $filter_conditions['user_id'] = $filter_user;
      }
    }
    if($filter_ip != 0) { $filter_conditions['ip_address'] = $filter_ip; }

    // Set filter options
    array_push($filters,['prop'=>'filter_type','all_values'=>$type_options]);
    array_push($filters,['prop'=>'filter_user','all_values'=>$user_options]);
    array_push($filters,['prop'=>'filter_ip','all_values'=>$ip_options]);

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

    $data = [
      "filter_type" => $filter_type,
      "filter_user" => $filter_user,
      "filter_ip" => $filter_ip,
      "filters" => $filters,
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
