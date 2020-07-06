<?php  
  include_once 'linkToCXS.php';
  $outputData=array();
  $sql="select id,CONCAT_WS('-',short_en,short_cn) as type_product from tbl_type_product order by convert(type_product using gbk) asc";

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