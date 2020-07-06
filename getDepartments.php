<?php  
  include_once 'linkToCXS.php';
  
  $outputData=[];//array()
  $sql="select * from tbl_department order by convert(name using gbk) asc";

  $result = $conn->query($sql);

  if($result){
    while($arr=$result->fetch_assoc()){//fetch_array
      array_push($outputData,$arr);
    }
    header("Content-Type: text/html; charset=UTF-8");
    echo json_encode($outputData);
  }
  $result->free();
  $conn->close();
?>