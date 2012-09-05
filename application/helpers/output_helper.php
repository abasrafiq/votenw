<?php
function preprint($s, $return=false) {
    $output = "<pre>".print_r($s, 1)."</pre>";
    if ($return) return $output;
    else print $output;
}