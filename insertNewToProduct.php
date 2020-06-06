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
  $unit=$_POST['unit'];
  $type=$_POST['id_type'];
  $model=$_POST['model'];
  $details=$_POST['details'];
  $other=$_POST['other'];
  
  $sql_insert="insert into `tbl_product` (name,unit,id_type,model,details,other) values (?,?,?,?,?,?)";

  $stmt=$conn->prepare($sql_insert);
  $stmt->bind_param('ssisss',$name,$unit,$type,$model,$details,$other);
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