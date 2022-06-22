<?php
session_start();
if (!isset($_SESSION["username"])) {
   header("Location: ../index.php");
   exit();
}
$username = $_GET['username'];
require('../include/config.php');
$cql = "delete from qna_user0 where username='$username'";
$stmt = $conn->prepare($cql);
$stmt->execute();
$msg =
   $msg = "<p align='center'><span style='font-size:30px; color:red;'>帳號:" . $username . ',已刪除!! </br> 系統會自行返回 !!</span></p>';
echo $msg;
echo '<meta http-equiv=REFRESH CONTENT=3;url=../qnauser_list.php>';
