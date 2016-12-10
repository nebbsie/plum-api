<?php

  function checkAuthentication($api, $token){
    include('../db/connect.php');

    $SQLGETAUTH = "SELECT * FROM apiauth WHERE username = '$api'";
    $result = $conn->query($SQLGETAUTH);
    $conn->close();

    while($row = $result->fetch_assoc()){
      if(strcmp($row['token'], $token)){
        return false;
      }else{
        return true;
      }
    }
  }

 ?>
