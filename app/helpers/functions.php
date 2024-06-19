<?php
function convertPhone($phone) {
     $numbers = mb_str_split($phone);
    if ($numbers[0] == '0') $numbers[0] = '+84';

    $result=implode('', $numbers);
    return $result;
  }