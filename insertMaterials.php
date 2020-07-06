<?php
  include_once 'linkToCXS.php';
  $outputData=array();

  $id=$_POST['id'];
  $name=$_POST['name'];
  $unit=$_POST['unit'];
  $brand=$_POST['brand'];
  $model=$_POST['model'];
  $min_unit_packing=$_POST['min_unit_packing'];
  $store_place=$_POST['store_place'];
  $remark=$_POST['remark'];
  $id_creater=$_POST['id_op'];
  
  $sql_insert="insert into `tbl_materials` (name,unit,brand,model,min_unit_packing,store_place,remark,id_creater,date_created,date_last_inventory) values (?,?,?,?,?,?,?,?,CURDATE(),CURDATE())";

  $stmt=$conn->prepare($sql_insert);
  $stmt->bind_param('sssssssi',$name,$unit,$brand,$model,$min_unit_packing,$store_place,$remark,$id_creater);
  $result_insert=$stmt->execute();
  $stmt->free_result();
  $stmt->close();

    //是否全部成功执行
  if($result_insert) {
    echo json_encode(true);
  } else {
    echo json_encode(false);
  }


  $conn->close();
?>