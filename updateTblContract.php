<?php
  include_once 'linkToCXS.php';
  $id=$_POST['id'];
  $number=$_POST['number'];
  $name_part_a=$_POST['partA'];
  $agent_a=$_POST['agentA'];
  $name_part_b=$_POST['ourCompany'];
  $basic_content=$_POST['basicContent'];
  $name_product=$_POST['nameOfProduct'];
  $unit_price=$_POST['unitPrice'];
  $count_product=$_POST['count'];
  $amount=$_POST['amount'];
  $way_pay=$_POST['payment'];
  $time_start=$_POST['startDate'];
  $time_end=$_POST['endDate'];
  $agent_b=$_POST['ourAgent'];
  $time_create='';
  $is_finished=$_POST['isFinished'];
  $time_sign=$_POST['signedDate'];
  $remark=$_POST['remark'];
  
  $sql_update="UPDATE `tbl_contract` SET `number`=?,`name_part_a`=?,`agent_a`=?,`name_part_b`=?,`basic_content`=?,`name_product`=?,`unit_price`=?,`count_product`=?,`amount`=?,`way_pay`=?,`time_start`=?,`time_end`=?,`is_finished`=?,`remark`=?  WHERE `id`=?";

  $stmt=$conn->prepare($sql_update);
  $stmt->bind_param('ssssssdddsssisi',$number,$name_part_a,$agent_a,$name_part_b,$basic_content,$name_product,$unit_price,$count_product,$amount,$way_pay,$time_start,$time_end,$is_finished,$remark,$id);
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