<?php
require_once('dbutil.php');
require_once('movie.php');

class Library
{
	public static function showLib($search, $sort, $genre){
		$search = trim($search);
		$searchStr = "name like '%" . $search . "%' and ";
		if(strlen($search) == 0) {
			$searchStr = '';
		}

		$sortStr = $sort;
		if($sortStr === "rating") {
			$sortStr = $sortStr . " DESC";
		}

		$genreStr = "genre = '" .$genre. "' and ";
		if($genre == "All") {
			$genreStr = "";
		}

		$query = "SELECT * from movies where " . $genreStr . $searchStr . "name in (SELECT name from movieInstances where id not in (select id from rentals where checkedin is null)) ORDER BY " . $sortStr;

		$result = DB::query($query);
		$movies = Library::getMovies($result);
		Library::showMovieList($movies);
	}

	public static function getMovies($result) {
		$movies = array();
		while($row = mysqli_fetch_array($result)){
			$movies[] = Movie::getMovieFromDB($row);
		}
		return $movies;
	}

	public static function showMovieList($movies) {
		$arrlength = count($movies);

		echo "<div class='movies-container'>";
		for($x = 0; $x < $arrlength; $x++) {
		    $movie = $movies[$x];
			echo "<div class='movie-div'>".
				"<img src='images/" . $movie->getTitle() . ".jpg'>" .
				"<input type='hidden' value='". $movie->getTitle() ."'>" .
			"</div>";
		}
		echo "</div>";
	}

	public static function doesMovieExist($title){
		$exists = false;
		$conn = DB::getConnection();

		$result = mysqli_query($conn, "SELECT * from movies where name='".$title."'");
		if($row = mysqli_fetch_array($result)){
			$exists = true;
		}

		return $exists;
	}

	public static function getGenreOptions() {
		$result = DB::query( 
        	"SELECT DISTINCT genre from movies"
        );

		echo "<option value='"."All"."'>"."All"."</option>";
        while($row = mysqli_fetch_array($result)) {
        	echo "<option value='".$row["genre"]."'>".$row["genre"]."</option>";
        }
	}

	public static function addMovie($title, $genre, $release, $num){
		// Create book if it doesn't exist
		if(!self::doesMovieExist($title)){
			DB::query("insert into movies values ('" . $title . "', 0, '" . $release . "', '" . $genre . "');");
		}

		for($i = 0; $i < $num; $i++){
			// Create the correct number of book copies in copy table
			DB::query("INSERT INTO movieInstances VALUES('". $title ."', ".Movie::getNextId().")");
		}
	}

	public static function addCopy($copyID){
		$conn = DB::getConnection();
		
		mysqli_query($conn, "INSERT INTO shelves VALUES(10, ".$shelfID.", ". $copyID .")");
	}

	public static function deleteMovie($title){
		DB::query("DELETE FROM rentals WHERE id in (select id from movieInstances WHERE name = '" . $title . "')");
		DB::query("DELETE FROM movieInstances WHERE name = '" . $title . "'");
		DB::query("DELETE FROM movies WHERE name = '" . $title . "'");
	}

	public static function getMovie($title){		
		$result = DB::query( 
        	"SELECT * from movies where name='" . $title . "'"
        );

		if($row = mysqli_fetch_array($result)){
			$movie =  Movie::getMovieFromDB($row);
			return $movie;
		} else {
			return null;
		}
	}

}
?>
