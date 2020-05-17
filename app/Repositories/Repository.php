<?php

namespace App\Repositories;

use Auth;

class Repository implements RepositoryInterface {

  protected $model;

  public function __construct() {
    $this->model = $this->modelClassName;
  }

  public function find($id) {
    return $this->model::find($id);
  }

  public function getAll($orderBy=null,$orderByDirection='asc',$take=null) {
    if($orderBy && $take) {
      return $this->model::orderBy($orderBy,$orderByDirection)->orderBy('id',$orderByDirection)->take($take)->get();
    } elseif($orderBy) {
      return $this->model::orderBy($orderBy,$orderByDirection)->orderBy('id',$orderByDirection)->get();
    } elseif($take) {
      return $this->model::take($take)->get();
    } else {
      return $this->model::get();
    }
  }

  public function getAllPaginated($orderBy=null,$orderByDirection='asc',$number) {
    if($orderBy) {
      return $this->model::orderBy($orderBy,$orderByDirection)->orderBy('id',$orderByDirection)->paginate($number);
    } else {
      return $this->model::paginate($number);
    }
  }

  public function where($data,$orderBy=null,$orderByDirection='asc',$take=null) {
    $collection = $this->model;
    $counter = 0;
    foreach($data as $key => $value) {
      $operator = "=";
      if(strpos($key,"::") !== false) {
        $key_and_operator = explode("::",$key);
        $key = $key_and_operator[0];
        $operator = $key_and_operator[1];
      }
      if($counter == 0) {
        $collection = $collection::where($key,$operator,$value);
      } else {
        $collection = $collection->where($key,$operator,$value);
      }
      $counter++;
    }
    if($orderBy && $take) {
      return $collection->orderBy($orderBy,$orderByDirection)->orderBy('id',$orderByDirection)->take($take)->get();
    } elseif($orderBy) {
      return $collection->orderBy($orderBy,$orderByDirection)->orderBy('id',$orderByDirection)->get();
    } elseif($take) {
      return $collection->take($take)->get();
    } else {
      return $collection->get();
    }
  }

  public function wherePaginated($data,$orderBy=null,$orderByDirection='asc',$number) {
    $collection = $this->model;
    $counter = 0;
    foreach($data as $key => $value) {
      $operator = "=";
      if(strpos($key,"::") !== false) {
        $key_and_operator = explode("::",$key);
        $key = $key_and_operator[0];
        $operator = $key_and_operator[1];
      }
      if($counter == 0) {
        $collection = $collection::where($key,$operator,$value);
      } else {
        $collection = $collection->where($key,$operator,$value);
      }
      $counter++;
    }
    if($orderBy) {
      return $collection->orderBy($orderBy,$orderByDirection)->orderBy('id',$orderByDirection)->paginate($number);
    } else {
      return $collection->paginate($number);
    }
  }

  public function whereFirst($data,$orderBy=null,$orderByDirection='asc') {
    $collection = $this->model;
    $counter = 0;
    foreach($data as $key => $value) {
      $operator = "=";
      if(strpos($key,"::") !== false) {
        $key_and_operator = explode("::",$key);
        $key = $key_and_operator[0];
        $operator = $key_and_operator[1];
      }
      if($counter == 0) {
        $collection = $collection::where($key,$operator,$value);
      } else {
        $collection = $collection->where($key,$operator,$value);
      }
      $counter++;
    }
    if($orderBy) {
      return $collection->orderBy($orderBy,$orderByDirection)->orderBy('id',$orderByDirection)->first();
    } else {
      return $collection->first();
    }
  }

  public function whereLast($data) {
    $collection = $this->model;
    $counter = 0;
    foreach($data as $key => $value) {
      $operator = "=";
      if(strpos($key,"::") !== false) {
        $key_and_operator = explode("::",$key);
        $key = $key_and_operator[0];
        $operator = $key_and_operator[1];
      }
      if($counter == 0) {
        $collection = $collection::where($key,$operator,$value);
      } else {
        $collection = $collection->where($key,$operator,$value);
      }
      $counter++;
    }
    return $collection->orderBy('id','desc')->first();
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

}
