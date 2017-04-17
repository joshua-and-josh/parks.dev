<?php

class Input
{
	public static function getString($key)
	{
		$input = self::get($key);
		if(is_numeric($input) || !is_string($input)) {
			throw new Exception ("Input for $key must be a string.");
		}
		return $input;
	}

	public static function getNumber($key)
	{
		$input = self::get($key);
		if(!is_numeric($input)) {
			throw new Exception ("Input for $key must be a number.");
		}
		return $input;
	}

	public static function getDate($key)
	{
		$input = self::get($key);

		$date = DateTime::creaetFromFormat('Y-m-d', $input);

		if(!$date) {
			throw new Exception("$key must be in the YYYY-MM-DD format");
		}
		return $date;
	}

	public static function has($key)
	{
		return isset($_REQUEST[$key]);
	}

	public static function get($key, $default = null)
	{
		return (self::has($key)) ? $_REQUEST[$key] : $default;
	}

	///////////////////////////////////////////////////////////////////////////
	//                      DO NOT EDIT ANYTHING BELOW!!                     //
	// The Input class should not ever be instantiated, so we prevent the    //
	// constructor method from being called. We will be covering private     //
	// later in the curriculum.                                              //
	///////////////////////////////////////////////////////////////////////////
	private function __construct() {}
}
