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
  $id=$_POST['id'];
  $name=$_POST['name'];
  $contacter_and_tel=$_POST['contacter'];
  $name_part_a=$_POST['partner'];
  $content=$_POST['contents'];
  $time_start=$_POST['startDate'];
  $time_end=$_POST['endDate'];
  $is_finished=$_POST['is_finished'];
  $address_of_project=$_POST['address'];
  $id_manager=$_POST['id_manager'];
  $id_contract=$_POST['id_contract'];
  $scale=$_POST['scale'];
  $other=$_POST['remark'];
  
  $sql_insert="INSERT INTO `tbl_project` (`name`,`contacter_and_tel`,`name_part_a`,`content`,`time_start`,`time_end`,`is_finished`,`address_of_project`,`id_manager`,`id_contract`,`scale`,`other`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
  $stmt=$conn->prepare($sql_insert);
  $stmt->bind_param('ssssssisiiss',$name,$contacter_and_tel,$name_part_a,$content,$time_start,$time_end,$is_finished,$address_of_project,$id_manager,$id_contract,$scale,$other);
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