<?php
function preprint($s, $return=false) {
    $output = "<pre>".print_r($s, 1)."</pre>";
    if ($return) return $output;
    else print $output;
}

function truncate($string, $limit, $pad="...")
{
  // return with no change if string is shorter than $limit
  if(strlen($string) <= $limit){
    return $string;
  }
  $string = substr($string, 0, $limit) . $pad;

  return $string;
}