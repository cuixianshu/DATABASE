<?php  
    $servername = "localhost";
    $username = "root";
    $password = "Mwy197301242811";
    $dbname = "cuixianshu"; // 要操作的数据库名
    $respondedData=[];
    // 创建连接 
    $conn= new mysqli($servername,$username,$password,$dbname); // 注意第四个参数
    if($conn->connect_error){
        die("连接失败，错误:" . $conn->connect_error);
    }

    $idApplied=$_POST['id_applyedPurchasing'];
// echo json_encode($_POST);
// exit;EnquiryingNotPassedApproving
    if($_POST['conditions']=='commitedEnquiryWithoutApproving') {
      $sql="select * from tbl_enquiry_price where id_applied_purchasing=?";
    } else if($_POST['conditions']=='ApprovingComparePassed') {
      $sql="select * from tbl_enquiry_price where id = (select id_selected_enquiry from tbl_approved_enquiry_compare_price where id_applied=? and result_approved=1)";
    } else if($_POST['conditions']=='EnquiryingNotPassedApproving') {
      $sql="select * from tbl_enquiry_price where id_applied_purchasing=? and is_commited_to_approve<>2 or is_commited_to_approve is null";
    } else if($_POST['conditions']=='NotSelectedSellerOrNotPassedApproving') {
      $sql="select * from tbl_enquiry_price where id_applied_purchasing=?";
    }
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('i',$idApplied);
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
?>
