<?php  
  include_once 'linkToCXS.php';
  $respondedData=[];
  if($_POST['conditions']==='UNRETURNEDMMS') {
    $time_start=$_POST['dateRange'][0];
    $time_end=$_POST['dateRange'][1];
    $id_applyer=$_POST['id_applyer'];
    $id_project=$_POST['id_project'];
    $keyWord=$_POST['keyWord'];
    $sql="select a.*,m.name as m_name,m.brand as m_brand,m.model as m_model,m.unit as m_unit,m.min_unit_packing as m_min_unit_packing,m.store_place as m_store_place,mio.time_op as mio_time,mio.qty as mio_qty from tbl_materials_in_outbound as mio LEFT JOIN tbl_apply_materials as a on mio.id=a.id_mio LEFT JOIN tbl_materials as m on mio.id_material = m.id where (m.is_need_return=1) and (a.qty_returned<a.qty_distributed) and (m.name like CONCAT('%',?,'%') or m.brand like CONCAT('%',?,'%') or m.model like CONCAT('%',?,'%') or a.use_for like CONCAT('%',?,'%') or a.remark like CONCAT('%',?,'%') or m.remark like CONCAT('%',?,'%') or mio.remark like CONCAT('%',?,'%')) and (mio.time_op between STR_TO_DATE(?,'%Y-%m-%d %H:%i:%s') and STR_TO_DATE(?,'%Y-%m-%d %H:%i:%s'))";

// echo $sql;
// exit;
    if($id_applyer!=0) {
      $sql.=" and (a.id_applyer=".$id_applyer.")";
    }
    if($id_project!=0) {
      $sql.=" and (a.id_project=".$id_project.")";
    }
    $sql.=";";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('sssssssss',$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$time_start,$time_end);
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
  }

  $conn->close();

?>
