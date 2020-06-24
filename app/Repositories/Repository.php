<?php

namespace App\Repositories;

use Auth;
use DB;

class Repository implements RepositoryInterface {

  protected $model;

  public function __construct() {
    $this->model = $this->modelClassName;
  }

  public function find($id) {
    return $this->model::find($id);
  }

  public function getDistinctFields($field) {
    return $this->model::select($field)->orderBy($field,'ASC')->distinct()->get();
  }

  public function getAll($orderBy=null,$orderByDirection='asc',$take=null) {
    return $this->orderByAndTake($this->model::query(),$orderBy,$orderByDirection,$take)->get();
  }

  public function getAllPaginated($orderBy=null,$orderByDirection='asc',$number) {
    return $this->orderByAndTake($this->model::query(),$orderBy,$orderByDirection,null)->paginate($number);
  }

  public function where($data,$orderBy=null,$orderByDirection='asc',$take=null) {
    return $this->orderByAndTake($this->whereOptions($this->model::query(),$data),$orderBy,$orderByDirection,$take)->get();
  }

  public function wherePaginated($data,$orderBy=null,$orderByDirection='asc',$number) {
    return $this->orderByAndTake($this->whereOptions($this->model::query(),$data),$orderBy,$orderByDirection,null)->paginate($number);
  }

  public function whereFirst($data,$orderBy=null,$orderByDirection='asc') {
    return $this->orderByAndTake($this->whereOptions($this->model::query(),$data),$orderBy,$orderByDirection,null)->first();
  }

  public function whereLast($data,$orderBy=null,$orderByDirection='asc') {
    $collection = $this->orderByAndTake($this->whereOptions($this->model::query(),$data),$orderBy,$orderByDirection,null)->get();
    return $collection[$collection->count()-1];
  }

  public function store($data) {
    $object = new $this->model;
    foreach ($data as $key => $value) {
      $object[$key] = $value;
    }
    $object->created_by = Auth::check()?Auth::id():null;
    $object->save();
    return $object;
  }

  public function update($id,$data) {
    $object = $this->model::find($id);
    foreach ($data as $key => $value) {
      $object[$key] = $value;
    }
    $object->updated_by = Auth::check()?Auth::id():null;
    $object->save();
    return $object;
  }

  public function delete($id) {
    $object = $this->model::find($id);
    $object->deleted_by = Auth::check()?Auth::id():null;
    $object->save();
    $this->model::destroy($id);
  }

  // SPECIAL QUERY FUNCTIONS

  public function orderByAndTake($query,$orderBy,$orderByDirection,$take) {
    if($orderBy) {
      foreach(explode(',',$orderBy) as $orderByItem) {
        $query->orderBy($orderByItem,$orderByDirection);
      }
      $query->orderBy('id',$orderByDirection);
    }
    if($take) { $query->take($take); }
    return $query;
  }

  // $query - the query being built up
  // $data = [[$key_1::$operator_1 => $value_1],...[$key_n::$operator_n => $value_n]]
  public function whereOptions($query,$data) {
    foreach($data as $key => $value) {
      $operator = "=";
      if(strpos($key,"::") !== false) {
        $key_and_operator = explode("::",$key);
        $key = $key_and_operator[0];
        $operator = $key_and_operator[1];
      }
      switch ($operator) {
        case 'null':
          $query = $query->whereNull($key);
          break;
        case 'notnull':
          $query = $query->whereNotNull($key);
          break;
        default:
          $query = $query->where($key,$operator,$value);
          break;
      }
    }
    return $query;
  }

}
