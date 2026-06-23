<?php

function trace($quoi)
{
    $user = isset($_SESSION['user']) ? $_SESSION['user'] : 'guest';
    $quand = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'];
    $del = '|';
    $string = $user . $del . $quoi . $del . $quand . $del . $ip;
    $f = fopen("trace.log", "a");
    fputs($f, $string);
    fputs($f, "\r\n");
    fclose($f);
}

?>
