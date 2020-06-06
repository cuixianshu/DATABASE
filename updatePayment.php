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

  //  $sql_update="UPDATE `tbl_request_funds` SET `id_project`=?,`amount`=?,`id_way_pay`=?,`account`=?,`use_for`=?,`id_relative`=?,`remark`=?,`id_applyer`=?,`time_applied`=CURRENT_TIME(),`id_approver`=null,`result_approved`=null,`time_approved`=null,`reason_reject`=null WHERE `id`=?";

  if($_POST['conditions']==='PAID') {
    $conn->autocommit(false); //设置为非自动提交

    $id_rqst_funds=$_POST['id_rqstFunds'];
    $amount=$_POST['amount'];
    $id_account=$_POST['id_account'];
    $id_way_pay=$_POST['id_way_pay'];
    $serial_paid=$_POST['serial_paid'];
    $numbers_bills=$_POST['numbers_bills'];
    $id_cashier=$_POST['id_cashier'];    
    $remark=$_POST['remark'];

    $sql_insert="insert into `tbl_pay` (id_rqst_funds,amount,id_account,id_way_pay,serial_paid,numbers_bills,id_cashier,remark,time_paid) values (?,?,?,?,?,?,?,?,CURRENT_TIME())";
    $stmt=$conn->prepare($sql_insert);
    $stmt->bind_param('idiissis',$id_rqst_funds,$amount,$id_account,$id_way_pay,$serial_paid,$numbers_bills,$id_cashier,$remark);
    $result_insert=$stmt->execute();
    $stmt->free_result();
    $stmt->close();


    $sql_update_rqst_funds="UPDATE `tbl_request_funds` SET `is_paid`=1 WHERE `id`=?"; 
    $stmt=$conn->prepare($sql_update_rqst_funds);
    $stmt->bind_param('i',$id_rqst_funds);
    $result_update_rqst_funds=$stmt->execute();
    $stmt->free_result();
    $stmt->close();
    //是否全部成功执行
    if($result_insert && $result_update_rqst_funds) {
      echo json_encode(true);
      $conn->commit();
    } else {
      echo json_encode(false);
      $conn->rollback();
    }

    $conn->autocommit(true); //重新设置为自动提交  
  }
/*
amount: "700.00"
conditions: "ReviewPaying"
id_account: 1
id_reviewer: 1
id_payment: 8
id_way_pay: 2
numbers_bills: "32131321"
opinion_reviewed: null
remark: ""
result_reviewed: "1"
serial_paid: "3242424"
 */
  if($_POST['conditions']==='ReviewPaying') {
    $amount=$_POST['amount'];
    $id_account=$_POST['id_account'];
    $id_way_pay=$_POST['id_way_pay'];
    $serial_paid=$_POST['serial_paid'];
    $numbers_bills=$_POST['numbers_bills'];
    $remark=$_POST['remark'];
    $id_reviewer=$_POST['id_reviewer'];
    $opinion_reviewed=$_POST['opinion_reviewed'];
    $result_reviewed=$_POST['result_reviewed'];
    $id=$_POST['id_payment'];

    $sql_update_pay="UPDATE `tbl_pay` SET `amount`=?,`id_account`=?,`id_way_pay`=?,`serial_paid`=?,`numbers_bills`=?,`remark`=?,`id_reviewer`=?,`time_reviewed`=CURRENT_TIME(),`opinion_reviewed`=?,`result_reviewed`=? WHERE `id`=?"; 
    $stmt=$conn->prepare($sql_update_pay);
    $stmt->bind_param('diisssisii',$amount,$id_account,$id_way_pay,$serial_paid,$numbers_bills,$remark,$id_reviewer,$opinion_reviewed,$result_reviewed,$id);
    $result_update_pay=$stmt->execute();
    $stmt->free_result();
    $stmt->close();
    //是否全部成功执行
    if($result_update_pay) {
      echo json_encode(true);
    } else {
      echo json_encode(false);
    }
  }

  $conn->close();
?>
