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
  $keyWord=$_POST['keyWord'];
  $start_date=$_POST['dateRange'][0];
  $end_date=$_POST['dateRange'][1];
// echo json_encode($_POST);
// exit;

  if($_POST['conditions']==='HasPaidNotReviewed') {
    $sql="select *,(select id_project from tbl_request_funds where id=tbl_pay.id_rqst_funds) as id_project,(select amount from tbl_request_funds where id=tbl_pay.id_rqst_funds) as amount_rqsted,(select account from tbl_request_funds where id=tbl_pay.id_rqst_funds) as account_rqsted,(select use_for from tbl_request_funds where id=tbl_pay.id_rqst_funds) as use_for,(select id_relative from tbl_request_funds where id=tbl_pay.id_rqst_funds) as id_relative,(select id_applyer from tbl_request_funds where id=tbl_pay.id_rqst_funds) as id_applyer,(select name from tbl_project where id=(select id_project from tbl_request_funds where id=tbl_pay.id_rqst_funds)) as project,(select name from tbl_way_pay where id=id_way_pay) as way_pay,(select name from tbl_employee where id=(select id_applyer from tbl_request_funds where id=id_rqst_funds)) as applyer,(select name from tbl_employee where id=id_cashier) as cashier from tbl_pay where (result_reviewed IS NULL or result_reviewed=0) and (time_paid between ? and ?)";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('ss',$start_date,$end_date);
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
