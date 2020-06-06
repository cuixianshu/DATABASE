<?php
	$servername = "localhost";
	$username = "root";
	$password = "Mwy197301242811";
	$dbname = "cuixianshu"; // 要操作的数据库名
	$outputData=array();
	// 创建连接 
	$conn= new mysqli($servername,$username,$password,$dbname); // 注意第四个参数
	if($conn->connect_error){
	    die("连接失败，错误:" . $conn->connect_error);
	}
  // echo json_encode($_POST);
  // exit;
  if($_POST['conditions']==='updateInfos') {
    $id=$_POST['id'];
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
    $name_department=$_POST['name_department'];
    $position=$_POST['position'];
    $certificate_and_rank=$_POST['certificate_and_rank'];
    $date_leave=empty($_POST['date_leave'])?null:$_POST['date_leave'];
    $why_leave=$_POST['why_leave'];
    $time_create=$_POST['time_create'];
    $id_operator=$_POST['id_operator'];
    $time_last_modify=$_POST['time_last_modify'];
    $remark=$_POST['remark'];
  
    $sql_update="UPDATE `tbl_employee` SET `name`=?,`gender`=?,`born_date`=?,`idcard`=?,`education`=?,`graduate_from`=?,`address`=?,`tel_private`=?,`tel_work`=?,`emergency_contacter`=?,`tel_emergency`=?,`id_department`=?,`position`=?,`certificate_and_rank`=?,`date_leave`=?,`why_leave`=?,`id_last_modifyer`=?,`time_last_modify`=CURRENT_TIME(), `remark`=? WHERE `id`=?";

    $stmt=$conn->prepare($sql_update);
    $stmt->bind_param('sssssssssssissssisi',$name,$gender,$born_date,$idcard,$education,$graduate_from,$address,$tel_private,$tel_work,$emergency_contacter,$tel_emergency,$id_department,$position,$certificate_and_rank,$date_leave,$why_leave,$id_operator,$remark,$id);
    $result_update=$stmt->execute();
    $stmt->free_result();
    $stmt->close();

      //是否全部成功执行
    if($result_update) {
      echo json_encode(true);
    } else {
      echo json_encode(false);
    }
  } else if ($_POST['conditions']==='changePswd') {
    $pswd=$_POST['pswd'];
    $id=$_POST['id'];
    $sql_update="UPDATE `tbl_employee` SET `pswd`=MD5(?) WHERE `id`=?";

    $stmt=$conn->prepare($sql_update);
    $stmt->bind_param('si',$pswd,$id);
    $result_update=$stmt->execute();
    $stmt->free_result();
    $stmt->close();
    if($result_update) {
      echo json_encode(true);
    } else {
      echo json_encode(false);
    }    
  }

	$conn->close();
?>