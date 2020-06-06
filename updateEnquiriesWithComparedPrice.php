<?php
  $servername = "localhost";
  $username = "root";
  $password = "Mwy197301242811";
  $dbname = "cuixianshu"; // 要操作的数据库名
  $outputData=array();
  // 创建连接 
  $conn= new mysqli($servername,$username,$password,$dbname); // 注意第四个参数
  if($conn->connect_error){
      die("连接失败，错误:" . $conn->connect_error);
  }
  // echo json_encode($_POST);
  // exit;
  /*
reason
selectedID
idOfApplyedPurchasing
   */
    $reason_of_deal=$_POST['reason'];
    $id=$_POST['selectedID'];
    $id_applied_purchasing=$_POST['idOfApplyedPurchasing'];

    $conn->autocommit(false);

    $sql_update_made="UPDATE `tbl_enquiry_price` SET `is_made_deal`=1,`is_commited_to_approve`=0,`reason_of_deal`=? WHERE `id`=?";
    $sql_update_missed="UPDATE `tbl_enquiry_price` SET `is_made_deal`=0,`is_commited_to_approve`=0,`reason_of_deal`=null WHERE `id`<>? AND `id_applied_purchasing`=?";

    $stmt=$conn->prepare($sql_update_made);
    $stmt->bind_param('si',$reason_of_deal,$id);
    $result_update_made=$stmt->execute();
    $stmt->free_result();
    $stmt->close();

    $stmt=$conn->prepare($sql_update_missed);
    $stmt->bind_param('ii',$id,$id_applied_purchasing);
    $result_update_missed=$stmt->execute();
    $stmt->free_result();
    $stmt->close();

    //是否全部成功执行
  if($result_update_made && $result_update_missed) {
    echo json_encode(true);
    $conn->commit();
  } else {
    echo json_encode(false);
    $conn->rollback();
  }

  $conn->autocommit(true);
  $conn->close();
?>