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
  
  if($_POST['conditions']==="ByInvoice") {
    $conn->autocommit(false); //设置为非自动提交

    $sql_insert="insert into tbl_cashier (id_way_pay,id_account,amount,id_cashier,time_collect,other,id_project) values (?,?,?,?,CURRENT_TIME(),?,?)";
    $sql_select="select max(id) as id from tbl_cashier";
    $sql_update_fill="UPDATE `tbl_fill_invoice` SET `id_tbl_cashier`=? WHERE `id`=?";
    $sql_update_orders="UPDATE `tbl_orders` SET `id_cashier`=? WHERE `id_fill_invoice`=?";

    $id_fill_invoice=$_POST['id'];
    $id_way_pay=$_POST['id_way_pay'];
    $id_account=$_POST['id_account'];
    $amount=$_POST['amount'];
    $id_cashier=$_POST['id_cashier'];
    $other=$_POST['other'];
    $id_project=$_POST['id_project'];
  
    //插入数据到tbl_cashier
    $result_insert=false;  
    $stmt=$conn->prepare($sql_insert);
    $stmt->bind_param('iidisi',$id_way_pay,$id_account,$amount,$id_cashier,$other,$id_project);
    $result_insert=$stmt->execute();
    $stmt->free_result();
    $stmt->close();
    //获取刚插入tbl_cashier中的id
    $result_select=false;
    if($result=$conn->query($sql_select)){
      $result_select=true;
      $row=$result->fetch_assoc();
      $id_tbl_cashier=$row["id"];
      $result->close();     
    }

    //因为返回结果只有一行,所以没用下面的标准用法 while($row=$result_select->fetch_assoc()){//fetch_assoc以一个关联数组方式抓取一行结果
    //     $id_request_invoice=$row["id"];
    // }

    //将收款的id写入到tbl_fill_invoice中
    $result_update_fill=false;
    $stmt=$conn->prepare($sql_update_fill);
    $stmt->bind_param('ii',$id_tbl_cashier,$id_fill_invoice);
    $result_update_fill=$stmt->execute();
    $stmt->free_result();
    $stmt->close();
    //将开票的id写入到tbl_orders中
    $result_update_orders=false;
    $stmt=$conn->prepare($sql_update_orders);
    $stmt->bind_param('ii',$id_tbl_cashier,$id_fill_invoice);
    $result_update_orders=$stmt->execute();
    $stmt->free_result();
    $stmt->close();
    //是否全部成功执行
    if($result_insert && $result_select && $result_update_fill && $result_update_orders) {
      echo json_encode(true);
      $conn->commit();  //操作无误，提交事务
    } else {
      echo json_encode(false);
      $conn->rollback(); //回滚事务
    }

    $conn->autocommit(true); //重新设置为自动提交
    $conn->close();
  }

  if($_POST['conditions']==="WithManualData") {
    $sql_insert="insert into tbl_cashier (id_way_pay,id_account,amount,id_cashier,time_collect,other,id_project) values (?,?,?,?,CURRENT_TIME(),?,?)";
    $id_way_pay=$_POST['id_way_pay'];
    $id_account=$_POST['id_account'];
    $amount=$_POST['amount'];
    $id_cashier=$_POST['id_cashier'];
    $other=$_POST['other'];
    $id_project=$_POST['id_project'];

    $stmt=$conn->prepare($sql_insert);
    $stmt->bind_param('iidisi',$id_way_pay,$id_account,$amount,$id_cashier,$other,$id_project);
    $result_insert=$stmt->execute();
    $stmt->free_result();
    $stmt->close();
 
    if($result_insert) {
      echo json_encode(true);
      } else {
      echo json_encode(false);
    }

    $conn->close(); 
  }

  if($_POST['conditions']==="WithCheckedData") {
    $sql_update="UPDATE `tbl_cashier` set `id_way_pay`=?,`id_account`=?,`amount`=?,`id_confirmer`=?,`time_confirm`=CURRENT_TIME(),`describe_confirm`=?,`id_project`=? where `id`=?";
    $id_way_pay=$_POST['id_way_pay'];
    $id_account=$_POST['id_account'];
    $amount=$_POST['amount'];
    $id_confirmer=$_POST['id_confirmer'];
    $describe_confirm=$_POST['describe_confirm'];
    $id_project=$_POST['id_project'];
    $id=$_POST['id'];

    $stmt=$conn->prepare($sql_update);
    $stmt->bind_param('iidisii',$id_way_pay,$id_account,$amount,$id_confirmer,$describe_confirm,$id_project,$id);
    $result_update=$stmt->execute();
    $stmt->free_result();
    $stmt->close();
 
    if($result_update) {
      echo json_encode(true);
      } else {
      echo json_encode(false);
    }

    $conn->close();    
  }

  if($_POST['conditions']==="ByTurnInFunds") {
    $conn->autocommit(false); //设置为非自动提交
    $sql_insert="insert into tbl_cashier (id_way_pay,id_account,amount,id_cashier,time_collect,other,id_project) values (?,?,?,?,CURRENT_TIME(),?,?)";
    $sql_select="select max(id) as id from tbl_cashier";
    $sql_update_turn_in_funds="UPDATE `tbl_turnin_funds` SET `id_tbl_cashier`=? WHERE `id`=?";

    $id_way_pay=$_POST['id_way_pay'];
    $id_account=$_POST['id_account'];
    $amount=$_POST['amount'];
    $id_cashier=$_POST['id_cashier'];
    $other=$_POST['remark'];
    $id_project=$_POST['id_project'];
  
    //插入数据到tbl_cashier
    $result_insert=false;  
    $stmt=$conn->prepare($sql_insert);
    $stmt->bind_param('iidisi',$id_way_pay,$id_account,$amount,$id_cashier,$other,$id_project);
    $result_insert=$stmt->execute();
    $stmt->free_result();
    $stmt->close();
    //获取刚插入tbl_cashier中的id
    $result_select=false;
    $id_tbl_cashier='';
    if($result=$conn->query($sql_select)){
      $result_select=true;
      $row=$result->fetch_assoc();
      $id_tbl_cashier=$row["id"];
      $result->close();     
    }

    //因为返回结果只有一行,所以没用下面的标准用法 while($row=$result_select->fetch_assoc()){//fetch_assoc以一个关联数组方式抓取一行结果
    //     $id_request_invoice=$row["id"];
    // }

    //将收款的id写入到tbl_turn_in_funds中
  
    $id_turn_in_funds=$_POST['id_turn_in_funds'];
    $result_update_turn_in=false;
    $stmt=$conn->prepare($sql_update_turn_in_funds);
    $stmt->bind_param('ii',$id_tbl_cashier,$id_turn_in_funds);
    $result_update_turn_in=$stmt->execute();
    $stmt->free_result();
    $stmt->close();


    //是否全部成功执行
    if($result_insert && $result_select && $result_update_turn_in) {
      echo json_encode(true);
      $conn->commit();  //操作无误，提交事务
      } else {
        echo json_encode(false);
        $conn->rollback(); //回滚事务
    }

    $conn->autocommit(true); //重新设置为自动提交
    $conn->close();
  }


  if($_POST['conditions']==="SaveTktCollection") {
    $conn->autocommit(false); //设置为非自动提交
   
    $sql_insert="insert into tbl_cashier (id_way_pay,id_account,amount,id_cashier,time_collect,other,id_project) values (?,?,?,?,CURRENT_TIME(),?,?)";
    // $sql_select="select max(id) as id from tbl_cashier";

    $id_way_pay=$_POST['id_way_pay'];
    $id_account=$_POST['id_account'];
    $amount=$_POST['amountIncludeInsurance']+$_POST['changeFee']+$_POST['refoundFee'];
    $id_cashier=$_POST['id_cashier'];
    $other='票款'.$_POST['number_ticket'].';'.$_POST['other'];
    $id_project=$_POST['id_project'];
  
    //插入数据到tbl_cashier
    $result_insert=false;  
    $stmt=$conn->prepare($sql_insert);
    $stmt->bind_param('iidisi',$id_way_pay,$id_account,$amount,$id_cashier,$other,$id_project);
    $result_insert=$stmt->execute();
    $stmt->free_result();
    $stmt->close();
    //获取刚插入tbl_cashier中的id
    // $result_select=false;
    // $id_tbl_cashier='';
    // if($result=$conn->query($sql_select)){
    //   $result_select=true;
    //   $row=$result->fetch_assoc();
    //   $id_tbl_cashier=$row["id"];
    //   $result->close();     
    // }

    //因为返回结果只有一行,所以没用下面的标准用法 while($row=$result_select->fetch_assoc()){//fetch_assoc以一个关联数组方式抓取一行结果
    //     $id_request_invoice=$row["id"];
    // }

    //将收款的id写入到tbl_turn_in_funds中
/*
amountIncludeInsurance: (...)
changeFee: (...)
conditions: "SaveTktCollection"
id_account: (...)
id_cashier: 1
id_project: (...)
id_way_pay: (...)
number_ticket: (...)
other: "票号781-5272209541;"
refoundFee: (...)
 */   
    
    $sql_update_tickets="UPDATE `tbl_tickets` SET `amount_clctd`=`amount_clctd`+?,`date_clctd`=IF(?>0,CURDATE(),`date_clctd`),`amount_clctd_refound`=`amount_clctd_refound`+?,`date_clct_refound_fee`=IF(?>0,CURDATE(),`date_clct_refound_fee`),`amount_clctd_changing_fee`=`amount_clctd_changing_fee`+?,`date_clct_change_fee`=IF(?>0,CURDATE(),`date_clct_change_fee`) WHERE `number_ticket`=?";
    $number_ticket=$_POST['number_ticket'];
    $amount_clctd=$_POST['amountIncludeInsurance'];
    $amount_clctd_refound=$_POST['refoundFee'];
    $amount_clctd_changing_fee=$_POST['changeFee'];
    $result_update_tickets=false;
    $stmt=$conn->prepare($sql_update_tickets);
    $stmt->bind_param('iiiiiis',$amount_clctd,$amount_clctd,$amount_clctd_refound,$amount_clctd_refound,$amount_clctd_changing_fee,$amount_clctd_changing_fee,$number_ticket);
    $result_update_tickets=$stmt->execute();
    $stmt->free_result();
    $stmt->close();

    //是否全部成功执行
    if($result_insert && $result_update_tickets) {
      echo json_encode(true);
      $conn->commit();  //操作无误，提交事务
      } else {
        echo json_encode(false);
        $conn->rollback(); //回滚事务
    }

    $conn->autocommit(true); //重新设置为自动提交
    $conn->close();
  }  

?>
