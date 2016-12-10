<?php

  class ErrorResponse {
    function getError($err){
      return json_encode(array("success" => "false" , "message" =>$err));
    }
  }

?>
