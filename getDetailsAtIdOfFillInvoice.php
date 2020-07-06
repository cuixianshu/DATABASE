<?php  
  include_once 'linkToCXS.php';
  $id_fill_invoice=$_POST['id_fill_invoice'];
  $respondedData=[];
  $sql="select (select DATE_FORMAT(time_fill,'%Y-%m-%d') from tbl_fill_invoice where id=id_fill_invoice) as 开票日期,id_fill_invoice as 开票ID,(select num_of_invoice from tbl_fill_invoice where id=id_fill_invoice ) as 发票号,id as 订单ID,(select name from tbl_product where id=id_product) as 产品,DATE_FORMAT(start_time,'%Y-%m-%d %H:%i') as 开始时间,DATE_FORMAT(end_time,'%Y-%m-%d %H:%i') as 结束时间,start_point as 起点,end_point as 终点,mileage as 里程,actual_price as 金额,park_fee as 停车费 from tbl_orders where id_fill_invoice=?";
  $stmt=$conn->prepare($sql);
  $stmt->bind_param('i',$id_fill_invoice);
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
