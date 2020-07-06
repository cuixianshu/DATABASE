<?php
  include_once 'linkToCXS.php';
  /*
          rqstRemark:'',
          id_op:''
          qty:0,
          id:'',
          id_project:'',
          use_for:'',
   */
  $remark=$_POST['remark'];
  $id_applyer=$_POST['id_op'];
  $qty=$_POST['qty'];
  $id_material=$_POST['id'];
  $id_project=$_POST['id_project'];
  $use_for=$_POST['use_for'];
// echo json_encode($_POST);
// exit;
  $sql_insert="INSERT INTO `tbl_apply_materials` (id_material,qty,id_applyer,time_applied,remark,id_project,use_for) values (?,?,?,CURTIME(),?,?,?)";
  $stmt=$conn->prepare($sql_insert);
  $stmt->bind_param('idisis',$id_material,$qty,$id_applyer,$remark,$id_project,$use_for);
  $result_insert=$stmt->execute();
  $stmt->free_result();
  $stmt->close();

  //是否全部成功执行
  if($result_insert) {
    echo json_encode(true);
    // $conn->commit();
  } else {
    echo json_encode(false);
    // $conn->rollback();
  }

  // $conn->autocommit(true);

  $conn->close();
?>
