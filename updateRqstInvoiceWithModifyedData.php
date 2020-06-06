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

  $name=$_POST['project'];
  $sql="select id from tbl_project where name=?";
  $stmt=$conn->prepare($sql);
  $stmt->bind_param('s',$name);
  $stmt->bind_result($id_project);
  $stmt->execute();
  $stmt->fetch();//只有一个数据
  // while($stmt->fetch()){
      // echo "联系人ID:".$id_contacter.";";
  // }
  $stmt->free_result();
  $stmt->close();

  $amount=$_POST['amount'];
  $id=$_POST['id'];
  $idOfApplyer=$_POST['idOfApplyer'];
  $idOfCstmrOgnztn=$_POST['idOfCstmrOgnztn'];
  $idOfOurCmpny=$_POST['idOfOurCmpny'];
  $idOfType=$_POST['idOfType'];
  $memInRqst=$_POST['memInRqst'];
  $nameOfGoods=$_POST['nameOfGoods'];


  $sql_update="UPDATE `tbl_rqst_invoice` SET `id_of_our_cmpny`=?,`id_type_invoice`=?,`id_clt_prnt_ognztn`=?,`googs_name`=?,`amount`=?,`id_applyer`=?,`time_apply`=CURRENT_TIME(),`other`=?  WHERE `id`=?";

  //因为返回结果只有一行,所以没用下面的标准用法 while($row=$result_select->fetch_assoc()){//fetch_assoc以一个关联数组方式抓取一行结果
  //     $id_request_invoice=$row["id"];
  // }

  $stmt=$conn->prepare($sql_update);
  $stmt->bind_param('iiisdisi',$idOfOurCmpny,$idOfType,$idOfCstmrOgnztn,$nameOfGoods,$amount,$idOfApplyer,$memInRqst,$id);
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