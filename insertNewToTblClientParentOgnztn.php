<?php
  include_once 'linkToCXS.php';

  $id=$_POST['id'];
  $short_name=$_POST['short_name'];
  $address=$_POST['address'];
  $full_name=$_POST['full_name'];
  $tax_num_in_invoice=$_POST['tax_num_in_invoice'];
  $address_in_invoice=$_POST['address_in_invoice'];
  $account_in_invoice=$_POST['account_in_invoice'];
  $other=$_POST['other'];
  
  $sql_insert="insert into `tbl_client_parent_ognztn` (short_name,address,full_name,tax_num_in_invoice,address_in_invoice,account_in_invoice,other) values (?,?,?,?,?,?,?)";

  $stmt=$conn->prepare($sql_insert);
  $stmt->bind_param('sssssss',$short_name,$address,$full_name,$tax_num_in_invoice,$address_in_invoice,$account_in_invoice,$other);
  $result_insert=$stmt->execute();
  $stmt->free_result();
  $stmt->close();

    //是否全部成功执行
  if($result_insert) {
    echo json_encode(true);
  } else {
    echo json_encode(false);
  }


    $conn->close();
?>