<?php
require_once('dbutil.php');

class User
{
	private $_username;
	private $_password;
	private $_firstname;
	private $_lastname;
	private $_email;
	private $_admin;

	public function __construct($user, $pass, $firstname, $lastname, $email, $admin){
		$this->_username = $user;
		$this->_password = $pass;
		$this->_firstname = $firstname;
		$this->_lastname = $lastname;
		$this->_email = $email;
		$this->_admin = $admin;
	}

	public function __sleep(){
		return array('_username', '_email', '_firstname', '_lastname', '_admin');
	}

	public function __wakeup(){
	}

	public function getUsername(){
		return $this->_username;
	}

	public function getEmail(){
		return $this->_email;
	}

	public function isAdmin(){
		return $this->_admin;
	}

	public static function viewLoanHistory($userName, $exact){
		echo "<TR class='info'><TH>Copy ID</TH><TH>Username</TH><TH>Due Date</TH><TH>Date Returned</TH><TR>";
		if(!$userName){
			return;
		}
		$result;
		if($exact == "true") {
			$result = DB::query("SELECT * from rentals where username='".$userName."'");
		} else {
			$result = DB::query("SELECT * from rentals where username LIKE '".$userName."%'");
		}

		while($row = mysqli_fetch_array($result)){
			echo "<TR><TD><B>".$row['id']."<B></TD><TD>".$row['username']."</TD><TD>".$row['duedate']."</TD><TD>".$row['checkedin']."</TD></TR>";
		}
	}
	
	public static function viewCheckedOutMovie($userName){
		echo "<TR class='info'><TH>Copy ID</TH><TH>Due Date</TH></TR>";
		if(!$userName){
			return;
		}
		$result = DB::query("SELECT * FROM rentals where username='".$userName."' and checkedin is NULL");
		while($row = mysqli_fetch_array($result)){
			echo "<TR><TD><B>".$row['id']."</B></TD><TD>".$row['duedate']."</TD></TR>";
		}
	}
	
	public static function viewLateRentals(){
		echo "<TR class='info'><TH>Copy ID</TH><TH>Username</TH><TH>Due Date</TH></TR>";
		$result = DB::query("SELECT * from rentals where checkedin is NULL and duedate <= CURDATE()");
		while($row = mysqli_fetch_array($result)){
			echo "<TR><TD><B>".$row['id']."<B></TD><TD>".$row['username']."</TD><TD>".$row['duedate']."</TD></TR>";
		}
	}

	public static function createRentalRecord($userid, $id, $days){
		$query = "INSERT INTO rentals (username, id, checkedout, duedate) ".
		    "VALUES ('" . $userid . "', " . $id . ", NOW(), DATE_ADD(CURDATE(), INTERVAL " . $days . " DAY))";

		$result = DB::query($query);
		return $result;
	}

	public static function checkoutMovie($userid, $title, $days) {
		$result = DB::query("SELECT id from movieInstances where name = '" . $title . "' and id not in (select id from rentals where checkedin is null);"); 

		if($row = mysqli_fetch_array($result)) {
			$res = self::createRentalRecord($userid, $row["id"], $days);
			if($res)
				echo "PASSED";	
			else
				echo "FAILED";
		}
		else {
			echo "FAILED";
		}
	}


	public static function returnMovie($userid, $id){
		// Update the loanHistory table
		$query = "UPDATE rentals SET checkedin=NOW() ".
			"WHERE username='".$userid."' and id=".$id.";";

		$updateCount = DB::query($query);

		// TODO need to check for null result and avoid adding to shelf in that case
		if($updateCount == false){
			echo "Error: ". mysqli_error(DB::getConnection());
			return;
		}
	}

	public static function doesUserExist($username){
		$exists = false;
		$result = DB::query("SELECT * from users where username='". $username ."'");

		if($row = mysqli_fetch_array($result))
			$exists = true;

		return $exists;
	}

	public static function checkUserAndPass($username, $pass){
		$success = false;
		$result = DB::query("SELECT * from users where username='". $username ."' and password='". md5($pass) ."'");
		if($row = mysqli_fetch_array($result))
			$success = true;

		return $success;
	}

	public static function isUserAdmin($uname){
		$admin;

		$result = DB::query("SELECT admin from users where username='". $uname ."'");
		if($row = mysqli_fetch_array($result))
			$admin = $row['admin'];

		return $admin;
	}

	public static function createUser($uname, $pwhash, $first, $last, $email, $admin){
		if(self::doesUserExist($uname)){
			echo "User " . $uname . " already exists for group 10.<BR>";
			return;
		}

		DB::query("INSERT INTO users ".
			"VALUES ('".$uname."','".$pwhash."','".$first."','".$last."','".$email."',".$admin.")");

		return new User($uname, $pwhash, $first, $last, $email, $admin);
	}

	public static function getUser($uname){
		$user = null;
	
		$result = DB::query("SELECT * FROM users ".
			"where username='". $uname ."'");

		if($row = mysqli_fetch_array($result)){
			$user   = $row['username'];
			$pass   = $row['password'];
			$email  = $row['email'];
			$admin  = $row['admin'];
			$first  = $row['firstname'];
			$last   = $row['lastname'];
			$user = new User($user, $pass, $first, $last, $email, $admin); 
		}

		return $user;
	}

	public static function hasRentalDueToday($userName){
		if(!$userName){
			return;
		}
		$result = DB::query("SELECT * FROM rentals where duedate=CURDATE() and username='".$userName."' and checkedin is NULL");
		while($row = mysqli_fetch_array($result)){
			echo "PASSED";
			return;
		}
		echo "FAILED";
		return;
	}
	
	public static function hasLateRental($userName){
		if(!$userName){
			return;
		}
		$result = DB::query("SELECT * FROM rentals where username='" . $userName . "' and checkedin is NULL and duedate <= CURDATE() ");
		while($row = mysqli_fetch_array($result)){
			echo "LATE";
			return;
		}
		echo "NONE";
		return;
	}
}
?>