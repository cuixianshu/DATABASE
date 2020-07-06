<?php
  include_once 'linkToCXS.php';

  $id=$_POST['id'];
  $name=$_POST['name'];
  $alias=$_POST['alias'];
  $brand=$_POST['brand'];
  $model=$_POST['model'];
  $serial_num=$_POST['serial_num'];
  $available=$_POST['available'];
  $id_responsible_person=$_POST['id_responsible_person'];
  $remark=$_POST['remark'];
  $name_responsible_person=$_POST['name_responsible_person'];
  
  $sql_insert="insert into `tbl_equipments` (name,alias,brand,model,serial_num,available,id_responsible_person,remark) values (?,?,?,?,?,?,?,?)";

  $stmt=$conn->prepare($sql_insert);
  $stmt->bind_param('sssssiis',$name,$alias,$brand,$model,$serial_num,$available,$id_responsible_person,$remark);
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