<?php
//Development Connection
//$link = mysqli_connect("localhost", "benswens_CarShare", "andrew77", "benswens_CarShare");

//Remote Database Connection
$link = mysqli_connect("remotemysql.com", "uQff1yb2Cd", "mgOSYzNmFz", "uQff1yb2Cd");
if(mysqli_connect_error()){
	die("ERROR: Unable to connect: " . mysqli_connect_error());
}


?>