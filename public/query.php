<?php

// connect to db, present db connection as $connection variable
require __DIR__ . '/../Park.php';
require __DIR__ . '/../Input.php';


function getLastPage($connection, $resultsPerPage) {

	$count = Park::count();

	$lastPage = ceil($count/$resultsPerPage);

	return $lastPage;
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
	$resultsPerPage = 4;

	if(!empty($_POST)) {

		$park = new Park();

		$park->name = Input::getString('name');
		$park->location = Input::getString('location');
		$park->dateEstablished = Input::getNumber('date_established');
		$park->areaInAcres = Input::getNumber('area_in_acres');
		$park->description = Input::getString('description');

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
		<style type="text/css">
			textarea {
				resize: none;
			}
		</style>
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
			<!-- <div>
				<form method="POST" action="query.php">
					<input type="text" name="name" class="form-control col-md-4" placeholder="Name"><br>
        			<input type="text" name="location" class="form-control col-md-4" placeholder="Location"><br>
       				<input type="text" name="date_established" class="form-control col-md-4" placeholder="Date Established YYYY-MM-DD"><br>
        			<input type="text" name="area_in_acres" class="form-control col-md-4" placeholder="Area in acres Use a NUMERIC VALUE"><br>
        			<input type="text" name="description" class="form-control col-md-4" placeholder="Description"><br>
        			<input class="btn" type="submit" name="submit">
				</form>
			</div> -->
			<div class="container">
				<div class="row">
					<button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">Add National Park</button>
					<div id="myModal" class="modal fade">
						<div class="modal-dialog modal-md">
							<div class="modal-content">
								<div class="modal-header">
									<strong>Enter park information</strong>
								</div>
								<div class="modal-body">
									<form method="POST" action="query.php">
										<div class="form-group">
											<label for="name">Park Name</label>
											<input type="text" name="name" class="form-control" id="name" placeholder="Park name">
										</div>
										<div class="form-group">
											<label for="location">Location</label>
											<input type="text" name="location" class="form-control" id="location" placeholder="Location">
										</div>
										<div class="form-group">
											<label for="date_established">Date Established</label>
											<input type="text" name="date_established" class="form-control" id="date_established" placeholder="YYYY-MM-DD">
										</div>
										<div class="form-group">
											<label for="area_in_acres">Area in acres</label>
											<input type="text" name="area_in_acres" class="form-control" id="area_in_acres" placeholder="Use a NUMERIC VALUE">
										</div>
										<div class="form-group">
											<label for="description">Description</label>
											<textarea name="description" class="form-control" rows="3" placeholder="Enter description here"></textarea>
										</div>
									<div class="btn-group btn-group-justified">
										<div class="btn-group"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>
										<input class="btn" type="submit" name="submit">
									</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<a href="query.php?page=<?=$page - 1;?>">Previous Page |</a>
			<a href="query.php?page=<?=$page + 1;?>">| Next Page</a>
		</main>
	<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
	</body>
</html>