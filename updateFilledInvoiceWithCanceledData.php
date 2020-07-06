<?php
  include_once 'linkToCXS.php';

	$conn->autocommit(false); //设置为非自动提交DATE_FORMAT(start_time,'%Y-%m-%d')

  $sql_update_fill="UPDATE `tbl_fill_invoice` SET `id_canceled_by`=?,`time_canceled`=CURRENT_TIME(),`other`=CONCAT_WS(',','canceled reason:',?,other) WHERE `id`=?";
  $sql_update_request="UPDATE `tbl_rqst_invoice` SET `id_fill_invoice`=null WHERE `id_fill_invoice`=?";
  $sql_update_orders="UPDATE `tbl_orders` SET `id_fill_invoice`=null WHERE `id_fill_invoice`=?";

  $id=$_POST['id'];
	$id_canceled_by=$_POST['id_canceled_by'];
  $other=$_POST['other'];
  
  $result_update_orders=false;
  $stmt=$conn->prepare($sql_update_orders);
  $stmt->bind_param('i',$id);
  $result_update_orders=$stmt->execute();
  $stmt->free_result();
  $stmt->close();
    //因为返回结果只有一行,所以没用下面的标准用法 while($row=$result_select->fetch_assoc()){//fetch_assoc以一个关联数组方式抓取一行结果
    //     $id_request_invoice=$row["id"];
    // }

	$result_update_rqst=false;
  $stmt=$conn->prepare($sql_update_request);
  $stmt->bind_param('i',$id);
  $result_update_rqst=$stmt->execute();
  $stmt->free_result();
  $stmt->close();

	$result_update_fill=false;
  $stmt=$conn->prepare($sql_update_fill);
  $stmt->bind_param('isi',$id_canceled_by,$other,$id);
  $result_update_fill=$stmt->execute();
  $stmt->free_result();
  $stmt->close();
//是否全部成功执行
  if($result_update_orders && $result_update_fill && $result_update_rqst) {
    echo json_encode(true);
	  $conn->commit();  //操作无误，提交事务
    } else {
      echo json_encode(false);
      $conn->rollback(); //回滚事务
  }

	$conn->autocommit(true); //重新设置为自动提交
	$conn->close();
?>