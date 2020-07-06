<?php  
  include_once 'linkToCXS.php';
  $respondedData=[];
  // echo json_encode($_POST['keyWord']);

  
  if($_POST['conditions']=="VehiclesNotChecked") {
    $keyWord=$_POST['keyWord'];
    $start_date=$_POST['dateRange'][0];
    $end_date=$_POST['dateRange'][1];    
    $sql="select *,DATE_FORMAT(start_time,'%Y-%m-%d %H:%i') as start_time,DATE_FORMAT(end_time,'%Y-%m-%d %H:%i') as end_time from tbl_orders where (id_contacter in (select id from tbl_contacter where name like CONCAT('%',?,'%')) or id_product in (select id from tbl_product where name like CONCAT('%',?,'%')) or cstmr_ognz like CONCAT('%',?,'%') or msg_for_driver like CONCAT('%',?,'%') or start_point like CONCAT('%',?,'%') or end_point like CONCAT('%',?,'%') or use_surcharge like CONCAT('%',?,'%') or id_prjct_belongto in (select id from tbl_project where name like CONCAT('%',?,'%')) or id_operater in (select id from tbl_employee where name like CONCAT('%',?,'%')) or id_equipment in (select id from tbl_equipments where alias like CONCAT('%',?,'%')) or mem like CONCAT('%',?,'%')) and (DATE_FORMAT(start_time,'%Y-%m-%d') between ? and ?) and ISNULL(time_verify)";    
    $stmt=$conn->prepare($sql);
    // GROUP BY id   
    $stmt->bind_param('sssssssssssss',$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$start_date,$end_date);
    $stmt->execute();
    $result = $stmt->get_result();

    $i=0;
    while ($row = $result->fetch_assoc()) {
        $respondedData[$i]=$row;
        $i++;
    }    
    echo json_encode($respondedData);

    $stmt->close();
    $result->free();        
  }

  if($_POST['conditions']=="NotRequestedInvoice") {
    $keyWord=$_POST['keyWord'];
    $start_date=$_POST['dateRange'][0];
    $end_date=$_POST['dateRange'][1];    
// echo json_encode($_POST);
// exit;
    $sql="select DISTINCT(id) as ID,(select CONCAT_WS('@',name,DATE_FORMAT(time_start,'%Y/%m/%d')) from tbl_project where id=tbl_orders.id_prjct_belongto) as 项目,CONCAT_WS('@',(select name from tbl_contacter where id=tbl_orders.id_contacter),cstmr_ognz,(select tel_mobile from tbl_contacter where id=tbl_orders.id_contacter)) as 客户,(select name from tbl_product where id=id_product) as 产品,DATE_FORMAT(start_time,'%H:%i %m-%d-%Y') as 开始时间,start_point as 起点,end_point as 终点,(select CONCAT_WS('@',name,tel_work) from tbl_employee where id=id_operater) as 执行人,actual_price as 金额, park_fee as 停车,surcharge as 垫付 from tbl_orders where (id_contacter in (select id from tbl_contacter where name like CONCAT('%',?,'%')) or cstmr_ognz like CONCAT('%',?,'%') or id_product in (select id from tbl_product where name like CONCAT('%',?,'%')) or msg_for_driver like CONCAT('%',?,'%') or use_surcharge like CONCAT('%',?,'%') or id_prjct_belongto in (select id from tbl_project where name like CONCAT('%',?,'%')) or id_operater in (select id from tbl_employee where name like CONCAT('%',?,'%')) or id_equipment in (select id from tbl_equipments where alias like CONCAT('%',?,'%')) or start_point like CONCAT('%',?,'%') or end_point like CONCAT('%',?,'%') or mem like CONCAT('%',?,'%')) and (DATE_FORMAT(start_time,'%Y-%m-%d') between ? and ?) and ISNULL(id_request_invoice) and (time_verify is not null or (time_verify IS NULL and (select id_type from tbl_product where id=id_product)<>2))";//
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('sssssssssssss',$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$start_date,$end_date);
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
  }

  if($_POST['conditions']=="ByRqstIDAndNotFilledInvoice") {
    $keyWord=$_POST['keyWord'];
    $sql="select id as ID,(select name from tbl_project where id=tbl_orders.id_prjct_belongto) as 项目,CONCAT_WS('@',(select name from tbl_contacter where id=tbl_orders.id_contacter),cstmr_ognz) as 客户,(select name from tbl_product where id=id_product) as 产品,DATE_FORMAT(start_time,'%H:%i %m-%d-%Y') as 开始时间,start_point as 起点,end_point as 终点,(select CONCAT_WS('@',name,tel_work) from tbl_employee where id=id_operater) as 执行人,actual_price as 金额, park_fee as 停车,surcharge as 垫付 from tbl_orders where id_request_invoice=? and ISNULL(id_fill_invoice)";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('i',$keyWord);
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
  }
//车辆销售报表的记录
  if($_POST['conditions']=="FLEETSALE") {
    // $keyWord=$_POST['keyWord'];
    $start_date=$_POST['dateRange'][0];
    $end_date=$_POST['dateRange'][1];
    $id_equipment=$_POST['id_car'];
    $id_contacter=$_POST['id_client'];
    $cstmr_ognz=$_POST['clntDptmt_short_name'];
    $id_operater=$_POST['id_driver'];
    $id_prjct_belongto=$_POST['id_project'];
    $isFilledInvoice=$_POST['isFilledInvoice'];
    $isReceivedFee=$_POST['isReceivedFee'];

    $sql="select * from tbl_orders where (id_product in (select id from tbl_product where id_type=2)) and (start_time between STR_TO_DATE('".$start_date."','%Y-%m-%d %H:%i:%s') and STR_TO_DATE('".$end_date."','%Y-%m-%d %H:%i:%s'))";
    if($isFilledInvoice==0) {
      $sql.=" and (ISNULL(id_fill_invoice))";
    } else if($isFilledInvoice==1) {
      $sql.=" and (id_fill_invoice IS NOT NULL)";
    }
    if($isReceivedFee==0) {
      $sql.=" and (ISNULL(id_cashier))";
    } else if($isReceivedFee==1) {
      $sql.=" and (id_cashier IS NOT NULL)";
    }
    if($id_equipment!=0) {
      $sql.=" and (id_equipment=".$id_equipment.")";
    }
    if($id_contacter!=0) {
      $sql.=" and (id_contacter=".$id_contacter.")";
    }
    if($cstmr_ognz!=="0") {
      $sql.=" and (cstmr_ognz='".$cstmr_ognz."')";
    }
    if($id_operater!=0) {
      $sql.=" and (id_operater=".$id_operater.")";
    }
    if($id_prjct_belongto!=0) {
      $sql.=" and (id_prjct_belongto=".$id_prjct_belongto.")";
    }
    $sql.=";";
    $result = $conn->query($sql);
    if($result){
      while($arr=$result->fetch_assoc()){//fetch_array
        array_push($respondedData,$arr);
      }
      // header("Content-Type: text/html; charset=UTF-8");
      echo json_encode($respondedData);
    }
    $result->free();
  }

//其它销售报表的记录
  if($_POST['conditions']=="OtherSaleForReport") {
    // $keyWord=$_POST['keyWord'];
    $start_date=$_POST['dateRange'][0];
    $end_date=$_POST['dateRange'][1];
    $id_contacter=$_POST['id_client'];
    $cstmr_ognz=$_POST['clntDptmt_short_name'];
    $id_operater=$_POST['id_operater'];
    $id_prjct_belongto=$_POST['id_project'];
    $isFilledInvoice=$_POST['isFilledInvoice'];
    $isReceivedFee=$_POST['isReceivedFee'];

    $sql="select * from tbl_orders where (id_product in (select id from tbl_product where id_type<>2)) and (start_time between STR_TO_DATE('".$start_date."','%Y-%m-%d %H:%i:%s') and STR_TO_DATE('".$end_date."','%Y-%m-%d %H:%i:%s'))";
    if($isFilledInvoice==0) {
      $sql.=" and (ISNULL(id_fill_invoice))";
    } else if($isFilledInvoice==1) {
      $sql.=" and (id_fill_invoice IS NOT NULL)";
    }
    if($isReceivedFee==0) {
      $sql.=" and (ISNULL(id_cashier))";
    } else if($isReceivedFee==1) {
      $sql.=" and (id_cashier IS NOT NULL)";
    }

    if($id_contacter!=0) {
      $sql.=" and (id_contacter=".$id_contacter.")";
    }
    if($cstmr_ognz!=="0") {
      $sql.=" and (cstmr_ognz='".$cstmr_ognz."')";
    }
    if($id_operater!=0) {
      $sql.=" and (id_operater=".$id_operater.")";
    }
    if($id_prjct_belongto!=0) {
      $sql.=" and (id_prjct_belongto=".$id_prjct_belongto.")";
    }
    $sql.=";";
    $result = $conn->query($sql);
    if($result){
      while($arr=$result->fetch_assoc()){//fetch_array
        array_push($respondedData,$arr);
      }
      // header("Content-Type: text/html; charset=UTF-8");
      echo json_encode($respondedData);
    }
    $result->free();
  }

  if($_POST['conditions']=="COHistory") {
    $start_time=$_POST['dateRange'][0];
    $end_time=$_POST['dateRange'][1];
    $uid=$_POST['uid'];
    $sql="select * from tbl_orders where (id_product in (select id from tbl_product where id_type=2)) and (time_verify between STR_TO_DATE(?,'%Y-%m-%d %H:%i:%s') and STR_TO_DATE(?,'%Y-%m-%d %H:%i:%s')) and id_verifyer=?";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('ssi',$start_time,$end_time,$uid);
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
  }

  $conn->close();
?>
