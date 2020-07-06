<?php  
  include_once 'linkToCXS.php';

  $respondedData=[];

  if($_POST['conditions']=='CollectionsWithoutChecking') {
    $sql="select *,(select name from tbl_way_pay where id=id_way_pay) as way_pay,(select name from tbl_project where id=id_project) as project,(select short_name from tbl_our_account where id=id_account) as account,(select name from tbl_employee where id=id_cashier) as cashier from `tbl_cashier` where (`time_collect` between ? and ?) and `other` like CONCAT('%',?,'%') and `id_confirmer` IS NULL";

    $stmt=$conn->prepare($sql);
    $stmt->bind_param('sss',$_POST['dateRange'][0],$_POST['dateRange'][1],$_POST['keyWord']);
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

  if($_POST['conditions']=='ForReport') {
    // $keyWord=$_POST['keyWord'];
    $start_time=$_POST['dateRange'][0];
    $end_time=$_POST['dateRange'][1];
    $id_project=$_POST['id_project'];
    $id_cashier=$_POST['id_cashier'];
    $id_way_pay=$_POST['id_way'];
    $id_account=$_POST['acnt'];
    $isReviewed=$_POST['isReviewed'];

    $sql="select * from tbl_cashier where (time_collect between STR_TO_DATE('".$start_time."','%Y-%m-%d %H:%i:%s') and STR_TO_DATE('".$end_time."','%Y-%m-%d %H:%i:%s'))";
    if($id_project!=0) {
      $sql.=" and (id_project=".$id_project.")";
    }
    if($id_cashier!=0) {
      $sql.=" and (id_cashier=".$id_cashier.")";
    }
    if($id_way_pay!=0) {
      $sql.=" and (id_way_pay=".$id_way_pay.")";
    }
    if($id_account!=0) {
      $sql.=" and (id_account=".$id_account.")";
    }
    if($isReviewed==0) {
      $sql.=" and (ISNULL(time_confirm))";
    } else if($isReviewed==1) {
      $sql.=" and (time_confirm IS NOT NULL)";
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

  $conn->close();
?>
