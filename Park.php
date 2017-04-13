<?php

/**
 * A Class for interacting with the national_parks database table
 *
 * contains static methods for retrieving records from the database
 * contains an instance method for persisting a record to the database
 *
 * Usage Examples
 *
 * Retrieve a list of parks and display some values for each record
 *
 *      $parks = Park::all();
 *      foreach($parks as $park) {
 *          echo $park->id . PHP_EOL;
 *          echo $park->name . PHP_EOL;
 *          echo $park->description . PHP_EOL;
 *          echo $park->areaInAcres . PHP_EOL;
 *      }
 * 
 * Inserting a new record into the database
 *
 *      $park = new Park();
 *      $park->name = 'Acadia';
 *      $park->location = 'Maine';
 *      $park->areaInAcres = 48995.91;
 *      $park->dateEstablished = '1919-02-26';
 *
 *      $park->insert();
 *
 */
class Park
{

	///////////////////////////////////
	// Static Methods and Properties //
	///////////////////////////////////

	/**
	 * our connection to the database
	 */

	public static $connection = null;


	/**
	 * establish a database connection if we do not have one
	 */
	public static function dbConnect() {

		if (! is_null(self::$connection)) {
			return;
		}
	   self::$connection = require 'db_connect.php';
	}

	/**
	 * returns the number of records in the database
	 */
	public static function count() {
		// TODO: call db_connect to ensure we have a database connection
		self::dbConnect();
		// TODO: use the $connection static property to query the database for the
		//       number of existing park records
		$statement = self::$connection->query('SELECT count(*) FROM national_parks');
		$count = $statement->fetch()[0];
		return $count;
	}

	/**
	 * returns all the records
	 */
	public static function all() {
		// TODO: call dbConnect to ensure we have a database connection

		self::dbConnect();
		// TODO: use the $connection static property to query the database for all the
		//       records in the parks table

		$parks = self::$connection->query('SELECT * FROM national_parks');
		// TODO: iterate over the results array and transform each associative
		//       array into a Park object

		$objectArray = [];

		foreach ($parks as $park) {

			$singlePark = new Park();

			$singlePark->id = $park['id'];
			$singlePark->name = $park['name'];
			$singlePark->location = $park['location'];
			$singlePark->dateEstablished = $park['date_established'];
			$singlePark->areaInAcres = $park['area_in_acres'];
			$singlePark->description = $park['description'];

			array_push($objectArray, $singlePark);
			
		}

		// TODO: return an array of Park objects
		return $objectArray;
	   
	}

	/**
	 * returns $resultsPerPage number of results for the given page number
	 */
	public static function paginate($page, $resultsPerPage = 4) {
		// TODO: call dbConnect to ensure we have a database connection
		self::dbConnect();

		// TODO: calculate the limit and offset needed based on the passed values

		$offset = ($page - 1) * $resultsPerPage;

		// TODO: use the $connection static property to query the database with the calculated limit and offset

		$statement = self::$connection->prepare("SELECT * FROM national_parks LIMIT :items_per_page OFFSET :offset");

		// TODO: return an array of the found Park objects
		$statement->bindValue('items_per_page', $resultsPerPage, PDO::PARAM_INT);

		$statement->bindValue('offset', $offset, PDO::PARAM_INT);

		$statement->execute();

		return $parks = $statement->fetchAll(PDO::FETCH_ASSOC);

	}

	/////////////////////////////////////
	// Instance Methods and Properties //
	/////////////////////////////////////

	/**
	 * properties that represent columns from the database
	 */
	public $id;
	public $name;
	public $location;
	public $dateEstablished;
	public $areaInAcres;
	public $description;

	/**
	 * inserts a record into the database
	 */
	public function insert() {
		// TODO: call dbConnect to ensure we have a database connection
		self::dbConnect();

		// TODO: use the $connection static property to create a perpared statement for
		//       inserting a record into the parks table
			// TODO: use the $this keyword to bind the values from this object to
		//       the prepared statement

		$statement = self::$connection->prepare('INSERT INTO national_parks (name, location, date_established, area_in_acres, description) VALUES (:name, :location, :date_established, :area_in_acres, :description)');

		$statement->bindValue(':name', $this->name, PDO::PARAM_STR);

		$statement->bindValue(':location', $this->location, PDO::PARAM_STR);

		$statement->bindValue(':date_established', $this->dateEstablished, PDO::PARAM_STR);

		$statement->bindValue(':area_in_acres', $this->areaInAcres, PDO::PARAM_INT);

		$statement->bindValue(':description', $this->description, PDO::PARAM_STR);

		$statement->execute();
		
		// TODO: execute the statement and set the $id property of this object to
		//       the newly created id

		$this->id = self::$connection->lastInsertId();
	}
}
