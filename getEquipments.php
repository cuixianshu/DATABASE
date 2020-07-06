<?php  
  include_once 'linkToCXS.php';
  $outputData=array();
    
  if(!empty($_POST['conditions']) && $_POST['conditions']=='All') {
    $sql="select *,(select name from tbl_employee where id=id_responsible_person) as rspnsbl_prsn,remark,CONCAT_WS('@',name,alias) as nmNmbr from tbl_equipments order by convert(name using gbk) asc";
  } else {//只获取公司内部的
    $sql="select *,(select name from tbl_employee where id=id_responsible_person) as rspnsbl_prsn,remark,CONCAT_WS('@',name,alias) as nmNmbr from tbl_equipments where is_own=1 order by convert(name using gbk) asc";
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
