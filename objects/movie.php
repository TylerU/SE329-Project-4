<?php
require_once('dbutil.php');

class Movie
{
	private $_title;
	private $_id;
	private $_rating;
	private $_release;

	function __construct($title, $rating, $release){
		$this->_title  = $title;
		// $this->_id = $id;
		$this->_rating = $rating;
		$this->_release = $release;
	}	

	public function getTitle(){
		return $this->_title;
	}

	public function getID(){
		// return $this->_id;
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
}
?>