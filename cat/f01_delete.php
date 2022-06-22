<?php
extract($_REQUEST);
include('conn.php');
$sql = "select * from prod_f01 where prod_id='$del'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$prod_id = $row['prod_id'];
$prod_idx = str_replace('/', '-', $prod_id);
$dir = './' . substr($prod_idx, 0, strlen($prod_idx) - 4);
$pfd = $row['pfd'];
$pcp = $row['pcp'];
$pfm =  $row['pfm'];
if ($pfd <> '') {
   $pfd = $dir . '/' . $pfd . '.pdf';
}
if ($pcp <> '') {
   $pcp = $dir . '/' . $pcp . '.pdf';
}
if ($pfm <> '') {
   $pfm = $dir . '/' . $pfm . '.pdf';
}
if (file_exists($pfd)) {
   unlink($pfd);
}
if (file_exists($pcp)) {
   unlink($pcp);
}
if (file_exists($pfm)) {
   unlink($pfm);
}
if (is_dir($dir)) {
   rmdir($dir);  //刪除目錄
}
$sql = "delete from prod_f01 where prod_id='$del'";
mysqli_query($conn, $sql);
$_SESSION['success'] = '清除資料:' . $prod_id . '...檔案(PFD,PCP,PFM)';
header("Location:f01_list.php");