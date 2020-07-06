<?php
  include_once 'linkToCXS.php';
  $id=$_POST['id'];
  $name=$_POST['name'];
  $id_for_ognztn=$_POST['id_for_ognztn'];
  $id_product_for=$_POST['id_product_for'];
  $price_basic=$_POST['price_basic'];
  $duration_basic=$_POST['duration_basic'];
  $scale_basic=$_POST['scale_basic'];
  $price_extra_duration=$_POST['price_extra_duration'];
  $price_extra_mileage=$_POST['price_extra_mileage'];
  $miss_meal_fee=$_POST['miss_meal_fee'];
  $id_modifyer=$_POST['id_operater'];
  $other=$_POST['other'];
  
  $sql_update="UPDATE `tbl_rule_price` SET `name`=?,`id_for_ognztn`=?,`id_product_for`=?,`price_basic`=?,`duration_basic`=?,`scale_basic`=?,`price_extra_duration`=?,`price_extra_mileage`=?,`miss_meal_fee`=?,`id_modifyer`=?,`time_modify`=CURRENT_TIME(),`other`=?  WHERE `id`=?";

  $stmt=$conn->prepare($sql_update);
  $stmt->bind_param('siiddddddisi',$name,$id_for_ognztn,$id_product_for,$price_basic,$duration_basic,$scale_basic,$price_extra_duration,$price_extra_mileage,$miss_meal_fee,$id_modifyer,$other,$id);
  $result_update=$stmt->execute();
  $stmt->free_result();
  $stmt->close();

    //是否全部成功执行
  if($result_update) {
    echo json_encode(true);
  } else {
    echo json_encode(false);
  }


	$conn->close();
?>