<?php  
  include_once 'linkToCXS.php';
  $respondedData=[];
//付款复核
  if($_POST['conditions']==='HasPaidNotReviewed') {
    $keyWord=$_POST['keyWord'];
    $start_date=$_POST['dateRange'][0];
    $end_date=$_POST['dateRange'][1];

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
  }
//财务付款报表
  if($_POST['conditions']==='ForPayReport') {
    $keyWord=$_POST['keyWord'];
    $start_time=$_POST['dateRange'][0];
    $end_time=$_POST['dateRange'][1];
    $id_account=$_POST['acnt'];
    $id_way_pay=$_POST['way'];
    $id_applyer=$_POST['id_applyer'];//tbl_requst_founs
    $id_project=$_POST['id_project'];//tbl_requst_founs
    $nature=$_POST['id_nature'];//tbl_requst_founs
    // $numbers_bills=$_POST['num_bills'];
    // $account=$_POST['rcptAccount'];//tbl_requst_founs
    // $serial_paid=$_POST['serial_paid'];
    // $use_for=$_POST['use_for'];//tbl_requst_founs

    $sql="select p.*,r.id_applyer as r_id_applyer,r.nature as r_nature,r.id_project as r_id_project,r.account as r_account,r.use_for as r_use_for,r.remark as r_remark from tbl_pay p INNER JOIN tbl_request_funds r on p.id_rqst_funds=r.id where (p.time_paid between STR_TO_DATE('".$start_time."','%Y-%m-%d %H:%i:%s') and STR_TO_DATE('".$end_time."','%Y-%m-%d %H:%i:%s'))";
    if($id_account!=0) {
      $sql.=" and (p.id_account=".$id_account.")";
    }
    if($id_way_pay!=0) {
      $sql.=" and (p.id_way_pay=".$id_way_pay.")";
    }
    if($id_applyer!=0) {
      $sql.=" and (r.id_applyer=".$id_applyer.")";
    }
    if($id_project!=0) {
      $sql.=" and (r.id_project=".$id_project.")";
    }
    if($nature!=0) {
      $sql.=" and (r.nature=".$nature.")";
    }
    $sql.=" and (p.numbers_bills like CONCAT('%',?,'%') or r.account like CONCAT('%',?,'%')";
    $sql.="  or p.remark like CONCAT('%',?,'%') or r.use_for like CONCAT('%',?,'%') or r.remark like CONCAT('%',?,'%') or p.serial_paid like CONCAT('%',?,'%'));";
// echo $sql;
// exit;
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('ssssss',$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$keyWord);
    $stmt->execute();
    $result = $stmt->get_result();

    $i=0;
    while ($row = $result->fetch_assoc()) {
        $respondedData[$i]=$row;
        $i++;
    }    
    echo json_encode($respondedData);

    $result->free();
    $stmt->close();     
  }
  
  $conn->close();        
?>
