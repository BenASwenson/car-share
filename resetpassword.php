<!--This file receives the user_id and key generated to create the new password-->
<!--This file displays a form to input new password-->

<?php

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

    <title>Password Reset</title>
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
			<h1>Reset Password</h1>
			<div id="resultmessage"></div>
<?php
//If user_id or key is missing
if(!isset($_GET['user_id']) || !isset($_GET['key'])){
	echo '<div class="alert alert-danger">There was an error. Please click on the link you received by email.</div>'; exit;
}
//else
	//Store them in two variables
$user_id = $_GET['user_id'];
$key = $_GET['key'];
$time = time() - 86400;
	//Prepare variables for the query
$user_id = mysqli_real_escape_string($link, $user_id);
$key = mysqli_real_escape_string($link, $key);

	//Run Query: Check combination of user_id & key exists and less than 24h old
$sql = "SELECT user_id FROM forgotpassword WHERE rkey='$key' AND user_id='$user_id' AND time > '$time' AND status='pending'";
$result = mysqli_query($link, $sql);
if(!$result){
	echo '<div class="alert alert-danger">Error running the query!</div>'; exit;
}
//If combination does not exist
//show an error message
$count = mysqli_num_rows($result);
if($count !== 1){
	echo '<div class="alert alert-danger">Please try again.</div>';
	exit;
}
//print reset password form with hidden user_id and key fields
echo "
<form method=post id='passwordreset'>
<input type=hidden name=key value=$key>
<input type=hidden name=user_id value=$user_id>
<div class='mb-3'>
	<label for='password'>Enter your new Password:</label>
	<input type='password' name='password' id='password' placeholder='Enter Password' class='form-control'>
</div>
<div class='mb-3'>
	<label for='password2'>Re-enter your new Password:</label>
	<input type='password' name='password2' id='password2' placeholder='Re-enter Password' class='form-control'>
</div>
<input type='submit' name='resetpassword' class='btn btn-success btn-lg' value='Reset Password'>

</form>
";
			
?>
			
		</div>
	</div>

</div>
    

    
   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	  <!--Script for Ajax Call to storeresetpassword.php which processes form data-->
  <script>
	  $("#passwordreset").submit(function(event){
	//prevent default php processing
	event.preventDefault();
	//collect user inputs
	var datatopost = $(this).serializeArray();
	//send them to login.php using AJAX
	$.ajax({
		url: "storeresetpassword.php",
		type: "POST",
		data: datatopost,
		success: function(data){
			$('#resultmessage').html(data);
			
		},
		error: function(){
			$('#resultmessage').html("<div class='alert alert-danger'>There was an error with the Ajax Call. Please try again later.</div>");
		}
	});
})


  </script>


   
  </body>
</html>


