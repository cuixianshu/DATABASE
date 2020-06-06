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
  if($_POST['conditions']==='NotApprovedOrNotPassedApproving') {
    $id_applyer=$_POST['id_applyer'];
    $sql="select distinct * from tbl_request_funds where is_paid=0 and (use_for like CONCAT('%',?,'%') or account like CONCAT('%',?,'%') or (id_project in (select id from tbl_project where name like CONCAT('%',?,'%')))) and (time_applied between ? and ?) and id_relative IS NULL and nature<>4 and id_applyer=?";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('sssssi',$keyWord,$keyWord,$keyWord,$start_date,$end_date,$id_applyer);
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

//退票 
  if($_POST['conditions']==='RefoundTktFee') {
    // $sql="select distinct * from tbl_request_funds where is_paid=0 and (use_for like CONCAT('%',?,'%') or account like CONCAT('%',?,'%') or (id_project in (select id from tbl_project where name like CONCAT('%',?,'%')))) and (time_applied between ? and ?) and id_relative IS NULL and nature=4";
    $sql="select distinct *,(select number_ticket from tbl_tickets where CONCAT('^^~',number_ticket,'~^^')=use_for) as number_ticket,(select name_psgr from tbl_tickets where CONCAT('^^~',number_ticket,'~^^')=use_for) as name_psgr,(select date_departure from tbl_tickets where CONCAT('^^~',number_ticket,'~^^')=use_for) as date_departure,(select number_flight from tbl_tickets where CONCAT('^^~',number_ticket,'~^^')=use_for) as number_flight,(select trip from tbl_tickets where CONCAT('^^~',number_ticket,'~^^')=use_for) as trip,(select amount_clctd-insurance-fee_refound-amount_actual_returned from tbl_tickets where CONCAT('^^~',number_ticket,'~^^')=use_for) as fee_need_return,(select price+tax from tbl_tickets where CONCAT('^^~',number_ticket,'~^^')=use_for) as price_include_tax,(select insurance from tbl_tickets where CONCAT('^^~',number_ticket,'~^^')=use_for) as insurance,(select fee_refound from tbl_tickets where CONCAT('^^~',number_ticket,'~^^')=use_for) as fee_refound from tbl_request_funds where is_paid=0 and (use_for like CONCAT('%',?,'%') or account like CONCAT('%',?,'%') or (id_project in (select id from tbl_project where name like CONCAT('%',?,'%'))) or use_for in (select CONCAT('^^~',number_ticket,'~^^') from tbl_tickets where name_psgr like CONCAT('%',?,'%')) or use_for in (select CONCAT('^^~',number_ticket,'~^^') from tbl_tickets where dptmt_client like CONCAT('%',?,'%'))) and (time_applied between ? and ?) and id_relative IS NULL and nature=4";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('sssssss',$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$start_date,$end_date);
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

  if($_POST['conditions']==='WithoutPrimaryAuditing') {
    $sql="select distinct *,(select name from tbl_project where id=id_project) as project,(select name from tbl_way_pay where id=id_way_pay) as way_pay,(select name from tbl_employee where id=id_applyer) as name_applyer from tbl_request_funds where result_approved IS NULL and (use_for like CONCAT('%',?,'%') or account like CONCAT('%',?,'%') or (id_project in (select id from tbl_project where name like CONCAT('%',?,'%')))) and (time_applied between ? and ?)";
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

  if($_POST['conditions']==='PassedPrimaryAuditingWithoutFinalAuditing') {
    $sql="select distinct *,(select name from tbl_project where id=id_project) as project,(select name from tbl_way_pay where id=id_way_pay) as way_pay,(select name from tbl_employee where id=id_applyer) as name_applyer,(select name from tbl_employee where id=id_approver) as name_approver from tbl_request_funds where result_approved=1 and result_approved2 IS NULL and (use_for like CONCAT('%',?,'%') or account like CONCAT('%',?,'%') or (id_project in (select id from tbl_project where name like CONCAT('%',?,'%')))) and (time_applied between ? and ?)";
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

  if($_POST['conditions']==='PassedAllApprovingAndNotPaid') {
    $sql="select *,(select name from tbl_project where id=id_project) as project,(select name from tbl_way_pay where id=id_way_pay) as way_pay,(select name from tbl_employee where id=id_applyer) as name_applyer,(select name from tbl_employee where id=id_approver) as name_approver,(select name from tbl_employee where id=id_approver2) as name_approver2 from tbl_request_funds where result_approved=1 and result_approved2=1 and (use_for like CONCAT('%',?,'%') or account like CONCAT('%',?,'%') or (id_project in (select id from tbl_project where name like CONCAT('%',?,'%')))) and (time_applied between ? and ?) and is_paid=0 and id not in (select id_rqst_funds from tbl_pay)";
    $stmt=$conn->prepare($sql);//
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

  if($_POST['conditions']==='HasPaidNotReviewed') {
    $sql="select *,(select name from tbl_project where id=id_project) as project,(select name from tbl_way_pay where id=id_way_pay) as way_pay,(select name from tbl_employee where id=id_applyer) as name_applyer,(select name from tbl_employee where id=id_approver) as name_approver,(select name from tbl_employee where id=id_approver2) as name_approver2 from tbl_request_funds where result_approved=1 and result_approved2=1 and (use_for like CONCAT('%',?,'%') or account like CONCAT('%',?,'%') or (id_project in (select id from tbl_project where name like CONCAT('%',?,'%')))) and (time_applied between ? and ?) and is_paid=1 and id in (select id_rqst_funds from tbl_pay where result_reviewed IS NULL)";
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
