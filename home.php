<?php
require_once('objects/user.php');
require_once('objects/movie.php');
require_once('objects/library.php');
session_start();
$user = unserialize($_SESSION['user']);
?>

<html>
<head>
<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel='stylesheet' type='text/css'>
<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<title>Unified Rental Service</title>
<body>
	<!-- nav bar, goes at the top of every page -->
	<nav class="navbar navbar-inverse" role="navigation">
		<div class="collapse navbar-collapse">
	    <ul class="nav navbar-nav">
	    	<li><h4 class="navbar-text"><b>Unified Rental Service</b></h4>
	    	<li class="active"><a href="#">Home</a></li>
			<li><a href="manage.php">Dashboard</a></li>
		</ul>
	    <ul class="nav navbar-nav navbar-right">
			<li><button type="button" class="btn btn-danger navbar-btn" onclick="logout()">Logout <?php echo $user->getUsername() ?></button></li>
			<li><a style="padding-right:10px"></a>
		</ul>
		</div>
	</nav>
	<div class="container">
			<div class="row sub-header">
				<div  class="col-md-9"><h1>Unified Rental Service</h1></div>
				<div class="col-md-3">
					<input id="search" type="text" placeholder="search"/>
					Sort By:
					<select id="sort">
					  <option value="name">ABC</option>
					  <option value="rating">Rating</option>
					  <option value="releasedate">Release Date</option>
					</select>
					<br>
					Genre Filter:
					<select id="genreChange" style="margin-top:10px;margin-bottom:10px">
					<?php
						Library::getGenreOptions();
					?>
					</select>
				</div>
			</div>
			<div class="urs-container" style="padding-left:25px">
				<div id="lib" class="table">
				</div>
			</div>
	</div>
	<!-- Modal for when a table cell is clicked -->
	<div id="mymodal" class="modal fade">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <input id="modal-copyid" type="hidden" value="">
	      <div class="modal-body" align="center">
	      </div>
	      <div class="modal-footer">
            <div class="row">
    	      	<div class="col-md-7">
                    <div class="progress">
                        <div id="rating-bar" class="progress-bar progress-bar-warning" style="width: 100%; min-width: 0px;">
                            
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="input-group">
                            <div class="input-group-btn">
                                <button id="rating-button" class="btn btn-default" type="button">Rate</button>
                            </div>
                            <input type="text" id="userRating" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <h6>out of 5</h6>
                    </div>
    	      	</div>
    	      	<div class="col-md-5">
    				<input type="radio" name="days" class="student" id="days_two" value="2">2 Day Rental<br>
    				<input type="radio" name="days" class="student" id="days_five" value="5" checked>5 Day Rental<br>
    		        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
    		        <button id="deleteBookBtn" type="button" class="btn btn-danger teacher" style="display:none" data-dismiss="modal">Delete</button>
    		        <button id="checkoutBookBtn" type="button" class="btn btn-primary student" style="display:none" data-dismiss="modal">Checkout</button>
    	        </div>
            </div>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</body>
<script>
function logout(){
	window.location.href = "index.php";
}
function updateRating(userRating){
    var copyID = $('#modal-copyid').val();
    $.ajax({
        type    :"GET",
        url     :"router.php",
        data    :{"function":"updateRating","title":copyID,"userRating":userRating},
        success :function(result){
            getRating(copyID);
            updateLib();
        }
    });
}
function getRating(title){
    $.ajax({
        type    :"GET",
        url     :"router.php",
        data    :{"function":"getRating","title":title},
        success :function(result){
            $('#rating-bar').css("width", result)
            $('#rating-bar').html(result);
        }
    });
} 
function checkRentalDue(){
	if(<?php echo $user->isAdmin() ?>)
		return;
	var username = "<?php echo $user->getUsername() ?>";
	$.ajax({
		type : "GET",
		url  : "router.php",
		data : {"function":"checkDueToday","userID":username},
		success : function(result){
			if(result.trim() == "PASSED"){
				sendMail();
			}
		}
	});
}
function checkRentalLate() {
	if(<?php echo $user->isAdmin() ?>)
		return;
	var username = "<?php echo $user->getUsername() ?>";
	$.ajax({
		type : "GET",
		url  : "router.php",
		data : {"function":"checkRentalLate","userID":username},
		success : function(result){
			if(result.trim() == "LATE"){
				alert("You have late rentals! Please check in Dashboard!");
			}
		}
	});
}

function sendMail(){
	var userEmail = "<?php echo $user->getEmail() ?>";
	$.ajax({
		type : "GET",
		url  : "router.php",
		data : {"function":"email","userEmail":userEmail},
		success : function(result){
			if(result.trim() != "")
				alert(result);
		}
	});
}

function showModal(title, body, copyID){
    	$('#mymodal .modal-body').html(body);
    	$('#modal-copyid').val(copyID);
        $('#mymodal').modal('show');
}

function getMovieInfo(title){
	$.ajax({
		type  : "GET",
		url   : "router.php",
		data  : {"function": "getMovieInfo","title": title},
		success: function(result){
			showModal("Information for Movie " + title, result, title);
            getRating(title);
		}
	});
}
function updateLib(){
	var input = $('#search').val();
	var sort = $('#sort').val();
	var genre = $('#genreChange').val();
	$.ajax({
		type : "GET",
		url	 : "router.php",
		data : {
			"function" : "showLib", 
			"search":input,  
			"sort": sort,
			"genre": genre,
		},
		success	: function(result){
			$("#lib").html(result);
			$('.movie-div').click(function(){
				getMovieInfo($(this).find("input").val());
			})
		}
	});
};
function removeBook(){
	var input = $("#modal-copyid").val();
	$.ajax({
		type : "GET",
		url  : "router.php",
		data : {"function":"removeMovie","name":input.trim()},
		success : function(result){
			updateLib();
		}
	});
}

function checkOutTable(){
	var username = "<?php echo $user->getUsername() ?>";
	$.ajax({
		type: "GET", 
		url: "router.php",
		data: {"function" :"viewCheckOut", "userID":username},
		success	: function(result){
			$('#checkOutTable').html(result);
		}
	});
}

$('#search').keyup(updateLib);
$('#sort').change(updateLib);
$('#genreChange').change(updateLib);

$('#viewLoansBtn').click(function(){
	var input = $('#viewUserHistory').val();
	$.ajax({
		type : "GET",
		url	 : "router.php",
		data : {"function" :"viewLoans", "user"	:input, "exact":"true"},
		success	: function(result){
			$('#historyTable').html(result);
		}
	});
	$('#viewUserHistory').val("");
});
$('#viewUserHistory').keyup(function() {
	var input = $('#viewUserHistory').val();
	$.ajax({
		type : "GET",
		url	 : "router.php",
		data : {"function" :"viewLoans", "user"	:input, "exact":"false"},
		success	: function(result){
			$('#historyTable').html(result);
		}
	});
});
$('#addBookBtn').click(function(){
	var bookName = $("#addBookName").val();
	var author 	 = $("#addAuthor").val();
	var qty      = $("#addQty").val();
	var validated = false;
	$.ajax({
		type : "GET",
		url  : "router.php",
		data : {"function":"validate","bookName":bookName,"author":author,"qty":qty},
		async:   false,
		success : function(result){
			if(result == "PASSED")
				validated = true;
			else
				alert(result);
		}
	})
	if(!validated)
		return;
	$.ajax({
		type : "GET",
		url  : "router.php",
		data : {"function":"addBook","title":bookName,"author":author,"qty":qty},
		success : function(result){
			updateLib();
		}
	});
	$("#addBookName").val("");
	$("#addAuthor").val("");
	$("#addQty").val("");
});
$('#checkoutBookBtn').click(function() {
	var input = $("#modal-copyid").val();
	var username = "<?php echo $user->getUsername() ?>";
	var days;
	if(document.getElementById('days_two').checked)
		days = 2;
	else
		days = 5;

	$.ajax({
		type : "GET",
		url  : "router.php",
		data : {"function":"checkoutMovie", "title": input.trim(), "userID":username, "days":days},
		success : function(result) {
			if(result == 'FAILED')
				alert("You have already checked out book " + input + " before.");
			updateLib();
			checkOutTable();
		}
	});
});
$('#rating-button').click(function(){
    updateRating($("#userRating").val());
});
$(document).ready(function(){
	updateLib();
	checkOutTable();
	if(<?php echo $user->isAdmin() ?>)
		$(".teacher").css("display","");
	else
		$(".student").css("display","");
	$('#deleteBookBtn').click(removeBook);
	checkRentalDue();
	checkRentalLate();
});
</script>
</html>

