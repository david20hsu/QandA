<?php
session_start();
if(!isset($_SESSION["username"])){
   header("Location: ../index.php");
   exit(); 
}
$id=$_GET['id'];
require('../include/config.php'); 
$cql = "update qna_posts set status='發佈' where id='$id'";
$stmt = $conn->prepare($cql); 
$stmt->execute();
$msg =
$msg="<p align='center'><span style='font-size:30px; color:red;'>問答編號:".$id.',已發佈!! </br> 系統會自行返回 !!</span></p>';
echo $msg;
echo '<meta http-equiv=REFRESH CONTENT=4;url=../index.php>';
