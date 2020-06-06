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

/*
          id:'',
          name:'',
          unit:'',
          brand:'',
          model:'',
          min_unit_packing:'',
          store_place:'',
          remark:''
 */
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