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
// exit;(result_approved IS NULL or result_approved=0)   
  if($_POST['conditions']==='NotOutBounded') {
    $sql="select * from tbl_tickets where date_outbound IS NULL and (name_psgr like CONCAT('%',?,'%') or number_ticket like CONCAT('%',?,'%') or dptmt_client like CONCAT('%',?,'%')) and (DATE_FORMAT(date_issued,'%Y-%m-%d') between ? and ?)";
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

//可以改签的票
  if($_POST['conditions']==='NotRefoundedAndNotDepartured') {
    $sql="select * from tbl_tickets where date_refound IS NULL and (name_psgr like CONCAT('%',?,'%') or number_ticket like CONCAT('%',?,'%') or dptmt_client like CONCAT('%',?,'%')) and (DATE_FORMAT(date_departure,'%Y-%m-%d') between ? and ?) and DATE_FORMAT(date_departure,'%Y-%m-%d')>=CURDATE() and date_clctd IS NULL";
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

//未支付退票费的退票
  if($_POST['conditions']==='BeenRefoundedAndNotPaidFeeAndCollected') {
    $sql="select * from tbl_tickets where date_refound IS NOT NULL and (name_psgr like CONCAT('%',?,'%') or number_ticket like CONCAT('%',?,'%') or dptmt_client like CONCAT('%',?,'%')) and (DATE_FORMAT(date_issued,'%Y-%m-%d') between ? and ?) and date_clctd IS NOT NULL and amount_clctd>=(price+tax+insurance) and amount_actual_returned=0 and fee_refound>0 and number_ticket not in (select substr(use_for, instr(use_for,'rfdTkdNum:')+10,instr(use_for,';')-(instr(use_for,'rfdTkdNum:')+10)) as num_tkt from tbl_request_funds where use_for like concat('%',number_ticket,'%') and use_for IS NOT NULL)";
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
//可以退的票  DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
  if($_POST['conditions']==='NotRefoundedAndNotUsed') {
    $sql="select * from tbl_tickets where date_refound IS NULL and INSTR(name_psgr,'(退)')=0 and DATE_SUB(DATE_FORMAT(date_issued,'%Y-%m-%d'),INTERVAL 12 MONTH) and (name_psgr like CONCAT('%',?,'%') or number_ticket like CONCAT('%',?,'%') or dptmt_client like CONCAT('%',?,'%')) and (DATE_FORMAT(date_issued,'%Y-%m-%d') between ? and ?) and date_clctd IS NULL";
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

  if($_POST['conditions']==='GetToCollect') {
// and date_clctd IS NULL
    $sql="select *,(price+tax+insurance) as amount_include_insurance from tbl_tickets where ((amount_clctd<(price+tax+insurance) and date_refound IS NULL) or (fee_refound>0 and amount_clctd_refound<fee_refound and amount_clctd<(fee_refound-amount_clctd_refound)) or (fee_change_trip>0 and amount_clctd_changing_fee<fee_change_trip) or (date_refound IS NOT NULL and insurance>0 and amount_clctd<insurance)) and (name_psgr like CONCAT('%',?,'%') or number_ticket like CONCAT('%',?,'%') or dptmt_client like CONCAT('%',?,'%')) and (DATE_FORMAT(date_departure,'%Y-%m-%d') between ? and ?) and date_outbound IS NOT NULL";
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
  // if($_POST['conditions']==='WithoutPrimaryAuditing') {
  //   $sql="select distinct *,(select name from tbl_project where id=id_project) as project,(select name from tbl_way_pay where id=id_way_pay) as way_pay,(select name from tbl_employee where id=id_applyer) as name_applyer from tbl_request_funds where result_approved IS NULL and (use_for like CONCAT('%',?,'%') or account like CONCAT('%',?,'%') or (id_project in (select id from tbl_project where name like CONCAT('%',?,'%')))) and (time_applied between ? and ?)";
  //   $stmt=$conn->prepare($sql);
  //   $stmt->bind_param('sssss',$keyWord,$keyWord,$keyWord,$start_date,$end_date);
  //   $stmt->execute();
  //   $result = $stmt->get_result();
  //   if(!$result) {
  //     echo json_encode($conn->error);
  //     exit;
  //   }
  //   $i=0;
  //   while ($row = $result->fetch_assoc()) {
  //       $respondedData[$i]=$row;
  //       $i++;
  //   }    
  //   echo json_encode($respondedData);
  //   $stmt->free_result();
  //   $stmt->close();
  //   $conn->close();        
  // }

  // if($_POST['conditions']==='PassedPrimaryAuditingWithoutFinalAuditing') {
  //   $sql="select distinct *,(select name from tbl_project where id=id_project) as project,(select name from tbl_way_pay where id=id_way_pay) as way_pay,(select name from tbl_employee where id=id_applyer) as name_applyer,(select name from tbl_employee where id=id_approver) as name_approver from tbl_request_funds where result_approved=1 and result_approved2 IS NULL and (use_for like CONCAT('%',?,'%') or account like CONCAT('%',?,'%') or (id_project in (select id from tbl_project where name like CONCAT('%',?,'%')))) and (time_applied between ? and ?)";
  //   $stmt=$conn->prepare($sql);
  //   $stmt->bind_param('sssss',$keyWord,$keyWord,$keyWord,$start_date,$end_date);
  //   $stmt->execute();
  //   $result = $stmt->get_result();
  //   if(!$result) {
  //     echo json_encode($conn->error);
  //     exit;
  //   }
  //   $i=0;
  //   while ($row = $result->fetch_assoc()) {
  //       $respondedData[$i]=$row;
  //       $i++;
  //   }    
  //   echo json_encode($respondedData);
  //   $stmt->free_result();
  //   $stmt->close();
  //   $conn->close();        
  // }

  // if($_POST['conditions']==='PassedAllApprovingAndNotPaid') {
  //   $sql="select *,(select name from tbl_project where id=id_project) as project,(select name from tbl_way_pay where id=id_way_pay) as way_pay,(select name from tbl_employee where id=id_applyer) as name_applyer,(select name from tbl_employee where id=id_approver) as name_approver,(select name from tbl_employee where id=id_approver2) as name_approver2 from tbl_request_funds where result_approved=1 and result_approved2=1 and (use_for like CONCAT('%',?,'%') or account like CONCAT('%',?,'%') or (id_project in (select id from tbl_project where name like CONCAT('%',?,'%')))) and (time_applied between ? and ?) and is_paid=0 and id not in (select id_rqst_funds from tbl_pay)";
  //   $stmt=$conn->prepare($sql);//
  //   $stmt->bind_param('sssss',$keyWord,$keyWord,$keyWord,$start_date,$end_date);
  //   $stmt->execute();
  //   $result = $stmt->get_result();
  //   if(!$result) {
  //     echo json_encode($conn->error);
  //     exit;
  //   }
  //   $i=0;
  //   while ($row = $result->fetch_assoc()) {
  //       $respondedData[$i]=$row;
  //       $i++;
  //   }    
  //   echo json_encode($respondedData);
  //   $stmt->free_result();
  //   $stmt->close();
  //   $conn->close();        
  // }

  // if($_POST['conditions']==='HasPaidNotReviewed') {
  //   $sql="select *,(select name from tbl_project where id=id_project) as project,(select name from tbl_way_pay where id=id_way_pay) as way_pay,(select name from tbl_employee where id=id_applyer) as name_applyer,(select name from tbl_employee where id=id_approver) as name_approver,(select name from tbl_employee where id=id_approver2) as name_approver2 from tbl_request_funds where result_approved=1 and result_approved2=1 and (use_for like CONCAT('%',?,'%') or account like CONCAT('%',?,'%') or (id_project in (select id from tbl_project where name like CONCAT('%',?,'%')))) and (time_applied between ? and ?) and is_paid=1 and id in (select id_rqst_funds from tbl_pay where result_reviewed IS NULL)";
  //   $stmt=$conn->prepare($sql);
  //   $stmt->bind_param('sssss',$keyWord,$keyWord,$keyWord,$start_date,$end_date);
  //   $stmt->execute();
  //   $result = $stmt->get_result();
  //   if(!$result) {
  //     echo json_encode($conn->error);
  //     exit;
  //   }
  //   $i=0;
  //   while ($row = $result->fetch_assoc()) {
  //       $respondedData[$i]=$row;
  //       $i++;
  //   }    
  //   echo json_encode($respondedData);
  //   $stmt->free_result();
  //   $stmt->close();
  //   $conn->close();        
  // }
?>
