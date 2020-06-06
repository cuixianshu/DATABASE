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
  // echo json_encode($_POST);
  // exit;

  $bscinfo=$_POST['bscinfo'];
  $bscinfo_authorization=$_POST['bscinfo_authorization'];
  $bscinfo_clnt_dptmt=$_POST['bscinfo_clnt_dptmt'];
  $bscinfo_contract=$_POST['bscinfo_contract'];
  $bscinfo_employee=$_POST['bscinfo_employee'];
  $bscinfo_equipment=$_POST['bscinfo_equipment'];
  $bscinfo_product=$_POST['bscinfo_product'];
  $bscinfo_project=$_POST['bscinfo_project'];
  $bscinfo_rule_price=$_POST['bscinfo_rule_price'];
  $finance=$_POST['finance'];
  $finance_accept_other_funds=$_POST['finance_accept_other_funds'];
  $finance_cashier=$_POST['finance_cashier'];
  $finance_check_receipts=$_POST['finance_check_receipts'];
  $finance_pay=$_POST['finance_pay'];
  $finance_review_payment=$_POST['finance_review_payment'];
  $finance_tkt_cashier=$_POST['finance_tkt_cashier'];
  $id_user=$_POST['id_user'];
  $invoices=$_POST['invoices'];
  $invoices_apply=$_POST['invoices_apply'];
  $invoices_details=$_POST['invoices_details'];
  $invoices_modify_fill=$_POST['invoices_modify_fill'];
  $invoices_refill_cancel=$_POST['invoices_refill_cancel'];
  $materials=$_POST['materials'];
  $materials_Inventory=$_POST['materials_Inventory'];
  $materials_acceptance=$_POST['materials_acceptance'];
  $materials_distribute=$_POST['materials_distribute'];
  $materials_search=$_POST['materials_search'];
  $orders=$_POST['orders'];
  $orders_check_orders=$_POST['orders_check_orders'];
  $orders_import_from_excel=$_POST['orders_import_from_excel'];
  $orders_input_byhand=$_POST['orders_input_byhand'];
  $orders_tkt_change_refound=$_POST['orders_tkt_change_refound'];
  $orders_tkt_inbound=$_POST['orders_tkt_inbound'];
  $orders_tkt_outbound=$_POST['orders_tkt_outbound'];
  $personal=$_POST['personal'];
  $personal_apply=$_POST['personal_apply'];
  $personal_audits=$_POST['personal_audits'];
  $personal_logout=$_POST['personal_logout'];
  $personal_modify_info=$_POST['personal_modify_info'];
  $personal_turn_in=$_POST['personal_turn_in'];
  $purchasing=$_POST['purchasing'];
  $purchasing_apply=$_POST['purchasing_apply'];
  $purchasing_approve_applying=$_POST['purchasing_approve_applying'];
  $purchasing_approve_enquiry=$_POST['purchasing_approve_enquiry'];
  $purchasing_enquiry_compare=$_POST['purchasing_enquiry_compare'];
  $purchasing_launch=$_POST['purchasing_launch'];
  $reports=$_POST['reports'];
  $reports_finance=$_POST['reports_finance'];
  $reports_other=$_POST['reports_other'];
  $reports_purchasing=$_POST['reports_purchasing'];
  $reports_sale=$_POST['reports_sale'];
  $rqstfunds=$_POST['rqstfunds'];
  $rqstfunds_borrow_reimburse=$_POST['rqstfunds_borrow_reimburse'];
  $rqstfunds_final_audits=$_POST['rqstfunds_final_audits'];
  $rqstfunds_primary_audits=$_POST['rqstfunds_primary_audits'];
  $rqstfunds_purchasing_funds=$_POST['rqstfunds_purchasing_funds'];
  $rqstfunds_rfdtkt_paying=$_POST['rqstfunds_rfdtkt_paying'];

  $sql_insert="INSERT INTO `tbl_user_authorization` (`bscinfo`,`bscinfo_authorization`,`bscinfo_clnt_dptmt`,`bscinfo_contract`,`bscinfo_employee`,`bscinfo_equipment`,`bscinfo_product`,`bscinfo_project`,`bscinfo_rule_price`,`finance`,`finance_accept_other_funds`,`finance_cashier`,`finance_check_receipts`,`finance_pay`,`finance_review_payment`,`finance_tkt_cashier`,`id_user`,`invoices`,`invoices_apply`,`invoices_details`,`invoices_modify_fill`,`invoices_refill_cancel`,`materials`,`materials_Inventory`,`materials_acceptance`,`materials_distribute`,`materials_search`,`orders`,`orders_check_orders`,`orders_import_from_excel`,`orders_input_byhand`,`orders_tkt_change_refound`,`orders_tkt_inbound`,`orders_tkt_outbound`,`personal`,`personal_apply`,`personal_audits`,`personal_logout`,`personal_modify_info`,`personal_turn_in`,`purchasing`,`purchasing_apply`,`purchasing_approve_applying`,`purchasing_approve_enquiry`,`purchasing_enquiry_compare`,`purchasing_launch`,`reports`,`reports_finance`,`reports_other`,`reports_purchasing`,`reports_sale`,`rqstfunds`,`rqstfunds_borrow_reimburse`,`rqstfunds_final_audits`,`rqstfunds_primary_audits`,`rqstfunds_purchasing_funds`,`rqstfunds_rfdtkt_paying`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
  $stmt=$conn->prepare($sql_insert);

  $stmt->bind_param('iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii',$bscinfo,$bscinfo_authorization,$bscinfo_clnt_dptmt,$bscinfo_contract,$bscinfo_employee,$bscinfo_equipment,$bscinfo_product,$bscinfo_project,$bscinfo_rule_price,$finance,$finance_accept_other_funds,$finance_cashier,$finance_check_receipts,$finance_pay,$finance_review_payment,$finance_tkt_cashier,$id_user,$invoices,$invoices_apply,$invoices_details,$invoices_modify_fill,$invoices_refill_cancel,$materials,$materials_Inventory,$materials_acceptance,$materials_distribute,$materials_search,$orders,$orders_check_orders,$orders_import_from_excel,$orders_input_byhand,$orders_tkt_change_refound,$orders_tkt_inbound,$orders_tkt_outbound,$personal,$personal_apply,$personal_audits,$personal_logout,$personal_modify_info,$personal_turn_in,$purchasing,$purchasing_apply,$purchasing_approve_applying,$purchasing_approve_enquiry,$purchasing_enquiry_compare,$purchasing_launch,$reports,$reports_finance,$reports_other,$reports_purchasing,$reports_sale,$rqstfunds,$rqstfunds_borrow_reimburse,$rqstfunds_final_audits,$rqstfunds_primary_audits,$rqstfunds_purchasing_funds,$rqstfunds_rfdtkt_paying);
  $result_insert=$stmt->execute();
  $stmt->free_result();
  $stmt->close();     
    //是否全部成功执行
  if($result_insert) {
    echo json_encode(true);
  } else {
    echo json_encode(false);
  }
  $conn->close();
?>
