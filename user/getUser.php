<?php
/*
	Returns a user using a posted username and password.
  TODO: make sure this is correct when is fully deployed.
*/
include('../db/connect.php');
require_once('../response/ErrorResponse.php');
require_once('../response/SuccessResponse.php');
require_once('../auth/checkAuth.php');
header('Content-Type: application/json');

$authenticated = false;

// Check if the username and password has been sent.
if(isset($_POST['email']) && isset($_POST['password']) ){
		// got username and password.
		$email = $_POST['email'];
		$password = $_POST['password'];

		if(isset($_POST['api']) && isset($_POST['token'])){
			// got api name and token.
			$apiname = $_POST['api'];
			$token = $_POST['token'];

			//Check if the user has send the correct authentication details.
			if(checkAuthentication($apiname, $token) === true){
				$authenticated = true;
			}else{
				$authenticated = false;
				$res = new ErrorResponse();
				echo $res->getError('authentication failed');
			}
		}
} else{
	// No post data has been sent.
	$res = new ErrorResponse();
	echo $res->getError('no post data recieved');
}

// If authenticated do the search for user.
if($authenticated === true){
	$SQLGETUSER = "SELECT * FROM users WHERE email = '$email'";
	$result = $conn->query($SQLGETUSER);
	$conn->close();

	// Have found users.
	if($result->num_rows > 0) {
		$users = array();
		while($row = $result->fetch_assoc()){
			$users[] = $row;
			if(strcmp($row['password'], $password)){
				// Password is incorrect.
				$res = new ErrorResponse();
				echo $res->getError('incorrect password');
      }else{
				// Password is correct.
				$res = new SuccessResponse();
				echo $res->getJSON($users);
      }
		}
	}else {
		// No users have been found.
		$res = new ErrorResponse();
		echo $res->getError('no user found');
	}
}
?>
