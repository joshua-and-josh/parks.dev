<?php 

require __DIR__ . '/constants.php'; //don't forget the forward slash at beginning when using DIR

$connection = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);

$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

return $connection;

?>