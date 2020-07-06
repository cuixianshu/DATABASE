<?php  
  include_once 'linkToCXS.php';
  $outputData=array();
  $sql="select * from tbl_way_pay order by convert(name using gbk) asc";

  $result = $conn->query($sql);
  $conn->close();

  if($result){
    while($arr=$result->fetch_assoc()){//fetch_array
      array_push($outputData,$arr);
    }
    echo json_encode($outputData);
  }
  $result->free();
?>