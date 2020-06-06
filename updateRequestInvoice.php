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
	$conn->autocommit(false); //设置为非自动提交

  $sql_delete="delete from tbl_rqst_invoice where id=?";
  $sql_update="UPDATE `tbl_orders` SET `id_request_invoice`=null WHERE `id_request_invoice`=? and ISNULL(`id_fill_invoice`)";

	$result_delete=false;
  $id=$_POST['id'];
  $stmt=$conn->prepare($sql_delete);
  $stmt->bind_param('i',$id);
  $result_delete=$stmt->execute();
  $stmt->free_result();
  $stmt->close();

  //因为返回结果只有一行,所以没用下面的标准用法 while($row=$result_select->fetch_assoc()){//fetch_assoc以一个关联数组方式抓取一行结果
  //     $id_request_invoice=$row["id"];
  // }

	$result_update=false;
  $stmt=$conn->prepare($sql_update);
  $stmt->bind_param('i',$id);
  $result_update=$stmt->execute();
  $stmt->free_result();
  $stmt->close();

    //是否全部成功执行
  if($result_delete && $result_update) {
    echo json_encode(true);
	  $conn->commit();  //操作无误，提交事务
  } else {
    echo json_encode(false);
    $conn->rollback(); //回滚事务
  }

	$conn->autocommit(true); //重新设置为自动提交

	$conn->close();
?>