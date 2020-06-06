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
    $sql="select id,number,name_part_a,agent_a,name_part_b,basic_content,name_product,unit_price,count_product,amount,way_pay,DATE_FORMAT(time_start,'%Y-%m-%d') as time_start,DATE_FORMAT(time_end,'%Y-%m-%d') as time_end,agent_b,DATE_FORMAT(time_create,'%Y-%m-%d') as time_create,is_finished,DATE_FORMAT(time_sign,'%Y-%m-%d') as time_sign,remark from tbl_contract where is_finished=0 order by time_start";

    $results = $conn->query($sql);
    $conn->close();

    if($results){
        while($arr=$results->fetch_assoc()){//fetch_array  
            array_push($outputData,$arr);
        }
        echo json_encode($outputData);
    }
    $results->free();
?>