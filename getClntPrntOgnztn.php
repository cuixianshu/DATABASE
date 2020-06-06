<?php  
	$servername = "localhost";
	$username = "root";
	$password = "Mwy197301242811";
	$dbname = "cuixianshu"; // 要操作的数据库名
    $outputData=[];
	// 创建连接 
	$conn= new mysqli($servername,$username,$password,$dbname); // 注意第四个参数
	if($conn->connect_error){
	    die("连接失败，错误:" . $conn->connect_error);
	}
    
    $keyWord=$_POST['keyWord'];

    $sql="select * from tbl_client_parent_ognztn where short_name like CONCAT('%',?,'%') or full_name like CONCAT('%',?,'%') order by id asc";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('ss',$keyWord,$keyWord);
    $stmt->execute();
    $result = $stmt->get_result();
  
    if(!$result) {
      echo json_encode($conn->error);
      exit;
    }
    $i=0;
    while ($row = $result->fetch_assoc()) {
        $outputData[$i]=$row;
        $i++;
    }    
    echo json_encode($outputData);
    $stmt->free_result();
    $stmt->close();
    $conn->close();
?>