<?php
require_once('dbutil.php');

class Movie
{
	private $_title;
	private $_rating;
	private $_release;

	function __construct($title, $rating, $release){
		$this->_title  = $title;
		$this->_rating = $rating;
		$this->_release = $release;
	}	

	public function getTitle(){
		return $this->_title;
	}

	public function getRating() {
		return $this->_rating;
	}

	public static function getNextId(){
		$result = DB::query("SELECT MAX(id) as id FROM movieInstances");
		$row = mysqli_fetch_array($result);
		$nextId = $row['id'];

		return $nextId + 1;
	}

	public static function getMovieFromDB($row){
		$title  = $row['name'];
		$release = $row['releasedate'];
		$rating = $row['rating'];
		$movie = new Movie($title, $rating, $release);
		return $movie;
	}

	public function updateRating($userRating){
		#TODO make update to DB here using the new userRating value;
		DB::query("Update movies set rating = " . $userRating . " where name = '" . $this->_title . "'");
	}

	public function getRatingStr(){
		echo ($this->_rating / 5.0 * 100) . "%";
	}
}
?>