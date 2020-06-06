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
  
//更新出库信息
  if($_POST['conditions']==='UpdateOutboundedData') {

    $numbersOfTkts=[];
    for($i=0;$i<count($_POST['data']);$i++) {
      $numbersOfTkts[$i]=$_POST['data'][$i]['number_ticket'];
    }

    $sql_update="update tbl_tickets set date_outbound=CURDATE() where number_ticket=?";
    $stmt=$conn->prepare($sql_update);
    for($i=0;$i<count($numbersOfTkts);$i++) {
      $stmt->bind_param('s',$numbersOfTkts[$i]);
      $result_update=$stmt->execute();
      $stmt->free_result();
    }
    $stmt->close();     
      //是否全部成功执行
    if($result_update) {
      echo json_encode(true);
    } else {
      echo json_encode(false);
    }
  }

//更新改签信息
  if($_POST['conditions']==='TicketChanded') {

    $date_departure=$_POST['data']['date_departure'];
    $number_flight=$_POST['data']['number_flight'];
    $trip=$_POST['data']['trip'];
    $number_ticket=$_POST['data']['number_ticket'];
    $fee_change_trip=$_POST['data']['feeOfChanging'];

    $sql_update="UPDATE `tbl_tickets` SET `fee_change_trip`=`fee_change_trip`+?,`name_psgr`=CONCAT(`name_psgr`,'(改)'),`date_departure`=?,`number_flight`=?,`trip`=? WHERE `number_ticket`=?";
    $stmt=$conn->prepare($sql_update);
    $stmt->bind_param('issss',$fee_change_trip,$date_departure,$number_flight,$trip,$number_ticket);
    $result_update=$stmt->execute();
    $stmt->free_result();
    $stmt->close();     
      //是否全部成功执行
    if($result_update) {
      echo json_encode(true);
    } else {
      echo json_encode(false);
    }
  }

//更新退票信息,如果已收取票款,需生成退票请款单
  if($_POST['conditions']==='TicketRefound') {
    
    $number_ticket=$_POST['data']['number_ticket'];
    $fee_refound=$_POST['data']['fee_refound'];
    $amount_clctd=$_POST['data']['amount_clctd'];

   if($amount_clctd==0) {//尚未收取票款
      $sql_update="UPDATE `tbl_tickets` SET `date_refound`=CURDATE(),`name_psgr`=CONCAT(`name_psgr`,'(退)'),`fee_refound`=?,`amount_actual_returned`=0 WHERE `number_ticket`=?";
      $stmt=$conn->prepare($sql_update);
      $stmt->bind_param('is',$fee_refound,$number_ticket);
      $result_update_tickets=$stmt->execute();
      $stmt->free_result();
      $stmt->close();
        //是否全部成功执行
      if($result_update_tickets) {
        echo json_encode(true);
      } else {
        echo json_encode(false);
      }

    } else {//已经收取票款

      //获取此票的原收款信息
      $sql_select_cashier="select * from tbl_cashier where other like concat('%',?,'%')";
      $stmt=$conn->prepare($sql_select_cashier);
      $stmt->bind_param('s',$number_ticket);
      $stmt->execute();
      $result_slct_tktCashier = $stmt->get_result();
      // if(!$result_slct_tktCashier) {
      //   echo json_encode($conn->error);
      //   exit;
      // }
      $i=0;
      while ($row = $result_slct_tktCashier->fetch_assoc()) {
          $tktCashier[$i]=$row;
          $i++;
      }
      $stmt->free_result();
      $stmt->close();
// echo $number_ticket;
// exit;

      //需要更新2个表,tbl_tickets tbl_request_funds,用事务更新
      $conn->autocommit(false);
// echo json_encode($tktCashier[0]['id']);
// exit;
      //更新tbl_tickets
      $sql_update_tickets="UPDATE `tbl_tickets` SET `date_refound`=CURDATE(),`name_psgr`=CONCAT(`name_psgr`,'(退)'),`fee_refound`=`fee_refound`+?,`amount_actual_returned`=0 WHERE `number_ticket`=?";
      $stmt=$conn->prepare($sql_update_tickets);
      $stmt->bind_param('is',$fee_refound,$number_ticket);
      $result_update_tickets=$stmt->execute();
      $stmt->free_result();
      $stmt->close();

      //更新tbl_request_funds
      $amount=$_POST['data']['amount_clctd']-$_POST['data']['insurance']-$_POST['data']['fee_refound']-$_POST['data']['amount_actual_returned'];
      $id_project=$tktCashier[0]['id_project'];
      $id_way_pay=$tktCashier[0]['id_way_pay'];
      $account=$_POST['data']['name_psgr'];
      $use_for='^^~'.$_POST['data']['number_ticket'].'~^^';
      $id_applyer=$_POST['currentUserID'];

      $sql_insert_rqst_funds="insert into `tbl_request_funds` (id_project,amount,id_way_pay,account,use_for,id_applyer,time_applied,nature) values (?,?,?,?,?,?,CURRENT_TIME(),4)";
      $stmt=$conn->prepare($sql_insert_rqst_funds);
      $stmt->bind_param('idissi',$id_project,$amount,$id_way_pay,$account,$use_for,$id_applyer);
      $result_insert_rqst_funds=$stmt->execute();
      $stmt->free_result();
      $stmt->close();

      if($result_update_tickets && $result_insert_rqst_funds) {
        echo json_encode(true);
        $conn->commit();  //操作无误，提交事务
      } else {
        echo json_encode(false);
        $conn->rollback(); //回滚事务
      }

      $conn->autocommit(true); //重新设置为自动提交
    }

  }

  $conn->close();
?>
