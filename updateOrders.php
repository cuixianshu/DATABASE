<?php
  date_default_timezone_set('Asia/Shanghai');
	$servername = "localhost";
	$username = "root";
	$password = "Mwy197301242811";
	$dbname = "cuixianshu"; // 要操作的数据库名
    $outputData=array();
	// 创建连接 
	$conn= new mysqli($servername,$username,$password,$dbname); // 注意第四个参数
	if($conn->connect_error){
	    die("连接失败,错误:" . $conn->connect_error);
	}
    
  if($_POST['conditions']==="DataByHandInput") {
    //获取单位简称
    $cstmr_ognz=$_POST['dptmt_client'];
    $id_equipment=$_POST['id_equipment'];
    $id_contacter=$_POST['id_booker'];
    $id_prjct_belongto=$_POST['id_project'];
    $id_product=$_POST['id_product'];
    $id_payer=$_POST['id_payer'];
    $id_operater=$_POST['id_operator'];

    $sql="insert into tbl_orders (id,cstmr_ognz,id_contacter,id_prjct_belongto,id_contract,id_product,id_rule_price,quantity,actual_price,surcharge,use_surcharge,start_time,end_time,start_point,end_point,id_operater,id_equipment,id_payer,mem,time_create,id_creater,time_verify,id_verifyer, id_request_invoice,id_fill_invoice,id_cashier,mileage,msg_for_driver,park_fee) values (null,?,?,?,?,?,null,?,?,?,?,?,?,?,?,?,?,?,?,CURRENT_TIME(),?,CURRENT_TIME(),1,null,null,null,null,null,null)";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('siiiiiddsssssiiisi',$cstmr_ognz,$id_contacter,$id_prjct_belongto,$id_contract,$id_product,$quantity,$actual_price,$surcharge,$use_surcharge,$start_time,$end_time,$start_point,$end_point,$id_operater,$id_equipment,$id_payer,$mem,$id_creater);
    $id_contract=$_POST['numOfContract'];
    $quantity=$_POST['quantity'];
    $actual_price=$_POST['salePrice'];
    $surcharge=$_POST['adtnlFee'];
    $use_surcharge=$_POST['useOfAdtnl'];
    $start_time=date('Y-m-d H:i',strtotime($_POST['startDate']));
    $end_time=(empty($_POST['endDate']))?NULL:date('Y-m-d H:i',strtotime($_POST['endDate']));
    $start_point=$_POST['startPoint'];
    $end_point=$_POST['endPoint'];
    $mem=$_POST['mem'];
    $id_creater=1;
    
    $result=$stmt->execute();
    if($result) {
      echo json_encode($result);
    } else {
      echo json_encode(strip_tags($stmt->error));

    }
    $stmt->close();
  }
  if($_POST['conditions']==="WithCheckedData") {
// echo json_encode($_POST);
// exit;
/*
actual_price: "314.83"
clntDptmt: "大工能源动力学院"
conditions: "WithCheckedData"
cstmr_ognz: "理工大学西部校区能源动力学院"
end_point: "东海路松下压缩机厂"
end_time: "2020-01-03T04:28:00.000Z"
id: "361"
id_cashier: ""
id_contacter: "34"
id_contract: "0"
id_creater: "1"
id_equipment: "20"
id_fill_invoice: ""
id_operater: "22"
id_payer: "34"
id_prjct_belongto: "3"
id_product: "9"
id_request_invoice: ""
id_rule_price: "14"
id_verifyer: ""
mem: ""
mileage: "19.30"
msg_for_driver: ""
park_fee: "0.00"
quantity: "1"
start_point: "大工"
start_time: "2020-01-03T01:00:00.000Z"
surcharge: "0.00"
time_create: "2020-04-15 11:52:48"
time_verify: ""
use_surcharge: ""
 */
    $id=$_POST['id'];
    
    //获取单位简称
    $cstmr_ognz=$_POST['clntDptmt'];

    //获取id_contacter(根据手机号$cstmr_tel),到tbl_contacter中查询获得
    $id_contacter=$_POST['id_contacter'];
    // //获取手机号
    // $cstmr_tel=substr(strrchr($_POST['booker'],'@'),1);

    // $sql="select id from tbl_contacter where tel_mobile=?";
    // $stmt=$conn->prepare($sql);
    // $stmt->bind_param('s',$cstmr_tel);
    // $stmt->bind_result($id_contacter);
    // $stmt->execute();
    // $stmt->fetch();//只有一个数据
    // // while($stmt->fetch()){
    //     // echo "执行者ID:".$id_contacter.";";
    // // }
    // $stmt->free_result();

    //获取操作人手机号
    // $tel_operater=substr(strstr($_POST['driver'],'@'),1);
    // //获取操作人的ID(根据手机号查找)
    // $sql="select id from tbl_employee where tel_work=?";
    // $stmt=$conn->prepare($sql);
    // $stmt->bind_param('s',$tel_operater);
    // $stmt->bind_result($id_operater);
    // $stmt->execute();
    // $stmt->fetch();
    // $stmt->free_result();

    $id_operater=$_POST['id_operater'];

    $end_point=$_POST['end_point'];
    $end_time=date('Y-m-d H:i',strtotime($_POST['end_time']));

    //获取equipment 的alias  id_equipment
    // $equipment=$_POST['equipment'];
    // $sql="select id from tbl_equipments where alias=?";
    // $stmt=$conn->prepare($sql);
    // $stmt->bind_param('s',$equipment);
    // $stmt->bind_result($id_equipment);
    // $stmt->execute();
    // $stmt->fetch();
    // $stmt->free_result();

    $id_equipment=$_POST['id_equipment'];

    $mem=$_POST['mem'];
    $mileage=$_POST['mileage'];
    $msg_for_driver=$_POST['msg_for_driver'];
    $park_fee=$_POST['park_fee'];

    //获取结算人的单位ID
    // $name_payer=strstr(substr(strstr($_POST['payer'],'@'),1),'@',true);
    // $sql="select id from tbl_client_department where short_name=?";
    // $stmt=$conn->prepare($sql);
    // $stmt->bind_param('s',$name_payer);
    // $stmt->bind_result($id_payer);
    // $stmt->execute();
    // $stmt->fetch();
    // $stmt->free_result();    
    $id_payer=$_POST['id_payer'];
    //获取项目ID
    $id_prjct_belongto=$_POST['id_prjct_belongto'];
    // $prjct_name=strstr($_POST['prjct'],'@',true);
    // $sql="select id from tbl_project where name=?";
    // $stmt=$conn->prepare($sql);
    // $stmt->bind_param('s',$prjct_name);
    // $stmt->bind_result($id_prjct_belongto);
    // $stmt->execute();
    // $stmt->fetch();
    // $stmt->free_result();

    //获取产品ＩＤ
    // $prdct_name=$_POST['product'];
    // $sql="select id from tbl_product where name=?";
    // $stmt=$conn->prepare($sql);
    // $stmt->bind_param('s',$prdct_name);
    // $stmt->bind_result($id_product);
    // $stmt->execute();
    // $stmt->fetch();
    // $stmt->free_result();
    
    $id_product=$_POST['id_product'];

    //获取计价器ＩＤ
    // $rule_price=$_POST['rulePrice'];

    // $sql="select id from tbl_rule_price where name=?";
    // $stmt=$conn->prepare($sql);
    // $stmt->bind_param('s',$rule_price);
    // $stmt->bind_result($id_rule_price);
    // $stmt->execute();
    // $stmt->fetch();
    // $stmt->free_result(); 

    $id_rule_price=$_POST['id_rule_price'];

    $actual_price=$_POST['actual_price'];
    $start_point=$_POST['start_point'];       
    $start_time=date('Y-m-d H:i',strtotime($_POST['start_time']));
    $surcharge=$_POST['surcharge'];
    $use_surcharge=$_POST['use_surcharge'];
    $id_verifyer=$_POST['id_verifyer'];
    $time_verify='';
// echo $start_time;
// exit;
/////////////////////////////////////////////////////////以下代码经验证没有问题
    
    $sql="UPDATE `tbl_orders` SET `cstmr_ognz`=?,`id_contacter`=?,`id_prjct_belongto`=?,`id_product`=?,`id_rule_price`=?,`actual_price`=?,`surcharge`=?,`use_surcharge`=?,`start_time`=?,`end_time`=?,`start_point`=?,`end_point`=?,`id_operater`=?,`id_equipment`=?,`id_payer`=?,`mem`=?,`time_verify`=CURRENT_TIME(),`id_verifyer`=?,`mileage`=?,`msg_for_driver`=?,`park_fee`=? WHERE `id`=?";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('siiiiddsssssiiisidsdi',$cstmr_ognz,$id_contacter,$id_prjct_belongto,$id_product,$id_rule_price,$actual_price,$surcharge,$use_surcharge,$start_time,$end_time,$start_point,$end_point,$id_operater,$id_equipment,$id_payer,$mem,$id_verifyer,$mileage,$msg_for_driver,$park_fee,$id);
    $result=$stmt->execute();
    if($result) {
        echo json_encode($result);
    } else {
        echo $stmt->error;
    }

    $stmt->free_result();
    $stmt->close();
  }

  if($_POST['conditions']==="CancelRequesting") {
    $id=$_POST['id'];

    $sql_update_orders="UPDATE `tbl_orders` SET `id_request_invoice`=null WHERE `id`=? and ISNULL(`id_fill_invoice`)";

    $result_update_orders=false;
    $stmt=$conn->prepare($sql_update_orders);
    $stmt->bind_param('i',$id);
    $result_update_orders=$stmt->execute();
    $stmt->free_result();
    $stmt->close();

    //是否全部成功执行
    if($result_update_orders) {
      echo json_encode(true);
    } else {
      echo json_encode(false);
    }

  }

  if($_POST['conditions']==="DeleteTheRequesting") {
    $conn->autocommit(false); //设置为非自动提交

    $sql_delete="delete from tbl_rqst_invoice where id=?";
    $sql_update="UPDATE `tbl_orders` SET `id_request_invoice`=null WHERE `id_request_invoice`=? and ISNULL(`id_fill_invoice`)";

    $result_delete=false;
    $id=$_POST['id'];
    $stmt=$conn->prepare($sql_delete);
    $stmt->bind_param('i',$id);
    $result_delete=$stmt->execute();
    $stmt->free_result();
    $stmt->close();

    //因为返回结果只有一行,所以没用下面的标准用法 while($row=$result_select->fetch_assoc()){//fetch_assoc以一个关联数组方式抓取一行结果
    //     $id_request_invoice=$row["id"];
    // }

    $result_update=false;
    $stmt=$conn->prepare($sql_update);
    $stmt->bind_param('i',$id);
    $result_update=$stmt->execute();
    $stmt->free_result();
    $stmt->close();

    //是否全部成功执行
    if($result_delete && $result_update) {
      echo json_encode(true);
        $conn->commit();  //操作无误，提交事务
    } else {
      echo json_encode(false);
      $conn->rollback(); //回滚事务
    }

    $conn->autocommit(true); //重新设置为自动提交    
  }  

  $conn->close();
?>
