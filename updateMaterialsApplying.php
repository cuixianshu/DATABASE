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
conditions: (...)
idApprover: (...)
id_applyMaterials: (...)
result: (...)
whyDisagree: (...)
 */

  if($_POST['conditions']==='WithApprovedApplyingData') {
    $id_approver=$_POST['idApprover'];
    $rslt_aprvd=$_POST['result'];
    $remark=$_POST['whyDisagree'];
    $id_applyMaterials=$_POST['id_applyMaterials'];

    $sql_update="UPDATE `tbl_apply_materials` SET `id_approver`=?,`rslt_aprvd`=?,`remark`=?,`time_aprvd`=CURRENT_TIME()  WHERE `id`=?";
    $stmt=$conn->prepare($sql_update);
    $stmt->bind_param('iisi',$id_approver,$rslt_aprvd,$remark,$id_applyMaterials);
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

  // if($_POST['conditions']==='NewRequestPurchasingFunds') {
  //   $id_project=$_POST['id_project'];
  //   $amount=$_POST['amount'];
  //   $id_way_pay=$_POST['id_way_pay'];
  //   $account=$_POST['account'];
  //   $use_for=$_POST['use_for'];
  //   $remark=$_POST['remark'];
  //   $id_applyer=$_POST['id_applyer'];    
  //   $id_relative=$_POST['id_relative'];
  //   $nature=$_POST['nature'];
  //   $sql_insert="insert into `tbl_request_funds` (id_project,amount,id_way_pay,account,use_for,id_relative,remark,id_applyer,time_applied,nature) values (?,?,?,?,?,?,?,?,CURRENT_TIME(),?)";
  //   $stmt=$conn->prepare($sql_insert);
  //   $stmt->bind_param('idissisii',$id_project,$amount,$id_way_pay,$account,$use_for,$id_relative,$remark,$id_applyer,$nature);
  //   $result_insert=$stmt->execute();
  //   $stmt->free_result();
  //   $stmt->close();     
  //     //是否全部成功执行
  //   if($result_insert) {
  //     echo json_encode(true);
  //   } else {
  //     echo json_encode(false);
  //   }
  // } 

  $conn->close();
?>
