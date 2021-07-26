//create a geocoder obect to use geocode
var geocoder = new google.maps.Geocoder();
var data;

//Ajax Call for the sign up form
//Once the form is submitted
$("#signupform").submit(function(event){
	//show spinner
	$('#spinner').show();
	//hide results
	$('#signupmessage').hide();
	//prevent default php processing
	event.preventDefault();
	//collect user inputs
	var datatopost = $(this).serializeArray();
//	console.log(datatopost);
	//send them to signup.php using AJAX
	$.ajax({
		url: "signup.php",
		type: "POST",
		data: datatopost,
		success: function(data){
			if(data){
				//hide spinner
				$('#spinner').hide();
				$("#signupmessage").html(data);
				$('#signupmessage').slideDown();
			}	
		},
		error: function(){
			//hide spinner
			$('#spinner').hide();
			$("#signupmessage").html("<div class='alert alert-danger'>There was an error with the AJAX Call. Please try again later.</div>");
			$('#signupmessage').slideDown();
			
		}
		
	});
	
	
});
	


//Ajax Call for the login form
//Once the form is submitted
$("#loginform").submit(function(event){
	//show spinner
	$('#spinner').show();
	//hide results
	$('#loginmessage').hide();
	//prevent default php processing
	event.preventDefault();
	//collect user inputs
	var datatopost = $(this).serializeArray();
	//send them to login.php using AJAX
	$.ajax({
		url: "login.php",
		type: "POST",
		data: datatopost,
		success: function(data){
			//AJAX Call successful
			if(data == "success"){
				//if php files returns "success": redirect the user to notes page
				window.location = "mainpageloggedin.php";
			}else{
				//hide spinner
				$('#spinner').hide();
				//otherwise show error message
				$("#loginmessage").html(data);
				$('#loginmessage').slideDown();
			}
		},
		//AJAX Call fails: show Ajax Call error
		error: function(){
			//hide spinner
			$('#spinner').hide();
			$("#loginmessage").html("<div class='alert alert-danger'>There was an error with the Ajax Call. Please try again later.</div>");
			$('#loginmessage').slideDown();
		}
			
	});
	
})		

//Ajax Call for the forgot password form
//Once the form is submitted
$("#forgotpasswordform").submit(function(event){
	//show spinner
	$('#spinner').show();
	//hide results
	$('#forgotpasswordmessage').hide();
	//prevent default php processing
	event.preventDefault();
	//collect user inputs
	var datatopost = $(this).serializeArray();
	//send them to login.php using AJAX
	$.ajax({
		url: "forgotpassword.php",
		type: "POST",
		data: datatopost,
		success: function(data){
			//hide spinner
			$('#spinner').hide();
			$('#forgotpasswordmessage').html(data);
			$('#forgotpasswordmessage').slideDown();
			
		},
		error: function(){
			//hide spinner
			$('#spinner').hide();
			$('#forgotpasswordmessage').html("<div class='alert alert-danger'>There was an error with the Ajax Call. Please try again later.</div>");
			$('#forgotpasswordmessage').slideDown();
		}
	});
});


//submit the search form
$('#searchForm').submit(function(event){
	//show spinner
	$('#spinner').show();
	//hide results
	$('#searchResults').fadeOut();
	event.preventDefault();
	data = $(this).serializeArray();
	getSearchDepartureCoordinates();
});

//define functions
function getSearchDepartureCoordinates(){
	geocoder.geocode(
		{
		'address': document.getElementById("departure").value
		},
		function(results, status){
			if(status == google.maps.GeocoderStatus.OK){
				departureLongitude = results[0].geometry.location.lng();
				departureLatitude = results[0].geometry.location.lat();
				data.push({name:'departureLongitude', value: departureLongitude});
				data.push({name:'departureLatitude', value: departureLatitude});
				getSearchDestinationCoordinates();
			   
			}else{
				getSearchDestinationCoordinates();
			   
			}
		}
	
	);
	
}

function getSearchDestinationCoordinates(){
	geocoder.geocode(
		{
		'address': document.getElementById("destination").value
		},
		function(results, status){
			if(status == google.maps.GeocoderStatus.OK){
				destinationLongitude = results[0].geometry.location.lng();
				destinationLatitude = results[0].geometry.location.lat();
				data.push({name:'destinationLongitude', value: destinationLongitude});
				data.push({name:'destinationLatitude', value: destinationLatitude});
				submitSearchRequest();
			}else{
				submitSearchRequest();   
			}
		}	
	);	
}

function submitSearchRequest(){
	console.log(data);
	//send AJAX call to addtrips.php
	$.ajax({
		url: "search.php",
		type: "POST",
		data: data,
		success: function(returnedData){
			//hide spinner
			$('#spinner').hide();
			$('#searchResults').html(returnedData);
			$('#tripResults').accordion({
				active: false,
				collapsible: true,
				heightStyle: "content",
				icons: false
			});
			//show results
			$('#searchResults').fadeIn();
				
		},
		error: function(){
			//hide spinner
			$('#spinner').hide();
			$("#searchResults").html("<div class='alert alert-danger'>There was an error with the AJAX Call. Please try again later.</div>");
			//show results
			$('#searchResults').fadeIn();
			
		}
		
	});
}

	
	
	
		
		