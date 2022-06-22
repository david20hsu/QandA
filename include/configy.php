<?php
$dbhost = '127.0.0.1';
$dbuser = 'root';
$dbpasswd = '';
$dbname = 'goal_pp';
$dsn = "mysql:host=".$dbhost.";dbname=".$dbname;
try
{
    //注意，使用PDO方式連結，需要指定一個資料庫，否則將拋出異常
    $conny = new PDO($dsn,$dbuser,$dbpasswd);
    $conny->exec("SET CHARACTER SET utf8");
    $conny->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 //   echo "Connected Successfully";
}
catch(PDOException $e)
{
    echo "資料庫連線失敗: ".$e->getMessage();
}
?>