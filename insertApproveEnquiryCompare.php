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

  /*
idApplied: "1"
id_selected_enquiry: 10
idApprover: 1
result: "1"
whyDisagree: ""
   */
  $conn->autocommit(false);

  $id_applied=$_POST['idApplied'];
  $id_selected_enquiry=$_POST['id_selected_enquiry'];
  $id_approver=$_POST['idApprover'];
  $result_approved=$_POST['result'];
  $reason_approved=$_POST['whyDisagree'];

  
  $sql_insert="insert into `tbl_approved_enquiry_compare_price` (id_applied,id_selected_enquiry,id_approver,result_approved,reason_approved,date_approved) values (?,?,?,?,?,CURRENT_TIME())";

  $stmt=$conn->prepare($sql_insert);
  $stmt->bind_param('iiiis',$id_applied,$id_selected_enquiry,$id_approver,$result_approved,$reason_approved);
  $result_insert=$stmt->execute();
  $stmt->free_result();
  $stmt->close();

  //更新tbl_enquiry_price
  if($result_approved==0) {//审核未通过
    $sql_update="UPDATE `tbl_enquiry_price` SET `is_commited_to_approve`=1 WHERE `id_applied_purchasing`=?";

  } else {//审核通过
    $sql_update="UPDATE `tbl_enquiry_price` SET `is_commited_to_approve`=2 WHERE `id_applied_purchasing`=?";
  }
  $stmt=$conn->prepare($sql_update);
  $stmt->bind_param('i',$id_applied);
  $result_update=$stmt->execute();
  $stmt->free_result();
  $stmt->close();



    //是否全部成功执行
  if($result_insert && $result_update) {
    echo json_encode(true);
    $conn->commit();
  } else {
    echo json_encode(false);
    $conn->rollback();
  }

  $conn->autocommit(true);
  $conn->close();
?>
