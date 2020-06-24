<?php

namespace App\Repositories;

interface RepositoryInterface {

  public function find($id);

  public function getDistinctFields($field);

  public function getAll($orderBy,$orderByDirection,$take);

  public function getAllPaginated($orderBy,$orderByDirection,$number);

  public function where($data,$orderBy,$orderByDirection,$take);

  public function wherePaginated($data,$orderBy=null,$orderByDirection='asc',$number);

  public function whereFirst($data);

  public function whereLast($data);

  public function store($data);

  public function update($id,$data);

  public function delete($id);

  // SPECIAL QUERY FUNCTIONS

  public function orderByAndTake($query,$orderBy,$orderByDirection,$take);

  public function whereOptions($query,$data);

}
