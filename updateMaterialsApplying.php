<?php
  include_once 'linkToCXS.php';

  if($_POST['conditions']==='WithApprovedApplyingData') {
    $id_approver=$_POST['idApprover'];
    $rslt_aprvd=$_POST['result'];
    $opinion_approved=$_POST['whyDisagree'];
    $id_applyMaterials=$_POST['id_applyMaterials'];

    $sql_update="UPDATE `tbl_apply_materials` SET `id_approver`=?,`rslt_aprvd`=?,`opinion_approved`=?,`time_aprvd`=CURRENT_TIME()  WHERE `id`=?";
    $stmt=$conn->prepare($sql_update);
    $stmt->bind_param('iisi',$id_approver,$rslt_aprvd,$opinion_approved,$id_applyMaterials);
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

  $conn->close();
?>
