<?php
function toArray($xml) {
    $array = json_decode(json_encode($xml), TRUE);
    
    foreach ( array_slice($array, 0) as $key => $value ) {
        if ( empty($value) ) $array[$key] = NULL;
        elseif ( is_array($value) ) $array[$key] = toArray($value);
    }

    return $array;
}