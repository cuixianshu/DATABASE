<?php
  include_once 'linkToCXS.php';

  $name=$_POST['name'];
  $gender=$_POST['gender'];
  $born_date=$_POST['born_date'];
  $idcard=$_POST['idcard'];
  $education=$_POST['education'];
  $graduate_from=$_POST['graduate_from'];
  $address=$_POST['address'];
  $tel_private=$_POST['tel_private'];
  $tel_work=$_POST['tel_work'];
  $emergency_contacter=$_POST['emergency_contacter'];
  $tel_emergency=$_POST['tel_emergency'];
  $date_join=$_POST['date_join'];
  $id_department=$_POST['id_department'];
  // $name_department=$_POST['name_department'];
  $position=$_POST['position'];
  $certificate_and_rank=$_POST['certificate_and_rank'];
  // $date_leave=$_POST['date_leave'];
  // $why_leave=$_POST['why_leave'];
  $id_creater=$_POST['id_operator'];
  // $time_create=$_POST['time_create'];
  // $time_last_modify=$_POST['time_last_modify'];
  $remark=$_POST['remark'];

//插入新员工数据  
  $sql_insert_employee="INSERT INTO `tbl_employee` (`name`,`gender`,`born_date`,`idcard`,`education`,`graduate_from`,`address`,`tel_private`,`tel_work`,`emergency_contacter`,`tel_emergency`,`date_join`,`id_department`,`position`,`certificate_and_rank`,`time_create`,`id_creater`, `remark`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,CURRENT_TIME(),?,?)";
  $stmt=$conn->prepare($sql_insert_employee);
  $stmt->bind_param('ssssssssssssissis',$name,$gender,$born_date,$idcard,$education,$graduate_from,$address,$tel_private,$tel_work,$emergency_contacter,$tel_emergency,$date_join,$id_department,$position,$certificate_and_rank,$id_creater,$remark);
  $result_insert_employee=$stmt->execute();
  $stmt->free_result();
  $stmt->close();

  //是否成功执行
  if($result_insert_employee) {
    echo json_encode(true);
  } else {
    echo json_encode(false);
  }


  $conn->close();
?>
