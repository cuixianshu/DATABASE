<?php  
  $servername = "localhost";
  $username = "root";
  $password = "Mwy197301242811";
  $dbname = "cuixianshu"; // 要操作的数据库名
    $outputData=[];//array()
  // 创建连接 
  $conn= new mysqli($servername,$username,$password,$dbname); // 注意第四个参数
  if($conn->connect_error){
      die("连接失败，错误:" . $conn->connect_error);
  }

  $sql="select * from tbl_user_authorization where id_user in (select id from tbl_employee where ISNULL(date_leave) and is_own=1)";


  $result = $conn->query($sql);
  $conn->close();

  if($result){
    while($arr=$result->fetch_assoc()){//fetch_array
      array_push($outputData,$arr);
    }
    header("Content-Type: text/html; charset=UTF-8");
    echo json_encode($outputData);
  }
  $result->free();
?>