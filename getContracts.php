<?php  
  include_once 'linkToCXS.php';
  
  $outputData=array();
  $sql="select id,number,name_part_a,agent_a,name_part_b,basic_content,name_product,unit_price,count_product,amount,way_pay,DATE_FORMAT(time_start,'%Y-%m-%d') as time_start,DATE_FORMAT(time_end,'%Y-%m-%d') as time_end,agent_b,DATE_FORMAT(time_create,'%Y-%m-%d') as time_create,is_finished,DATE_FORMAT(time_sign,'%Y-%m-%d') as time_sign,remark from tbl_contract where is_finished=0 order by time_start";

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