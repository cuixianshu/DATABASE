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

  $tel_work=$_POST['tel_work'];
  $pswd=$_POST['pswd'];

  // $sql="select * from tbl_employee where tel_work=? and pswd=MD5(?) and ISNULL(date_leave) and is_own=1";
  $sql="select *,(select name from tbl_employee where id=id_user) as name from tbl_user_authorization where id_user=(select id from tbl_employee where tel_work=? and pswd=MD5(?) and ISNULL(date_leave) and is_own=1)";
  $stmt=$conn->prepare($sql);
  $stmt->bind_param('ss',$tel_work,$pswd);
  $stmt->execute();
  $result = $stmt->get_result();
  
  if(!$result) {
    echo json_encode($conn->error);
    exit;
  }
  $i=0;
  while ($row = $result->fetch_assoc()) {
    $respondedData[$i]=$row;
    $i++;
  }    
  echo json_encode($respondedData);
  $stmt->free_result();
  $stmt->close();
  $conn->close();      

?>
