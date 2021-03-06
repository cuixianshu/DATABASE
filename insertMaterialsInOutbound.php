<?php
  include_once 'linkToCXS.php';

  if($_POST['conditions']==='updateQTYWithInboundData') {
    $conn->autocommit(false);

    $name=$_POST['name'];
    $unit=$_POST['unit'];
    $brand=$_POST['brand'];
    $model=$_POST['model'];
    $min_unit_packing=$_POST['min_unit_packing'];
    $store_place=$_POST['store_place'];
    $remark=$_POST['remark'];
    $id_op=$_POST['id_op'];
    $qty=$_POST['qty_inbound'];
    $id_material=$_POST['id'];

    $sql_insert="INSERT INTO `tbl_materials_in_outbound` (id_material,qty,type_op,id_op,time_op,remark) values (?,?,1,?,CURTIME(),?)";
    $stmt=$conn->prepare($sql_insert);
    $stmt->bind_param('idis',$id_material,$qty,$id_op,$remark);
    $result_insert=$stmt->execute();
    $stmt->free_result();
    $stmt->close();

    $sql_update="UPDATE `tbl_materials` SET `qty_stocks`=(?+qty_stocks) WHERE `id`=?";
    $stmt=$conn->prepare($sql_update);
    $stmt->bind_param('di',$qty,$id_material);
    $result_update=$stmt->execute();
    $stmt->free_result();
    $stmt->close();
  
    //是否全部成功执行
    if($result_insert && $result_update) {
      echo json_encode(true);
      $conn->commit();
    } else {
      echo json_encode(false);
      $conn->rollback();
    }

    $conn->autocommit(true);
  }

  if($_POST['conditions']==='WithDistributedData') {
    $conn->autocommit(false);
   
    $id_applyForm=$_POST['id_applyForm'];
    $id_material=$_POST['id_material'];
    $id_op=$_POST['id_op'];
    $qty=$_POST['qty'];

    $result_insert=false;
    $sql_insert="INSERT INTO `tbl_materials_in_outbound` (id_material,qty,type_op,id_op,time_op) values (?,?,0,?,CURTIME())";
    $stmt=$conn->prepare($sql_insert);
    $stmt->bind_param('idi',$id_material,$qty,$id_op);
    $result_insert=$stmt->execute();
    $stmt->free_result();
    $stmt->close();
    
    $sql_select="select max(id) as id from tbl_materials_in_outbound";
    $result_select=false;
    $id_in_outbound='';
    if($result=$conn->query($sql_select)){
      $result_select=true;
      $row=$result->fetch_assoc();
      $id_in_outbound=$row["id"];
      $result->close();     
    }    

    $result_update_apply_mat=false;
    $sql_update_apply_mat="UPDATE `tbl_apply_materials` SET `id_mio`=?,`qty_distributed`=`qty_distributed`+? WHERE `id`=?";
    $stmt=$conn->prepare($sql_update_apply_mat);
    $stmt->bind_param('idi',$id_in_outbound,$qty,$id_applyForm);
    $result_update_apply_mat=$stmt->execute();
    $stmt->free_result();
    $stmt->close();

    $result_update_mat=false;
    $sql_update_mat="UPDATE `tbl_materials` SET `qty_stocks`=(qty_stocks-?) WHERE `id`=?";
    $stmt=$conn->prepare($sql_update_mat);
    $stmt->bind_param('di',$qty,$id_material);
    $result_update_mat=$stmt->execute();
    $stmt->free_result();
    $stmt->close();

    //是否全部成功执行
    if($result_insert && $result_select && $result_update_apply_mat && $result_update_mat) {
      echo json_encode(true);
      $conn->commit();
    } else {
      echo json_encode(false);
      $conn->rollback();
    }

    $conn->autocommit(true);
  }

  if($_POST['conditions']==='updateQTYWithInventoryData') {
    $conn->autocommit(false);
   
    $id_material=$_POST['id'];
    $id_op=$_POST['id_op'];
    $qty_actual=$_POST['qty_actual'];
    $qty_due=$_POST['qty_stocks'];
    $type_op=4;//2:addCheck,3:decreaseCheck
    $qty_variable=0;

    if($qty_actual>$qty_due) {//实际数量多于表上数量
      $type_op=2;
      $qty_variable=$qty_actual-$qty_due;
    } else if($qty_actual<$qty_due) {
      $type_op=3;
      $qty_variable=$qty_due-$qty_actual;      
    } else {
      $type_op=4;
      $qty_variable=0;      
    }

    $result_insert=false;
    $sql_insert="INSERT INTO `tbl_materials_in_outbound` (id_material,qty,type_op,id_op,time_op) values (?,?,?,?,CURTIME())";
    $stmt=$conn->prepare($sql_insert);
    $stmt->bind_param('idii',$id_material,$qty_variable,$type_op,$id_op);
    $result_insert=$stmt->execute();
    $stmt->free_result();
    $stmt->close();

    $result_update_mat=false;
    if($qty_variable!==0) {
      $sql_update_mat="UPDATE `tbl_materials` SET `qty_stocks`=?,`date_last_inventory`=CURDATE()  WHERE `id`=?";
      $stmt=$conn->prepare($sql_update_mat);
      $stmt->bind_param('di',$qty_actual,$id_material);
      $result_update_mat=$stmt->execute();
      $stmt->free_result();
      $stmt->close();
    } else {
      $sql_update_mat="UPDATE `tbl_materials` SET `date_last_inventory`=CURDATE()  WHERE `id`=?";
      $stmt=$conn->prepare($sql_update_mat);
      $stmt->bind_param('i',$id_material);
      $result_update_mat=$stmt->execute();
      $stmt->free_result();
      $stmt->close();      
    }

    //是否全部成功执行
    if($result_insert && $result_update_mat) {
      echo json_encode(true);
      $conn->commit();
    } else {
      echo json_encode(false);
      $conn->rollback();
    }

    $conn->autocommit(true);
  }
  if($_POST['conditions']==='updateAMIOWithReturnData') {
    $conn->autocommit(false);
/*
id: (...)
id_applyer: (...)
id_approver: (...)
id_material: (...)
id_mio: (...)
id_op: 1
id_project: (...)
m_brand: (...)
m_min_unit_packing: (...)
m_model: (...)
m_name: (...)
m_store_place: (...)
m_unit: (...)
mio_qty: (...)
mio_remark: "4567890"
mio_time: (...)
opinion_approved: (...)
qty: (...)
qty_distributed: (...)
qty_returned: (...)
remark: (...)
rslt_aprvd: (...)
rtn_qty: "56"
time_applied: (...)
time_aprvd: (...)
use_for: (...)
 */   
    $id_applyForm=$_POST['id'];
    $id_material=$_POST['id_material'];
    $id_op=$_POST['id_op'];
    $qty=$_POST['rtn_qty'];
    $mio_remark=$_POST['mio_remark'];
    $result_insert=false;
    $sql_insert="INSERT INTO `tbl_materials_in_outbound` (id_material,qty,type_op,id_op,time_op,remark) values (?,?,1,?,CURTIME(),?)";
    $stmt=$conn->prepare($sql_insert);
    $stmt->bind_param('idis',$id_material,$qty,$id_op,$mio_remark);
    $result_insert=$stmt->execute();
    $stmt->free_result();
    $stmt->close();
    
    $sql_select="select max(id) as id from tbl_materials_in_outbound";
    $result_select=false;
    $id_in_outbound='';
    if($result=$conn->query($sql_select)){
      $result_select=true;
      $row=$result->fetch_assoc();
      $id_in_outbound=$row['id'];
      $result->close();     
    }    

    $result_update_apply_mat=false;
    $sql_update_apply_mat="UPDATE `tbl_apply_materials` SET `id_return_mio`=CONCAT(IF(ISNULL(`id_return_mio`),'',`id_return_mio`),',',?),`qty_returned`=`qty_returned`+? WHERE `id`=?";
    $stmt=$conn->prepare($sql_update_apply_mat);
    $stmt->bind_param('sdi',$id_in_outbound,$qty,$id_applyForm);
    $result_update_apply_mat=$stmt->execute();
    $stmt->free_result();
    $stmt->close();

    $result_update_mat=false;
    $sql_update_mat="UPDATE `tbl_materials` SET `qty_stocks`=(qty_stocks+?) WHERE `id`=?";
    $stmt=$conn->prepare($sql_update_mat);
    $stmt->bind_param('di',$qty,$id_material);
    $result_update_mat=$stmt->execute();
    $stmt->free_result();
    $stmt->close();

    //是否全部成功执行
    if($result_insert && $result_select && $result_update_apply_mat && $result_update_mat) {
      echo json_encode(true);
      $conn->commit();
    } else {
      echo json_encode(false);
      $conn->rollback();
    }

    $conn->autocommit(true);
  }
  $conn->close();
?>
