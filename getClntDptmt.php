<?php  
  include_once 'linkToCXS.php';
  $outputData=array();

  $sql="select * from tbl_client_department order by convert(short_name using gbk) asc";
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