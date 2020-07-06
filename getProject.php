<?php  
  include_once 'linkToCXS.php';
  $outputData=array();
  $sql="select id,name,contacter_and_tel,name_part_a,content,DATE_FORMAT(time_start,'%Y-%m-%d') as time_start,DATE_FORMAT(time_end,'%Y-%m-%d') as time_end, is_finished,address_of_project,id_manager,(select name from tbl_employee where id=id_manager) as manager,(select number from tbl_contract where id=id_contract) as num_contract,scale,other,CONCAT_WS('@',name,DATE_FORMAT(time_start,'%Y/%m/%d')) as prjct from tbl_project where is_finished=0 order by time_start";

  $results = $conn->query($sql);

  if($results){
    while($arr=$results->fetch_assoc()){//fetch_array
      array_push($outputData,$arr);
    }
    echo json_encode($outputData);
  }
  $results->free();
  $conn->close();
?>