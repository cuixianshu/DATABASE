<?php
  include_once 'linkToCXS.php';
  $name=$_POST['name'];
  $unit=$_POST['unit'];
  $type=$_POST['id_type'];
  $model=$_POST['model'];
  $details=$_POST['details'];
  $other=$_POST['other'];
  
  $sql_insert="insert into `tbl_product` (name,unit,id_type,model,details,other) values (?,?,?,?,?,?)";

  $stmt=$conn->prepare($sql_insert);
  $stmt->bind_param('ssisss',$name,$unit,$type,$model,$details,$other);
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