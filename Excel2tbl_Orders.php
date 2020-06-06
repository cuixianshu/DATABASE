<?php
  date_default_timezone_set('Asia/Shanghai');
	$servername = "localhost";
	$username = "root";
	$password = "Mwy197301242811";
	$dbname = "cuixianshu"; // 要操作的数据库名
  $feild_name_in_neccessary=array();
  $array_of_title_in_orders=array();
  $array_of_title_in_excel=array();
  $rows_by_compute=count($_POST);
  $duplicate_count=0;//重复项计数器
  $duplicate_recorders=[];//重复的记录

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
  //客户单位中的short_name和id_client_patent_ognztn
  class ClientDepartment {
    public $id;
    public $id_prnt;
    public $short_name;
    function __construct() {
      $this->id='';
      $this->id_prnt='';
      $this->short_name='';
    }
  }
  //产品
  class Product {
    public $id;
    public $name;
    function __construct() {
      $this->id='';
      $this->name='';
    }
  } 
  //客户
  class Contacter {
    public $id;
    public $name;
    public $id_dptmt;
    public $tel_mobile;
    function __construct() {
      $this->id='';
      $this->name='';
      $this->id_dptmt='';
      $this->tel_mobile='';
    }
  }
  class Employee {
    public $id;
    public $name;
    public $tel_work;
    function __construct() {
      $this->id='';
      $this->name='';
      $this->tel_work='';
    }
  }
  class Equipment {
    public $id;
    public $alias;
    function __construct() {
      $this->id='';
      $this->alias='';
    }        
  }    
	// 创建连接
	$conn= new mysqli($servername,$username,$password,$dbname); // 注意第四个参数
	if($conn->connect_error){
	  die("连接失败,错误:" . $conn->connect_error);
	}

  //把EXCEL表头转换为数据库表字段
  //拉取字典
  $sql="select * from  tbl_dictionary_name_in_orders_excel where type_product_en='VECL'";
  $result = $conn->query($sql);
  if($result){
    while($arr=$result->fetch_assoc()){//fetch_array
      array_push($array_of_title_in_orders,$arr['name_in_orders']);
      array_push($array_of_title_in_excel,$arr['name_in_excel']);
    }
  }     
  $result->free();

  //获取EXCEL表头
  $title_excel=array_keys($_POST[0]);
  //更换$_POST[$k]中的键名
  for($k=0;$k<count($_POST);$k++) {//所有的记录
	  for($index=0;$index<count($title_excel);$index++) {
	  	for($i=0;$i<count($array_of_title_in_excel);$i++) {	
	  		//查询字典中是否有当前EXCEL表头定义
	      if(stristr($array_of_title_in_excel[$i],$title_excel[$index])) {//转换表头
	        $_POST[$k][$array_of_title_in_orders[$i]]=$_POST[$k][$title_excel[$index]];
	        unset($_POST[$k][$title_excel[$index]]);
	      }
	  	}
	  }
	  if($k===0){
	    $translated_titles=array_keys($_POST[$k]);//已完成转换的字段
	  }
  //补齐要插入表中的字段	    
	  for($i=0;$i<count($array_of_title_in_orders);$i++){
	    if(!in_array($array_of_title_in_orders[$i],$translated_titles)){
	      $_POST[$k][$array_of_title_in_orders[$i]]='';
	    }
	  }
  }
  // echo json_encode($_POST);
  // exit;

  //拉取最近24个月的出车记录特征码,以去除重复的项目DATE_FORMAT(SYSDATE(),'%Y-%m-%d %H:%i:%s')id_product,
  $sql="select CONCAT(id_contacter,DATE_FORMAT(start_time,'%Y-%m-%d %H:%i'),id_operater,id_equipment) as identifying_code from  tbl_orders where start_time > DATE_SUB(CURDATE(), INTERVAL 24 MONTH)";
  $result = $conn->query($sql);
  $last6month_identifyingcodes=[];

  $i=0;
  if($result){
    while($arr=$result->fetch_array(MYSQLI_NUM)){//fetch_array
	    $last6month_identifyingcodes[$i]=$arr[0];
      $i++;
    }
  }
  $result->free();

  //暂存tbl_client_department数据,以提高性能
  $arr_client_departments=[];
  $sql="select id,id_of_parent_ognztn,short_name from tbl_client_department";
  $result = $conn->query($sql);
  if($result){
    $i=0;
    while($arr=$result->fetch_array(MYSQLI_NUM)){//fetch_array
      $obj_client_department=new ClientDepartment;
      $obj_client_department->id=$arr[0];
      $obj_client_department->id_prnt=$arr[1];
      $obj_client_department->short_name=$arr[2];
      $arr_client_departments[$i]=$obj_client_department;
      $i++;
      unset($obj_client_department);
    }
  }
  $result->free();

  //暂存tbl_client_parent_ognztn数据,以提高性能
  $arr_parent_ognztns=[];
  $sql="select id,short_name from tbl_client_parent_ognztn";
  $result = $conn->query($sql);
  if($result){
    $i=0;
    while($arr=$result->fetch_array(MYSQLI_NUM)){//fetch_array
      $obj_parent_ognztn=new ClientDepartment;
      $obj_parent_ognztn->id=$arr[0];
      $obj_parent_ognztn->short_name=$arr[1];
      $arr_parent_ognztns[$i]=$obj_parent_ognztn;
      $i++;
      unset($obj_parent_ognztn);
    }
  }
  $result->free();

  //暂存产品信息tbl_product以提高性能
  $arr_products=[];
  $sql="select id,name from tbl_product";
  $result = $conn->query($sql);
  if($result){
    $i=0;
    while($arr=$result->fetch_array(MYSQLI_NUM)){//fetch_array
      $obj_product=new Product;
      $obj_product->id=$arr[0];
      $obj_product->name=$arr[1];
      $arr_products[$i]=$obj_product;
      $i++;
      unset($obj_product);
    }
  }
  $result->free();

  //暂存tbl_contacter,以提高性能
  $arr_contacters=[];
  $sql="select id,name,id_client_dptmt,tel_mobile from tbl_contacter";
  $result = $conn->query($sql);
  if($result){
    $i=0;
    while($arr=$result->fetch_array(MYSQLI_NUM)){//fetch_array
      $obj_contacter=new Contacter;
      $obj_contacter->id=$arr[0];
      $obj_contacter->name=$arr[1];
      $obj_contacter->id_dptmt=$arr[2];
      $obj_contacter->tel_mobile=$arr[3];
      $arr_contacters[$i]=$obj_contacter;
      $i++;
      unset($obj_contacter);
    }
  }
  $result->free();        

  //暂存tbl_employee,以提高性能
  $arr_employees=[];
  $sql="select id,name,tel_work from tbl_employee";
  $result = $conn->query($sql);
  if($result){
    $i=0;
    while($arr=$result->fetch_array(MYSQLI_NUM)){//fetch_array
      $obj_employee=new Employee;
      $obj_employee->id=$arr[0];
      $obj_employee->name=$arr[1];
      $obj_employee->tel_work=$arr[2];
      $arr_employees[$i]=$obj_employee;
      $i++;
      unset($obj_employee);
    }
  }
  $result->free();

  //暂存tbl_equipments,以提高性能$sql="select id from tbl_equipments where alias='"
  $arr_equipments=[];
  $sql="select id,alias from tbl_equipments";
  $result = $conn->query($sql);
  if($result){
    $i=0;
    while($arr=$result->fetch_array(MYSQLI_NUM)){//fetch_array
      $obj_equipment=new Equipment;
      $obj_equipment->id=$arr[0];
      $obj_equipment->alias=$arr[1];
      $arr_equipments[$i]=$obj_equipment;
      $i++;
      unset($obj_equipment);
    }
  }
  $result->free();

  //如果数据库中没有contacter或者client_departmet或者equipment或者employee信息,创建它们
  for($index=0;$index<count($_POST);$index++) {
    $id_of_parent_ognztn='';
    $cstmr_ognz=$_POST[$index]['cstmr_ognz'];
    if($cstmr_ognz!==''){
      $cstmr_ognz=$_POST[$index]['cstmr_ognz']=str_ireplace(['－','-'],'',$cstmr_ognz);
        
      //如果cstmr_ognz没有出现在tbl_client_department中,在数据库中创建它
      for($i=0;$i<count($arr_client_departments);$i++) {
        if($cstmr_ognz===$arr_client_departments[$i]->short_name) {
          break;
        } else {

          //数据库中没有,创建一个
          if($i===count($arr_client_departments)-1) {
            $id_parent=7;
            //数据库中是否有这个cstmr_ognz的id_parent
            for($j=0;$j<count($arr_parent_ognztns);$j++) {
              if(strpos($cstmr_ognz,$arr_parent_ognztns[$j]->short_name) !==false){
                $id_parent=$arr_parent_ognztns[$j]->id;
                break;
              }
            }
            $sql_insert="insert into tbl_client_department (short_name, id_creater, time_create,id_of_parent_ognztn) values (?,1,CURRENT_TIME(),?)";
            $stmt=$conn->prepare($sql_insert);
            $stmt->bind_param('si',$cstmr_ognz,$id_parent);
            $result_insert=$stmt->execute();
            $stmt->free_result();
            $stmt->close();
              
            //更新$arr_client_departments
            $sql="select id,id_of_parent_ognztn,short_name from tbl_client_department";
            $result = $conn->query($sql);
            if($result){
              $h=0;
              while($arr=$result->fetch_array(MYSQLI_NUM)){//fetch_array
                $obj_client_department=new ClientDepartment;
                $obj_client_department->id=$arr[0];
                $obj_client_department->id_prnt=$arr[1];
                $obj_client_department->short_name=$arr[2];
                $arr_client_departments[$h]=$obj_client_department;
                $h++;
                unset($obj_client_department);
              }
            }
            $result->free();                           
          }
        }
      }
    }
    //id_of_parent  
    $_POST[$index]['id_of_parent_ognztn']=7;
    for($i=0;$i<count($arr_client_departments);$i++) {
      if($arr_client_departments[$i]->short_name===$cstmr_ognz) {
        $id_of_parent_ognztn=$_POST[$index]['id_of_parent_ognztn']=$arr_client_departments[$i]->id_prnt;
        break;
      } else {
        if($i===count($arr_client_departments)-1) {
          $id_of_parent_ognztn=$_POST[$index]['id_of_parent_ognztn']=7;//默认:未指定
        }
      }
    }
    //联系人信息
    for($i=0;$i<count($arr_contacters);$i++) {
      $tel_mobile=$_POST[$index]['id_contacter'];
      if($arr_contacters[$i]->tel_mobile===$tel_mobile) {
        $id_contacter=$_POST[$index]['id_contacter']=$arr_contacters[$i]->id;
        break;
      } else {
        if($i===count($arr_contacters)-1) {
          //创建id_contacter
          $name=empty($_POST[$index]['book'])?'Not Given':$_POST[$index]['book'];
          $gender='男';
          $id_responsible=1;
          $id_creater=1;
          $id_client_dptmt='';
          for($h=0;$h<count($arr_client_departments);$h++) {
            if($cstmr_ognz===$arr_client_departments[$h]->short_name) {
              $id_client_dptmt=$arr_client_departments[$h]->id;
            }
          }
          $sql_insert="insert into tbl_contacter (name,gender,tel_mobile,id_client_dptmt,id_responsible,id_creater,time_create) values (?,?,?,?,?,?,CURRENT_TIME())";
          $stmt=$conn->prepare($sql_insert);
          $stmt->bind_param('sssiii',$name,$gender,$tel_mobile,$id_client_dptmt,$id_responsible,$id_creater);
          $result_insert=$stmt->execute();
          $stmt->free_result();
          $stmt->close();
          //更新arr_contacters            
          $sql="select id,name,id_client_dptmt,tel_mobile from tbl_contacter";
          $result = $conn->query($sql);
          if($result){
            $h=0;
            while($arr=$result->fetch_array(MYSQLI_NUM)){//fetch_array
              $obj_contacter=new Contacter;
              $obj_contacter->id=$arr[0];
              $obj_contacter->name=$arr[1];
              $obj_contacter->id_dptmt=$arr[2];
              $obj_contacter->tel_mobile=$arr[3];
              $arr_contacters[$h]=$obj_contacter;
              $h++;
              unset($obj_contacter);
            }
          }
          $result->free();             
        }
      }
    }

    //如果没有此employee或者equipment,创建它们
    // $id_operaters=[];
    //用英文替换中文分号,并在末尾添加英文分号
    $_POST[$index]['id_equipment']=str_ireplace('；',';',$_POST[$index]['id_equipment']).';';
    $_POST[$index]['id_operater']=str_ireplace('；',';',$_POST[$index]['id_operater']).';';
    $count_operaters = substr_count($_POST[$index]['id_operater'],";");
    if(empty($_POST[$index]['driver_name'])) {
      $_POST[$index]['driver_name']='Not Given;';
      for($i=0;$i<$count_operaters-1;$i++) {
        $_POST[$index]['driver_name'].=$_POST[$index]['driver_name'];
      }
    } else {
      $_POST[$index]['driver_name']=str_ireplace('；',';',$_POST[$index]['driver_name']).';';
    }
    $str_tmp_oprtrs=$_POST[$index]['id_operater'];
    $str_tmp_names=$_POST[$index]['driver_name'];
    $str_tmp_eqpmts=$_POST[$index]['id_equipment'];
    for($i=0;$i<$count_operaters;$i++){
      $tel=stristr($str_tmp_oprtrs,';',true);//获取不含分号的电话号
      $nm=stristr($str_tmp_names,';',true);//获取不含分号的姓名
      $eqpmt=stristr($str_tmp_eqpmts,';',true);//获取不含分号的
      //司机
      for($j=0;$j<count($arr_employees);$j++) {
        if($tel===$arr_employees[$j]->tel_work) {
          break;
        } else {
          //如果没有此员工,这里用的是默认值,需要改成如果没有此员工,创建它
          if($j===count($arr_employees)-1) {
            $sql_insert="insert into tbl_employee (name,tel_work,from_ognztn,is_own,id_creater,time_create) values (?,?,'外协',0,1,CURRENT_TIME())";
            $stmt=$conn->prepare($sql_insert);
            $stmt->bind_param('ss',$nm,$tel);
            $result_insert=$stmt->execute();
            $stmt->free_result();
            $stmt->close();
          }
        }
      }
      //车辆
      for($j=0;$j<count($arr_equipments);$j++) {
        if($eqpmt===$arr_equipments[$j]->alias) {
          break;
        } else {
          //如果没有,创建它
          if($j===count($arr_equipments)-1) {
            $sql_insert="insert into tbl_equipments (alias,serial_num,is_own) values (?,?,0)";
            $stmt=$conn->prepare($sql_insert);
            $stmt->bind_param('ss',$eqpmt,$eqpmt);
            $result_insert=$stmt->execute();
            $stmt->free_result();
            $stmt->close();
          }
        }
      }
      //取下一电话号(含分号) 
      $str_tmp_oprtrs = substr(stristr($str_tmp_oprtrs,';'),1);
      $str_tmp_eqpmts = substr(stristr($str_tmp_eqpmts,';'),1);
      $str_tmp_names = substr(stristr($str_tmp_names,';'),1);
    }
    //更新equipments employees 
    $arr_employees=[];
    $sql="select id,name,tel_work from tbl_employee";
    $result = $conn->query($sql);
    if($result){
      $i=0;
      while($arr=$result->fetch_array(MYSQLI_NUM)){//fetch_array
        $obj_employee=new Employee;
        $obj_employee->id=$arr[0];
        $obj_employee->name=$arr[1];
        $obj_employee->tel_work=$arr[2];
        $arr_employees[$i]=$obj_employee;
        $i++;
        unset($obj_employee);
      }
    }
    $result->free();
    
    $arr_equipments=[];
    $sql="select id,alias from tbl_equipments";
    $result = $conn->query($sql);
    if($result){
      $i=0;
      while($arr=$result->fetch_array(MYSQLI_NUM)){//fetch_array
        $obj_equipment=new Equipment;
        $obj_equipment->id=$arr[0];
        $obj_equipment->alias=$arr[1];
        $arr_equipments[$i]=$obj_equipment;
        $i++;
        unset($obj_equipment);
      }
    }
    $result->free();       
  }

  $oprtRslt= new Operate_result($_POST);
  $insert="insert into tbl_orders (cstmr_ognz,id_contacter,id_prjct_belongto,id_contract,id_product,id_rule_price,quantity,actual_price,surcharge,use_surcharge,start_time,end_time,start_point,end_point,id_operater,id_equipment,id_payer,mem,time_create,id_creater,mileage,msg_for_driver,park_fee) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,CURRENT_TIME(),?,?,?,?)";
  $stmt_insert=$conn->prepare($insert);

  for ($count_recorders=0;$count_recorders<count($_POST);$count_recorders++){
    $cstmr_ognz=$_POST[$count_recorders]['cstmr_ognz'];
    $id_of_parent_ognztn=$_POST[$count_recorders]['id_of_parent_ognztn'];
    $id_contacter=$_POST[$count_recorders]['id_contacter'];        

    //这里应该到数据库中动态查询得到项目号
    if($_POST[$count_recorders]['id_prjct_belongto']!==''){
	    $id_prjct_belongto=$_POST[$count_recorders]['id_prjct_belongto']; 
    } else {
      $id_prjct_belongto=$_POST[$count_recorders]['id_prjct_belongto']=3;
    }

    if($_POST[$count_recorders]['id_contract']!==''){
	    $id_contract=$_POST[$count_recorders]['id_contract']; 
    } else {
      $id_contract=$_POST[$count_recorders]['id_contract']=0;//0的合同为日常合同
    }
	  //id_product需要动态查询,为保证兼容,需进行转换
	  $i=3;//从第4个字符开始
	  $id_product='';
	  if($_POST[$count_recorders]['id_product']!==''){
      //每次从开始位置取一个字符,判断是否大写字母,如果是大写字母,更新$id_product
		  while(preg_match('/^[A-Z]+$/',substr($_POST[$count_recorders]['id_product'],$i,1)) && $i<7){
		    $id_product=substr($_POST[$count_recorders]['id_product'],0,$i+1);
			  $i++;
		  }
      // for($i=0;$i<count($arr_products);$i++) {
      //   if($id_product===$arr_products[$i]->) {

      //   }
      // }
		  switch ($id_product) {
        case 'SWBQT':
          $_POST[$count_recorders]['id_product']='211商务全天';
          break;
        case 'SWBBT':
          $_POST[$count_recorders]['id_product']='212商务半天';
          break; 
        case 'SWYY':
          $_POST[$count_recorders]['id_product']='213商务预约';
          break;
		  	case 'SWJCDC':
		  	case 'SWJCWF':
		  		$_POST[$count_recorders]['id_product']='214商务机场/大连站';
		  		break;
        case 'SWBZDC':
        case 'SWBZWF':
          $_POST[$count_recorders]['id_product']='215商务北站';
          break;
        case 'KSTBQT':
          $_POST[$count_recorders]['id_product']='221考斯特全天';
          break;
        case 'KSTBBT':
          $_POST[$count_recorders]['id_product']='222考斯特半天';
          break;
        case 'KSTYY':
          $_POST[$count_recorders]['id_product']='223考斯特预约';
          break;
        case 'KSTJCDC':
        case 'KSTJCWF':
          $_POST[$count_recorders]['id_product']='224考斯特机场/大连站';
          break;
        case 'KSTBZDC':
        case 'KSTBZWF':
          $_POST[$count_recorders]['id_product']='225考斯特北站';
          break;          
        case 'SSBQT':
          $_POST[$count_recorders]['id_product']='231舒适全天';
          break;                                             
        case 'SSBBT':
          $_POST[$count_recorders]['id_product']='232舒适半天';
          break;                                             
        case 'SSYY':
          $_POST[$count_recorders]['id_product']='233舒适预约';
          break;                                             
        case 'SSJCDC':
        case 'SSJCWF':
          $_POST[$count_recorders]['id_product']='234舒适机场/大连站';
          break;                                             
        case 'SSBZDC':
        case 'SSBZWF':
          $_POST[$count_recorders]['id_product']='235舒适北站';
          break;                                             
        case 'HHBQT':
          $_POST[$count_recorders]['id_product']='241豪华全天';
          break;
        case 'HHBBT':
          $_POST[$count_recorders]['id_product']='242豪华半天';
          break;
        case 'HHYY':
          $_POST[$count_recorders]['id_product']='243豪华预约';
          break;
        case 'HHJCDC':
        case 'HHJCWF':
          $_POST[$count_recorders]['id_product']='244豪华机场/大连站';
          break;
		  	case 'HHBZDC':
        case 'HHBZWF':
		  	  $_POST[$count_recorders]['id_product']='245豪华北站';
		  		break;
        case 'DBBQT':
          $_POST[$count_recorders]['id_product']='251大巴全天';
          break;
        case 'DBBBT':
          $_POST[$count_recorders]['id_product']='252大巴半天';
          break;
        case 'DBYY':
          $_POST[$count_recorders]['id_product']='253大巴预约';
          break;
        case 'DBJCDC':
        case 'DBJCWF':
          $_POST[$count_recorders]['id_product']='254大巴机场/大连站';
          break;
		  	case 'DBBZDC':
        case 'DBBZWF':
		  	  $_POST[$count_recorders]['id_product']='255大巴北站';
		  		break;
		  	default:
		  	  break;
		  }

	    for($i=0;$i<count($arr_products);$i++) {
          if($_POST[$count_recorders]['id_product']===$arr_products[$i]->name) {
            $id_product=$_POST[$count_recorders]['id_product']=$arr_products[$i]->id;
            break;
          } else {
            if($i===count($arr_products)-1) {
              $id_product=$_POST[$count_recorders]['id_product']=12;//默认
            }
          }
        }
    } else {
	    $id_product=$_POST[$count_recorders]['id_product']=12;//默认	
	  }
   
    $_POST[$count_recorders]['id_rule_price']='';//增加一个属性
    $id_rule_price=$_POST[$count_recorders]['id_rule_price']=0;

    if($_POST[$count_recorders]['quantity']!==''){
	    $quantity=$_POST[$count_recorders]['quantity']; 
    } else {
      $quantity=$_POST[$count_recorders]['quantity']=1;//默认
    }
    if(!empty($_POST[$count_recorders]['actual_price'])){
	    $actual_price=$_POST[$count_recorders]['actual_price']; 
    } else {
      $actual_price=$_POST[$count_recorders]['actual_price']=0;//默认
    }
    if(!empty($_POST[$count_recorders]['surcharge'])){
	    $surcharge=$_POST[$count_recorders]['surcharge']; 
    } else {
      $surcharge=$_POST[$count_recorders]['surcharge']=0;//默认
    }
    if($_POST[$count_recorders]['use_surcharge']!==''){
	    $use_surcharge=$_POST[$count_recorders]['use_surcharge']; 
    } else {
      $use_surcharge=$_POST[$count_recorders]['use_surcharge']='';//默认
    }
    if($_POST[$count_recorders]['start_time']!==''){
	    $start_time=$_POST[$count_recorders]['start_time']; 
    } else {
      $start_time=$_POST[$count_recorders]['start_time']='08:00';//默认
    }
    if($_POST[$count_recorders]['start_date']!==''){
	    $start_date=$_POST[$count_recorders]['start_date']; 
    } else {
      $start_date=$_POST[$count_recorders]['start_date']=date("Y-m-d",strtotime("-1 day"));//默认昨天
    }        
	  $start_time=$_POST[$count_recorders]['start_time']=$_POST[$count_recorders]['start_date'].' '.$_POST[$count_recorders]['start_time'];
	  unset($_POST[$count_recorders]['start_date']);

    if($_POST[$count_recorders]['end_time']!==''){
	    $end_time=$_POST[$count_recorders]['end_time']; 
    } else {
      $end_time=$_POST[$count_recorders]['end_time']='17:00';//默认
    }
    if($_POST[$count_recorders]['end_date']!==''){
	    $end_date=$_POST[$count_recorders]['end_date']; 
    } else {
      $end_date=$_POST[$count_recorders]['end_date']=$start_date;//默认
    }        	    
	  $end_time=$_POST[$count_recorders]['end_time']=$_POST[$count_recorders]['end_date'].' '.$_POST[$count_recorders]['end_time'];
	  unset($_POST[$count_recorders]['end_date']);

    if($_POST[$count_recorders]['start_point']!==''){
	    $start_point=$_POST[$count_recorders]['start_point']; 
    } else {
      $start_point='Not Given';  
    }

    if($_POST[$count_recorders]['end_point']!==''){
	    $end_point=$_POST[$count_recorders]['end_point']; 
    } else {
      $end_point='Not Given';  
    }

    $id_operaters=[];
    //用英文替换中文分号,并在末尾添加英文分号
	  $count_operaters = substr_count($_POST[$count_recorders]['id_operater'],";");
    $str_tmp=$_POST[$count_recorders]['id_operater'];
	  for($i=0;$i<$count_operaters;$i++){
      $tel=stristr($str_tmp,';',true);//获取不含分号的电话号
      for($j=0;$j<count($arr_employees);$j++) {
        if($tel===$arr_employees[$j]->tel_work) {
          $id_operaters[$i]=$arr_employees[$j]->id;
          break;
        }
      }
      //取下一电话号(含分号) 
      $str_tmp= substr(stristr($str_tmp,';'),1);
    }
        
    $id_equipments=[];
    //用英文替换中文分号,并在末尾添加英文分号
    $count_equipments = substr_count($_POST[$count_recorders]['id_equipment'],";");
    $str_tmp=$_POST[$count_recorders]['id_equipment'];
    for($i=0;$i<$count_equipments;$i++){
      $num_car=stristr($str_tmp,';',true);//获取不含分号的车牌号
      for($j=0;$j<count($arr_equipments);$j++) {
        if($num_car===$arr_equipments[$j]->alias) {
          $id_equipments[$i]=$arr_equipments[$j]->id;
          break;
        }
      }
      //取下一车牌号(含分号) 
      $str_tmp= substr(stristr($str_tmp,';'),1);
    }

    for($i=0;$i<count($arr_client_departments);$i++) {
      if($arr_client_departments[$i]->short_name===$_POST[$count_recorders]['id_payer']) {
        $id_payer=$_POST[$count_recorders]['id_payer']=$arr_client_departments[$i]->id;
        break;
      } else {
        if($i===count($arr_client_departments)-1) {
          $id_payer=$_POST[$count_recorders]['id_payer']=7;//默认:未指定
        }
      }
    }        

    if($_POST[$count_recorders]['mem']!==''){
	    $mem=$_POST[$count_recorders]['mem']; 
    } else {
      $mem=$_POST[$count_recorders]['mem']='';
    }

    if($_POST[$count_recorders]['id_creater']!==''){
	    $id_creater=$_POST[$count_recorders]['id_creater']; 
    } else {
      $id_creater=$_POST[$count_recorders]['id_creater']=1;//默认
    }

    if($_POST[$count_recorders]['mileage']!==''){
	    $mileage=$_POST[$count_recorders]['mileage']; 
    } else {
      $mileage=$_POST[$count_recorders]['mileage']=0;//默认
    }

    if($_POST[$count_recorders]['msg_for_driver']!==''){
	    $msg_for_driver=$_POST[$count_recorders]['msg_for_driver']; 
    } else {
      $msg_for_driver='';  
    }

    if($_POST[$count_recorders]['park_fee']!==''){
	    $park_fee=$_POST[$count_recorders]['park_fee']; 
    } else {
      $park_fee=$_POST[$count_recorders]['park_fee']=0;//默认
    }
// echo json_encode($_POST);
// exit;
// 判断是否重复.如重复,则退出;否则进行插入操作$last6month_identifyingcodes
    for($i=0;$i<count($id_operaters);$i++) {
    	//id_operaters 和 id_equipments的长度不匹配时
    	if(count($id_operaters)>count($id_equipments)){
        $id_operaters=array_slice($id_operaters,0,count($id_equipments),true);
    	}
	    $current_identifyingcode=$id_contacter.$start_time.$id_operaters[$i].$id_equipments[$i];
	    if(!in_array($current_identifyingcode,$last6month_identifyingcodes)) {//不重复
			  $stmt_insert->bind_param('siiiiiiddsssssiiisidsd',$cstmr_ognz,$id_contacter,$id_prjct_belongto,$id_contract,$id_product,$id_rule_price,$quantity,$actual_price,$surcharge,$use_surcharge,$start_time,$end_time,$start_point,$end_point,$id_operaters[$i],$id_equipments[$i],$id_payer,$mem,$id_creater,$mileage,$msg_for_driver,$park_fee);
			  $result=$stmt_insert->execute();
			  $stmt_insert->free_result();
			  if($result) {
			  	$oprtRslt->countOfInserted+=1;
			  } else {
			    $oprtRslt->error=$stmt_insert->error;
			  }
	    } else {//重复
        $dplctedRcdr='时间:'.$start_time.';';
        $dplctedRcdr=$dplctedRcdr.$start_point;
        $dplctedRcdr=$dplctedRcdr.'------>'.$end_point;
        array_push($oprtRslt->duplicate_recorders,$dplctedRcdr);
        $dplctedRcdr='';
	    }        	
      $current_identifyingcode='';//清空识别码
    }
      
    unset($id_operaters);
    unset($id_equipments);
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