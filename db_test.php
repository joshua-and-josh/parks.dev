<?php 

require __DIR__ . '/db_connect.php';

echo $connection->getAttribute(PDO::ATTR_CONNECTION_STATUS) . "\n";

 ?>