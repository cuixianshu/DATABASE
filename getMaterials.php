<?php  
  $servername = "localhost";
  $username = "root";
  $password = "Mwy197301242811";
  $dbname = "cuixianshu"; // 要操作的数据库名
  $respondedData=[];
  // 创建连接 
  $conn= new mysqli($servername,$username,$password,$dbname); // 注意第四个参数
  if($conn->connect_error){
      die("连接失败，错误:" . $conn->connect_error);
  }
  
  $keyWord=$_POST['keyWord'];

  if(!empty($_POST['conditions']) && $_POST['conditions']==='availableQtyForApplying') {
    $sql="select *,(select (qty_stocks-(select IFNULL(SUM(qty),0) from tbl_apply_materials where id_material=tbl_materials.id and (ISNULL(id_mio) and (rslt_aprvd=1 or ISNULL(rslt_aprvd)))))) as availableQtyForApplying from tbl_materials where name like CONCAT('%',?,'%') or brand like CONCAT('%',?,'%') or model like CONCAT('%',?,'%') or store_place like CONCAT('%',?,'%')";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('ssss',$keyWord,$keyWord,$keyWord,$keyWord);
    $stmt->execute();
    $result = $stmt->get_result();
  
    if(!$result) {
      echo json_encode($conn->error);
      exit;
    }
    $i=0;
    while ($row = $result->fetch_assoc()) {
      $respondedData[$i]=$row;
      $i++;
    }    
    echo json_encode($respondedData);
    $stmt->free_result();
    $stmt->close();    
  } else if(!empty($_POST['conditions']) && $_POST['conditions']==='lastInventoryBefore3DaysAgo') {
    $sql="select * from tbl_materials where (name like CONCAT('%',?,'%') or brand like CONCAT('%',?,'%') or model like CONCAT('%',?,'%') or store_place like CONCAT('%',?,'%')) and date_last_inventory < DATE_SUB(CURDATE(), INTERVAL 3 DAY)";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('ssss',$keyWord,$keyWord,$keyWord,$keyWord);
    $stmt->execute();
    $result = $stmt->get_result();
  
    if(!$result) {
      echo json_encode($conn->error);
      exit;
    }
    $i=0;
    while ($row = $result->fetch_assoc()) {
      $respondedData[$i]=$row;
      $i++;
    }    
    echo json_encode($respondedData);
    $stmt->free_result();
    $stmt->close();
  } else {
    $sql="select * from tbl_materials where name like CONCAT('%',?,'%') or brand like CONCAT('%',?,'%') or model like CONCAT('%',?,'%') or store_place like CONCAT('%',?,'%')";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('ssss',$keyWord,$keyWord,$keyWord,$keyWord);
    $stmt->execute();
    $result = $stmt->get_result();
  
    if(!$result) {
      echo json_encode($conn->error);
      exit;
    }
    $i=0;
    while ($row = $result->fetch_assoc()) {
      $respondedData[$i]=$row;
      $i++;
    }    
    echo json_encode($respondedData);
    $stmt->free_result();
    $stmt->close();
  }


  $conn->close();

?>
