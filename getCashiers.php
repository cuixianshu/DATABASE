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
/*
amount: "200.00"
conditions: "WithOrder"
describe_confirm: "sfdsf"
id: 15
id_account: "1"
id_cashier: 1
id_way_pay: "2"
other: ""
 */
/*
conditions: "ReiceiptsWithoutChecking"
dateRange: (2) ["2020-03-17", "2020-03-24"]
keyWord: ""
 */
// echo json_encode($_POST['dateRange'][0]);
// exit;
    if($_POST['conditions']=='CollectionsWithoutChecking') {
      $sql="select *,(select name from tbl_way_pay where id=id_way_pay) as way_pay,(select name from tbl_project where id=id_project) as project,(select short_name from tbl_our_account where id=id_account) as account,(select name from tbl_employee where id=id_cashier) as cashier from `tbl_cashier` where (`time_collect` between ? and ?) and `other` like CONCAT('%',?,'%') and `id_confirmer` IS NULL";
    }

    $stmt=$conn->prepare($sql);
    $stmt->bind_param('sss',$_POST['dateRange'][0],$_POST['dateRange'][1],$_POST['keyWord']);
    $stmt->execute();
    $result = $stmt->get_result();
  
    if(!$result) {
      echo json_encode($conn->error);
      exit;
    }
    $i=0;
    while ($row = $result->fetch_assoc()) {
        $respondedData[$i]=$row;
        $i++;
    }    
    echo json_encode($respondedData);
    $stmt->free_result();
    $stmt->close();
    $conn->close();
?>
