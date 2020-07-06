<?php
  date_default_timezone_set('Asia/Shanghai');
  include_once 'linkToCXS.php';
  //回传给前端的数据结构
  class Operate_result {
    public $countOfInserted;
    public $error;
    public $duplicate_recorders;
    public $rows_in_excel;
    function __construct($arr=[]) {
      $this->countOfInserted=0;
      $this->error='';
      $this->duplicate_recorders=[];
      $this->rows_in_excel=count($arr);
    }
  }


  //拉取最近12个月的出票票号清单
  $sql="select number_ticket from tbl_tickets where date_issued > DATE_SUB(CURDATE(), INTERVAL 12 MONTH)";
  $result = $conn->query($sql);
  $numbersOfTktInlast1year=[];

  $i=0;
  if($result){
    while($arr=$result->fetch_array(MYSQLI_NUM)){//fetch_array
      $numbersOfTktInlast1year[$i]=$arr[0];
      $i++;
    }
  }
  $result->free();
/*
client_dptmt: "科技处"
乘机人: "杨伟伟"
乘机日期: "2020-01-10"
保险: "0"
其他金额: "0"
出票时间: "2020-01-01"
备注: ""
实退金额: "0"
客户卡号: "2318713"
客户姓名: "大连美途商旅-马晶"
已收金额: "888"
折扣: "0"
折让: "12"
支付方式: "预存款支付"
收款时间: "2020-01-01"
未收金额: "0"
机票类型: "国内机票"
票价: "850"
票号: "784-3730144401"
票应收: "912"
税: "50"
航班号: "CZ6129"
舱位: "S"
订单号: "9899351"
订单应收: "888"
退票手续费: "0"
预订人: "大连美途商旅-马晶"
首航段: "大连-北京首都"
 */



  $oprtRslt= new Operate_result($_POST);
  $insert="insert into tbl_tickets (date_issued,number_ticket,name_psgr,dptmt_client,number_flight,date_departure,trip,class_seat,price,tax,commission,insurance,amount_pcs,fee_refound,amount_actual_returned) values (curdate(),?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
  $stmt_insert=$conn->prepare($insert);

  for ($i=0;$i<count($_POST);$i++){
    $number_ticket=$_POST[$i]['票号'];
    $name_psgr=$_POST[$i]['乘机人'];
    $dptmt_client=$_POST[$i]['client_dptmt'];
    $number_flight=$_POST[$i]['航班号'];
    $date_departure=$_POST[$i]['乘机日期'];
    $trip=$_POST[$i]['首航段'];
    $class_seat=$_POST[$i]['舱位'];
    $price=$_POST[$i]['票价'];
    $tax=$_POST[$i]['税'];
    $commission=$_POST[$i]['折让'];
    $insurance=$_POST[$i]['保险'];
    $amount_pcs=$_POST[$i]['已收金额'];
    $fee_refound=$_POST[$i]['退票手续费'];
    $amount_actual_returned=$_POST[$i]['实退金额'];
// echo json_encode($_POST);
// exit;
// 判断是否重复.如重复,则退出;否则进行插入操作$numbersOfTktInlast1year
    if(!in_array($_POST[$i]['票号'],$numbersOfTktInlast1year)) {//不重复
      $stmt_insert->bind_param('sssssssiiiiiii',$number_ticket,$name_psgr,$dptmt_client,$number_flight,$date_departure,$trip,$class_seat,$price,$tax,$commission,$insurance,$amount_pcs,$fee_refound,$amount_actual_returned);
      $result=$stmt_insert->execute();
      $stmt_insert->free_result();
      if($result) {
        $oprtRslt->countOfInserted+=1;
      } else {
        $oprtRslt->error=$stmt_insert->error;
      }
    } else {//重复
      $dplctedRcdr='票号:'.$number_ticket.',日期:'.$date_departure.',乘客:'.$name_psgr.',航程:'.$trip.';';
      array_push($oprtRslt->duplicate_recorders,$dplctedRcdr);
      $dplctedRcdr='';
    }         
  }
  $stmt_insert->close(); 
  echo json_encode($oprtRslt);
  // exit();
  // echo json_encode($_POST);
  // echo stripos("You love php, I love php too!","QWEU");
  // $mbl_cntctr="发送给司机的信息";//[0][$mbl_cntctr]mem
  //gettype($)获取类型,返回string
  //array_keys(array),返回所有key组成的数组,返回array
  //array_key_exists(key,array),返回boolean 
  // $mbl_cntctr=array_key_exists('起点站', $_POST[5]);
  //count(array)返回array的长度
  //in_array(search,array,type)
  $conn->close();
  
    
?>  