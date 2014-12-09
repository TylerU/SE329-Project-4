<?php

require_once('objects/user.php');
require_once('objects/movie.php');
require_once('objects/library.php');
require_once('dbutil.php');
   
/* 
*
*           WHITE BOX TEST CASES
*
*/

// Library Tests
function getMoviesTest(){
    $query = "SELECT * from movies where name in (SELECT name from movieInstances where id not in (select id from rentals where checkedin is null)) ORDER BY name";
    $result = DB::query($query);
    $movies = Library::getMovies($result);
    
    if($movies[0]->getTitle() == "Dracula"){
        print("<li class='list-group-item list-group-item-success'>Dracula found</li>");
    }else{
        print("<li class='list-group-item list-group-item-danger'>Dracula not found</li>");
    }
    if($movies[1]->getTitle() == "Frozen"){
        print("<li class='list-group-item list-group-item-success'>Frozen found</li>");
    }else{
        print("<li class='list-group-item list-group-item-danger'>Frozen not found</li>");
    }
    if($movies[2]->getTitle() == "Fury"){
        print("<li class='list-group-item list-group-item-success'>Fury found</li>");
    }else{
        print("<li class='list-group-item list-group-item-danger'>Fury not found</li>");
    }
    if($movies[3]->getTitle() == "Interstellar"){
        print("<li class='list-group-item list-group-item-success'>Inerstellar found</li>");
    }else{
        print("<li class='list-group-item list-group-item-danger'>Inerstellar not found</li>");
    }
    if($movies[4]->getTitle() == "The Hobbit"){
        print("<li class='list-group-item list-group-item-success'>The Hobbit found</li>");
    }else{
        print("<li class='list-group-item list-group-item-danger'>The Hobbit not found</li>");
    }
    if($movies[5]->getTitle() == "Thor"){
        print("<li class='list-group-item list-group-item-success'>Thor found</li>");
    }else{
        print("<li class='list-group-item list-group-item-danger'>Thor not found</li>");
    }
    if($movies[6]->getTitle() == "Transformers"){
        print("<li class='list-group-item list-group-item-success'>Transformers found</li>");
    }else{
        print("<li class='list-group-item list-group-item-danger'>Transformers not found</li>");
    }
}

function doesMovieExistTest(){
    $eFrozen = Library::doesMovieExist("Frozen");
    if($eFrozen == true){
        print "<li class='list-group-item list-group-item-success'>Frozen Exists:  success</li>";
    }else{
        print "<li class='list-group-item list-group-item-danger'>Frozen Exists:  FAIL</li>";
    }
    $eNotInDB = Library::doesMovieExist("Not a Movie");
    if($eFrozen == true){
        print "<li class='list-group-item list-group-item-success'>Not in DB does not exists:  success</li>";
    }else{
        print "<li class='list-group-item list-group-item-danger'>Not in DB does not exists:  FAIL</li>";
    }
}

//Movie test cases
function getRatingTest(){
    $query = "SELECT * from movies where name in (SELECT name from movieInstances where id not in (select id from rentals where checkedin is null)) ORDER BY name";
    $result = DB::query($query);
    $movies = Library::getMovies($result);

    if($movies[0]->getRating() == "2"){
        print("<li class='list-group-item list-group-item-success'>Dracula: Correct</li>");
    }else{
        print("<li class='list-group-item list-group-item-danger'>Dracula: FAIL</li>");
    }
    if($movies[1]->getRating() == "1"){
        print("<li class='list-group-item list-group-item-success'>Frozen: Correct</li>");
    }else{
        print("<li class='list-group-item list-group-item-danger'>Frozen: FAIL</li>");
    }
    if($movies[2]->getRating() == "3"){
        print("<li class='list-group-item list-group-item-success'>Fury: Correct</li>");
    }else{
        print("<li class='list-group-item list-group-item-danger'>Fury: FAIL</li>");
    }
    if($movies[3]->getRating() == "5"){
        print("<li class='list-group-item list-group-item-success'>Inerstellar: Correct</li>");
    }else{
        print("<li class='list-group-item list-group-item-danger'>Inerstellar: FAIL</li>");
    }
    if($movies[4]->getRating() == "5"){
        print("<li class='list-group-item list-group-item-success'>The Hobbit: Correct</li>");
    }else{
        print("<li class='list-group-item list-group-item-danger'>The Hobbit: FAIL</li>");
    }
    if($movies[5]->getRating() == "5"){
        print("<li class='list-group-item list-group-item-success'>Thor: Correct</li>");
    }else{
        print("<li class='list-group-item list-group-item-danger'>Thor: FAIL</li>");
    }
    if($movies[6]->getRating() == "4"){
        print("<li class='list-group-item list-group-item-success'>Transformers: Correct</li>");
    }else{
        print("<li class='list-group-item list-group-item-danger'>Transformers: FAIL</li>");
    }
}

function getGenreTest(){
    $query = "SELECT * from movies where name in (SELECT name from movieInstances where id not in (select id from rentals where checkedin is null)) ORDER BY name";
    $result = DB::query($query);
    $movies = Library::getMovies($result);

    if($movies[0]->getGenre() == "Action"){
        print("<li class='list-group-item list-group-item-success'>Dracula: Correct</li>");
    }else{
        print("<li class='list-group-item list-group-item-danger'>Dracula: FAIL</li>");
    }
    if($movies[1]->getGenre() == "Family"){
        print("<li class='list-group-item list-group-item-success'>Frozen: Correct</li>");
    }else{
        print("<li class='list-group-item list-group-item-danger'>Frozen: FAIL</li>");
    }
    if($movies[2]->getGenre() == "History"){
        print("<li class='list-group-item list-group-item-success'>Fury: Correct</li>");
    }else{
        print("<li class='list-group-item list-group-item-danger'>Fury: FAIL</li>");
    }
}

function getRatingStrTest(){
    $movie = new Movie("TEST", "5", "7-8-2014", "Action");
    ob_start();
    $movie->getRatingStr();
    $output = ob_get_clean();
    if($output == "100%"){
        print "<li class='list-group-item list-group-item-success'>Create rating string: Correct</li>";
    }else{
        print "<li class='list-group-item list-group-item-danger'>Create rating string: FAIL</li>";
    }
}

//User test cases
function viewLoanHistoryTest(){
    ob_start();
    User::viewLoanHistory("tyler", true);
    $output = ob_get_clean();
    if($output == "<TR class='info'><TH>Copy ID</TH><TH>Username</TH><TH>Due Date</TH><TH>Date Returned</TH><TR>"){
        print "<li class='list-group-item list-group-item-success'>No History: Correct</li>";
    }else{
        print "<li class='list-group-item list-group-item-danger'>No History: FAIL</li>";
    }
    ob_start();
    User::viewLoanHistory("joe", true);
    $output = ob_get_clean();
    if($output == "<TR class='info'><TH>Copy ID</TH><TH>Username</TH><TH>Due Date</TH><TH>Date Returned</TH><TR><TR><TD><B>0<B></TD><TD>joe</TD><TD>2014-12-08</TD><TD></TD></TR><TR><TD><B>2<B></TD><TD>joe</TD><TD>2014-11-30</TD><TD></TD></TR>"){
        print "<li class='list-group-item list-group-item-success'>Found History: Correct</li>";
    }else{
        print "<li class='list-group-item list-group-item-danger'>Found History: FAIL</li>";
    }
}

function viewLateRentalsTest(){
    ob_start();
    User::viewLateRentals();
    $output = ob_get_clean();
    if($output == "<TR class='info'><TH>Copy ID</TH><TH>Username</TH><TH>Due Date</TH></TR><TR><TD><B>0<B></TD><TD>joe</TD><TD>2014-12-08</TD></TR><TR><TD><B>2<B></TD><TD>joe</TD><TD>2014-11-30</TD></TR>"){
        print "<li class='list-group-item list-group-item-success'>Late Records: Correct</li>";
    }else{
        print "<li class='list-group-item list-group-item-danger'>Late Records: FAIL</li>";
    }
}

function hasLateRentalTest(){
    ob_start();
    User::hasLateRental("joe");
    $output = ob_get_clean();
    if($output == "LATE"){
        print "<li class='list-group-item list-group-item-success'>Has late rental true: Correct";
    }else{
        print "<li class='list-group-item list-group-item-danger'>Has late rental true: FAIL";
    }

    ob_start();
    User::hasLateRental("tyler");
    $output = ob_get_clean();
    if($output == "NONE"){
        print "<li class='list-group-item list-group-item-success'>Has late rental false: Correct</li>";
    }else{
        print "<li class='list-group-item list-group-item-danger'>Has late rental false: FAIL</li>";
    }
}

/* 
*
*           BLACK BOX TEST CASES
*
*/

function showLibTest(){
    ob_start();
    Library::showLib("","name","All");
    $output = ob_get_clean();
    if($output == "<div class='movies-container'><div class='movie-div'><img src='images/Dracula.jpg'><input type='hidden' value='Dracula'></div><div class='movie-div'><img src='images/Frozen.jpg'><input type='hidden' value='Frozen'></div><div class='movie-div'><img src='images/Fury.jpg'><input type='hidden' value='Fury'></div><div class='movie-div'><img src='images/Interstellar.jpg'><input type='hidden' value='Interstellar'></div><div class='movie-div'><img src='images/The Hobbit.jpg'><input type='hidden' value='The Hobbit'></div><div class='movie-div'><img src='images/Thor.jpg'><input type='hidden' value='Thor'></div><div class='movie-div'><img src='images/Transformers.jpg'><input type='hidden' value='Transformers'></div></div>"){
        print "<li class='list-group-item list-group-item-success'>Movie Library Formating Name sort: Correct</li>";
    }else{
        print "<li class='list-group-item list-group-item-danger'>Movie Library Formating Name sort: FAIL</li>";
    }

    ob_start();
    Library::showLib("","rating","All");
    $output = ob_get_clean();
    if($output == "<div class='movies-container'><div class='movie-div'><img src='images/Interstellar.jpg'><input type='hidden' value='Interstellar'></div><div class='movie-div'><img src='images/The Hobbit.jpg'><input type='hidden' value='The Hobbit'></div><div class='movie-div'><img src='images/Thor.jpg'><input type='hidden' value='Thor'></div><div class='movie-div'><img src='images/Transformers.jpg'><input type='hidden' value='Transformers'></div><div class='movie-div'><img src='images/Fury.jpg'><input type='hidden' value='Fury'></div><div class='movie-div'><img src='images/Dracula.jpg'><input type='hidden' value='Dracula'></div><div class='movie-div'><img src='images/Frozen.jpg'><input type='hidden' value='Frozen'></div></div>"){
        print "<li class='list-group-item list-group-item-success'>Movie Library Formating Ratings sort: Correct</li>";
    }else{
        print "<li class='list-group-item list-group-item-danger'>Movie Library Formating Ratings sort: FAIL</li>";
    }

    ob_start();
    Library::showLib("","name","Action");
    $output = ob_get_clean();

    if($output == "<div class='movies-container'><div class='movie-div'><img src='images/Dracula.jpg'><input type='hidden' value='Dracula'></div><div class='movie-div'><img src='images/Thor.jpg'><input type='hidden' value='Thor'></div><div class='movie-div'><img src='images/Transformers.jpg'><input type='hidden' value='Transformers'></div></div>"){
        print "<li class='list-group-item list-group-item-success'>Movie Library Formating Action Movie Name sort: Correct</li>";
    }else{
        print "<li class='list-group-item list-group-item-danger'>Movie Library Formating Action Movie Name sort: FAIL</li>";
    }

    ob_start();
    Library::showLib("Frozen","name","All");
    $output = ob_get_clean();
    if($output == "<div class='movies-container'><div class='movie-div'><img src='images/Frozen.jpg'><input type='hidden' value='Frozen'></div></div>"){
        print "<li class='list-group-item list-group-item-success'>Movie Library Formating Search: Correct</li>";
    }else{
        print "<li class='list-group-item list-group-item-danger'>Movie Library Formating Search: FAIL</li>";
    }
}


?>

<html>
<head>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    
<br>
</head>
<title>Unified Rental Service - Tests</title>
<body>
    <div class="row">
        <div class="col-md-5">
            <h3>WHITE BOX TEST CASES</h3>
            <p class="bg-primary">getMoviesTest():</p>
            <ul class="list-group">
                <?php getMoviesTest(); ?>
            </ul>
            <p class="bg-primary">doesMovieExistTest():</p>
            <ul class="list-group">
                <?php doesMovieExistTest(); ?>
            </ul>
            <p class="bg-primary">getRatingTest():</p>
            <ul class="list-group">
                <?php getRatingTest();?>
            </ul>
            <p class="bg-primary">getGenreTest():</p>
            <ul class="list-group">
                <?php getGenreTest();?>
            </ul>
            <p class="bg-primary">getRatingStrTest():</p>
            <ul class="list-group">
                <?php getRatingStrTest();?>
            </ul>
            <p class="bg-primary">viewLoanHistoryTest():</p>
            <ul class="list-group">
                <?php viewLoanHistoryTest();?>
            </ul>
            <p class="bg-primary">viewLateRentalsTest():</p>
            <ul class="list-group">
                <?php viewLateRentalsTest();?>
            </ul>
            <p class="bg-primary">hasLateRentalTest():</p>
            <ul class="list-group">
                <?php hasLateRentalTest();?>
            </ul>
        </div>
        <div class="col-md-5 col-md-offset-1">
            <h3>BLACK BOX TEST CASES</h3>
            <p class="bg-primary">showLibTest():</p>
            <ul class="list-group">
                <?php showLibTest(); ?>
            </ul>
        </div>
    </div>
</body>
</html>