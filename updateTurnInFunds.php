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

// amount: "056"
// cause: "海天白云加大海"
// conditions: "InsertNew"
// currentUserId: "1"
// id: ""
// id_project: "1"
// id_way_pay: "5"
// remark: ""
// way: "电汇"
  

  if($_POST['conditions']==='InsertNew') {
    $cause=$_POST['cause'];
    $id_project=$_POST['id_project'];
    $id_way_pay=$_POST['id_way_pay'];
    $id_payer=$_POST['currentUserId'];
    $amount=$_POST['amount'];
    $remark=$_POST['remark'];

    $sql_insert="insert into `tbl_turnin_funds` (cause,  id_project,id_way_pay,id_payer,amount,time_paid,remark) values (?,?,?,?,?,CURRENT_TIME(),?)";
    $stmt=$conn->prepare($sql_insert);
    $stmt->bind_param('siiids',$cause,$id_project,$id_way_pay,$id_payer,$amount,$remark);
    $result_insert=$stmt->execute();
    $stmt->free_result();
    $stmt->close();     
      //是否全部成功执行
    if($result_insert) {
      echo json_encode(true);
    } else {
      echo json_encode(false);
    }
  }

  if($_POST['conditions']==='Update') {
    $cause=$_POST['cause'];
    $id_project=$_POST['id_project'];
    $id_way_pay=$_POST['id_way_pay'];
    $id_payer=$_POST['currentUserId'];
    $amount=$_POST['amount'];
    $remark=$_POST['remark'];   
    $id=$_POST['id'];
    $sql_update="UPDATE `tbl_turnin_funds` SET `cause`=?,`id_project`=?,`id_way_pay`=?,`id_payer`=?,`amount`=?,`remark`=?,`time_paid`=CURRENT_TIME() WHERE `id`=?";
    $stmt=$conn->prepare($sql_update);
    $stmt->bind_param('siiidsi',$cause,$id_project,$id_way_pay,$id_payer,$amount,$remark,$id);
    $result_update=$stmt->execute();
    $stmt->free_result();
    $stmt->close();  
    //是否全部成功执行
    if($result_update) {
      echo json_encode(true);
      // $conn->commit();
    } else {
      echo json_encode(false);
      // $conn->rollback();
    }  
  }

  $conn->close();
?>
