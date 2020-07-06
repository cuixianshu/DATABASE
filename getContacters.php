<?php  
  include_once 'linkToCXS.php';

  $outputData=array();
  $sql="select *,CONCAT_WS('@',name,clnt_dptmt_name) as mix_name,id_prnt from ((select id, name,tel_mobile,(select short_name from tbl_client_department where id=tbl_contacter.id_client_dptmt) as clnt_dptmt_name,(select id from tbl_client_department where id=tbl_contacter.id_client_dptmt) as id_clnt_dptmt,(select id_of_parent_ognztn from tbl_client_department where id=tbl_contacter.id_client_dptmt) as id_prnt from tbl_contacter order by convert(name using gbk) asc) as virTbl)";
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