<?php
  include_once 'linkToCXS.php';
  $brand=$_POST['brand'];
  $detail=$_POST['detail'];
  $id=$_POST['id'];
  $model=$_POST['model'];
  $name=$_POST['name'];
  $date_needed=$_POST['date_needed'];
  $project=$_POST['project'];
  $id_project=$_POST['id_project'];
  $quantity=$_POST['quantity'];
  $remark=$_POST['remark'];
  $id_applier=$_POST['id_applier'];
  $unit=$_POST['unit'];
  
  $sql_insert="insert into `tbl_apply_purchasing` (id_project,name,unit,quantity,brand,model,detail,date_needed,remark,id_applier,date_applied) values (?,?,?,?,?,?,?,?,?,?,CURRENT_TIME())";

  $stmt=$conn->prepare($sql_insert);
  $stmt->bind_param('issdsssssi',$id_project,$name,$unit,$quantity,$brand,$model,$detail,$date_needed,$remark,$id_applier);
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