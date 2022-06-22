<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}
$title = "嘉南羊乳-問題與解答(新增)";
require_once("html/header.html");
require_once("html/menu.html");
// Include config file

// Define variables and initialize with empty values
$pass_new = $pass_cfm = "";
$pass_old = "";
$msg = "";
// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "./include/config.php";
    // print_r('L'.trim($_POST['pass_new']).',N'.trim($_POST['pass_old']));
    // exit();
    if (trim($_POST['pass_new']) <> trim($_POST['pass_cfm'])) {
        $msg = "<script>alert('新設密碼 與 確認密碼..不一致')</script>";
    }
    if (trim($_POST['pass_new']) == trim($_POST['pass_old'])) {
        $msg = "<script>alert('新設密碼 與 原始密碼..相同')</script>";
    }
    if ($msg <> "") {
        echo $msg;
    } else {
        $data = [
            'userpass' => md5($_POST['pass_new']),
            "username" => $_SESSION["username"]
        ];
        $sql = "UPDATE qna_user0 SET `userpass`=:userpass WHERE username =:username";
        $stmt = $conn->prepare($sql);
        $stmt->execute($data);
        $msg = "<p align='center'><span style='font-size:30px;font-family: Microsoft JhengHei; color:red;'>重設密碼 完成!!</span></p>";
        echo $msg;
        echo '<meta http-equiv=REFRESH CONTENT=3;url=./index.php>';
    }
}
?>
<style>
    .required {
        color: red;
    }
</style>
<div class="container mt-1">
    <div class="row mt-4">
        <div class="col-sm-4"></div>
        <!--card center-->
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title btn-warning btn-block text-center"><?php echo $_SESSION["username"]; ?> &emsp; 重設密碼</div>
                    <div class="card-text">
                        <form name="form" method="post" action="#">
                            <div class="form-group">
                                <label for="pass_old" class="col-sm-5 control-label">原始密碼<span class="required">*</span>
                                    :</label>
                                <div class="col-sm-5">
                                    <input type="password" name="pass_old" id="pass_old" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="pass_new" class="col-sm-5 control-label">新設密碼<span class="required">*</span>
                                    :</label>
                                <div class="col-sm-5">
                                    <input type="password" name="pass_new" id="pass_new" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="pass_new" class="col-sm-5 control-label">確認密碼<span class="required">*</span>
                                    :</label>
                                <div class="col-sm-5">
                                    <input type="password" name="pass_cfm" id="pass_cfm" required>
                                </div>
                            </div>
                            </p>
                            <button type="submit" name='submit' class="btn btn-success btn-block">提交</button> </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

</html>