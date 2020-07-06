<?php  
  include_once 'linkToCXS.php';
  $outputData=[];//array()

  if(!empty($_POST['conditions']) && $_POST['conditions']=='All') {
    $sql="select id,name,gender,born_date,idcard,education,graduate_from,address,tel_private,tel_work,emergency_contacter,tel_emergency,date_join,id_department,(select name from tbl_department where id=id_department) as name_department,position,certificate_and_rank,DATE_FORMAT(date_leave,'%Y-%m-%d') as date_leave,why_leave,id_creater,(select name from tbl_employee where id=id_creater) as name_creater,DATE_FORMAT(time_create,'%Y-%m-%d') as time_create,id_last_modifyer,(select name from tbl_employee where id=id_last_modifyer) as name_modifyer,DATE_FORMAT(time_last_modify,'%Y-%m-%d') as time_last_modify,remark,CONCAT_WS('@',name,tel_work) as nmMbl from tbl_employee where ISNULL(date_leave) order by convert(name using gbk) asc";



  } else {//只获取公司内部的
    $sql="select id,name,gender,born_date,idcard,education,graduate_from,address,tel_private,tel_work,emergency_contacter,tel_emergency,date_join,id_department,(select name from tbl_department where id=id_department) as name_department,position,certificate_and_rank,DATE_FORMAT(date_leave,'%Y-%m-%d') as date_leave,why_leave,id_creater,(select name from tbl_employee where id=id_creater) as name_creater,DATE_FORMAT(time_create,'%Y-%m-%d') as time_create,id_last_modifyer,(select name from tbl_employee where id=id_last_modifyer) as name_modifyer,DATE_FORMAT(time_last_modify,'%Y-%m-%d') as time_last_modify,remark,CONCAT_WS('@',name,tel_work) as nmMbl from tbl_employee where is_own=1 and ISNULL(date_leave) order by convert(name using gbk) asc";
  }

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