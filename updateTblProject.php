<?php
  include_once 'linkToCXS.php';
  $id=$_POST['id'];
  $name=$_POST['name'];
  $contacter_and_tel=$_POST['contacter'];
  $name_part_a=$_POST['partner'];
  $content=$_POST['contents'];
  $time_start=$_POST['startDate'];
  $time_end=$_POST['endDate'];
  $is_finished=$_POST['is_finished'];
  $address_of_project=$_POST['address'];
  $id_manager=$_POST['id_manager'];
  $id_contract=$_POST['id_contract'];
  $scale=$_POST['scale'];
  $other=$_POST['remark'];
  
  $sql_update="UPDATE `tbl_project` SET `name`=?,`contacter_and_tel`=?,`name_part_a`=?,`content`=?,`time_start`=?,`time_end`=?,`is_finished`=?,`address_of_project`=?,`id_manager`=?,`id_contract`=?,`scale`=?,`other`=?  WHERE `id`=?";
  $stmt=$conn->prepare($sql_update);
  $stmt->bind_param('ssssssisiissi',$name,$contacter_and_tel,$name_part_a,$content,$time_start,$time_end,$is_finished,$address_of_project,$id_manager,$id_contract,$scale,$other,$id);
  $result_update=$stmt->execute();
  $stmt->free_result();
  $stmt->close();

    //是否全部成功执行
  if($result_update) {
    echo json_encode(true);
  } else {
    echo json_encode(false);
  }


    $conn->close();
?>