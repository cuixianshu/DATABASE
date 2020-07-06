<?php  
  include_once 'linkToCXS.php';
  $respondedData=[];
/*
conditions: "ForReport"
keyWord: (...)
dateRange: (...)
id_applier: (...)
id_project: (...)
id_purchasher: (...)
isFinished: (...)
 */
//财务付款报表
  if($_POST['conditions']==='ForReport') {
    $keyWord=$_POST['keyWord'];
    $start_time=$_POST['dateRange'][0];
    $end_time=$_POST['dateRange'][1];
    $id_applier=$_POST['id_applier'];
    $id_project=$_POST['id_project'];//tbl_requst_founs
    $id_purchasher=$_POST['id_purchasher'];//tbl_requst_founs
    $isFinished=$_POST['isFinished'];


    $sql="select a.*,e.id_enquiryer as e_id_enquiryer,e.seller as e_seller,e.actual_amount as e_amount from tbl_apply_purchasing a LEFT JOIN tbl_enquiry_price e on (a.id=e.id_applied_purchasing  and e.is_made_deal=1) where (a.date_applied between STR_TO_DATE('".$start_time."','%Y-%m-%d %H:%i:%s') and STR_TO_DATE('".$end_time."','%Y-%m-%d %H:%i:%s'))";
    if($id_applier!=0) {
      $sql.=" and (a.id_applier=".$id_applier.")";
    }
    if($id_project!=0) {
      $sql.=" and (a.id_project=".$id_project.")";
    }
    if($id_purchasher!=0) {
      $sql.=" and (e.id_enquiryer=".$id_purchasher.")";
    }
    if($isFinished==0) {
      $sql.=" and (a.is_finished=0)";
    } else if($isFinished==1) {
      $sql.=" and (a.is_finished=1)";
    }
    $sql.=" and (e.seller like CONCAT('%',?,'%') or a.name like CONCAT('%',?,'%')";
    $sql.="  or a.brand like CONCAT('%',?,'%') or a.model like CONCAT('%',?,'%') or a.detail like CONCAT('%',?,'%') or a. remark like CONCAT('%',?,'%'));";
// echo $sql;
// exit;
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('ssssss',$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$keyWord);
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
