<?php  
  include_once 'linkToCXS.php';
  $respondedData=[];

  $sql="select id,name from tbl_our_company";
  $results = $conn->query($sql);
  $conn->close();

  if($results){
    while($arr=$results->fetch_assoc()){//fetch_array
      array_push($respondedData,$arr);
    }
    echo json_encode($respondedData);
  }
  $results->free();
?>