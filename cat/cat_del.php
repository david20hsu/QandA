<?php
session_start();
if (!isset($_SESSION["username"])) {
   header("Location: ../index.php");
   exit();
}
$cat_id = $_GET['cat_id'];
require('../include/config.php');
$cql = "delete from qna_category where cat_id='$cat_id'";
$stmt = $conn->prepare($cql);
$stmt->execute();
$msg =
   $msg = "<p align='center'><span style='font-size:30px; color:red;'>類別編號:" . $cat_id . ',已刪除!! </br> 系統會自行返回 !!</span></p>';
echo $msg;
echo '<meta http-equiv=REFRESH CONTENT=4;url=../cat_list.php>';
