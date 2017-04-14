<?php

// connect to db, present db connection as $connection variable

// 
require __DIR__ . '/../Park.php';
// require __DIR__ . '/../db_connect.php';
require __DIR__ . '/../Input.php';


function getLastPage($connection, $resultsPerPage) {

	$count = Park::count();

	$lastPage = ceil($count/$resultsPerPage);

	return $lastPage;
}

// function getPaginatedParks($page, $resultsPerPage) {

// 	// $offset = ($page - 1) * $resultsPerPage;

// 	// $statement = $connection->prepare("SELECT * FROM national_parks LIMIT  :resultsPerPage OFFSET :offset");

// 	// $statement->bindValue('resultsPerPage', $resultsPerPage, PDO::PARAM_INT);

// 	// $statement->bindValue('offset', $offset, PDO::PARAM_INT);

// 	// $statement->execute();

// 	// return $parks = $statement->fetchAll(PDO::FETCH_ASSOC);


// }

function handleOutOfRangeRequests ($page, $lastPage) {

	if($page < 1 || !is_numeric($page)) {

		header("location: query.php?page=1");

		die;

	} elseif ($page > $lastPage) {

		header("location: query.php?page=$lastPage");

		die;
	}

}

function pageController($connection) {

	$data = [];

	// set the number of items to display per page
	$resultsPerPage = 4;

	if(!empty($_POST)) {

		$park = new Park();

		$park->name = Input::get('name');
		$park->location = Input::get('location');
		$park->dateEstablished = Input::get('date_established');
		$park->areaInAcres = Input::get('area_in_acres');
		$park->description = Input::get('description');

		$park->insert();

	}

	$page = Input::get('page', 1);

	$lastPage = getLastPage($connection, $resultsPerPage);

	handleOutOfRangeRequests($page, $lastPage);

	$data['parks'] = Park::paginate($page);
	$data['page'] = $page;
	$data['lastPage'] = $lastPage;

	return $data;

}

extract(pageController(Park::dbConnect())) ;

?>

<!DOCTYPE html>

<html>
	<head>
		<title>National Parks</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
		<link rel="stylesheet" href="css/nationalparksstyle.css">
	</head>
	<body>
		<main>
			<h1>National Parks</h1>
			<table class="table table-bordered">
				<thead class="thead-inverse">
				<tr>
					<th>Name</th>
					<th>Location</th>
					<th>Date Established</th>
					<th>Area In Acres</th>
					<th>Description</th>
				</tr>
				</thead>
				<?php foreach($parks as $park): ?>
				<tr>
					<td><?= $park['name'] ;  ?></td>
					<td><?= $park['location'] ; ?></td>
					<td><?= $park['date_established'] ; ?></td>
					<td><?= $park['area_in_acres'] ; ?></td>
					<td><?= $park['description'] ; ?></td>
				</tr>
				<?php endforeach; ?>
				</table>
			<div>
				<form method="POST" action="query.php">
					<input type="text" name="name" class="form-control col-md-4" placeholder="Name"><br>
        			<input type="text" name="location" class="form-control col-md-4" placeholder="Location"><br>
       				<input type="text" name="date_established" class="form-control col-md-4" placeholder="Date Established YYYY-MM-DD"><br>
        			<input type="text" name="area_in_acres" class="form-control col-md-4" placeholder="Area in acres Use a NUMERIC VALUE"><br>
        			<input type="text" name="description" class="form-control col-md-4" placeholder="Description"><br>
        			<input class="btn" type="submit" name="submit">
				</form>
			</div>
			<a href="query.php?page=<?=$page - 1;?>">Previous Page |</a>
			<a href="query.php?page=<?=$page + 1;?>">| Next Page</a>
		</main>
	<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
	</body>
</html>
