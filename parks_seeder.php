<?php 

require __DIR__ . '/db_test.php';

$clearParks = 'TRUNCATE national_parks;';

$connection->exec($clearParks);

$parks = [

	['name' => 'Acadia', 'location' => 'Maine', 'date_established' => '1919-02-26'
	, 'area_in_acres' => 48995.91, 'description' => 'Covering most of Mount Desert Island and other coastal islands, Acadia features the tallest mountain on the Atlantic coast of the United States, granite peaks, ocean shoreline, woodlands, and lakes. There are freshwater, estuary, forest, and intertidal habitats.'],
	['name' => 'American Samoa', 'location' => 'American Samoa', 'date_established' => '1988-10-31'
	, 'area_in_acres' => 8256.67, 'description' => 'The southernmost national park is on three Samoan islands and protects coral reefs, rainforests, volcanic mountains, and white beaches. The area is also home to flying foxes, brown boobies, sea turtles, and 900 species of fish.'],
	['name' => 'Cuyahoga Valley', 'location' => 'Ohio', 'date_established' => '2000-10-11'
	, 'area_in_acres' => 48995.91, 'description' => 'This park along the Cuyahoga River has waterfalls, hills, trails, and exhibits on early rural living. The Ohio and Erie Canal Towpath Trail follows the Ohio and Erie Canal, where mules towed canal boats. The park has numerous historic homes, bridges, and structures, and also offers a scenic train ride.'],
	['name' => 'Glacier Bay', 'location' => 'Alaska', 'date_established' => '1980-12-02'
	, 'area_in_acres' => 3223383.43, 'description' => 'Glacier Bay contains tidewater glaciers, mountains, fjords, and a temperate rainforest, and is home to large populations of grizzly bears, mountain goats, whales, seals, and eagles. When discovered in 1794 by George Vancouver, the entire bay was covered by ice, but the glaciers have since receded more than 65 miles.'],
	['name' => 'Guadalupe Mountains', 'location' => 'Texas', 'date_established' => '1966-10-15'
	, 'area_in_acres' => 86367.10, 'description' => 'This park contains Guadalupe Peak, the highest point in Texas, as well as the scenic McKittrick Canyon filled with bigtooth maples, a corner of the arid Chihuahuan Desert, and a fossilized coral reef from the Permian era.'],
	['name' => 'Isle Royale', 'location' => 'Michigan', 'date_established' => '1940-04-03'
	, 'area_in_acres' => 571790.11, 'description' => 'The largest island in Lake Superior is a place of isolation and wilderness. Along with its many shipwrecks, waterways, and hiking trails, the park also includes over 400 smaller islands within 4.5 miles of its shores. There are only 20 mammal species on the entire island, though the relationship between its wolf and moose populations is especially unique.'],
	['name' => 'Kings Canyon', 'location' => 'California', 'date_established' => '1940-03-04', 'area_in_acres' => 461901.20, 'description' => 'Home to several giant sequoia groves and the General Grant Tree, the world\'s second largest measured tree, this park also features part of the Kings River, sculptor of the dramatic granite canyon that is its namesake, and the San Joaquin River, as well as Boyden Cave.'],
	['name' => 'Mammoth Cave', 'location' => 'Kentucky', 'date_established' => '1941-07-01'
	, 'area_in_acres' => 52830.19 , 'description' => 'With more than 400 miles of passageways explored, Mammoth Cave is the world\'s longest known cave system. Subterranean wildlife includes eight bat species, Kentucky cave shrimp, Northern cavefish, and cave salamanders. Above ground, the park provides recreation on the Green River, 70 miles of hiking trails, and plenty of sinkholes and springs.'],
	['name' => 'Olympic', 'location' => 'Washington', 'date_established' => '1938-06-29'
	, 'area_in_acres' => 922650.10  , 'description' => 'Situated on the Olympic Peninsula, this park includes a wide range of ecosystems from Pacific shoreline to temperate rainforests to the alpine slopes of Mount Olympus. The scenic Olympic Mountains overlook the Hoh Rain Forest and Quinault Rain Forest, the wettest area in the contiguous United States, with the Hoh receiving an average of almost 12 ft of rain every year.'],
	['name' => 'Pinnacles', 'location' => 'California', 'date_established' => '2013-01-10'
	, 'area_in_acres' => 26685.73  , 'description' => 'Named for the eroded leftovers of a portion of an extinct volcano, the park\'s massive black and gold monoliths of andesite and rhyolite are a popular destination for rock climbers. Hikers have access to trails crossing the Coast Range wilderness. The park is home to the endangered California condor (Gymnogyps californianus) and one of the few locations in the world where these extremely rare birds can be seen in the wild. Pinnacles also supports a dense population of prairie falcons, and more than 13 species of bat which populate its talus caves.']
];

$statement =  $connection->prepare('INSERT INTO national_parks (name, location, date_established, area_in_acres, description) VALUES (:name, :location, :date_established, :area_in_acres, :description)');

foreach ($parks as $park) {
	
	$statement->bindValue(':name', $park['name'], PDO::PARAM_STR);

	$statement->bindValue(':location', $park['location'], PDO::PARAM_STR);

	$statement->bindValue(':date_established', $park['date_established'], PDO::PARAM_STR);

	$statement->bindValue(':area_in_acres', $park['area_in_acres'], PDO::PARAM_INT);

	$statement->bindValue(':description', $park['description'], PDO::PARAM_STR);

	$statement->execute();

	echo "Inserted ID: " . $connection->lastInsertId() . PHP_EOL;

}














?>