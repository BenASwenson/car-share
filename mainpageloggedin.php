<?php
session_start();
if(!isset($_SESSION['user_id'])){
	header("location: index.php");
}
include("connection.php");

$user_id = $_SESSION['user_id'];

//run query to get username and email from table
$sql = "SELECT * FROM users WHERE user_id='$user_id'";
$result = mysqli_query($link, $sql);

$count = mysqli_num_rows($result);

if($count == 1){
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
	$username = $row['username'];
	$email = $row['email'];
	$picture = $row['profilepicture'];
}else{
	echo "There was an error retrieving the username and email from the database.";
}
?>


<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
	  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
<!--	<link href="css/bootstrap.min.css" rel="stylesheet">-->
	  <link rel="preconnect" href="https://fonts.gstatic.com">
	  <link href="https://fonts.googleapis.com/css2?family=Yusei+Magic&display=swap" rel="stylesheet">

      <title>My Trips</title>
	  <link href="styling.css" rel="stylesheet">
	  <!-- Option 1: Bootstrap Bundle with Popper -->
	  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDL6wkXjVSBbKqAljr1AX66Tyqk7evvCec&libraries=places">
	  </script>
	  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/sunny/jquery-ui.css">
	  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	  
	  <style>
		 
		  .btn-group{
			  display: grid;
			  grid-template-columns: repeat(auto-fill,minmax(80px, 1fr));
			  margin-bottom: 20px;
		  }
		  .modal{
			  z-index: 20;
		  }
		  .modal-backdrop{
			  z-index: 10;
		  }
		  #googleMap{
			  width: 300px;
			  height: 200px;
			  margin: 30px auto;
		  }
		  .container{
			  margin-top: 120px;
			  padding-bottom: 150px;
		  }
		  .trip{
			  border: 1px solid grey;
			  border-radius: 10px;
			  padding: 10px;
			  margin: 10px auto;
			  background: linear-gradient(#ECE9E6, #FFFFFF);
		  }
		  #myTrips{
			  margin-bottom: 20px;
			  margin-top: 20px;
		  }
		  .departure, .destination{
			  font-size: 1.5em;
		  }
		  .price, .seatsavailable{
			  font-size: 2em;
		  }
	  </style>
	  
  </head>
  <body>
	  <!--Navigation Bar-->
	  <nav class="navbar navbar-dark navbar-expand-md bg-custom-2">
		  <div class="container-fluid">
			  <a class="navbar-brand" href="#">Car Share</a>
			  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSuppportedContent" aria-expanded="false" aria-label="Toggle navigation">
				  <span class="navbar-toggler-icon"></span>
			  </button>
			  <div class="collapse navbar-collapse" id="navbarSupportedContent">
				  <ul class="navbar-nav list-group me-auto mb-2 mb-lg-0">
					  <li class="nav-item">
					  	  <a class="nav-link" aria-current="page" href="index.php">Search</a>
				  	  </li>
					  <li class="nav-item">
						  <a class="nav-link" aria-current="page" href="profile.php">Profile</a>
					  </li>
					  <li class="nav-item">
						  <a class="nav-link active" href="#">My Trips</a>
					  </li>
				  </ul>
				  <ul class="navbar-nav mb-2 mb-lg-0">
					  <li class="nav-item">
						  <a href="#" class="nav-link">
							  <?php
								if(empty($picture)){
									echo "<div class='image_preview' data-bs-target='#updatepicture' data-bs-toggle='modal'><img class='preview' src='profilepicture/car.jpg'/></div>";
								}else{
									echo "<div class='image_preview' data-bs-target='#updatepicture' data-bs-toggle='modal'><img class='preview' src='$picture'/></div>";
								}
							  ?>
						  </a>
					  </li>
					  <li class="nav-item">
						  <a href="#" class="nav-link"><?php echo $username; ?></a>
					  </li>
					  <li class="nav-item">
						  <a class="nav-link" href="index.php?logout=1">Logout</a>
					  </li>
				  </ul>
			  </div>
		  </div>
	  </nav>
	  
	  <!--Container-->
	  <div class="container" id="container">
		  
		  <div class="row">
			  <div class="col-md-8 offset-md-2">
				  <div>
					  <button type="button" class="btn btn-lg green" data-bs-toggle="modal" data-bs-target="#addtripModal">
						  Add trips
					  </button>
				  </div>
				  <div id="myTrips" class="trips">
					  <!--Ajax Call to PHP file-->	  
				  </div>
				  
			  </div>
			  
		  </div>
	  </div>
	  
	  <!--Add Trip Form-->
	  <form method="post" id="addtripform">
		  <div class="modal" id="addtripModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-dialog">
				  <div class="modal-content">
					  <div class="modal-header">
						  <h4 id="myModalLabel">New trip:</h4>
						  <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
						  </button>
					  </div>
					  <div class="modal-body">
						  
						  <!--Add Trip message from PHP file-->
						  <div id="addtripmessage"></div>
						  
						  <!--Google Map-->
						  <div id="googleMap"></div>
						  
						  <div class="mb-3">
							  <label for="departure" class="visually-hidden">Departure:</label>
							  <input type="text" class="form-control" name="departure" placeholder="Departure" id="departure">
						  </div>
						  <div class="mb-3">
							  <label for="destinatioin" class="visually-hidden">Destination:</label>
							  <input type="text" class="form-control" name="destination" placeholder="Destination" id="destination">
						  </div>
						  <div class="mb-3">
							  <label for="price" class="visually-hidden">Price:</label>
							  <input type="number" class="form-control" name="price" placeholder="Price" id="price">
						  </div>
						  <div class="mb-3">
							  <label for="seatsavailable" class="visually-hidden">Seats Available:</label>
							  <input type="number" class="form-control" name="seatsavailable" placeholder="Seats Available" id="seatsavailable">
						  </div>
						  <div class="mb-3">
							  <label><input type="radio" name="regular" id="yes" value="Y">Regular</label>
							  <label><input type="radio" name="regular" id="no" value="N">One-off</label>
						  </div>
						  <div class="btn-group regular" role="group" aria-label="Basic checkbox toggle button group">
							  <input type="checkbox" class="btn-check" id="monday" name="monday" value="1" autocomplete="off">
							  <label class="btn btn-outline-dark" for="monday">Monday</label>
							  <input type="checkbox" class="btn-check" id="tuesday" name="tuesday" value="1" autocomplete="off">
							  <label class="btn btn-outline-dark" for="tuesday">Tuesday</label>
							  <input type="checkbox" class="btn-check" id="wednesday" name="wednesday" value="1" autocomplete="off">
							  <label class="btn btn-outline-dark" for="wednesday">Wednesday</label>
							  <input type="checkbox" class="btn-check" id="thursday" name="thursday" value="1" autocomplete="off">
							  <label class="btn btn-outline-dark" for="thursday">Thursday</label>
							  <input type="checkbox" class="btn-check" id="friday" name="friday" value="1" autocomplete="off">
							  <label class="btn btn-outline-dark" for="friday">Friday</label>
							  <input type="checkbox" class="btn-check" id="saturday" name="saturday" value="1" autocomplete="off">
							  <label class="btn btn-outline-dark" for="saturday">Saturday</label>
							  <input type="checkbox" class="btn-check" id="sunday" name="sunday" value="1" autocomplete="off">
							  <label class="btn btn-outline-dark" for="sunday">Sunday</label>
						  </div>
						  <div class="mb-3 one-off">
							  <label for="date" class="visually-hidden">Date:</label>
							  <input class="form-control" name="date" id="date">
						  </div>
						  <div class="mb-3 regular one-off">
							  <label for="time" class="visually-hidden">Time:</label>
							  <input type="time" class="form-control" name="time" id="time">
						  </div>
						  	  
					  </div>
					  <div class="modal-footer">
						  <input class="btn btn-primary" name="createTrip" type="submit" value="Create Trip">
						  <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancel</button>
					  </div>
				  </div>
			  </div>
		  </div>	  
	  </form>
	  
	  <!--Edit Trip Form-->
	  <form method="post" id="edittripform">
		  <div class="modal" id="edittripModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-dialog">
				  <div class="modal-content">
					  <div class="modal-header">
						  <h4 id="myModalLabel">Edit trip:</h4>
						  <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
						  </button>
					  </div>
					  <div class="modal-body">
						  
						  <!--Edit Trip message from PHP file-->
						  <div id="edittripmessage"></div>
		
						  <div class="mb-3">
							  <label for="departure2" class="visually-hidden">Departure:</label>
							  <input type="text" class="form-control" name="departure2" placeholder="Departure" id="departure2">
						  </div>
						  <div class="mb-3">
							  <label for="destinatioin2" class="visually-hidden">Destination:</label>
							  <input type="text" class="form-control" name="destination2" placeholder="Destination" id="destination2">
						  </div>
						  <div class="mb-3">
							  <label for="price2" class="visually-hidden">Price:</label>
							  <input type="number" class="form-control" name="price2" placeholder="Price" id="price2">
						  </div>
						  <div class="mb-3">
							  <label for="seatsavailable2" class="visually-hidden">Seats Available:</label>
							  <input type="number" class="form-control" name="seatsavailable2" placeholder="Seats Available" id="seatsavailable2">
						  </div>
						  <div class="mb-3">
							  <label><input type="radio" name="regular2" id="yes2" value="Y">Regular</label>
							  <label><input type="radio" name="regular2" id="no2" value="N">One-off</label>
						  </div>
						  <div class="btn-group regular2" role="group" aria-label="Basic checkbox toggle button group">
							  <input type="checkbox" class="btn-check" id="monday2" name="monday2" value="1" autocomplete="off">
							  <label class="btn btn-outline-dark" for="monday2">Monday</label>
							  <input type="checkbox" class="btn-check" id="tuesday2" name="tuesday2" value="1" autocomplete="off">
							  <label class="btn btn-outline-dark" for="tuesday2">Tuesday</label>
							  <input type="checkbox" class="btn-check" id="wednesday2" name="wednesday2" value="1" autocomplete="off">
							  <label class="btn btn-outline-dark" for="wednesday2">Wednesday</label>
							  <input type="checkbox" class="btn-check" id="thursday2" name="thursday2" value="1" autocomplete="off">
							  <label class="btn btn-outline-dark" for="thursday2">Thursday</label>
							  <input type="checkbox" class="btn-check" id="friday2" name="friday2" value="1" autocomplete="off">
							  <label class="btn btn-outline-dark" for="friday2">Friday</label>
							  <input type="checkbox" class="btn-check" id="saturday2" name="saturday2" value="1" autocomplete="off">
							  <label class="btn btn-outline-dark" for="saturday2">Saturday</label>
							  <input type="checkbox" class="btn-check" id="sunday2" name="sunday2" value="1" autocomplete="off">
							  <label class="btn btn-outline-dark" for="sunday2">Sunday</label>
						  </div>
						  <div class="mb-3 one-off2">
							  <label for="date2" class="visually-hidden">Date:</label>
							  <input class="form-control" name="date2" id="date2">
						  </div>
						  <div class="mb-3 regular2 one-off2">
							  <label for="time2" class="visually-hidden">Time:</label>
							  <input type="time" class="form-control" name="time2" id="time2" readonly="readonly">
						  </div>
						  	  
					  </div>
					  <div class="modal-footer">
						  <input class="btn btn-primary" name="updateTrip" type="submit" value="Edit Trip">
						  <input class="btn btn-danger" name="deleteTrip" id="deleteTrip" value="Delete Trip" type="button">
						  <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
					  </div>
				  </div>
			  </div>
		  </div>	  
	  </form>
	  
	  <!--Footer-->
	  <footer class="footer mt-5 py-3">
		  <div>
			  <p>benswenson.com Copyright &copy; <?php $today = date("Y"); echo $today ?></p>
		  </div>
	  </footer>
	  <!--Spinner-->
	  <div id="spinner">
		  <img src="ajax-loader.gif" width='50' height='50'/>
		  <br /> Loading ..
	  </div>
	  <script src="map.js"></script>
	  <script src="mytrips.js"></script>

  </body>
</html>