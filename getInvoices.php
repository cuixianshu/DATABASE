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

    if($_POST['conditions']==="GetToCollect") {
      $keyWord=$_POST['keyWord'];
      $start_date=$_POST['dateRange'][0];
      $end_date=$_POST['dateRange'][1];
      $sql="select id,num_of_invoice,(select full_name from tbl_client_parent_ognztn where id=(select id_clt_prnt_ognztn from tbl_rqst_invoice where id_fill_invoice=tbl_fill_invoice.id limit 1)) as title,(select name from tbl_contacter where id=(select id_contacter from tbl_orders where id_fill_invoice=tbl_fill_invoice.id limit 1)) as client,amount,time_fill,other,(select id_prjct_belongto from tbl_orders where id_fill_invoice=tbl_fill_invoice.id limit 1) as id_project from tbl_fill_invoice where (id in (select id_fill_invoice from tbl_orders where cstmr_ognz like CONCAT('%',?,'%')) or id in (select id_fill_invoice from tbl_rqst_invoice where id_clt_prnt_ognztn in (select id from tbl_client_parent_ognztn where full_name like CONCAT('%',?,'%'))) or id in (select id_fill_invoice from tbl_orders where id_contacter in (select id from tbl_contacter where name like CONCAT('%',?,'%'))) or num_of_invoice like CONCAT('%',?,'%') or other like CONCAT('%',?,'%') or amount like CONCAT('%',?,'%')) and ISNULL(time_canceled) and ISNULL(id_tbl_cashier) and (DATE_FORMAT(time_fill,'%Y-%m-%d') between ? and ?)";
      $stmt=$conn->prepare($sql);
      $stmt->bind_param('ssssssss',$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$keyWord,$start_date,$end_date);
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
    }

?>
