<?php
  $servername = "localhost";
  $username = "root";
  $password = "Mwy197301242811";
  $dbname = "cuixianshu"; // 要操作的数据库名
  $outputData=array();
  // 创建连接 
  $conn= new mysqli($servername,$username,$password,$dbname); // 注意第四个参数
  if($conn->connect_error){
      die("连接失败，错误:" . $conn->connect_error);
  }



  $name=$_POST['name'];
  $id_for_ognztn=$_POST['id_for_ognztn'];
  $id_product_for=$_POST['id_product_for'];
  $price_basic=$_POST['price_basic'];
  $duration_basic=$_POST['duration_basic'];
  $scale_basic=$_POST['scale_basic'];
  $price_extra_duration=$_POST['price_extra_duration'];
  $price_extra_mileage=$_POST['price_extra_mileage'];
  $miss_meal_fee=$_POST['miss_meal_fee'];
  $id_creater=$_POST['id_operater'];
  $other=$_POST['other'];
  
  $sql_insert="insert into `tbl_rule_price` (name,id_for_ognztn,id_product_for,price_basic,duration_basic,scale_basic,price_extra_duration,price_extra_mileage,miss_meal_fee,id_creater,time_create,other) values (?,?,?,?,?,?,?,?,?,?,CURRENT_TIME(),?)";

  $stmt=$conn->prepare($sql_insert);
  $stmt->bind_param('siiddddddis',$name,$id_for_ognztn,$id_product_for,$price_basic,$duration_basic,$scale_basic,$price_extra_duration,$price_extra_mileage,$miss_meal_fee,$id_creater,$other);
  $result_insert=$stmt->execute();
  $stmt->free_result();
  $stmt->close();

    //是否全部成功执行
  if($result_insert) {
    echo json_encode(true);
  } else {
    echo json_encode(false);
  }


    $conn->close();
?>