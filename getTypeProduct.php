<?php  
	$servername = "localhost";
	$username = "root";
	$password = "Mwy197301242811";
	$dbname = "cuixianshu"; // 要操作的数据库名
    $outputData=array();
	// 创建连接 
	$link= new mysqli($servername,$username,$password,$dbname); // 注意第四个参数
	if($link->connect_error){
	    die("连接失败，错误:" . $link->connect_error);
	}
    $sql="select id,CONCAT_WS('-',short_en,short_cn) as type_product from tbl_type_product order by convert(type_product using gbk) asc";

    $result = $link->query($sql);
    $link->close();

    if($result){
        while($arr=$result->fetch_assoc()){//fetch_array
            array_push($outputData,$arr);
        }
        echo json_encode($outputData);
    }
    $result->free();
?>