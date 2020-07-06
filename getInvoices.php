<?php  
  include_once 'linkToCXS.php';
  $respondedData=[];

  if($_POST['conditions']==="GetToCollect") {
    $keyWord=$_POST['keyWord'];
    $start_date=$_POST['dateRange'][0];
    $end_date=$_POST['dateRange'][1];
    $sql="select id,num_of_invoice,(select full_name from tbl_client_parent_ognztn where id=(select id_clt_prnt_ognztn from tbl_rqst_invoice where id_fill_invoice=tbl_fill_invoice.id limit 1)) as title,(select name from tbl_contacter where id=(select id_contacter from tbl_orders where id_fill_invoice=tbl_fill_invoice.id limit 1)) as client,amount,time_fill,other,(select id_prjct_belongto from tbl_orders where id_fill_invoice=tbl_fill_invoice.id limit 1) as id_project from tbl_fill_invoice where (id in (select id_fill_invoice from tbl_orders where cstmr_ognz like CONCAT('%',?,'%')) or id in (select id_fill_invoice from tbl_rqst_invoice where id_clt_prnt_ognztn in (select id from tbl_client_parent_ognztn where full_name like CONCAT('%',?,'%'))) or id in (select id_fill_invoice from tbl_orders where id_contacter in (select id from tbl_contacter where name like CONCAT('%',?,'%'))) or num_of_invoice like CONCAT('%',?,'%') or other like CONCAT('%',?,'%') or amount like CONCAT('%',?,'%')) and ISNULL(time_canceled) and ISNULL(id_tbl_cashier) and (DATE_FORMAT(time_fill,'%Y-%m-%d') between ? and ?)";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('ssssssss',$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$start_date,$end_date);
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
/*
conditions: "ForinvoiceReport"
keyWord: (...)
dateRange: (...)
id_ourCompany: (...)
id_project: (...)
isCanceled: (...)
 */
  if($_POST['conditions']==="ForinvoiceReport") {
    $keyWord=$_POST['keyWord'];
    $start_time=$_POST['dateRange'][0];
    $end_time=$_POST['dateRange'][1];
    $id_ourCompany=$_POST['id_ourCompany'];
    $id_project=$_POST['id_project'];
    $isCanceled=$_POST['isCanceled'];
    $isCashed=$_POST['isCashed'];

    $sql="select f.*,r.id as r_id,r.id_of_our_cmpny as r_id_of_our_cmpny,r.id_type_invoice as r_id_type_invoice,r.id_clt_prnt_ognztn as r_id_clt_prnt_ognztn,r.googs_name as r_googs_name,r.amount as r_amount,r.id_applyer as r_id_applyer,r.time_apply as r_time_apply,r.other as r_other,o.id_prjct_belongto as id_project from tbl_fill_invoice f LEFT JOIN tbl_rqst_invoice r on f.id=r.id_fill_invoice LEFT JOIN (select DISTINCT id_prjct_belongto,id_fill_invoice from tbl_orders where id_fill_invoice IS NOT NULL) as o on f.id=o.id_fill_invoice where (f.time_fill between STR_TO_DATE('".$start_time."','%Y-%m-%d %H:%i:%s') and STR_TO_DATE('".$end_time."','%Y-%m-%d %H:%i:%s'))";
    if($id_ourCompany!=0) {
      $sql.=" and (r.id_of_our_cmpny=".$id_ourCompany.")";
    }
    if($id_project!=0) {
      $sql.=" and (o.id_prjct_belongto=".$id_project.")";//o.id_prjct_belongto
    }
    if($isCanceled==0) {
      $sql.=" and (ISNULL(f.time_canceled))";
    } else if($isCanceled==1) {
      $sql.=" and (f.time_canceled IS NOT NULL)";
    }
    if($isCashed==0) {
      $sql.=" and (ISNULL(f.id_tbl_cashier))";
    } else if($isCashed==1) {
      $sql.=" and (f.id_tbl_cashier IS NOT NULL)";
    }
    $sql.=" and (f.num_of_invoice like CONCAT('%',?,'%') or f.amount like CONCAT('%',?,'%')";
    $sql.=" or r.googs_name like CONCAT('%',?,'%') or r.other like CONCAT('%',?,'%')";
    $sql.=" or f.other like CONCAT('%',?,'%')";
    $sql.=" or (r.id_clt_prnt_ognztn in (select id from tbl_client_parent_ognztn where short_name like CONCAT('%',?,'%') or full_name like CONCAT('%',?,'%'))));";
// echo $sql;
// exit;
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('sssssss',$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$keyWord);
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
