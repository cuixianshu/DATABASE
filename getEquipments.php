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
    
  if(!empty($_POST['conditions']) && $_POST['conditions']=='All') {
    $sql="select *,(select name from tbl_employee where id=id_responsible_person) as rspnsbl_prsn,remark,CONCAT_WS('@',name,alias) as nmNmbr from tbl_equipments order by convert(name using gbk) asc";



  } else {//只获取公司内部的
    $sql="select *,(select name from tbl_employee where id=id_responsible_person) as rspnsbl_prsn,remark,CONCAT_WS('@',name,alias) as nmNmbr from tbl_equipments where is_own=1 order by convert(name using gbk) asc";
  }

  $result = $conn->query($sql);
  if($result){
    while($arr=$result->fetch_assoc()){//fetch_array
      array_push($outputData,$arr);
    }
    echo json_encode($outputData);
  }
  $result->free();
  $conn->close();
?>
