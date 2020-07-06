<?php  
  include_once 'linkToCXS.php';

  $outputData=[];
  if(empty($_POST['conditions']) || $_POST['conditions']!='All') {
    $keyWord=$_POST['keyWord'];
    $sql="select * from tbl_client_parent_ognztn where short_name like CONCAT('%',?,'%') or full_name like CONCAT('%',?,'%') order by id asc";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('ss',$keyWord,$keyWord);
    $stmt->execute();
    $result = $stmt->get_result();
    if(!$result) {
      echo json_encode($conn->error);
      exit;
    }
    $i=0;
    while ($row = $result->fetch_assoc()) {
        $outputData[$i]=$row;
        $i++;
    }    
    echo json_encode($outputData);
    $stmt->free_result();
    $stmt->close();
  }

  $outputData=[];
  if(!empty($_POST['conditions']) && $_POST['conditions']=='All') {
    $keyWord='';
    $sql="select * from tbl_client_parent_ognztn where short_name like CONCAT('%',?,'%') or full_name like CONCAT('%',?,'%') order by id asc";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('ss',$keyWord,$keyWord);
    $stmt->execute();
    $result = $stmt->get_result();
    if(!$result) {
      echo json_encode($conn->error);
      exit;
    }
    $i=0;
    while ($row = $result->fetch_assoc()) {
        $outputData[$i]=$row;
        $i++;
    }    
    echo json_encode($outputData);
    $stmt->free_result();
    $stmt->close();
  }  

  $conn->close();
?>