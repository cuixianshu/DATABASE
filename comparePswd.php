<?php  
  $servername = "localhost";
  $username = "root";
  $password = "Mwy197301242811";
  $dbname = "cuixianshu"; // 要操作的数据库名
  $respondedData=[];
  // 创建连接 
  $conn= new mysqli($servername,$username,$password,$dbname); // 注意第四个参数
  if($conn->connect_error){
    die("连接失败，错误:" . $conn->connect_error);
  }

  $id=$_POST['id'];
  $pswd=$_POST['pswd'];

  //IF(expr1,expr2,expr3)
  $sql="select IF(MD5(?)=pswd,1,0) as rslt_cmp from tbl_employee where id=?";

  $stmt=$conn->prepare($sql);
  $stmt->bind_param('si',$pswd,$id);
  $stmt->execute();
  $result = $stmt->get_result();
  
  if(!$result) {
    echo json_encode($conn->error);
    $stmt->free_result();
    $stmt->close();
    $conn->close();     
    exit;
  }

  $row = $result->fetch_assoc();
  $rslt_cmp=$row['rslt_cmp'];
  // $i=0;
  // while ($row = $result->fetch_assoc()) {
  //   $respondedData[$i]=$row;
  //   $i++;
  // }    
  echo json_encode($rslt_cmp);
  $stmt->free_result();
  $stmt->close();
  $conn->close();      

?>
