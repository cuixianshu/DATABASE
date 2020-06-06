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
    $sql="select name_in_excel from tbl_dictionary_name_in_orders_excel where type_product_en='VECL'";

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