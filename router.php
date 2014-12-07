<?php
require_once('objects/library.php');
require_once('objects/user.php');

session_start();

if(isset($_GET['function'])){
	$function = $_GET['function'];

	if($function === 'showLib'){
		Library::showLib();
		return;
	}
	
	if($function === 'addMovie'){
		Library::addMovie($_GET['name'], $_GET['genre'], $_GET['date'], $_GET['qty']);
		return;
	}
	
	if($function === 'removeMovie'){
		Library::deleteMovie($_GET['name']);
		return;
	}
	
	if($function == 'getMovieInfo'){
		$movie = Library::getMovie($_GET['title']);
		echo "<h1 style='color:#333333'>".$movie->getTitle()."</h1>".
		     "<img src='images/".$movie->getTitle().".jpg' alt='".$movie->getTitle()."' border=10 style='height:300px;'/>";
	 	return;
	}
	
	if($function == 'checkoutMovie'){
		User::checkoutMovie($_GET['userID'], $_GET['title'], $_GET['days']);
		return;
	}
	if($function == 'updateRating'){
		$movie = Library::getBook($_GET['copyID']);
		$movie->updateRating($_GET['userRating']);
		return;
	}
	if($function == 'getRating'){
		$movie = Library::getBook($_GET['copyID']);
		$movie->getRating();
		return;
	}

	if($function == 'returnMovie'){
		User::returnMovie($_GET['userID'], $_GET['copyID']);
		return;
	}
	
	if($function == 'viewLoans'){
		$userName = $_GET['user'];
		$exact = $_GET['exact'];
		User::viewLoanHistory($userName, $exact);
		return;
	}
	
	if($function == 'viewLates'){
		User::viewLateRentals();
		return;
	}
	
	if($function == 'viewCheckOut'){
		$userName = $_GET['userID'];
		User::viewCheckedOutMovie($userName);
		return;
	}
	
	if($function == 'validate') {
		$bookName = $_GET['name'];
		$genre   = $_GET['genre'];
		$qty      = $_GET['qty'];
		$date      = $_GET['date'];

		if(!ctype_digit($qty)) {
			echo "Invalid qty: " . $qty;
			return;
		}
		echo "PASSED";
		return;
	}
	
	if($function == 'email'){
		if(!isset($_SESSION['notified'])){
			$userEmail = $_GET['userEmail'];
			if(mail($userEmail,
					'[Unified Rental Service] Upcoming rental deadline',
					'One of your rentals is due today, make sure you bring that back to us!'))
			{
				$_SESSION['notified'] = true;
				echo "You have a rental due today!\nAn email reminder has been sent to:\n".$userEmail;
				return;
			}
			echo "Unable to send an email reminder to your email address at\n".$userEmail;
			return;
		} else {
			return;
		}
	}
	if($function == 'checkDueToday'){
		$userName = $_GET['userID'];
		User::hasRentalDueToday($userName);
		return;
	}	
	if($function == 'checkRentalLate'){
		$userName = $_GET['userID'];
		User::hasLateRental($userName);
		return;
	}	
}
?>