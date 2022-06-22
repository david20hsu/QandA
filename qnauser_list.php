<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}
$title = "嘉南羊乳-帳號管理";
require_once("html/header.html");
require_once("html/menu.html");
$ym = date('Y-m-d');
$user_role = '';
$fullname = '';
$userpass = '';
$msg = '';
?>
<?php
if (isset($_POST["editsubmit"])) {
    require_once "./include/config.php";
    $username = $_POST['usernamex'];
    $userpass = $_POST['userpassx'];
    if (strlen($userpass) > 5) {
        $userpass = md5($_POST['userpassx']);
        $fullname = $_POST['fullnamex'];
        $user_role = $_POST['user_rolex'];
        $data = [
            'userpass' => $userpass,
            "fullname" => $fullname,
            "user_role" => $user_role,
            'username' => $username
        ];
        $cql = "UPDATE `qna_user0` SET `userpass`=:userpass,`fullname`=:fullname,`user_role`=:user_role where `username`=:username";
        $stmt = $conn->prepare($cql);
        $stmt->execute($data);
    } else {
        echo '<script>alert("密碼太簡單..建議 > 5 字..重設")</script>';
    }
    //  $conn=null;
}
function chk_key($sql)
{
    require('./include/config.php');
    $sth = $conn->prepare($sql);
    $sth->execute();
    $count = $sth->rowCount();
    $conn = null;
    return $count;
}
if (isset($_POST["addsubmit"])) {
    require_once "./include/config.php";
    $username = $_POST['username'];
    $userpass = $_POST['userpass'];
    if (strlen($userpass) > 5) {
        $sql = "select `fullname` from `qna_user0` where username='$username'";
        $rowcount = chk_key($sql);
        if ($rowcount == 0) {
            $userpass = md5($_POST['userpass']);
            $fullname = $_POST['fullname'];
            $user_role = $_POST['user_role'];
            $data = [
                'username' => $username,
                'userpass' => $userpass,
                "fullname" => $fullname,
                "user_role" => $user_role
            ];
            $cql = "INSERT INTO `qna_user0`(`username`,`userpass`,`fullname`,`user_role`) Value(:username,:userpass,:fullname,:user_role)";
            $stmt = $conn->prepare($cql);
            $stmt->execute($data);
        } else {
            echo  '<script>alert("帳號:' . $username . ' 已建檔..不能重覆!!")</script>';
        }
        $conn = null;
    } else {
        echo '<script>alert("密碼太簡單..建議 > 5 字..重設")</script>';
    }
}
?>
<style>
    table {
        margin: auto;
    }

    table.dataTable thead th,
    tbody th,
    table.dataTable tbody td {
        padding: 2px 2px;
        /* e.g. change 8x to 4px here */
    }

    .required {
        color: red;
    }
</style>
<div class="container-fluid">
    <div class="row" id="tprow">
        <div class="col-8">
            <h3 align='center'><?php echo $title ?></h3>
        </div>
        <div class="col-4">
            <a href="#addModal" data-toggle="modal" class="btn btn-info btn-sm mt-1">新增</a>
        </div>
    </div>
    <table id="myDataTalbe" class="table table-bordered table-striped table-hove" style="width:90%">
        <thead>
            <tr>
                <th>帳號</th>
                <th>身份</th>
                <th>全名</th>
                <th>訪問次數</th>
                <th>建檔日期</th>
                <th>編修日期</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require('include/config.php');
            $sql = "select *  from qna_user0 order by user_role desc";
            $sth = $conn->prepare($sql);
            $sth->execute();
            while ($row = $sth->fetch()) {
                $user_role = $row["user_role"];
            ?>
                <tr>
                    <td><?php echo $row["username"]; ?> </td>
                    <td><?php echo $row["user_role"]; ?> </td>
                    <td><?php echo $row["fullname"]; ?></td>
                    <td><?php echo $row["visited"]; ?></td>
                    <td><?php echo substr($row["created"], 0, 10); ?></td>
                    <td><?php echo substr($row["updated"], 0, 10); ?></td>
                    <td>
                        <a href="#updModal<?php echo $row['username']; ?>" data-toggle="modal" class="btn-sm btn-info mt-1">編修</a>
                        <a onclick="return confirm('確定要作廢嗎?')" href="cat/user_del.php?username=<?php echo $row['username']; ?>" class='btn-sm btn-danger mt-1'>刪除</a>
                    </td>
                </tr>
                <!--Start Update Modal-->
                <div class="modal fade" id="updModal<?php echo $row['username'] ?>">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <!--upd Modal 頭部 -->
                            <div class="modal-header">
                                <h4 class="modal-title"><?php echo $title . '-編修'; ?></h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <form class="form-horizontal" action="#" method="POST" name="edit_Form" id="edit_Form">
                                <div class="modal-body">
                                    <div class="edit-messages"></div>
                                    <input type="hidden" name="usernamex" id="usernamex" value="<?php echo $row['username'] ?>">
                                    <div class="form-group">
                                        <label for="fullnamex" class="col-sm-5 control-label">全名<span class="required">*</span>:</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="fullnamex" name="fullnamex" value="<?php echo $row['fullname'] ?>" required placeholder="全名">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="userpassx" class="col-sm-5 control-label">密碼<span class="required">*</span>:</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="userpassx" name="userpassx" required placeholder="密碼(建議 > 5個字)">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="user_rolex" class="col-sm-5 control-label">身份<span class="required">*</span>:</label>
                                        <div class="col-sm-10">
                                            <select id="user_rolex" name="user_rolex" required>
                                                <?php
                                                require('./include/config.php');
                                                $cql = " SELECT COLUMN_TYPE as AllPossibleEnumValues
                                                     FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'qna_user0'  AND COLUMN_NAME = 'user_role'";
                                                $sthm = $conn->prepare($cql);
                                                $sthm->execute();
                                                while ($rows = $sthm->fetch()) {
                                                    preg_match('/enum\((.*)\)$/', $rows[0], $matches);
                                                    $vals = explode(",", $matches[1]);
                                                    foreach ($vals as $val) {
                                                        $val = substr($val, 1);
                                                        $val = rtrim($val, "'");
                                                        foreach ($vals as $val) {
                                                            $val = substr($val, 1);
                                                            $val = rtrim($val, "'");
                                                            if ($val == $user_role) {
                                                                echo '<option value="' . $val . '" selected="selected">' . $val . '</option>';
                                                            } else {
                                                                echo '<option value="' . $val . '">' . $val . '</option>';
                                                            }
                                                        }
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer editMemberModal">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">返回/關閉</button>
                                        <button type="submit" name="editsubmit" id="editsubmit" class="btn btn-primary">存檔</button>
                                    </div>
                            </form>
                        </div>

                    </div>
                </div>
                <!--End Update Modal--->
            <?php  } ?>
        </tbody>
    </table>
    <?php
    require("./html/endcnd.html");
    require("./html/footer.html");
    ?>
    <!-- Add_Modal 本體 -->
    <div class="modal fade" id="addModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal 頭部 -->
                <div class="modal-header">
                    <h4 class="modal-title"><?php echo $title . '-新增' ?></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal 身部 -->
                <form class="form-horizontal" method="POST" id="insert_form">
                    <div class="modal-body">
                        <div class="add-messages"> </div>
                        <div class="form-group">
                            <label for="username" class="col-sm-5 control-label">帳號<span class="required">*</span>:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="username" name="username" required placeholder="帳號">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fullname" class="col-sm-5 control-label">全名<span class="required">*</span>:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="fullname" name="fullname" required placeholder="全名">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="userpass" class="col-sm-5 control-label">密碼<span class="required">*</span>:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="userpass" name="userpass" required placeholder="密碼(建議 > 5個字)">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="user_rolex" class="col-sm-5 control-label">身份<span class="required">*</span>:</label>
                            <div class="col-sm-10">
                                <select id="user_role" name="user_role" required>
                                    <?php
                                    require('./include/config.php');
                                    $sql = " SELECT COLUMN_TYPE as AllPossibleEnumValues
                                                     FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'qna_user0'  AND COLUMN_NAME = 'user_role'";
                                    $sth = $conn->prepare($sql);
                                    $sth->execute();
                                    while ($row = $sth->fetch()) {
                                        preg_match('/enum\((.*)\)$/', $row[0], $matches);
                                        $vals = explode(",", $matches[1]);
                                        foreach ($vals as $val) {
                                            $val = substr($val, 1);
                                            $val = rtrim($val, "'");
                                            if ($val == $user_role) {
                                                echo '<option value="' . $val . '" selected="selected">' . $val . '</option>';
                                            } else
                                                echo '<option value="' . $val . '">' . $val . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Modal 底部 -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
                        <button type="submit" name="addsubmit" class="btn btn-primary">存檔</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!----end  upd-modal-->
<!-- add save-->

<script type="text/javascript">
    $(function() {
        $("#myDataTalbe").DataTable({
            dom: 'Blfrtip',
            responsive: true,
            buttons: [
                'copy', {
                    extend: 'csv',
                    text: 'CSV',
                    bom: true
                }, 'print'
            ],
            lengthMenu: [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, "All"]
            ],
            language: {
                sProcessing: "處理中...",
                sLengthMenu: "顯示 _MENU_ 項結果",
                sZeroRecords: "沒有匹配結果",
                sInfo: "顯示第 _START_ 至 _END_ 項結果，共 _TOTAL_ 項",
                sInfoEmpty: "顯示第 0 至 0 項結果，共 0 項",
                sInfoFiltered: "(由 _MAX_ 項結果過濾)",
                sInfoPostFix: "",
                sSearch: "搜索:",
                sUrl: "",
                sEmptyTable: "表中數據為空",
                sLoadingRecords: "載入中...",
                sInfoThousands: ",",
                oPaginate: {
                    sFirst: "首頁",
                    sPrevious: "上頁",
                    sNext: "下頁",
                    sLast: "末頁"
                },
                oAria: {
                    sSortAscending: ": 以升序排列此列",
                    sSortDescending: ": 以降序排列此列"
                }
            },

            // searching: false, //關閉filter功能 ,查詢
            columnDefs: [{
                targets: [],
                orderable: false,
                targets: [],
                searchable: false
            }]
        });
        //  columnDefs參數則定義最後的資料行無法排序;
    });
</script>
</body>

</html>