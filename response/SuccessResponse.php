<?php

  class SuccessResponse {
    function getJSON($data){
      return json_encode(array("success" => "true" , "data" =>$data));
    }

  }

?>
