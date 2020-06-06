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
    $keyWord=$_POST['keyWord'];
    $start_date=$_POST['dateRange'][0];
    $end_date=$_POST['dateRange'][1];
    $sql="select DISTINCT(id_fill_invoice) as ID,(select num_of_invoice from tbl_fill_invoice where id=tbl_rqst_invoice.id_fill_invoice limit 1) as 发票号,(select name from tbl_project where id=(select id_prjct_belongto from tbl_orders where id_request_invoice=tbl_rqst_invoice.id limit 1)) as 项目,(select name from tbl_contacter where id=(select id_contacter from tbl_orders where id_request_invoice=tbl_rqst_invoice.id limit 1)) as 订车人,(select cstmr_ognz from tbl_orders where id_request_invoice=tbl_rqst_invoice.id limit 1) as 订车部门,(select name from tbl_our_company where id=tbl_rqst_invoice.id_of_our_cmpny limit 1) as 出票公司,(select name from tbl_type_invoice where id=tbl_rqst_invoice.id_type_invoice) as 类型,(select full_name from tbl_client_parent_ognztn where id=tbl_rqst_invoice.id_clt_prnt_ognztn limit 1) as 发票抬头,googs_name as 商品名称, amount as 金额,(select name from tbl_employee where id=tbl_rqst_invoice.id_applyer limit 1) as 申请人,(select DATE_FORMAT(time_fill,'%Y-%m-%d') from tbl_fill_invoice where id=id_fill_invoice) as 开票日期,other as 备注  from tbl_rqst_invoice where (id_of_our_cmpny in (select id from tbl_our_company where name like CONCAT('%',?,'%')) or (id_clt_prnt_ognztn in (select id from tbl_client_parent_ognztn where full_name like CONCAT('%',?,'%'))) or id in (select id_request_invoice from tbl_orders where id_prjct_belongto in (select id from tbl_project where name like CONCAT('%',?,'%'))) or id in (select id_request_invoice from tbl_orders where cstmr_ognz like CONCAT('%',?,'%'))  or (googs_name like CONCAT('%',?,'%')) or (id_applyer in (select id from tbl_employee where name like CONCAT('%',?,'%'))) or (amount like CONCAT('%',?,'%')) or (other like CONCAT('%',?,'%')) or id in (select id_request_invoice from tbl_orders where id_contacter in (select id from tbl_contacter where name like CONCAT('%',?,'%')))) and id_fill_invoice in (select id from tbl_fill_invoice where ISNULL(time_canceled)) and (DATE_FORMAT(time_apply,'%Y-%m-%d') between ? and ?) and id_fill_invoice IS NOT NULL group by id_fill_invoice";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('sssssssssss',$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$start_date,$end_date);
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
