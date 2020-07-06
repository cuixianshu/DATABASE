<?php  
  include_once 'linkToCXS.php';
  $respondedData=[];


  //未出库的票
  if($_POST['conditions']==='NotOutBounded') {
    $keyWord=$_POST['keyWord'];
    $start_date=$_POST['dateRange'][0];
    $end_date=$_POST['dateRange'][1];    
    $sql="select * from tbl_tickets where date_outbound IS NULL and (name_psgr like CONCAT('%',?,'%') or number_ticket like CONCAT('%',?,'%') or dptmt_client like CONCAT('%',?,'%')) and (DATE_FORMAT(date_issued,'%Y-%m-%d') between ? and ?)";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('sssss',$keyWord,$keyWord,$keyWord,$start_date,$end_date);
    $stmt->execute();
    $result = $stmt->get_result();
    if(!$result) {
      echo json_encode($conn->error);
      exit;
    }
    $i=0;
    while ($row = $result->fetch_assoc()) {
      $respondedData[$i]=$row;
      $i++;
    }    
    echo json_encode($respondedData);
    $stmt->free_result();
    $stmt->close();
    $conn->close();        
  }

//可以改签的票
  if($_POST['conditions']==='NotRefoundedAndNotDepartured') {
    $keyWord=$_POST['keyWord'];
    $start_date=$_POST['dateRange'][0];
    $end_date=$_POST['dateRange'][1];    
    $sql="select * from tbl_tickets where date_refound IS NULL and (name_psgr like CONCAT('%',?,'%') or number_ticket like CONCAT('%',?,'%') or dptmt_client like CONCAT('%',?,'%')) and (DATE_FORMAT(date_departure,'%Y-%m-%d') between ? and ?) and DATE_FORMAT(date_departure,'%Y-%m-%d')>=CURDATE() and date_clctd IS NULL";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('sssss',$keyWord,$keyWord,$keyWord,$start_date,$end_date);
    $stmt->execute();
    $result = $stmt->get_result();
    if(!$result) {
      echo json_encode($conn->error);
      exit;
    }
    $i=0;
    while ($row = $result->fetch_assoc()) {
      $respondedData[$i]=$row;
      $i++;
    }    
    echo json_encode($respondedData);
    $stmt->free_result();
    $stmt->close();
    $conn->close();        
  }

//未支付退票费的退票
  if($_POST['conditions']==='BeenRefoundedAndNotPaidFeeAndCollected') {
    $keyWord=$_POST['keyWord'];
    $start_date=$_POST['dateRange'][0];
    $end_date=$_POST['dateRange'][1];    
    $sql="select * from tbl_tickets where date_refound IS NOT NULL and (name_psgr like CONCAT('%',?,'%') or number_ticket like CONCAT('%',?,'%') or dptmt_client like CONCAT('%',?,'%')) and (DATE_FORMAT(date_issued,'%Y-%m-%d') between ? and ?) and date_clctd IS NOT NULL and amount_clctd>=(price+tax+insurance) and amount_actual_returned=0 and fee_refound>0 and number_ticket not in (select substr(use_for, instr(use_for,'rfdTkdNum:')+10,instr(use_for,';')-(instr(use_for,'rfdTkdNum:')+10)) as num_tkt from tbl_request_funds where use_for like concat('%',number_ticket,'%') and use_for IS NOT NULL)";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('sssss',$keyWord,$keyWord,$keyWord,$start_date,$end_date);
    $stmt->execute();
    $result = $stmt->get_result();
    if(!$result) {
      echo json_encode($conn->error);
      exit;
    }
    $i=0;
    while ($row = $result->fetch_assoc()) {
      $respondedData[$i]=$row;
      $i++;
    }    
    echo json_encode($respondedData);
    $stmt->free_result();
    $stmt->close();
    $conn->close();        
  }  
//可以退的票  DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
  if($_POST['conditions']==='NotRefoundedAndNotUsed') {
    $keyWord=$_POST['keyWord'];
    $start_date=$_POST['dateRange'][0];
    $end_date=$_POST['dateRange'][1];    
    $sql="select * from tbl_tickets where date_refound IS NULL and INSTR(name_psgr,'(退)')=0 and DATE_SUB(DATE_FORMAT(date_issued,'%Y-%m-%d'),INTERVAL 12 MONTH) and (name_psgr like CONCAT('%',?,'%') or number_ticket like CONCAT('%',?,'%') or dptmt_client like CONCAT('%',?,'%')) and (DATE_FORMAT(date_issued,'%Y-%m-%d') between ? and ?) and date_clctd IS NULL";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('sssss',$keyWord,$keyWord,$keyWord,$start_date,$end_date);
    $stmt->execute();
    $result = $stmt->get_result();
    if(!$result) {
      echo json_encode($conn->error);
      exit;
    }
    $i=0;
    while ($row = $result->fetch_assoc()) {
      $respondedData[$i]=$row;
      $i++;
    }    
    echo json_encode($respondedData);
    $stmt->free_result();
    $stmt->close();
    $conn->close();        
  }
//未收款的票
  if($_POST['conditions']==='GetToCollect') {
    $keyWord=$_POST['keyWord'];
    $start_date=$_POST['dateRange'][0];
    $end_date=$_POST['dateRange'][1];    
    $sql="select *,(price+tax+insurance) as amount_include_insurance from tbl_tickets where ((amount_clctd<(price+tax+insurance) and date_refound IS NULL) or (fee_refound>0 and amount_clctd_refound<fee_refound and amount_clctd<(fee_refound-amount_clctd_refound)) or (fee_change_trip>0 and amount_clctd_changing_fee<fee_change_trip) or (date_refound IS NOT NULL and insurance>0 and amount_clctd<insurance)) and (name_psgr like CONCAT('%',?,'%') or number_ticket like CONCAT('%',?,'%') or dptmt_client like CONCAT('%',?,'%')) and (DATE_FORMAT(date_departure,'%Y-%m-%d') between ? and ?) and date_outbound IS NOT NULL";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('sssss',$keyWord,$keyWord,$keyWord,$start_date,$end_date);
    $stmt->execute();
    $result = $stmt->get_result();
    if(!$result) {
      echo json_encode($conn->error);
      exit;
    }
    $i=0;
    while ($row = $result->fetch_assoc()) {
      $respondedData[$i]=$row;
      $i++;
    }    
    echo json_encode($respondedData);
    $stmt->free_result();
    $stmt->close();
    $conn->close();        
  }

//用于报表的票
  if($_POST['conditions']==='ForSaleReport') {

    // $keyWord=$_POST['keyWord'];
    $start_date=$_POST['dateRange'][0];
    $end_date=$_POST['dateRange'][1];
    $dptmt_client=$_POST['clntDptmt_short_name'];
    $id_project=$_POST['id_project'];
    $name_psgr=trim($_POST['psgrName']);
    $isOutbounded=$_POST['isOutbounded'];
    $isReceivedFee=$_POST['isReceivedFee'];
    $isRefounded=$_POST['isRefounded'];

    $sql="select * from tbl_tickets where (date_issued between STR_TO_DATE('".$start_date."','%Y-%m-%d') and STR_TO_DATE('".$end_date."','%Y-%m-%d'))";

    if($dptmt_client!='0') {
      $sql.=" and (dptmt_client like '%".$dptmt_client."%')";
    }
    if($id_project!='0') {
      $sql.=" and (id_project=".$id_project.")";
    }
    if($isOutbounded=='1') {
      $sql.=" and (date_outbound IS NOT NULL)";
    } else if($isOutbounded=='0') {
      $sql.=" and (ISNULL(date_outbound))";
    }
    if($isReceivedFee=='1') {
      $sql.=" and (date_clctd IS NOT NULL)";
    } else if($isReceivedFee=='0') {
      $sql.=" and (ISNULL(date_clctd))";
    }
    if($isRefounded=='1') {
      $sql.=" and (date_refound IS NOT NULL)";
    } else if($isRefounded=='0') {
      $sql.=" and (ISNULL(date_refound))";
    }

    // if(!empty($name_psgr)) {
    //   $sql.=" and (name_psgr like '%".$name_psgr."%')";
    // }
    $sql.=" and (name_psgr like CONCAT('%',?,'%'))";    
    $sql.=";";
// echo $sql;
// exit;

    // $result = $conn->query($sql);
    // if($result){
    //   while($arr=$result->fetch_assoc()){//fetch_array
    //     array_push($respondedData,$arr);
    //   }
    //   // header("Content-Type: text/html; charset=UTF-8");
    //   echo json_encode($respondedData);
    // }
    // $result->free();
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('s',$name_psgr);
    $stmt->execute();
    $result = $stmt->get_result();

    $i=0;
    while ($row = $result->fetch_assoc()) {
        $respondedData[$i]=$row;
        $i++;
    }    
    echo json_encode($respondedData);

    $result->free();
    $stmt->close();      

    $conn->close();        
  }
?>
