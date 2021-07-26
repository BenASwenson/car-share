<?php
//The user is re-directed to this file after clicking the link received by email and aiming at proving they own the new email address
//link contains three GET parameters: email, new email and activation key
session_start();
include('connection.php');
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <title>New Email Activation</title>
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
			<h1>Email Activation</h1>
<?php
//If email, new email or activation key is missing show an error
if(!isset($_GET['email']) || !isset($_GET['newemail']) || !isset($_GET['key'])){
	echo '<div class="alert alert-danger">There was an error. Please click on the link you received by email.</div>'; exit;
}
//else
	//Store them in three variables
$email = $_GET['email'];
$newemail = $_GET['newemail'];
$key = $_GET['key'];
	//Prepare variables for the query
$email = mysqli_real_escape_string($link, $email);
$newemail = mysqli_real_escape_string($link, $newemail);
$key = mysqli_real_escape_string($link, $key);
			
	//Run query: update email
$sql = "UPDATE users SET email='$newemail', activation2='0' WHERE (email='$email' AND activation2='$key') LIMIT 1";
$result = mysqli_query($link, $sql);
			
	//If query is successful, show success message 
if(mysqli_affected_rows($link) == 1){
	session_destroy();
	setcookie("rememberme", "", time()-3600);
	echo "<div class='alert alert-success'>Your email has been updated!</div>";
	echo '<a href="index.php" type="button" class="btn-lg btn-success">Login</a>';
	
}else{
	//Show error message
	echo "<div class='alert alert-danger'>Your email could not be updated. Please try again later.</div>";
	echo "<div class='alert alert-danger'>" . mysqli_error($link) . "</div>";

}
			
?>
			
		</div>
	</div>

</div>
    

    
   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

   
  </body>
</html>