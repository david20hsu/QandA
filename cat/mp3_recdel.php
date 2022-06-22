
<?php
session_start();
if (!isset($_SESSION["username"])) {
   header("Location: ../index.php");
   exit();
}
extract($_REQUEST);
require('../include/config.php');
$cql = "delete from mp3_rec where id='$del'";
$stmt = $conn->prepare($cql);
$stmt->execute();
$msg = "<p align='center'><span style='font-size:30px;font-family:Microsoft JhengHei; color:red;'>語音檔紀錄代號:" . $del . ',已刪除!! </br> 系統會自行返回 !!</span></p>';
echo $msg;
echo '<meta http-equiv=REFRESH CONTENT=3;url=../mp3_reclist.php>';
?>
