<?php  
  include_once 'linkToCXS.php';
  $outputData=array();
  $sql="select name_in_excel from tbl_dictionary_name_in_orders_excel where type_product_en='VECL'";

  $result = $conn->query($sql);

  if($result){
    while($arr=$result->fetch_assoc()){//fetch_array
      array_push($outputData,$arr);
    }
    echo json_encode($outputData);
  }
  $result->free();
  $conn->close();
?>