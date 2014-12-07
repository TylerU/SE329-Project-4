<?php
require_once('dbutil.php');


echo "Users:\n";
$sql = "SELECT * FROM users";
$result = DB::query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo $row["username"] . " " . $row["password"] . " " . $row["admin"] . "\n";
    }
} else {
    echo "0 results";
}


echo "\n\nMovies:\n";

$sql = "SELECT * FROM movies";
$result = DB::query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo $row["name"] . "\n";
    }
} else {
    echo "0 results";
}

echo "\n\nMovie Instances:\n";

$sql = "SELECT * FROM movieInstances";
$result = DB::query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo $row["name"] . " " . $row["id"] . "\n";
    }
} else {
    echo "0 results";
}


echo "\n\nRentals:\n";

$sql = "SELECT * FROM rentals";
$result = DB::query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo $row["username"] . " " . $row["id"] . " " . $row["checkedout"] . " " . $row["duedate"] . " " . $row["checkedin"] . "\n";
    }
} else {
    echo "0 results";
}

?>