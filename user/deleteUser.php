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
if(isset($_POST['username']) && isset($_POST['password'])){
		// got username and password.
		$username = $_POST['username'];
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
}else{
	// No post data has been sent.
	$res = new ErrorResponse();
	echo $res->getError('no post data recieved');
}

// If authenticated do the search for user.
if($authenticated === true){
	$SQLDELETEUSER = "DELETE FROM users WHERE username = '$username' AND password = '$password' ";

	// Checks if user exists then executes the query.
	if(checkIfUserExists($username)){
		if(mysqli_query($conn, $SQLDELETEUSER)) {
			$res = new SuccessResponse();
			echo $res->getJSON("successfully deleted user");
		} else {
			$res = new ErrorResponse();
			echo $res->getError('error deleting user');
		}
	} else{
		// User does not exist.
		$res = new ErrorResponse();
		echo $res->getError('user does not exist');
	}

}
?>
