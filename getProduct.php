<?php  
  include_once 'linkToCXS.php';
  $outputData=array();

  if(!empty($_POST['conditions']) && $_POST['conditions']==='ExceptVehicle') {
    $sql="select * from tbl_product where id_type<>2 and id_type<>3 order by name asc";
  } else {
    $sql="select * from tbl_product order by name asc";
  }

  $result = $conn->query($sql);

  if($result){
    while($arr=$result->fetch_assoc()){//fetch_array
      array_push($outputData,$arr);
    }
    echo json_encode($outputData);
  }
  $result->free();
  $conn->close();
?>