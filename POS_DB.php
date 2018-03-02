<?php

$user = "root";
$pass = "";
$db = "testdb";

// Create connection
$db = new mysqli( "localhost", $user, $pass, $db ) or die("Unable to connect");

$res = $db->query("SELECT * FROM `stock file`");
$result = array();
//$result = $res->mysqli_fetch_all(MYSQLI_ASSOC);
while( $row = $res->fetch_assoc() ){
  // $result[$row['Item']] = $row['Price'];
  $result[] = $row;
  //$row['Price'] = 'Stock';
}

print "\n\n";
print json_encode($result);

$data = json_decode(file_get_contents("php://input"));
//var_dump($data);

//while($row = $result->fetch_array())

if (is_array($data) || is_object($data)){

  print("   STreaming data from order...    ...     ...");
  print_r ($data);

  $date = date("Y-m-d ");
  print "\n".$date;

  $sql = "INSERT INTO `order_tbl` (`Date_of_Purchase`)
  VALUES ('$date')";

  $db->query($sql);

  $last_order_id = $db->insert_id;

  print "\n  Last ID of order_tbl: ".$last_order_id."     ";

  foreach ($data as $key => $value) {
    if($value->Total == TRUE){    //Entering total into order tbl

      $total = $value->Total;
      //$sql = "SELECT `Total` FROM `order_tbl` WHERE `ID`= '$last_order_id' ";
      print " \n        Total spent: ".$total;

      print " \n  Confirming invoice tbl ID for total entry: ".$last_order_id;

      $db->query("UPDATE `order_tbl` SET `Total` = $total WHERE `ID` = '$last_order_id' ");
      break;
    }

    print " \n ID: ".$value->ID;
    print " \n Item: ".$value->Item;
    print " \n Price: ".$value->Price;
    print " \n Stock: ".$value->Stock;


    $sql = "SELECT `Stock` FROM `stock file` WHERE `ID`= '$value->ID' ";

    if($result1 = $db->query($sql)){
      print "     AHA SUCCESS!!!     ";
      $res1 = $result1->fetch_assoc();
      $res1['Stock']++;
      $a = $res1['Stock'];
      $sql = "UPDATE `stock file` SET `Stock` = $a WHERE `Item` = '$value->Item' ";
      if($result2 = $db->query($sql)){
        print "   Passing info into Database    ";
      }
      $product_id = $db->query("SELECT `ID` FROM `stock file` WHERE `Item`= '$value->Item'");
      $product_id = $product_id->fetch_assoc();
      $product_id = $product_id['ID'];

      Print "\n   Acquiring Order ID:   ".$last_order_id."    ";
      print "\n   Acquiring product ID:  ".$product_id."    ";
      print "\n   Now acquiring date:   ".$date."     \n";

      $db->query("INSERT INTO `order_invoice` (`Order ID`, `Product ID`, `Date_order`)
      VALUES ($last_order_id, $product_id, '$date')");
    }
  }
}//print json_decode($_POST['json']);

//***********WARNING DO NOT USE ECHO STATEMENTS*******//
//***********THEY MESS WITH THE JSON parse
//http://localhost/POS_Proj/
$db->close();
?>
