<?php  
  include_once 'linkToCXS.php';
  $respondedData=[];

if($_POST['conditions']==='ForMIOReport') {  
  $keyWord=$_POST['keyWord'];
  $start_time=$_POST['dateRange'][0];
  $end_time=$_POST['dateRange'][1];
  $id_applyer=$_POST['id_applyer'];
  $id_project=$_POST['id_project'];
  $opType=$_POST['opType'];
  $material=$_POST['material'];

  $sql="select mio.*,a.id_project as a_id_project,a.id_applyer as a_id_applyer,a.qty as a_qty,a.time_applied as a_time_applied,a.use_for as a_use_for,a.remark as a_remark,m.name as m_name,m.brand as m_brand,m.model as m_model,m.unit as m_unit,m.store_place as m_store_place,m.date_last_inventory as m_date_last_inventory,m.remark as m_remark from tbl_materials_in_outbound mio LEFT JOIN tbl_apply_materials a on mio.id=a.id_mio LEFT JOIN tbl_materials m on m.id=mio.id_material where (mio.time_op between STR_TO_DATE('".$start_time."','%Y-%m-%d %H:%i:%s') and STR_TO_DATE('".$end_time."','%Y-%m-%d %H:%i:%s'))";

  if($id_applyer!=0) {
    $sql.=" and (a.id_applyer=".$id_applyer.")";
  }
  if($id_project!=0) {
    $sql.=" and (a.id_project=".$id_project.")";
  }
  if($opType!=5) {
    $sql.=" and (mio.type_op=".$opType.")";
  }
  if($material!=0) {
    $sql.=" and (mio.id_material=".$material.")";
  }
  $sql.=" and (mio.remark like CONCAT('%',?,'%') or a.use_for like CONCAT('%',?,'%')";
  $sql.=" or a.remark like CONCAT('%',?,'%') or m.brand like CONCAT('%',?,'%')";
  $sql.=" or m.model like CONCAT('%',?,'%') or m.remark like CONCAT('%',?,'%')";
  $sql.=" or m.name like CONCAT('%',?,'%'));";
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
