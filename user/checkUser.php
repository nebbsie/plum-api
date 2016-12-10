<?php


function checkIfUserExists($iusername){
  include('../db/connect.php');
  $SQLSEARCHUSER = "SELECT * FROM users WHERE username = '$iusername'";
	$result = $conn->query($SQLSEARCHUSER);

	if($result->num_rows > 0) {
		return true;
	} else {
		return false;
	}

}


 ?>
