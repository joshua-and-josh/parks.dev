<?php

// connect to db, present db connection as $connection variable

require __DIR__ . '/db_connect.php';
require __DIR__ . '/../Input.php';


function getLastPage($connection, $items_per_page) {

	$statement = $connection->query('SELECT count(*) FROM national_parks');

	$count = $statement->fetch()[0];

	$lastPage = ceil($count/$items_per_page);

	return $lastPage;
}

function getPaginatedParks($connection, $page, $items_per_page) {

	$offset = ($page - 1) * $items_per_page;

	$statement = $connection->prepare("SELECT * FROM national_parks LIMIT  :items_per_page OFFSET :offset");

	$statement->bindValue('items_per_page', $items_per_page, PDO::PARAM_INT);

	$statement->bindValue('offset', $offset, PDO::PARAM_INT);

	$statement->execute();

	return $parks = $statement->fetchAll(PDO::FETCH_ASSOC);


}

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
	$items_per_page = 4;

	if(!empty($_POST)) {

		$statement =  $connection->prepare('INSERT INTO national_parks (name, location, date_established, area_in_acres, description) VALUES (:name, :location, :date_established, :area_in_acres, :description)');

		$statement->bindValue(':name', $_POST['name'], PDO::PARAM_STR);

		$statement->bindValue(':location', $_POST['location'], PDO::PARAM_STR);

		$statement->bindValue(':date_established', $_POST['date_established'], PDO::PARAM_STR);

		$statement->bindValue(':area_in_acres', $_POST['area_in_acres'], PDO::PARAM_INT);

		$statement->bindValue(':description', $_POST['description'], PDO::PARAM_STR);

		$statement->execute();

	}

	$page = Input::get('page',1);

	$lastPage = getLastPage($connection, $items_per_page);

	handleOutOfRangeRequests($page, $lastPage);

	$data['parks'] = getPaginatedParks($connection, $page, $items_per_page);
	$data['page'] = $page;
	$data['lastPage'] = $lastPage;

	return $data;

}

extract(pageController($connection));

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
				<form method="post" action="query.php">
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
