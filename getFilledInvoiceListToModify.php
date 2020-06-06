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
    $sql="select id_fill_invoice as ID,(select name from tbl_project where id=(select id_prjct_belongto from tbl_orders where id_request_invoice=tbl_rqst_invoice.id group by id_request_invoice)) as 项目,(select name from tbl_contacter where id in (select id_contacter from tbl_orders where id_request_invoice=tbl_rqst_invoice.id)) as 订车人,(select cstmr_ognz from tbl_orders where id_request_invoice=tbl_rqst_invoice.id group by id_request_invoice) as 订车部门,(select name from tbl_our_company where id=tbl_rqst_invoice.id_of_our_cmpny) as 出票公司,(select name from tbl_type_invoice where id=tbl_rqst_invoice.id_type_invoice) as 类型,(select full_name from tbl_client_parent_ognztn where id=tbl_rqst_invoice.id_clt_prnt_ognztn) as 发票抬头,googs_name as 商品名称, amount as 金额,(select name from tbl_employee where id=tbl_rqst_invoice.id_applyer) as 申请人,other as 备注  from tbl_rqst_invoice where id_fill_invoice=?";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('i',$keyWord);
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
