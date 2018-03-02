<?php
$user = "root";
$pass = "";
$db = "testdb";

// Create connection
$db = new mysqli( "localhost", $user, $pass, $db ) or die("Unable to connect");
$data = json_decode(file_get_contents("php://input"));
$result_inv = array();
if ( $data ){

  //echo gettype($data);
  //$_data = mysqli_real_escape_string($db, $data);
  //$_data = $data;

  $res = $db->query("SELECT `Order ID`,`Product ID` FROM `order_invoice` WHERE `Order ID` = '$data'");

  while( $row = $res->fetch_assoc() ){
    $result_inv[] = $row;
  }
}

if( $result_inv ){
  //print json_encode($result_inv);
}
$db->close();


?>
