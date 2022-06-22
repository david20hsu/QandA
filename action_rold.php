<?php
header('Content-Type: application/json;charset=utf-8'); //return json string
require('./include/config0.php');
$city_id = $_GET['city_id'];
$jarray = array(); //使用array儲存結果，再以json_encode一次回傳
if ($city_id  != '') {
  $sql = "SELECT distinct rold_id,rold_name  FROM city_rold where city_id='$city_id' order by rold_name";
  $statement = $conn->prepare($sql);
  $statement->execute();
  $jarray = $statement->fetchAll();
} else {
  echo 0;
  return;
}
echo json_encode($jarray, JSON_UNESCAPED_UNICODE);
return json_encode($jarray, JSON_UNESCAPED_UNICODE);
