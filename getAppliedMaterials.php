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

  if($_POST['conditions']==='AllOfCurrentUser') {
    $id_applyer=$_POST['id_applyer'];//> DATE_SUB(CURDATE(), INTERVAL 24 MONTH)

    $sql="select *,(select name from tbl_materials where id=id_material) as name,(select brand from tbl_materials where id=id_material) as brand,(select model from tbl_materials where id=id_material) as model,(select unit from tbl_materials where id=id_material) as unit,(select min_unit_packing from tbl_materials where id=id_material) as min_unit_packing,(select store_place from tbl_materials where id=id_material) as store_place from tbl_apply_materials where time_applied > DATE_SUB(CURDATE(), INTERVAL 1 MONTH) and id_applyer=?";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('i',$id_applyer);
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
  }

  if($_POST['conditions']==='NotApproved') {

    $sql="select *,(select name from tbl_employee where id=id_applyer) as aplrname,(select name from tbl_materials where id=id_material) as mtname,(select brand from tbl_materials where id=id_material) as brand,(select model from tbl_materials where id=id_material) as model,(select unit from tbl_materials where id=id_material) as unit,(select min_unit_packing from tbl_materials where id=id_material) as min_unit_packing,(select store_place from tbl_materials where id=id_material) as store_place from tbl_apply_materials where time_applied > DATE_SUB(CURDATE(), INTERVAL 1 MONTH) and ISNULL(rslt_aprvd)";
    $result = $conn->query($sql);

    if($result){
      while($arr=$result->fetch_assoc()){//fetch_array
        array_push($respondedData,$arr);
      }
      echo json_encode($respondedData);
    }
    $result->free();
  }

  if($_POST['conditions']==='PassedApproving') {

    $sql="select *,(select name from tbl_employee where id=id_applyer) as aplrname,(select name from tbl_materials where id=id_material) as mtname,(select brand from tbl_materials where id=id_material) as brand,(select model from tbl_materials where id=id_material) as model,(select unit from tbl_materials where id=id_material) as unit,(select min_unit_packing from tbl_materials where id=id_material) as min_unit_packing,(select store_place from tbl_materials where id=id_material) as store_place from tbl_apply_materials where time_applied > DATE_SUB(CURDATE(), INTERVAL 1 MONTH) and rslt_aprvd=1 and ISNULL(id_mio)";
    $result = $conn->query($sql);

    if($result){
      while($arr=$result->fetch_assoc()){//fetch_array
        array_push($respondedData,$arr);
      }
      echo json_encode($respondedData);
    }
    $result->free();
  }

  $conn->close();

?>
