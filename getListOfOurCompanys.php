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

    $sql="select id,name from tbl_our_company";
    $results = $conn->query($sql);
    $conn->close();

    if($results){
        while($arr=$results->fetch_assoc()){//fetch_array
            array_push($respondedData,$arr);
        }
        echo json_encode($respondedData);
    }
    $results->free();
?>