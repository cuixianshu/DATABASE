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

  // echo json_encode($_POST);
  // exit;

  if($_POST['conditions']=='isNotFinishedWithState') {
    $sql="select *,(select count(id) from tbl_enquiry_price where id_applied_purchasing=tbl_apply_purchasing.id) as count_enquiries,(select is_commited_to_approve from tbl_enquiry_price where id_applied_purchasing=tbl_apply_purchasing.id LIMIT 1) as state_of_enquiries from `tbl_apply_purchasing` where `is_finished`=0 order by `date_applied`";
  }
  /*
  $sql="select *,(select count(id) from tbl_enquiry_price where id_applied_purchasing=tbl_apply_purchasing.id) as count_enquiries,(select is_commited_to_approve from tbl_enquiry_price where id_applied_purchasing=tbl_apply_purchasing.id LIMIT 1) as state_of_enquiries from `tbl_apply_purchasing` where `is_finished`=0 order by `date_applied`";
   */
  if($_POST['conditions']=='EnquiryCommitedNotApprovedAndComparedPrice') {
    $sql="select * from `tbl_apply_purchasing` where `is_finished`=0 and (id in (select id_applied_purchasing from tbl_enquiry_price where is_commited_to_approve<>2 or is_commited_to_approve is null)) order by `date_applied`";
  }
  if($_POST['conditions']=='NotComparedOrEnquiryNotCommitedOrNotPassedApproving') {
    $sql="select * ,(select is_commited_to_approve from tbl_enquiry_price where id_applied_purchasing=tbl_apply_purchasing.id limit 1) as result_of_approved_comparing from `tbl_apply_purchasing` where `result_approved`=1 and `is_finished`=0 and ((id in (select id_applied_purchasing from tbl_enquiry_price where is_commited_to_approve <>2 or is_commited_to_approve is null)) or (id not in (select id_applied_purchasing from tbl_enquiry_price))) order by `date_applied`";
    /*
      
 and (id not in (select id_applied from tbl_approved_enquiry_compare_price))
      "select * from `tbl_apply_purchasing` where `result_approved`=1 and `is_finished`=0 and ((id not in (select id_applied_purchasing from tbl_enquiry_price)) or (id in (select id_applied_purchasing from tbl_enquiry_price where is_commited_to_approve=0 or ISNULL(is_commited_to_approve)))) order by `date_applied`"
     */
  }

  if($_POST['conditions']=='not approved') {
    $sql="select * from `tbl_apply_purchasing` where ISNULL(`id_approver`) order by `date_applied`";
  }

  if($_POST['conditions']=='PassedApprovingCompareAndUnfinished') {
    $sql="select * from `tbl_apply_purchasing` where `is_finished`=0 and (id in (select `id_applied_purchasing` from `tbl_enquiry_price` where `is_commited_to_approve`=2)) order by `date_applied`";
  }

  if($_POST['conditions']=='PcsgIsFinishedAndEnqryPsdAprvg') {
    $sql="select *,(select seller from tbl_enquiry_price where id_applied_purchasing=tbl_apply_purchasing.id and is_commited_to_approve=2 and is_made_deal=1 ) as seller,(select actual_amount from tbl_enquiry_price where id_applied_purchasing=tbl_apply_purchasing.id and is_commited_to_approve=2 and is_made_deal=1 ) as actual_amount,(select result_approved from tbl_request_funds where id_relative=tbl_apply_purchasing.id) as result_approved,(select result_approved2 from tbl_request_funds where id_relative=tbl_apply_purchasing.id) as result_approved2,(select time_applied from tbl_request_funds where id_relative=tbl_apply_purchasing.id) as date_of_rqst_funds,(select id from tbl_request_funds where id_relative=tbl_apply_purchasing.id) as id_of_rqst_funds,(select account from tbl_request_funds where id_relative=tbl_apply_purchasing.id) as account,(select reason_reject from tbl_request_funds where id_relative=tbl_apply_purchasing.id) as reason_reject,(select reason_reject2 from tbl_request_funds where id_relative=tbl_apply_purchasing.id) as reason_reject2 from `tbl_apply_purchasing` where `is_finished`=1 and ((id in (select id_relative from tbl_request_funds where is_paid=0)) or id not in (select id_relative from tbl_request_funds where id_relative is not null))";
  }

  $results = $conn->query($sql);
  $conn->close();
  if($results){
      while($arr=$results->fetch_assoc()){//fetch_array  
          array_push($outputData,$arr);
      }
      echo json_encode($outputData);
  }
  $results->free();
?>