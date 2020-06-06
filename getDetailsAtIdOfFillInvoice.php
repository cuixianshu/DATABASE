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
    $id_fill_invoice=$_POST['id_fill_invoice'];
// echo json_encode($_POST);
// exit;
/*
 $sql="select id_fill_invoice as 开票ID,id as 订单ID,(select CONCAT_WS('@',name,DATE_FORMAT(time_start,'%Y-%m-%d')) from tbl_project where id=id_prjct_belongto) as 项目,(select name from tbl_product where id=id_product) as 产品,(select name from tbl_contacter where id=id_contacter) as 订车人,cstmr_ognz as 订车部门,actual_price as 金额,DATE_FORMAT(start_time,'%Y-%m-%d %H:%i') as 开始时间,DATE_FORMAT(end_time,'%Y-%m-%d %H:%i') as 结束时间,start_point as 起点,end_point as 终点,mileage as 里程,(select name from tbl_employee where id=id_operater) as 司机,(select alias from tbl_equipments where id=id_equipment) as 车辆,park_fee as 停车费,msg_for_driver as 执行信息,mem as 备注 from tbl_orders where id_fill_invoice=?"
 */
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
