<?php

namespace App\Helpers;

class Helper {

  // Get the pages that are visible in pagination
  public static function getVisiblePages($total_pages,$current_page,$spread) {
    $pages = [];
    $low_end = $current_page > $spread ? $current_page - $spread : 1;
    $high_end = $current_page + $spread <= $total_pages ? $current_page + $spread : $total_pages;
    if($low_end !== 1) {
      $pages[] = 1;
      if($low_end > 2) {
        $pages[] = '';
      }
    }
    for($i=$low_end;$i<=$high_end;$i++) {
      $pages[] = $i;
    }
    if($high_end !== $total_pages) {
      if($high_end < $total_pages - 1) {
        $pages[] = '';
      }
      $pages[] = $total_pages;
    }
    return $pages;
  }

  // Removes duplicates, removes empty values, and sorts array.
  public static function cleanArray($array) {
    $return_array = [];
    foreach($array as $value) {
      if($value !== '') { $return_array[] = $value; }
    }
    sort($return_array);
    return array_unique($return_array);
  }

}
