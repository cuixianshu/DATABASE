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
  // echo json_encode($_POST['keyWord']);
  $keyWord=$_POST['keyWord'];
  $start_date=$_POST['dateRange'][0];
  $end_date=$_POST['dateRange'][1];
  
  if($_POST['conditions']=="VehiclesNotChecked") {
    $sql="select *,DATE_FORMAT(start_time,'%Y-%m-%d %H:%i') as start_time,DATE_FORMAT(end_time,'%Y-%m-%d %H:%i') as end_time from tbl_orders where (id_contacter in (select id from tbl_contacter where name like CONCAT('%',?,'%')) or id_product in (select id from tbl_product where name like CONCAT('%',?,'%')) or cstmr_ognz like CONCAT('%',?,'%') or msg_for_driver like CONCAT('%',?,'%') or start_point like CONCAT('%',?,'%') or end_point like CONCAT('%',?,'%') or use_surcharge like CONCAT('%',?,'%') or id_prjct_belongto in (select id from tbl_project where name like CONCAT('%',?,'%')) or id_operater in (select id from tbl_employee where name like CONCAT('%',?,'%')) or id_equipment in (select id from tbl_equipments where alias like CONCAT('%',?,'%')) or mem like CONCAT('%',?,'%')) and (DATE_FORMAT(start_time,'%Y-%m-%d') between ? and ?) and ISNULL(time_verify)";    
    $stmt=$conn->prepare($sql);
    // GROUP BY id   
    $stmt->bind_param('sssssssssssss',$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$start_date,$end_date);
    $stmt->execute();
    $result = $stmt->get_result();

    $i=0;
    while ($row = $result->fetch_assoc()) {
        $respondedData[$i]=$row;
        $i++;
    }    
    echo json_encode($respondedData);

    $stmt->close();
    $result->free();        
  }

  if($_POST['conditions']=="NotRequestedInvoice") {
// echo json_encode($_POST);
// exit;
    $sql="select DISTINCT(id) as ID,(select CONCAT_WS('@',name,DATE_FORMAT(time_start,'%Y/%m/%d')) from tbl_project where id=tbl_orders.id_prjct_belongto) as 项目,CONCAT_WS('@',(select name from tbl_contacter where id=tbl_orders.id_contacter),cstmr_ognz,(select tel_mobile from tbl_contacter where id=tbl_orders.id_contacter)) as 客户,(select name from tbl_product where id=id_product) as 产品,DATE_FORMAT(start_time,'%H:%i %m-%d-%Y') as 开始时间,start_point as 起点,end_point as 终点,(select CONCAT_WS('@',name,tel_work) from tbl_employee where id=id_operater) as 执行人,actual_price as 金额, park_fee as 停车,surcharge as 垫付 from tbl_orders where (id_contacter in (select id from tbl_contacter where name like CONCAT('%',?,'%')) or cstmr_ognz like CONCAT('%',?,'%') or id_product in (select id from tbl_product where name like CONCAT('%',?,'%')) or msg_for_driver like CONCAT('%',?,'%') or use_surcharge like CONCAT('%',?,'%') or id_prjct_belongto in (select id from tbl_project where name like CONCAT('%',?,'%')) or id_operater in (select id from tbl_employee where name like CONCAT('%',?,'%')) or id_equipment in (select id from tbl_equipments where alias like CONCAT('%',?,'%')) or start_point like CONCAT('%',?,'%') or end_point like CONCAT('%',?,'%') or mem like CONCAT('%',?,'%')) and (DATE_FORMAT(start_time,'%Y-%m-%d') between ? and ?) and ISNULL(id_request_invoice) and (time_verify is not null or (time_verify IS NULL and (select id_type from tbl_product where id=id_product)<>2))";//
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('sssssssssssss',$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$start_date,$end_date);
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

  if($_POST['conditions']=="ByRqstIDAndNotFilledInvoice") {
    $sql="select id as ID,(select name from tbl_project where id=tbl_orders.id_prjct_belongto) as 项目,CONCAT_WS('@',(select name from tbl_contacter where id=tbl_orders.id_contacter),cstmr_ognz) as 订车人,(select name from tbl_product where id=id_product) as 产品,DATE_FORMAT(start_time,'%H:%i %m-%d-%Y') as 开始时间,start_point as 起点,end_point as 终点,(select CONCAT_WS('@',name,tel_work) from tbl_employee where id=id_operater) as 司机,actual_price as 金额, park_fee as 停车,surcharge as 垫付 from tbl_orders where id_request_invoice=? and ISNULL(id_fill_invoice)";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('i',$keyWord);
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
