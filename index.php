<?php
session_start();
include('connection.php');



//remember me 
include('rememberme.php');
//logout
include('logout.php');
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

    <title>Car Share</title>
	<link href="styling.css" rel="stylesheet">
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDL6wkXjVSBbKqAljr1AX66Tyqk7evvCec&libraries=places">
	</script>
	<!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/humanity/jquery-ui.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  </head>
  <body>
	  <!--Navigation Bar-->
	  <?php
	  if(isset($_SESSION['user_id'])){
		  include('navigationbarconnected.php');
	  }else{
		  include('navigationbarnotconnected.php');
	  }
	  ?>
	  
	  <div class="container-fluid" id="myContainer">
		  <div class="row">
			  <div class="col-md-6 offset-md-3">
				  <h1>Plan your next trip now!</h1>
				  <p class="lead">Save Money! Save the Environment!</p>
				  <p class="bold">You can save up to 3000$ a year using car sharing!</p>
				  
				  <!--Search Form-->
				  <form class="row gy-2 align-items-center mx-auto" id="searchForm">
					  <div class="col-sm-5">
						  <label class="visually-hidden" for="departure">Departure:</label>
						  <input type="text" placeholder="Departure" name="departure" id="departure" class="form-control">
					  </div>
					  <div class="col-sm-5">
						  <label class="visually-hidden" for="departure">Destination:</label>
						  <input type="text" placeholder="Destination" name="destination" id="destination" class="form-control">
					  </div>
					  <div class="col-sm-2">
						  <input type="submit" value="Search" class="btn btn-lg green" name="search" class="form-control">
					  </div>
				  </form>
				  
				  <!--Google Map-->
				  <div id="googleMap"></div>
	
				  <!--trips-->
				  <div id="searchResults"></div>
				  
				  <!--Sign up button-->
				  <?php
					  if(!isset($_SESSION['user_id'])){
						  echo "<button class='btn btn-lg green signup' data-bs-toggle='modal' data-bs-target='#signupModal'>Sign up-It's free</button>"; 
					  }
	  			  ?>
				  
			  </div>
		  </div>
	  </div>
	  
	  
	  <!--Login form-->
	  <form method="post" id="loginform">
		  <div class="modal" id="loginModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-dialog">
				  <div class="modal-content">
					  <div class="modal-header">
						  <h4 id="myModalLabel">Login:</h4>
						  <button class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
					  </div>
					  <div class="modal-body">
						  
						  <!--Login message from PHP file-->
						  <div id="loginmessage"></div>
						  
						  <div class="mb-3">
							  <label for="loginemail" class="visually-hidden">Email</label>
							  <input type="email" class="form-control" name="loginemail" id="loginemail" placeholder="Email" maxlength="50">
						  </div>
						  <div class="mb-3">
							  <label for="loginpassword" class="visually-hidden">Password</label>
							  <input type="password" class="form-control" placeholder="Password" maxlength="30" name="loginpassword" id="loginpassword">
						  </div>
						  <div class="checkbox">
							  <label>
								  <input type="checkbox" name="rememberme" id="rememberme">
								  Remember me
							  </label>
							  <a class="float-end" style="cursor: pointer" data-bs-dismiss="modal" data-bs-target="#forgotpasswordModal" data-bs-toggle="modal">
							  Forgot Password?
						  	  </a>
						  </div>
						  
					  </div>
					  <div class="modal-footer justify-content-between">
						  <button type="button" class="btn btn-default" data-bs-dismiss="modal" data-bs-target="#signupModal" data-bs-toggle="modal">
							  Register
						  </button>
						  <div>
							  <input class="btn green" name="login" type="submit" value="Login">
						  	  <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancel</button>
						  </div>
						  
					  </div>
				  </div>
			  </div>
		  </div>
		  
	  </form>
	  
	  <!--Sign up form-->
	  <form method="post" id="signupform">
		  <div class="modal" id="signupModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-dialog">
				  <div class="modal-content">
					  <div class="modal-header">
						  <h4 id="myModalLabel">Sign up today and Start using our Car Share App!</h4>
						  <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
						  </button>
					  </div>
					  <div class="modal-body">
						  
						  <!--sign up message from PHP file-->
						  <div id="signupmessage"></div>
						  
						  <div class="mb-3">
							  <label for="username" class="visually-hidden">Username:</label>
							  <input type="text" class="form-control" name="username" placeholder="Username" maxlength="30" id="username">
						  </div>
						  <div class="mb-3">
							  <label for="firstname" class="visually-hidden">Firstname:</label>
							  <input type="text" class="form-control" name="firstname" placeholder="Firstname" maxlength="30" id="firstname">
						  </div>
						  <div class="mb-3">
							  <label for="lastname" class="visually-hidden">Lastname:</label>
							  <input type="text" class="form-control" name="lastname" placeholder="Lastname" maxlength="30" id="lastname">
						  </div>
						  <div class="mb-3">
							  <label for="email" class="visually-hidden">Email:</label>
							  <input type="email" class="form-control" name="email" placeholder="Email Address" maxlength="50" id="email">
						  </div>
						  <div class="mb-3">
							  <label for="password" class="visually-hidden">Choose a password:</label>
							  <input type="password" class="form-control" name="password" placeholder="Choose a password" maxlength="30" id="password">
						  </div>
						  <div class="mb-3">
							  <label for="password2" class="visually-hidden">Confirm password</label>
							  <input type="password" class="form-control" name="password2" placeholder="Confirm password" id="password2" maxlength="30">
						  </div>
						  <div class="mb-3">
							  <label for="phonenumber" class="visually-hidden">Telephone:</label>
							  <input type="text" class="form-control" name="phonenumber" placeholder="Telephone Number" maxlength="30" id="phonenumber">
						  </div>
						  <div class="mb-3">
							  <label><input type="radio" name="gender" id="male" value="male">Male</label>
							  <label><input type="radio" name="gender" id="female" value="female">Female</label>
						  </div>
						  <div class="mb-3">
							  <label for="moreinformation">Comments:</label>
							  <textarea name="moreinformation" class="form-control" id="moreinformation" rows="5" maxlength="300"></textarea>
						  </div>	  
					  </div>
					  <div class="modal-footer">
						  <input class="btn green" name="signup" type="submit" value="Sign up">
						  <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancel</button>
					  </div>
				  </div>
			  </div>
		  </div>
		  
	  </form>
	  
	  <!--Forgot password form-->
	  <form method="get" id="forgotpasswordform">
		  <div class="modal" id="forgotpasswordModal" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-dialog">
				  <div class="modal-content">
					  <div class="modal-header">
						  <h4 id="myModalLabel">
							  Forgot Password? Enter your email address:
						  </h4>
						  <button class="btn-close" data-bs-dismiss="modal">
						  </button>
					  </div>
					  <div class="modal-body">
						  
						  <!--forgot password message-->
						  <div id="forgotpasswordmessage"></div>
						  
						  <div class="mb-3">
							  <label for="forgotemail" class="visually-hidden">Email</label>
							  <input class="form-control" type="email" name="forgotemail" id="forgotemail" password="Email" maxlength="50" placeholder="Email">
						  </div>
					  </div>
					  <div class="modal-footer justify-content-between">
						  <button type="button" class="btn btn-default" data-bs-dismiss="modal" data-bs-target="#signupModal" data-bs-toggle="modal">
							  Register
						  </button>
						  <div>
							  <input class="btn green" name="login" type="submit" value="Login">
							  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						  </div>
					  </div>
				  </div>
			  </div>
		  </div>
	  </form>
	  
	  <!--Footer-->
	  <div class="footer mt-auto py-3">
		  <div class="container">
			  <p>benswenson.com Copyright &copy; <?php $today = date("Y"); echo $today ?></p>
		  </div>
	  </div>
	  <!--Spinner-->
	  <div id="spinner">
		  <img src="ajax-loader.gif" width='50' height='50'/>
		  <br /> Loading ..
	  </div>
	  
	  
    

    

    
	<script src="index.js"></script>
	<script src="map.js"></script>


   

   
  </body>
</html>