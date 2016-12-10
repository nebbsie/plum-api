<?php
/*
	Returns all of the users from the database.
  TODO: make sure this is correct when is fully deployed
*/
include('../db/connect.php');
require_once('../response/ErrorResponse.php');
require_once('../response/SuccessResponse.php');
header('Content-Type: application/json');

$authenticated = false;

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
}else{
		// No post data has been sent.
		//$res = new ErrorResponse();
	//	echo $res->getError('no post data recieved');

	//TODO: REMOVE THIS IN DEV
		$authenticated = true;
}

// If authenticated try and get the users.
if($authenticated === true){
		$SQLGETUSERS = "SELECT * FROM users";
		$result = $conn->query($SQLGETUSERS);
		$conn->close();

		// Have found users.
		if($result->num_rows > 0) {
			$users = array();

			// Add users to the users array to then return as JSON.
			while($row = $result->fetch_assoc())
				$users[] = $row;

				$res = new SuccessResponse();
				echo $res->getJSON($users);
		} else{
				// No users found.
				$res = new ErrorResponse();
				echo $res->getError("failed to load users");
		}
}


?>
