<?php  

require 'Park.php';

echo Park::count();

// print_r(Park::all());

print_r(Park::paginate(2));


?>