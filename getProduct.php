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

    if(!empty($_POST['conditions']) && $_POST['conditions']==='ExceptVehicle') {
      $sql="select * from tbl_product where id_type<>2 and id_type<>3 order by name asc";
    } else {
      $sql="select * from tbl_product order by name asc";
    }

    $result = $conn->query($sql);
    $conn->close();

    if($result){
        while($arr=$result->fetch_assoc()){//fetch_array
            array_push($outputData,$arr);
        }
        echo json_encode($outputData);
    }
    $result->free();
?>