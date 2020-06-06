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

    if($_POST['conditions']==="All") {
      $keyWord=$_POST['keyWord'];
      $start_date=$_POST['dateRange'][0];
      $end_date=$_POST['dateRange'][1];
      $sql="select distinct * from tbl_turnin_funds where (cause like CONCAT('%',?,'%') or remark like CONCAT('%',?,'%') or id_project in (select id from tbl_project where name like CONCAT('%',?,'%'))) and (DATE_FORMAT(time_paid,'%Y-%m-%d') between ? and ?)";
      $stmt=$conn->prepare($sql);
      $stmt->bind_param('sssss',$keyWord,$keyWord,$keyWord,$start_date,$end_date);
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
    }

    if($_POST['conditions']==="NotCollected") {
      $keyWord=$_POST['keyWord'];
      $start_date=$_POST['dateRange'][0];
      $end_date=$_POST['dateRange'][1];
      $sql="select distinct * from tbl_turnin_funds where (cause like CONCAT('%',?,'%') or remark like CONCAT('%',?,'%') or id_project in (select id from tbl_project where name like CONCAT('%',?,'%'))) and (DATE_FORMAT(time_paid,'%Y-%m-%d') between ? and ?) and id_tbl_cashier IS NULL";
      $stmt=$conn->prepare($sql);
      $stmt->bind_param('sssss',$keyWord,$keyWord,$keyWord,$start_date,$end_date);
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
    }    

?>
