<?php
define('HOST', 'localhost');
define('USER', 'root');
define('PASS', '');
define('PORT', 3306);
define('DB', 'it_tickets');

$connection = mysqli_connect(HOST, USER, PASS, DB);
if ($connection == false) {
    echo "pb de connection";
    exit(1);
}
?>
