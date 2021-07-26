<?php
session_start();
include('connection.php');

//define error messages
$missingDeparture = '<p><strong>Please enter your departure!</strong></p>';
$invalidDeparture = '<p><strong>Please enter a valid departure!</strong></p>';
$missingDestination = '<p><strong>Please enter your destination!</strong></p>';
$invalidDestination = '<p><strong>Please enter a valid destination!</strong></p>';
$missingPrice = '<p><strong>Please choose a price per seat!</strong></p>';
$invalidPrice = '<p><strong>Please choose a valid price per seat using numbers only!</strong></p>';
$missingSeatsAvailable = '<p><strong>Please select the number of available seats!</strong></p>';
$invalidSeatsAvailable = '<p><strong>The number of seats available should contain digits only!</strong></p>';
$missingFrequency = '<p><strong>Please select a frequency!</strong></p>';
$missingDays = '<p><strong>Please select at least one weekday!</strong></p>';
$missingDate = '<p><strong>Please choose a date for your trip!</strong></p>';
$missingTime = '<p><strong>Please choose a time for your trip!</strong></p>';

//Get inputs:
$departure = $_POST['departure'];
$destination = $_POST['destination'];
$price = $_POST['price'];
$seatsavailable = $_POST['seatsavailable'];
$regular = $_POST['regular'];
$date = $_POST['date'];
$time = $_POST['time'];
$monday = $_POST['monday'];
$tuesday = $_POST['tuesday'];
$wednesday = $_POST['wednesday'];
$thursday = $_POST['thursday'];
$friday = $_POST['friday'];
$saturday = $_POST['saturday'];
$sunday = $_POST['sunday'];

//check departure
if(empty($departure)){
	$errors .= $missingDeparture;
}else{
	//check coordinates
	if(!isset($_POST['departureLatitude']) or !isset($_POST['departureLongitude'])){
		$errors .= $invalidDeparture;
	}else{
		$departureLatitude = $_POST['departureLatitude'];
		$departureLongitude = $_POST['departureLongitude'];
		$departure = filter_var($departure, FILTER_SANITIZE_STRING);
	}
}

//check destination
if(empty($destination)){
	$errors .= $missingDestination;
}else{
	//check coordinates
	if(!isset($_POST['destinationLatitude']) or !isset($_POST['destinationLongitude'])){
		$errors .= $invalidDestination;
	}else{
		$destinationLatitude = $_POST['destinationLatitude'];
		$destinationLongitude = $_POST['destinationLongitude'];
		$destination = filter_var($destination, FILTER_SANITIZE_STRING);
	}
}

//check price
if(empty($price)){
	$errors .= $missingPrice;
}elseif(preg_match('/\D/', $price)){
	$errors .= $invalidPrice;
}else{
	$price = filter_var($price, FILTER_SANITIZE_STRING);
}

//check seats available
if(empty($seatsavailable)){
	$errors .= $missingSeatsAvailable;
}elseif(preg_match('/\D/', $seatsavailable)){
	$errors .= $invalidSeatsAvailable;
}else{
	$seatsavailable = filter_var($seatsavailable, FILTER_SANITIZE_STRING);
}

//check frequency
if(empty($regular)){
	$errors .= $missingFrequency;
}elseif($regular == "Y"){
	if(empty($monday) && empty($tuesday) && empty($wednesday) && empty($thursday) && empty($friday) && empty($saturday) && empty($sunday)){
		$errors .= $missingDays;
	}
	if(empty($time)){
		$errors .= $missingTime;
	}
}elseif($regular == "N"){
	if(empty($date)){
		$errors .= $missingDate;
	}
	if(empty($time)){
		$errors .= $missingTime;
	}
}

//if there is an error and print an error message
if($errors){
	$resultMessage = "<div class='alert alert-danger'>$errors</div>";
	echo $resultMessage;
}else{
	//no errors, prepare variables to the query
	$departure = mysqli_real_escape_string($link, $departure);
	$destination = mysqli_real_escape_string($link, $destination);
	$tblName = 'carsharetrips';
	$user_id = $_SESSION['user_id'];
	if($regular == "Y"){
		//query for a regular trip
		$sql = "INSERT INTO $tblName (`user_id`, `departure`, `departureLongitude`, `departureLatitude`, `destination`, `destinationLongitude`, `destinationLatitude`, `price`, `seatsavailable`, `regular`, `monday`, `tuesday`, `wednesday`, `thursday`, `friday`, `saturday`, `sunday`, `time`) VALUES ('$user_id', '$departure', '$departureLongitude', '$departureLatitude', '$destination', '$destinationLongitude', '$destinationLatitude', '$price', '$seatsavailable', '$regular', '$monday', '$tuesday', '$wednesday', '$thursday', '$friday', '$saturday', '$sunday', '$time')";
	}else{
		//query for a one-off trip
		$sql = "INSERT INTO $tblName (`user_id`, `departure`, `departureLongitude`, `departureLatitude`, `destination`, `destinationLongitude`, `destinationLatitude`, `price`, `seatsavailable`, `regular`, `date`, `time`) VALUES ('$user_id', '$departure', '$departureLongitude', '$departureLatitude', '$destination', '$destinationLongitude', '$destinationLatitude', '$price', '$seatsavailable', '$regular', '$date', '$time')";
	}
	
	$result = mysqli_query($link, $sql);
	//check if query is successful
	if(!$result){
		echo "<div class='alert alert-danger'>There was an error. The trip could not be added to the database!</div>";
	}
}





?>