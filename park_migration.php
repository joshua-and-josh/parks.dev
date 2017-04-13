<?php  

require __DIR__ . '/db_connect.php';

// drop table
$query = 'DROP TABLE IF EXISTS national_parks;';

$connection->exec($query);

// Create the table and assign to variable
$national_parks = 'CREATE TABLE IF NOT EXISTS national_parks (
	id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    name VARCHAR(50) NOT NULL,
    location TEXT NOT NULL,
    date_established DATE NOT NULL,
    area_in_acres DECIMAL(24,2) NOT NULL,
    description TEXT NOT NULL,
    PRIMARY KEY (id)
)';

// Run query, if there are errors they will be thrown as PDOExceptions

$connection->exec($national_parks);


