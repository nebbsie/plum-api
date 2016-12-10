<?php
/*
	Returns a user using a posted username and password.
  TODO: make sure this is correct when is fully deployed.
*/
include('../db/connect.php');
require_once('../response/ErrorResponse.php');
require_once('../response/SuccessResponse.php');
require_once('../auth/checkAuth.php');
require_once('checkUser.php');
header('Content-Type: application/json');

$authenticated = false;

// Check if the username and password has been sent.
if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['password-verify']) && isset($_POST['fname']) && isset($_POST['lname']) && isset($_POST['email']) ){
		// got username and password.
		$username = $_POST['username'];
		$password = $_POST['password'];
		$password_verify = $_POST['password-verify'];
		$fname = $_POST['fname'];
		$lname = $_POST['lname'];
		$email = $_POST['email'];

		// Check if the passwords are the same.
		if(strcmp($password, $password_verify)){
			$res = new ErrorResponse();
			echo $res->getError('passwords dont match');
		}else{
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
		}
}else{
	// No post data has been sent.
	$res = new ErrorResponse();
	echo $res->getError('no post data recieved');
}

// If authenticated do the search for user.
if($authenticated === true){
	$sqlCreateUser = "INSERT INTO users (username, email ,password, fname, lname)
	 								  VALUES ('$username' ,'$email' ,'$password', '$fname', '$lname')";
	// Checks if the user is created if it has not it will create a user.
  if(checkIfUserExists($username)){
		$res = new ErrorResponse();
		echo $res->getError('user with that name exists');
	}else{
		if(mysqli_query($conn, $sqlCreateUser)) {
			$res = new SuccessResponse();
			echo $res->getJSON("successfully created user");
		} else {
			$res = new ErrorResponse();
			echo $res->getError('error creating user');
		}
	}
}
?>
