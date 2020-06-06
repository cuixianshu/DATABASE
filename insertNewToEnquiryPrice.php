<?php
  $servername = "localhost";
  $username = "root";
  $password = "Mwy197301242811";
  $dbname = "cuixianshu"; // 要操作的数据库名
  $outputData=array();
  // 创建连接 
  $conn= new mysqli($servername,$username,$password,$dbname); // 注意第四个参数
  if($conn->connect_error){
      die("连接失败，错误:" . $conn->connect_error);
  }


  $actual_amount=$_POST['actualAmount'];
  $contacter=$_POST['contacter'];
  $idApplyed=$_POST['idApplyed'];
  $promiseDeliveryDate=$_POST['promiseDeliveryDate'];
  $id_enquiryer=$_POST['id_enquiryer'];
  $priceIncludedTax=$_POST['priceIncludedTax'];
  $remark=$_POST['remark'];
  $seller=$_POST['seller'];
  $tel=$_POST['tel'];
  $wayOfPayment=$_POST['wayOfPayment'];
/*
actualAmount
contacter
idApplyed
expectDeliveryDate
id_enquiryer
priceIncludedTax
remark
seller
tel
wayOfPayment

 */  
  $sql_insert="insert into `tbl_enquiry_price` (id_applied_purchasing,seller,contacter,tel,price_include_tax,way_payment,promise_delivery_date,actual_amount,id_enquiryer,enquiried_date,remark) values (?,?,?,?,?,?,?,?,?,CURRENT_TIME(),?)";

  $stmt=$conn->prepare($sql_insert);
  $stmt->bind_param('isssdssdis',$idApplyed,$seller,$contacter,$tel,$priceIncludedTax,$wayOfPayment,$promiseDeliveryDate,$actual_amount,$id_enquiryer,$remark);
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