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
brand: "速度发生"
detail: "方式歌月"
id: ""
id_applier: "1"
id_project: "1"
model: "发生收费"
name: "发票开具"
neededDate: "2020-03-05"
project: "第一届射频会@2019/08/12"
quantity: "1"
remark: ""
unit: "个"



id_applyedPurchasing: "1"
idApprover: "1"
result: "1"
whyDisagree: ""
   */
  if($_POST['conditions']==='Approved') {//审核
    $id_approver=$_POST['idApprover'];
    $result_approved=$_POST['result'];
    $why_disagree=$_POST['whyDisagree'];
    $id=$_POST['id_applyedPurchasing'];

    $sql_update="UPDATE `tbl_apply_purchasing` SET `id_approver`=?,`result_approved`=?,`why_disagree`=?,`date_approved`=CURRENT_TIME() WHERE `id`=?";
    $stmt=$conn->prepare($sql_update);
    $stmt->bind_param('iisi',$id_approver,$result_approved,$why_disagree,$id);
    $result_update=$stmt->execute();
    $stmt->free_result();
    $stmt->close();    
  }

  if($_POST['conditions']==='Modified'){//更改
    $brand=$_POST['brand'];
    $detail=$_POST['detail'];
    $id=$_POST['id'];
    $model=$_POST['model'];
    $name=$_POST['name'];
    $date_needed=$_POST['date_needed'];
    // $project=$_POST['project'];
    $id_project=$_POST['id_project'];
    $quantity=$_POST['quantity'];
    $remark=$_POST['remark'];
    $id_applier=$_POST['id_applier'];
    $unit=$_POST['unit'];
  
    $sql_update="UPDATE `tbl_apply_purchasing` SET `id_project`=?,`name`=?,`unit`=?,`quantity`=?,`brand`=?,`model`=?,`detail`=?,`date_needed`=?,`remark`=?,`id_applier`=?  WHERE `id`=?";
    $stmt=$conn->prepare($sql_update);
    $stmt->bind_param('issdsssssii',$id_project,$name,$unit,$quantity,$brand,$model,$detail,$date_needed,$remark,$id_applier,$id);
    $result_update=$stmt->execute();
    $stmt->free_result();
    $stmt->close();
  }

  if($_POST['conditions']==='Finished') {//完成采购
    $id=$_POST['id_applyedPurchasing'];

    $sql_update="UPDATE `tbl_apply_purchasing` SET `is_finished`=1,`date_finished`=CURRENT_TIME() WHERE `id`=?";
    $stmt=$conn->prepare($sql_update);
    $stmt->bind_param('i',$id);
    $result_update=$stmt->execute();
    $stmt->free_result();
    $stmt->close();    
  }


    //是否成功执行
  if($result_update) {
    echo json_encode(true);
  } else {
    echo json_encode(false);
  }


  $conn->close();
?>