<?php
  include_once 'linkToCXS.php';

  if($_POST['conditions']==='Approved') {//审核
    $id_approver=$_POST['idApprover'];
    $result_approved=$_POST['result'];
    $why_disagree=$_POST['whyDisagree'];
    $id=$_POST['id_applyedPurchasing'];

    $sql_update="UPDATE `tbl_apply_purchasing` SET `id_approver`=?,`result_approved`=?,`why_disagree`=?,`date_approved`=CURRENT_TIME() WHERE `id`=?";
    $stmt=$conn->prepare($sql_update);
    $stmt->bind_param('iisi',$id_approver,$result_approved,$why_disagree,$id);
    $result_update=$stmt->execute();
    $stmt->free_result();
    $stmt->close();    
  }

  if($_POST['conditions']==='Modified'){//更改
    $brand=$_POST['brand'];
    $detail=$_POST['detail'];
    $id=$_POST['id'];
    $model=$_POST['model'];
    $name=$_POST['name'];
    $date_needed=$_POST['date_needed'];
    // $project=$_POST['project'];
    $id_project=$_POST['id_project'];
    $quantity=$_POST['quantity'];
    $remark=$_POST['remark'];
    $id_applier=$_POST['id_applier'];
    $unit=$_POST['unit'];
  
    $sql_update="UPDATE `tbl_apply_purchasing` SET `id_project`=?,`name`=?,`unit`=?,`quantity`=?,`brand`=?,`model`=?,`detail`=?,`date_needed`=?,`remark`=?,`id_applier`=?  WHERE `id`=?";
    $stmt=$conn->prepare($sql_update);
    $stmt->bind_param('issdsssssii',$id_project,$name,$unit,$quantity,$brand,$model,$detail,$date_needed,$remark,$id_applier,$id);
    $result_update=$stmt->execute();
    $stmt->free_result();
    $stmt->close();
  }

  if($_POST['conditions']==='Finished') {//完成采购
    $id=$_POST['id_applyedPurchasing'];

    $sql_update="UPDATE `tbl_apply_purchasing` SET `is_finished`=1,`date_finished`=CURRENT_TIME() WHERE `id`=?";
    $stmt=$conn->prepare($sql_update);
    $stmt->bind_param('i',$id);
    $result_update=$stmt->execute();
    $stmt->free_result();
    $stmt->close();    
  }


    //是否成功执行
  if($result_update) {
    echo json_encode(true);
  } else {
    echo json_encode(false);
  }


  $conn->close();
?>