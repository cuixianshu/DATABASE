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
  // $conn->autocommit(false);
/*
availableQtyForApplying: (...)
brand: (...)
conditions: "insertMaterialApplying"
date_created: "2020-06-04"
id: 1
id_creater: (...)
id_op: 1
min_unit_packing: (...)
model: (...)
name: (...)
qty_apply: "10"
qty_stocks: (...)
remark: (...)
store_place: (...)
unit: (...)
 */
  // $name=$_POST['name'];
  // $unit=$_POST['unit'];
  // $brand=$_POST['brand'];
  // $model=$_POST['model'];
  // $min_unit_packing=$_POST['min_unit_packing'];
  // $store_place=$_POST['store_place'];
  $remark=$_POST['remark'];
  $id_applyer=$_POST['id_op'];
  $qty=$_POST['qty_apply'];
  $id_material=$_POST['id'];
  $id_project=$_POST['id_project'];

  $sql_insert="INSERT INTO `tbl_apply_materials` (id_material,qty,id_applyer,time_applied,remark,id_project) values (?,?,?,CURTIME(),?,?)";
  $stmt=$conn->prepare($sql_insert);
  $stmt->bind_param('idisi',$id_material,$qty,$id_applyer,$remark,$id_project);
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
