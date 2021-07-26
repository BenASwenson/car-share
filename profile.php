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
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
      <meta charset="utf-8">
	  <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
	  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
<!--	<link href="css/bootstrap.min.css" rel="stylesheet">-->
	  <link rel="preconnect" href="https://fonts.gstatic.com">
	  <link href="https://fonts.googleapis.com/css2?family=Yusei+Magic&display=swap" rel="stylesheet">

      <title>Profile</title>
	  <link href="styling.css" rel="stylesheet">
	  <style>
		  #container{
			  margin-top: 120px;
		  }
		  #notepad, #done, #allNotes{
			  display: none;
		  }
		  .buttons{
			  margin-bottom: 20px;
		  }
		  textarea{
			  width: 100%;
			  max-width: 100%;
			  font-size: 18px;
			  line-height: 1.5em;
			  border-left-width: 20px;
			  border-color: #CA3DD9;
			  color: #CA3DD9;
			  background-color: #f4e8fc;
			  padding: 10px;
		  }
		  tr{
			  cursor: pointer;
		  }

		  .preview2{
			  height: auto;
			  max-width: 100%;
			  border-radius: 50%;
		  }
		  table tr{
			  color: white;
		  }
	  </style>
	  
  </head>
  <body>
	  <!--Navigation Bar-->
	  <nav class="navbar navbar-expand-md navbar-dark bg-custom-2">
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
						  <a class="nav-link active" aria-current="page" href="#">Profile</a>
					  </li>
					  <li class="nav-item">
						  <a class="nav-link" href="#">Help</a>
					  </li>
					  <li class="nav-item">
						  <a class="nav-link" href="#">Contact us</a>
					  </li>
					  <li class="nav-item">
						  <a class="nav-link" href="mainpageloggedin.php">My Trips</a>
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
	  <div class="container" id="container" style="margin-top: 25px;">
		  <div class="row">
			  <div class="col-md-6 offset-md-3 account">
				  <h2>General Account Settings:</h2>
				  <div class="table-responsive">
					  <table class="table table-hover table-sm table-bordered">
						  <tr data-bs-target="#updateusername" data-bs-toggle="modal">
							  <td>Username</td>
							  <td><?php echo $username; ?></td>
					  	  </tr>
						  <tr data-bs-target="#updateemail" data-bs-toggle="modal">
							  <td>Email</td>
							  <td><?php echo $email ?></td>
						  </tr>
						  <tr data-bs-target="#updatepassword" data-bs-toggle="modal">
							  <td>Password</td>
							  <td>hidden</td>
						  </tr>
					  </table>
				  </div>  
			  </div>
		  </div>
	  </div>
	  
	
	  
	  <!--Update username-->
	  <form method="post" id="updateusernameform">
		  <div class="modal" id="updateusername" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-dialog">
				  <div class="modal-content">
					  <div class="modal-header">
						  <h4 id="myModalLabel">Edit Username:</h4>
						  <button class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
					  </div>
					  <div class="modal-body">
						  
						  <!--Edit username message from PHP file-->
						  <div id="editusernamemessage"></div>
						  
						  <div class="mb-3">
							  <label for="username">Username:</label>
							  <input type="text" class="form-control" name="username" id="username" maxlength="30" value="<?php echo $username; ?>">
						  </div> 
					  </div>
					  <div class="modal-footer float-end">
						  
						  <div>
							  <input class="btn green" name="updateusername" type="submit" value="Submit">
						  	  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
						  </div>
						  
					  </div>
				  </div>
			  </div>
		  </div>		  
	  </form>
	  
	  <!--Update Email-->
	  <form method="post" id="updateemailform">
		  <div class="modal" id="updateemail" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-dialog">
				  <div class="modal-content">
					  <div class="modal-header">
						  <h4 id="myModalLabel">Enter new email:</h4>
						  <button class="btn-close" data-bs-dismiss="modal"></button>
					  </div>
					  <div class="modal-body">
						  
						  <!--update email message from php file-->
						  <div id="updateemailmessage"></div>
						  
						  <label for="email">Email</label>
						  <input type="email" id="email" class="form-control" name="email" maxlength="50" value="<?php echo $email ?>">
					  </div>
					  <div class="modal-footer">
						  <input class="btn green" type="submit" value="Submit" name="updateemail">
						  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
							  Cancel
						  </button>
					  </div>
				  </div>
			  </div>
		  </div>
	  </form>
	  
	  <!--Update password-->
	  <form method="post" id="updatepasswordform">
		  <div class="modal" id="updatepassword" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-dialog">
				  <div class="modal-content">
					  <div class="modal-header">
						  <h4 id="myModalLabel">Enter Current and New password:</h4>
						  <button class="btn-close" data-bs-dismiss="modal"></button>
					  </div>
					  <div class="modal-body">
						  
						  <!--update password message from php file-->
						  <div id="updatepasswordmessage"></div>
						  
						  <div class="mb-3">
							  <label for="currentpassword" class="visually-hidden">Your Current Password</label>
							  <input type="password" class="form-control" name="currentpassword" id="currentpassword" maxlength="30" placeholder="Your Current Password">
						  </div>
						  <div class="mb-3">
							  <label for="password" class="visually-hidden">Choose a Password:</label>
							  <input type="password" name="password" id="password" class="form-control" maxlength="30" placeholder="Choose a Password">
						  </div>
						  <div class="mb-3">
							  <label for="password2" class="visually-hidden">Confirm password:</label>
						  	  <input type="password" name="password2" id="password2" class="form-control" placeholder="Confirm password">
						  </div>
					  </div>
					  <div class="modal-footer">
						  <input class="btn green" type="submit" value="Submit" name="updatepassword">
						  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
					  </div>
				  </div>
			  </div>
		  </div>
	  </form>
	  
	  <!--update picture-->
	  <form method="post" id="updatepictureform" enctype="multipart/form-data">
		  <div class="modal" id="updatepicture" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-dialog">
				  <div class="modal-content">
					  <div class="modal-header">
						  <h4 id="myModalLabel">Upload Picture:</h4>
						  <button class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
					  </div>
					  <div class="modal-body">
						  
						  <!--Update picture message from PHP file-->
						  <div id="updatepicturemessage"></div>
						  
						  <?php
							if(empty($picture)){
								echo "<div class='image_preview'><img class='preview2' id='preview2' src='profilepicture/car.jpg'/></div>";
							}else{
								echo "<div class='image_preview'><img class='preview2' id='preview2' src='$picture'/></div>";
							}
						  ?>
						  
						  
						  
						  <div class="mb-3">
							  <label for="picture">Select a picture:</label>
							  <input type="file" class="form-control" name="picture" id="picture">
						  </div> 
					  </div>
					  <div class="modal-footer float-end">
						  
						  <div>
							  <input class="btn green" name="updateusername" type="submit" value="Submit">
						  	  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
						  </div>
						  
					  </div>
				  </div>
			  </div>
		  </div>		  
	  </form>
	  
	  <!--Footer-->
	  <footer class="footer mt-5 py-3 bg-primary" style="position: absolute; bottom: 0;">
		  <div class="container">
			  <p>benswenson.com Copyright &copy; <?php $today = date("Y"); echo $today ?></p>
		  </div>
	  </footer>
	  
	  
    

    

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="profile.js"></script>

   

   
  </body>
</html>