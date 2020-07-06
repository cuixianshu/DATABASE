<?php
  include_once 'linkToCXS.php';
	$conn->autocommit(false); //设置为非自动提交

  $sql_insert="insert into tbl_fill_invoice (num_of_invoice, amount, time_fill, id_filler,path_pict, other) values (?,?,CURRENT_TIME(),?,?,?)";
  $sql_select="select max(id) as id from tbl_fill_invoice";
  $sql_update_request="UPDATE `tbl_rqst_invoice` SET `id_fill_invoice`=? WHERE `id`=?";
  $sql_update_orders="UPDATE `tbl_orders` SET `id_fill_invoice`=? WHERE `id_request_invoice`=?";
  //插入数据到tbl_fill_invoice
  $num_of_invoice=$_POST['numberOfInvoice'];
	$amount=$_POST['amount'];
  $id_filler=$_POST['idOfFiller'];
	$path_pict=$_POST['imageOfInvoice'];
	$other=$_POST['memForFilling'];

  $stmt=$conn->prepare($sql_insert);
  $stmt->bind_param('sdiss',$num_of_invoice,$amount,$id_filler,$path_pict,$other);
  $result_insert=$stmt->execute();
  $stmt->free_result();
  $stmt->close();
//获取刚插入tbl_fill_invoice中的id
  $result_select=false;
  if($result=$conn->query($sql_select)){
    $result_select=true;
    $row=$result->fetch_assoc();
    $id_fill_invoice=$row["id"];
    $result->close();    	
  }

    //因为返回结果只有一行,所以没用下面的标准用法 while($row=$result_select->fetch_assoc()){//fetch_assoc以一个关联数组方式抓取一行结果
    //     $id_request_invoice=$row["id"];
    // }

    //将开票的id写入到tbl_rqst_invoice中
	$list_of_IDs=$_POST['listOfIDS'];
	$result_update_rqst=false;
  $stmt=$conn->prepare($sql_update_request);
  for($i=0;$i<count($list_of_IDs);$i++) {
    $stmt->bind_param('ii',$id_fill_invoice,$list_of_IDs[$i]);
    $result_update_rqst=$stmt->execute();
    $stmt->free_result();
    if(!$result_update_rqst) {
      break;
    }
  }
  $stmt->close();
 //将开票的id写入到tbl_orders中
	$result_update_orders=false;
  $stmt=$conn->prepare($sql_update_orders);
  for($i=0;$i<count($list_of_IDs);$i++) {
    $stmt->bind_param('ii',$id_fill_invoice,$list_of_IDs[$i]);
    $result_update_orders=$stmt->execute();
    $stmt->free_result();
    if(!$result_update_orders) {
      break;
    }
  }    
  $stmt->close();
//是否全部成功执行
  if($result_insert && $result_select && $result_update_rqst && $result_update_orders) {
    echo json_encode(true);
	  $conn->commit();  //操作无误，提交事务
    } else {
      echo json_encode(false);
      $conn->rollback(); //回滚事务
  }

	$conn->autocommit(true); //重新设置为自动提交
	$conn->close();
?>