<?php
$user_id = $_SESSION['user_id'];

//get username
$sql = "SELECT * FROM users WHERE user_id='$user_id'";
$result = mysqli_query($link, $sql);
$count = mysqli_num_rows($result);

if($count == 1){
	$row = mysqli_fetch_array($result, MYSQL_ASSOC);
	$username = $row['username'];
	$picture = $row['profilepicture'];
}else{
	echo '<div class="alert alert-danger">There was an error retrieving the username from the database.</div>';
}


?>

<nav class="navbar navbar-dark navbar-expand-md bg-custom-2">
	  <div class="container-fluid">
		  <a class="navbar-brand" href="#">Car Share</a>
		  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSuppportedContent" aria-expanded="false" aria-label="Toggle navigation">
			  <span class="navbar-toggler-icon"></span>
		  </button>
		  <div class="collapse navbar-collapse" id="navbarSupportedContent">
			  <ul class="navbar-nav me-auto mb-2 mb-lg-0">
				  <li class="nav-item">
					  <a class="nav-link active" aria-current="page" href="#">Search</a>
				  </li>
				  <li class="nav-item">
					  <a class="nav-link" href="profile.php">Profile</a>
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