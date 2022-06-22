<?php
require_once("./html/header.html");
require_once("./html/menu.html");
$msg="";
$title="";
$message="";
$cat_id="";
$category="";
$status="";
$username="";
$hits="";
$created="";
$updated="";

if(isset($_GET["id"]) && !empty($_GET["id"])){
    require_once "./include/config.php";
    $sql = "SELECT a.*,b.category FROM qna_posts a left join qna_category b using(cat_id) WHERE `id`=?";
    $id=$_GET["id"];
	$query = $conn->prepare($sql);
	$query->execute(array($_GET["id"]));
	$row = $query->rowCount();
	$fetch = $query->fetch();
	if($row > 0) {
        $title=$fetch['title'];
        $message=$fetch['message'];
        $cat_id=$fetch['cat_id'];
        $category=$fetch['category'];
        $status=$fetch['status'];
        $username=$fetch['username'];
        $hits=$fetch['hits'];
        $created=$fetch['created'];
        $updated=$fetch['updated'];
        $data = [
            'hits' =>(int)$hits+1,
            "id"=>$id
        ]; 
        $cql = "UPDATE `qna_posts` SET `hits`=:hits where  `id`=:id";
        $stmt = $conn->prepare($cql); 
        $stmt->execute($data);
     } else{
         $msg="糟糕！ 出問題了。 請稍後再試.<br>";
      }
}
?>
<p></p>
<style>
 #show{
  margin-left:10px;
  margin-right:10px;
  margin-top:10px;
  margin-bottom: 10px;
 }
 #show0{
  margin-left:10px;
  margin-right:10px;
  margin-top:10px;
  margin-bottom: 10px;
 }
</style>
<?php
    if($msg<>''){?>
        <div class='err'>
            <p align='center'><span style='font-size:20px;'>
            <?php echo $msg; ?>
          </span></p>
          </div>
<?php }else{ ?>
<div class="row" id="show">
<div class="col-7">
<label class="btn btn-warning btn-sm">問題描述:</label></br>
<span style="font-size:20px; color:blue;"> <?php echo nl2br($title); ?></span>
</div>
<div class="col-3">
<label class="btn btn-secondary btn-sm">編修時間:<?php echo substr($created,0,10).'&nbsp&nbsp;'.$username; ?></label>
</div>
<div class="col-2">
<label class="btn btn-secondary btn-sm" >點閱:<?php echo $hits; ?></label>
</div>
<div>
<div class="row" id="show0">
<div class="col-12">
<label class="btn btn-success btn-sm">解說內容:</label></br>
<span tyle="font-size:20px;"> <?php echo nl2br($message); ?></span>
</div>
<hr>
</div>
<div>
<?php }?>
<p align='center'><a href="index.php"  class="btn btn-info" role="button">返回</a> </p>
<hr>
<?php
  require("./html/footer.html");
?>
</body>
</html>