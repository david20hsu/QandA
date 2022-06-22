
<?php
session_start();
if (!isset($_SESSION["username"])) {
   header("Location: ../index.php");
   exit();
}
extract($_REQUEST);
require('../include/config.php');
$cql = "delete from mp3_file where id='$del'";
$dir = '../upload_mp3/' . $file_id;
$stmt = $conn->prepare($cql);
$stmt->execute();
$dir = '../upload_mp3/' . $file_id;
if (file_exists($dir)) {
   unlink($dir);
}
$msg = "<p align='center'><span style='font-size:30px;font-family: Microsoft JhengHei; color:red;'>語音檔編號:" . $del . ',已刪除!! </br> 系統會自行返回 !!</span></p>';
echo $msg;
echo '<meta http-equiv=REFRESH CONTENT=3;url=../mp3_list.php>';
?>
