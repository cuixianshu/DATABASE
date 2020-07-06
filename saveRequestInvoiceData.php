<?php
  include_once 'linkToCXS.php';
	$conn->autocommit(false); //设置为非自动提交

    $sql_insert="insert into tbl_rqst_invoice (id_of_our_cmpny,id_type_invoice,id_clt_prnt_ognztn,googs_name,amount,id_applyer,time_apply,other) values (?,?,?,?,?,?,CURRENT_TIME(),?)";
    $sql_select="select max(id) as id from tbl_rqst_invoice";
    $sql_update="UPDATE `tbl_orders` SET `id_request_invoice`=? WHERE `id`=?";
    //插入数据到tbl_rqst_invoice
	$id_of_our_cmpny=$_POST['idOfOurCmpny'];
	$id_type_invoice=$_POST['type'];
	$id_clt_prnt_ognztn=$_POST['idOfCstmrOgnztn'];
	$googs_name=$_POST['nameOfGoods'];
	$amount=$_POST['amount'];
	$id_applyer=$_POST['idOfApplyer'];
	$other=$_POST['mem'];


    $stmt=$conn->prepare($sql_insert);
    $stmt->bind_param('iiisdis',$id_of_our_cmpny,$id_type_invoice,$id_clt_prnt_ognztn,$googs_name,$amount,$id_applyer,$other);
    $result_insert=$stmt->execute();
    $stmt->free_result();
    $stmt->close();

    //获取刚插入tbl_rqst_invoice中的id   result_select
    $result_select=false;
    if($result=$conn->query($sql_select)){
      $result_select=true;
      $row=$result->fetch_assoc();
      $id_request_invoice=$row["id"];
      $result->close();    	
    }

    //因为返回结果只有一行,所以没用下面的标准用法 while($row=$result_select->fetch_assoc()){//fetch_assoc以一个关联数组方式抓取一行结果
    //     $id_request_invoice=$row["id"];
    // }

    //将开票申请的id写入到tbl_orders中对应的订单中
	$list_of_IDs=$_POST['listOfIDS'];
	$result_update=false;
    $stmt=$conn->prepare($sql_update);
    for($i=0;$i<count($list_of_IDs);$i++) {
      $stmt->bind_param('ii',$id_request_invoice,$list_of_IDs[$i]);
      $result_update=$stmt->execute();
      $stmt->free_result();
      if(!$result_update) {
        break;
      }
    }
    $stmt->close();

    //是否全部成功执行
    if($result_insert && $result_select && $result_update) {
      echo json_encode(true);
	  $conn->commit();  //操作无误，提交事务
    } else {
      echo json_encode(false);
      $conn->rollback(); //回滚事务
    }

	$conn->autocommit(true); //重新设置为自动提交

	$conn->close();
?>
