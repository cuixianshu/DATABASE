<?php
  include_once 'linkToCXS.php';
  $id=$_POST['id'];
  $name=$_POST['name'];
  $unit=$_POST['unit'];
  $type=$_POST['id_type'];
  $model=$_POST['model'];
  $details=$_POST['details'];
  $other=$_POST['other'];
  
  // $sql_update="insert into `tbl_product` (name,unit,id_type,model,details,other) values (?,?,?,?,?,?)";
  $sql_update="UPDATE `tbl_product` SET `name`=?,`unit`=?,`id_type`=?,`model`=?,`details`=?,`other`=?  WHERE `id`=?";
  $stmt=$conn->prepare($sql_update);
  $stmt->bind_param('ssisssi',$name,$unit,$type,$model,$details,$other,$id);
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