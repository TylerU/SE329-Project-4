<?php
require_once('dbutil.php');

DB::query("drop table movies");
DB::query("drop table movieInstances");
DB::query("drop table rentals");
DB::query("drop table users");

DB::query("create table users (username char (20), password char (200), firstname char (30), lastname char (30), email char(50), admin bool, Primary key (username));");
DB::query("create table movies (name char (100), rating int, releasedate DATE, genre char (100), Primary key (name));");
DB::query("create table movieInstances (name char (100) not null references movies, id int not null, Primary key (id));");
DB::query("create table rentals (username char (20) references users, id int not null references movieInstances, checkedout DATETIME, duedate DATE, checkedin DATETIME, Primary key (username, id, checkedout));");

DB::query("insert into users values ('joe','" . md5("joe") . "', 'Joe', 'Miller', 'joe@joe.com', FALSE);");
DB::query("insert into users values ('tyler','" . md5("tyler") . "', 'Tyler', 'Miller', 'tyler@tyler.com', TRUE);");

DB::query("insert into movies values ('Transformers', 3, CURDATE(), 'Action');");
DB::query("insert into movies values ('Frozen', 5, CURDATE(), 'Family');");
DB::query("insert into movies values ('Dracula', 5, CURDATE(), 'Action');");
DB::query("insert into movies values ('Fury', 5, CURDATE(), 'History');");
DB::query("insert into movies values ('Interstellar', 5, CURDATE(), 'Adventure');");
DB::query("insert into movies values ('Thor', 5, CURDATE(), 'Action');");
DB::query("insert into movies values ('The Hobbit', 5, CURDATE(), 'Adventure');");

DB::query("insert into movieInstances values ('Transformers', 0);");
DB::query("insert into movieInstances values ('Transformers', 1);");
DB::query("insert into movieInstances values ('Frozen', 2);");
DB::query("insert into movieInstances values ('Frozen', 3);");
DB::query("insert into movieInstances values ('Dracula', 4);");
DB::query("insert into movieInstances values ('Fury', 5);");
DB::query("insert into movieInstances values ('Interstellar', 6);");
DB::query("insert into movieInstances values ('Thor', 7);");
DB::query("insert into movieInstances values ('The Hobbit', 8);");

DB::query("insert into rentals values ('joe', 0, NOW(), DATE_ADD(CURDATE(), INTERVAL 1 DAY), NULL);");
DB::query("insert into rentals values ('joe', 2, NOW(), DATE_ADD(CURDATE(), INTERVAL -7 DAY), NULL);");


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
        echo $row["username"] . " " . $row["id"] . " " . $row["checkedout"] . " " . $row["duedate"] . "\n";
    }
} else {
    echo "0 results";
}
?>
