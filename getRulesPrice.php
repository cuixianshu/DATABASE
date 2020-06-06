<?php  
	$servername = "localhost";
	$username = "root";
	$password = "Mwy197301242811";
	$dbname = "cuixianshu"; // 要操作的数据库名
    $outputData=array();
	$conn= new mysqli($servername,$username,$password,$dbname); // 注意第四个参数
	if($conn->connect_error){
	    die("连接失败，错误:" . $conn->connect_error);
	}
    $sql="select id,name,id_for_ognztn,id_product_for,price_basic,duration_basic,scale_basic,price_extra_duration,price_extra_mileage,miss_meal_fee,usable,other,(select short_name from tbl_client_parent_ognztn where id=id_for_ognztn) as name_ognztn from tbl_rule_price order by convert(name using gbk) asc";
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