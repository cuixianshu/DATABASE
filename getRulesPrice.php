<?php  
  include_once 'linkToCXS.php';
  $outputData=array();
  $sql="select id,name,id_for_ognztn,id_product_for,price_basic,duration_basic,scale_basic,price_extra_duration,price_extra_mileage,miss_meal_fee,usable,other,(select short_name from tbl_client_parent_ognztn where id=id_for_ognztn) as name_ognztn from tbl_rule_price order by convert(name using gbk) asc";
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