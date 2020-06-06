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
    $sql_update_apply_mat="UPDATE `tbl_apply_materials` SET `id_mio`=? WHERE `id`=?";
    $stmt=$conn->prepare($sql_update_apply_mat);
    $stmt->bind_param('ii',$id_in_outbound,$id_applyForm);
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
      $sql_update_mat="UPDATE `tbl_materials` SET `qty_stocks`=?,SET `date_last_inventory`=CURDATE()  WHERE `id`=?";
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

  $conn->close();
?>
