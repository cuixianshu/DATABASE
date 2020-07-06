<?php
  include_once 'linkToCXS.php';

  if($_POST['conditions']==='InsertNew') {
    $cause=$_POST['cause'];
    $id_project=$_POST['id_project'];
    $id_way_pay=$_POST['id_way_pay'];
    $id_payer=$_POST['currentUserId'];
    $amount=$_POST['amount'];
    $remark=$_POST['remark'];

    $sql_insert="insert into `tbl_turnin_funds` (cause,  id_project,id_way_pay,id_payer,amount,time_paid,remark) values (?,?,?,?,?,CURRENT_TIME(),?)";
    $stmt=$conn->prepare($sql_insert);
    $stmt->bind_param('siiids',$cause,$id_project,$id_way_pay,$id_payer,$amount,$remark);
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

  if($_POST['conditions']==='Update') {
    $cause=$_POST['cause'];
    $id_project=$_POST['id_project'];
    $id_way_pay=$_POST['id_way_pay'];
    $id_payer=$_POST['currentUserId'];
    $amount=$_POST['amount'];
    $remark=$_POST['remark'];   
    $id=$_POST['id'];
    $sql_update="UPDATE `tbl_turnin_funds` SET `cause`=?,`id_project`=?,`id_way_pay`=?,`id_payer`=?,`amount`=?,`remark`=?,`time_paid`=CURRENT_TIME() WHERE `id`=?";
    $stmt=$conn->prepare($sql_update);
    $stmt->bind_param('siiidsi',$cause,$id_project,$id_way_pay,$id_payer,$amount,$remark,$id);
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

//还款
  if($_POST['conditions']==='WithReturnMoney') {
    $cause='归还借款';
    $id_project=$_POST['id_project'];
    $id_way_pay=$_POST['iWP'];
    $id_payer=$_POST['id_debter'];
    $amount=$_POST['actRTNAmount'];
    $remark=$_POST['RTNrmk'];
    $signature_code='{"id_request":"'.$_POST['id'].'","id_pay":"'.$_POST['p_id'].'"}';

    $sql_insert="insert into `tbl_turnin_funds` (cause,  id_project,id_way_pay,id_payer,amount,time_paid,remark,nature,signature_code) values (?,?,?,?,?,CURRENT_TIME(),?,2,?)";
    $stmt=$conn->prepare($sql_insert);
    $stmt->bind_param('siiidss',$cause,$id_project,$id_way_pay,$id_payer,$amount,$remark,$signature_code);
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

  $conn->close();
?>
