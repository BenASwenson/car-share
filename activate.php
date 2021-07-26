<?php
//The user is re-directed to this file after clicking the activation link
//Signup link contains two GET parameters: email and activation key
session_start();
include('connection.php');
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <title>Account Activation</title>
	<style>
		h1{
			color:purple;
		}
		.contactForm{
			border: 1px solid #6e60c7;
			margin-top: 50px;
			border-radius: 15px;
		}
	</style>
  </head>
  <body>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-10 offset-sm-1 contactForm">
			<h1>Account Activation</h1>
<?php
//If email or activation key is missing show an error
if(!isset($_GET['email']) || !isset($_GET['key'])){
	echo '<div class="alert alert-danger">There was an error. Please click on the activation link you received by email.</div>'; exit;
}
//else
	//Store them in two variables
$email = $_GET['email'];
$key = $_GET['key'];
	//Prepare variables for the query
$email = mysqli_real_escape_string($link, $email);
$key = mysqli_real_escape_string($link, $key);
	//Run query: set activation field to "activated" for the provided email
$sql = "UPDATE users SET activation= 'activated' WHERE (email='$email' AND activation= '$key') LIMIT 1";
$result = mysqli_query($link, $sql);
			
	//If query is successful, show success message and invite user to login
if(mysqli_affected_rows($link) == 1){
	echo "<div class='alert alert-success'>Your account has been activated.</div>";
	echo '<a href="index.php" type="button" class="btn-lg btn-success">Login</a>';
	
}else{
	//Show error message
	echo "<div class='alert alert-danger'>Your account could not be activated. Please try again later.</div>";
//	echo "<div class='alert alert-danger'>" . mysqli_error($link) . "</div>";

}
			
?>
			
		</div>
	</div>

</div>
    

    
   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

   
  </body>
</html>