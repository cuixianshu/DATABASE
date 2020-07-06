<?php
  include_once 'linkToCXS.php';

  if($_POST['conditions']==='NewCreateRequestFunds') {
    $id_project=$_POST['id_project'];
    $amount=$_POST['amount'];
    $id_way_pay=$_POST['id_way_pay'];
    $account=$_POST['account'];
    $use_for=$_POST['use_for'];
    $remark=$_POST['remark'];
    $id_applyer=$_POST['id_applyer'];
    $nature=$_POST['nature'];
    $id=$_POST['id'];
    $sql_insert="insert into `tbl_request_funds` (  id_project,amount,id_way_pay,account,use_for,remark,id_applyer,time_applied,nature) values (?,?,?,?,?,?,?,CURRENT_TIME(),?)";
    $stmt=$conn->prepare($sql_insert);
    $stmt->bind_param('idisssii',$id_project,$amount,$id_way_pay,$account,$use_for,$remark,$id_applyer,$nature);
    $result_insert=$stmt->execute();
    $stmt->free_result();
    $stmt->close();     
      //是否全部成功执行
    if($result_insert) {
      echo json_encode(true);
    } else {
      echo json_encode(false);
    }
  }

  if($_POST['conditions']==='ModifyRequestFunds') {
    $id_project=$_POST['id_project'];
    $amount=$_POST['amount'];
    $id_way_pay=$_POST['id_way_pay'];
    $account=$_POST['account'];
    $use_for=$_POST['use_for'];
    $remark=$_POST['remark'];
    $id_applyer=$_POST['id_applyer'];    
    $id=$_POST['id'];
    $sql_update="UPDATE `tbl_request_funds` SET `id_project`=?,`amount`=?,`id_way_pay`=?,`account`=?,`use_for`=?,`remark`=?,`id_applyer`=?,`time_applied`=CURRENT_TIME(),`id_approver`=null,`result_approved`=null,`time_approved`=null,`reason_reject`=null WHERE `id`=?";
    $stmt=$conn->prepare($sql_update);
    $stmt->bind_param('idisssii',$id_project,$amount,$id_way_pay,$account,$use_for,$remark,$id_applyer,$id);
    $result_update=$stmt->execute();
    $stmt->free_result();
    $stmt->close();  
    //是否全部成功执行
    if($result_update) {
      echo json_encode(true);
      // $conn->commit();
    } else {
      echo json_encode(false);
      // $conn->rollback();
    }  
  }

  if($_POST['conditions']==='NewRequestPurchasingFunds') {
    $id_project=$_POST['id_project'];
    $amount=$_POST['amount'];
    $id_way_pay=$_POST['id_way_pay'];
    $account=$_POST['account'];
    $use_for=$_POST['use_for'];
    $remark=$_POST['remark'];
    $id_applyer=$_POST['id_applyer'];    
    $id_relative=$_POST['id_relative'];
    $nature=$_POST['nature'];
    $sql_insert="insert into `tbl_request_funds` (id_project,amount,id_way_pay,account,use_for,id_relative,remark,id_applyer,time_applied,nature) values (?,?,?,?,?,?,?,?,CURRENT_TIME(),?)";
    $stmt=$conn->prepare($sql_insert);
    $stmt->bind_param('idissisii',$id_project,$amount,$id_way_pay,$account,$use_for,$id_relative,$remark,$id_applyer,$nature);
    $result_insert=$stmt->execute();
    $stmt->free_result();
    $stmt->close();     
      //是否全部成功执行
    if($result_insert) {
      echo json_encode(true);
    } else {
      echo json_encode(false);
    }
  } 

  if($_POST['conditions']==='UpdateRequestPurchasingFunds') {
    $id_project=$_POST['id_project'];
    $amount=$_POST['amount'];
    $id_way_pay=$_POST['id_way_pay'];
    $account=$_POST['account'];
    $use_for=$_POST['use_for'];
    $remark=$_POST['remark'];
    $id_applyer=$_POST['id_applyer'];    
    $id_relative=$_POST['id_relative'];
    $id_rqst_funds=$_POST['id_rqst_funds'];

    $sql_update="UPDATE `tbl_request_funds` SET `id_project`=?,`amount`=?,`id_way_pay`=?,`account`=?,`use_for`=?,`id_relative`=?,`remark`=?,`id_applyer`=?,`time_applied`=CURRENT_TIME(),`id_approver`=null,`result_approved`=null,`time_approved`=null,`reason_reject`=null WHERE `id`=?";

    $stmt=$conn->prepare($sql_update);
    $stmt->bind_param('idissisii',$id_project,$amount,$id_way_pay,$account,$use_for,$id_relative,$remark,$id_applyer,$id_rqst_funds);
    $result_update=$stmt->execute();
    $stmt->free_result();
    $stmt->close();     
      //是否全部成功执行
    if($result_update) {
      echo json_encode(true);
    } else {
      echo json_encode(false);
    }
  }

  if($_POST['conditions']==='WithPrimaryAuditedData') {
/*
conditions: "WithPrimaryAuditedData"
id_auditer: 1
id_rqst_funds: (...)
reasonResult: (...)
resultValue: (...)
 */
    $id_approver=$_POST['id_auditer'];
    $id_rqst_funds=$_POST['id_rqst_funds'];
    $reasonResult=$_POST['reasonResult'];
    $resultValue=$_POST['resultValue'];
 

    $sql_update="UPDATE `tbl_request_funds` SET `id_approver`=?,`result_approved`=?,`reason_reject`=?,`time_approved`=CURRENT_TIME() WHERE `id`=?";

    $stmt=$conn->prepare($sql_update);
    $stmt->bind_param('iisi',$id_approver,$resultValue,$reasonResult,$id_rqst_funds);
    $result_update=$stmt->execute();
    $stmt->free_result();
    $stmt->close();     
      //是否全部成功执行
    if($result_update) {
      echo json_encode(true);
    } else {
      echo json_encode(false);
    }
  }

  if($_POST['conditions']==='WithFinalAuditedData') {
/*
conditions: "WithPrimaryAuditedData"
id_auditer: 1
id_rqst_funds: (...)
reasonResult: (...)
resultValue: (...)
 */
    $id_approver=$_POST['id_auditer'];
    $id_rqst_funds=$_POST['id_rqst_funds'];
    $reasonResult=$_POST['reasonResult'];
    $resultValue=$_POST['resultValue'];
 

    $sql_update="UPDATE `tbl_request_funds` SET `id_approver2`=?,`result_approved2`=?,`reason_reject2`=?,`time_approved2`=CURRENT_TIME() WHERE `id`=?";

    $stmt=$conn->prepare($sql_update);
    $stmt->bind_param('iisi',$id_approver,$resultValue,$reasonResult,$id_rqst_funds);
    $result_update=$stmt->execute();
    $stmt->free_result();
    $stmt->close();     
      //是否全部成功执行
    if($result_update) {
      echo json_encode(true);
    } else {
      echo json_encode(false);
    }
  }
  $conn->close();
?>
