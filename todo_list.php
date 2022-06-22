<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}
$title = "和民宅實-工作項目";
require_once("html/header.html");
require_once("html/menu.html");
//echo __DIR__."<br/>";
?>
<style>
    table {
        margin-top: auto;
        margin-left: auto;
        margin-right: auto;
    }

    table.dataTable thead th,
    tbody th,
    table.dataTable tbody td {
        padding: 2px 2px;
        /* e.g. change 8x to 4px here */
    }

    #toprow {
        margin-top: -15px;
    }

    textarea:invalid {
        border: 1px dashed red;
    }

    textarea:valid {
        border: 2px solid lime;
    }
</style>
<?php
$hum_id = $_SESSION['username'];
$hum_id0 = $hum_id;
$sdate0 = $edate = "";
$tdate0 = date('Y-m-d');

$tdate = date('Y-m-d');
date_default_timezone_set("Asia/Taipei");
if (isset($_POST['submit']) != "") {
    $sdate0 = $_POST['sdate0'];
    $edate0 = $_POST['edate0'];
    $hum_id0 = $_POST['hum_id0'];;
} else {
    $sdate0 = date('Y-m-d', strtotime('-7 day'));
    $edate0 = date('Y-m-d', strtotime('7 day'));
}
if (isset($_POST["addsubmit"])) {
    require_once "./include/config.php";
    $hum_id = $_POST['hum_id'];
    $item = $_POST['item'];
    $tgid = $_POST['tgid'];
    $tg_qty = (float)$_POST['tg_qty'];
    $sdate = $_POST['sdate'];
    $edate = $_POST['edate'];
    $todo_desp = $_POST['todo_desp'];
    $status = $_POST['status'];
    $sql = "insert into todo(hum_id,item,tgid,tg_qty,sdate,edate,todo_desp,status) values('$hum_id','$item','$tgid','$tg_qty','$sdate','$edate','$todo_desp','$status')";
    $statement = $conn->prepare($sql);
    $statement->execute();
    $conn = null;
}
if (isset($_POST["editsubmit"])) {
    require_once "./include/config.php";
    $id = $_POST['idx'];
    $item = $_POST['itemx'];
    $tgid = $_POST['tgidx'];
    $tg_qty = (float)$_POST['tg_qtyx'];
    $sdate = $_POST['sdatex'];
    $edate = $_POST['edatex'];
    $todo_desp = $_POST['todo_despx'];
    $status = $_POST['statusx'];
    $sql = "UPDATE `todo`  SET item='$item',tgid='$tgid',tg_qty='$tg_qty',sdate='$sdate',edate='$edate',todo_desp='$todo_desp',`status`='$status' where `id`='$id'";
    $statement = $conn->prepare($sql);
    $statement->execute();
    $conn = null;
}
?>
<div class="container">
    <h2 class="text-center" style="margin-top:-15px;">工作紀錄</h2>
    <div class="row">
        <div class="col-3"></div>
        <div class="col-7" style="margin-top:-10px; margin-bottom:5px;">
            <form id="submitForm" action="" method="post">
                <div class="form-group">
                    <div class="row">
                        <nobr>
                            擔當人:
                            <select name="hum_id0" id="hum_id0" style="background-color:lightskyblue">
                                <?php
                                require('./include/config.php');
                                $sql = "select username ,fullname from qna_user0 where username not in('guest','master','tester','writer') order by fullname";
                                $sth = $conn->prepare($sql);
                                $sth->execute();
                                echo "<option value=''></option>\n";
                                while ($row = $sth->fetch()) {
                                    if ($row['username'] == $hum_id0) {
                                        echo '<option   value="' . $row['username'] . '" selected>' . $row['fullname'] . '</option>';
                                    } else {
                                        echo '<option value="' . $row["username"] . '">' . $row["fullname"] . '</option>' . "\n";
                                    }
                                }
                                ?>
                            </select>
                            &emsp;期間:<input type="date" name="sdate0" id="sdate0" value="<?php echo $sdate0; ?>" placeholder="起始日期" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}">
                            →
                            <input type="date" name="edate0" id="edate0" value="<?php echo $edate0; ?>" placeholder="終止日期" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}">
                            &emsp; &emsp;
                            <input type="submit" name="submit" class="btn-sm btn-info mt-1" value="查詢">
                        </nobr>
                    </div>
                </div>
            </form>

        </div>
        <div class="col-2" style="margin-top:-10px; margin-bottom:5px;">
            <?php if ($hum_id <> '') { ?>
                <a href="#addModal" data-toggle="modal" class="btn btn-success btn-sm mt-1">新增</a>
            <?php } ?>
        </div>
    </div>
</div>
<div>
    <table id="myDataTalbe" class="table table-bordered table-striped table-hove" style="width:100%">
        <thead>
            <tr>
                <th>序號</th>
                <th>擔當人</th>
                <th>工作項目</th>
                <th>目標</th>
                <th>目標值</th>
                <th>起始日期</th>
                <th>完成日期</th>
                <th>進度</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require('./include/config.php');
            if ($hum_id <> '') {
                $sql = "select a.*,b.tg_name,c.fullname from todo a left join todo_tg b using(tgid) left join qna_user0 c on a.hum_id=c.username where sdate between '$sdate0' and '$edate0' and hum_id='$hum_id0' order by sdate";
            } else {
                $sql = "select a.*,b.tg_name,c.fullname from todo a left join todo_tg b using(tgid) left join qna_user0 c on a.hum_id=c.username where sdate between '$sdate0' and '$edate0'  order by hum_id,sdate";
            }
            $sth = $conn->query($sql);
            //$sth->execute();
            $sth->setFetchMode(PDO::FETCH_ASSOC);
            while ($row = $sth->fetch()) {
                $tg_qty = "";
                if ($row['tg_qty'] <> 0) {
                    $tg_qty = $row['tg_qty'];
                }
                $status = $row['status'];
                $str = '';
                switch ($status) {
                    case '0':
                        $str = '-計畫';
                        break;
                    case '1':
                        $str = '-暫緩';
                        break;
                    case '2':
                        $str = '-進行';
                        break;
                    case '3':
                        $str = '-停滯';
                    case '4':
                        $str = '-取消';
                        break;
                    case '9':
                        $str = '-完工';
                        break;
                }
            ?>
                <tr>
                    <td><?php echo $row["id"]; ?> </td>
                    <td><?php echo $row["fullname"]; ?> </td>
                    <td><?php echo $row["item"]; ?> </td>
                    <td><?php echo $row["tg_name"]; ?> </td>
                    <td><?php echo $tg_qty; ?> </td>
                    <td><?php echo substr($row["sdate"], -5); ?></td>
                    <td><?php echo substr($row["edate"], -5); ?></td>
                    <td><?php echo $row['status'] . $str; ?></td>
                    <td>
                        <?php if ($row['hum_id'] == $_SESSION['username']) { ?>
                            <a href="#updModal<?php echo $row['id']; ?>" data-toggle="modal" class="btn-sm btn-info mt-1">編修</a>
                            <?php if ($row['status'] == '2') { ?>
                                <a onclick="return confirm('確定要結案嗎?')" href="todo/todo_end.php?id=<?php echo $row['id']; ?>" class='btn-sm btn-success mt-1'>結案</a>
                            <?php } ?>
                            <?php if ($row['status'] <> '9') { ?>
                                <a onclick="return confirm('確定要作廢嗎?')" href="todo/todo_del.php?id=<?php echo $row['id']; ?>" class='btn-sm btn-danger mt-1'>刪除</a>
                            <?php } ?>
                        <?php } else { ?>
                            <a href="#updModal<?php echo $row['id']; ?>" data-toggle="modal" class="btn-sm btn-info mt-1">檢視</a>
                        <?php } ?>
                    </td>
                    <div class="modal fade" id="updModal<?php echo $row['id'] ?>">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <!--upd Modal 頭部 -->
                                <div class="modal-header">
                                    <h4 class="modal-title"><?php echo $title . '-編修'; ?></h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <form class="form-horizontal" action="#" method="POST" name="edit_Form" id="edit_Form">
                                    <div class="modal-body">
                                        <input type="hidden" name="hum_idx" id="hum_idx" value="<?php echo $row['hum_id'] ?>">
                                        <input type="hidden" name="idx" id="idx" value="<?php echo $row['id'] ?>">
                                        <div class="form-group">
                                            <label for="itemx" class="col-sm-5 control-label">工作項目<span class="required">*</span>:</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="itemx" name="itemx" value="<?php echo $row['item'] ?>" required placeholder="類別名稱">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-10">
                                                <label for="sdatex">期間<span class="required">*</span>:</label>
                                                <input type="date" id="sdatex" name="sdatex" value="<?php echo $row['sdate'] ?>" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}">&nbsp;~&nbsp;
                                                <input type="date" id="edatex" name="edatex" value="<?php echo $row['edate'] ?>" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-10">
                                                <label for="tgid">目標<span class="required">*</span>:</label>
                                                <select name="tgidx" id="tgidx" required style="background-color:lightskyblue">
                                                    <?php
                                                    require('./include/config.php');
                                                    $sql = "select tgid ,tg_name from todo_tg order by tgid";
                                                    $sthm = $conn->prepare($sql);
                                                    $sthm->execute();
                                                    while ($rows = $sthm->fetch()) {
                                                        if ($rows['tgid'] == $row['tgid']) {
                                                            echo '<option   value="' . $row['tgid'] . '" selected>' . $row['tg_name'] . '</option>';
                                                        } else {
                                                            echo '<option value="' . $rows["tgid"] . '">' . $rows["tg_name"] . '</option>' . "\n";
                                                        }
                                                    }
                                                    ?>
                                                </select>&emsp;
                                                目標值<span class="required"></span>:
                                                <input type="number" id="tg_qtyx" name="tg_qtyx" value="<?php echo $row['tg_qty']; ?>" style="width:100px;"></input>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="todo_despx" class="col-sm-5 control-label">內容說明:</label>
                                            <div class="col-sm-10">
                                                <textarea name="todo_despx" id="todo_despx" COLS="50" ROWS="3"><?php echo str_replace(" ", "", htmlspecialchars($row['todo_desp'])); ?> </textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-10">
                                                <label for="statusx">進度狀況<span class="required">*</span>:</label>
                                                <select name="statusx" id="statusx" required style="background-color:lightskyblue">
                                                    <option value="0" <?php if ($status == '0') {
                                                                            echo 'selected="selected"';
                                                                        } ?>>計劃</option>
                                                    <option value="1" <?php if ($status == '1') {
                                                                            echo 'selected="selected"';
                                                                        } ?>>暫緩</option>
                                                    <option value="2" <?php if ($status == '2') {
                                                                            echo 'selected="selected"';
                                                                        } ?>>進行</option>
                                                    <option value="3" <?php if ($status == '3') {
                                                                            echo 'selected="selected"';
                                                                        } ?>>停滯</option>
                                                    <option value="4" <?php if ($status == '4') {
                                                                            echo 'selected="selected"';
                                                                        } ?>>取消</option>
                                                    <option value="9" <?php if ($status == '9') {
                                                                            echo 'selected="selected"';
                                                                        } ?>>結案</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer editMemberModal">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
                                        <?php if ($row['hum_id'] == $_SESSION['username']) { ?>
                                            <button type="submit" name="editsubmit" id="editsubmit" class="btn btn-primary">存檔</button>
                                        <?php } ?>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php
require("html/footer.html");
require("html/endcnd.html");
?>
<!-- Add_Modal 本體 -->
<div class="modal fade" id="addModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo $title . '-新增' ?></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal 身部 -->
            <form class="form-horizontal" method="POST" id="insert_form">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" id="hum_id" name="hum_id" value="<?php echo $hum_id; ?>">
                        <label for="item" class="col-sm-5 control-label">工作項目<span class="required">*</span>:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="item" name="item" maxlength="30" style="width:450px;" required placeholder="工作項目">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-10">
                            <label for="sdate">期間<span class="required">*</span>:</label>
                            <input type="date" id="sdate" name="sdate" value="<?php echo $tdate; ?>" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}">&nbsp;~&nbsp;
                            <input type="date" id="edate" name="edate" value="<?php echo $tdate; ?>" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-10">
                            <label for="tgid">目標<span class="required">*</span>:</label>
                            <select name="tgid" id="tgid" required style="background-color:lightskyblue">
                                <?php
                                require('./include/config.php');
                                $sql = "select tgid ,tg_name from todo_tg order by tgid";
                                $sth = $conn->prepare($sql);
                                $sth->execute();
                                while ($row = $sth->fetch()) {
                                    echo '<option value="' . $row["tgid"] . '">' . $row["tg_name"] . '</option>' . "\n";
                                }
                                ?>
                            </select>&emsp;
                            目標值<span class="required"></span>:
                            <input type="number" id="tg_qty" name="tg_qty" style="width:100px;"></input>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="item" class="col-sm-5 control-label">內容說明:</label>
                        <div class="col-sm-10">
                            <textarea name="todo_desp" id="todo_desp" rows="3" cols="50"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-10">
                            <label for="status">進度狀況<span class="required">*</span>:</label>
                            <select name="status" id="status" required style="background-color:lightskyblue">
                                <option value="0">計劃</option>
                                <option value="1">暫緩</option>
                                <option value="2">進行</option>
                                <option value="3">停滯</option>
                                <option value="4">取消</option>
                                <option value="9">結案</option>
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
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            "scrollX": true,
            //設置固定高度為200px 數據量溢出時出現滾動條
            //"scrollY": "500px",
            //"scrollCollapse": "true",
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