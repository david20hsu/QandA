<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}
ini_set('upload_max_filesize', '20M');
$title = "嘉南羊乳-業務員錄音紀錄資料";
require_once("html/header.html");
require_once("html/menu.html");
//ini_set('display_errors', 'On');
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

    #doc_keyword {
        width: 446px;
    }

    .required {
        color: red;
    }
</style>
<?php
date_default_timezone_set("Asia/Taipei");
if (isset($_POST['submit']) != "") {
    require('./include/config.php');
    $username =  $_SESSION['username'];
    $rec_date = $_POST['rec_date'];
    $sales_id = $_POST['sales_id'];
    $rec_min = $_POST['rec_min'];
    $rec_yes = $_POST['rec_yes'];
    $rec_no = $_POST['rec_no'];
    $rec_desp = $_POST['rec_desp'];
    $data = array();
    $data = [
        "rec_date" => $rec_date,
        "sales_id" => $sales_id,
        "rec_min" => $rec_min,
        "rec_yes" => $rec_yes,
        "rec_no" => $rec_no,
        "rec_desp" => $rec_desp,
        "username" => $username
    ];
    $cql = "INSERT INTO mp3_rec (`rec_date`,`sales_id`,`rec_min`,`rec_yes`,`rec_no`,`rec_desp`,username) ";
    $cql = $cql . " VALUES(:rec_date,:sales_id,:rec_min,:rec_yes,:rec_no,:rec_desp,:username)";
    $stmt = $conn->prepare($cql);
    $stmt->execute($data);
}
$_POST['submit'] = "";
if (isset($_POST["editsubmit"])) {
    require_once "./include/config.php";
    $rec_date = $_POST['rec_datex'];
    $sales_id = $_POST['sales_idx'];
    $rec_desp = $_POST['rec_despx'];
    $rec_min = $_POST['rec_minx'];
    $rec_yes = $_POST['rec_yesx'];
    $rec_no = $_POST['rec_nox'];
    $username = $_SESSION['username'];
    $id = $_POST['id'];
    $data = [
        'rec_date' => $rec_date,
        'sales_id' => $sales_id,
        'rec_desp' => $rec_desp,
        'rec_min' => $rec_min,
        'rec_yes' => $rec_yes,
        'rec_no' => $rec_no,
        'username' => $username,
        'id' => $id
    ];
    $cql = "UPDATE `mp3_rec` SET `rec_date`=:rec_date,`sales_id`=:sales_id,`rec_desp`=:rec_desp,`rec_min`=:rec_min,`rec_yes`=:rec_yes,`rec_no`=:rec_no,`username`=:username where `id`=:id";
    $stmt = $conn->prepare($cql);
    $stmt->execute($data);
    $conn = null;
}
// 編修
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
        padding: -2px -2px -2px -2px;
        /* e.g. change 8x to 4px here */
    }

    table.dataTable.nowrap th,
    table.dataTable.nowrap td {
        white-space: normal !important;
    }
</style>
<div class="container-fluid">
    <!-- Add_Modal 本體 -->
    <div class="modal fade" id="addModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><?php echo $title . '-新增' ?></h4>
                    <!--
                    <button data-dismiss="modal">&times;</button>
                   type="button" class="close" -->
                </div>
                <!-- Modal 身部 -->
                <form enctype="multipart/form-data" class="form-horizontal" action="#" method="POST" name="add_Form" id="add_Form">
                    <div class="modal-body">
                        <div class="edit-messages"></div>
                        <div class="form-group">
                            <label for="file_name" class="col-sm-5 control-label">錄音日期<span class="required">*</span>:</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control" id="rec_date" name="rec_date" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="mp3_idx" class="col-sm-5 control-label">業務員<span class="required">*</span>:</label>
                            <div class="col-sm-10">
                                <select name="sales_id" id="sales_id" classs="form-control" required style="width:250px;">
                                    <?php
                                    require('./include/config.php');
                                    $cql = "select sales_id,sales_name from sys_sales where (lv_date='' or isnull(lv_date)) and tp_id in('0','1') order by sales_name";
                                    $sthm = $conn->prepare($cql);
                                    $sthm->execute();
                                    while ($rows = $sthm->fetch()) { ?>
                                        <option value="<?php echo $rows['sales_id']; ?>"><?php echo $rows['sales_name']; ?></option>
                                    <?php }   ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="rec_desp" class="col-sm-5 control-label">備註說明:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="rec_desp" name="rec_desp" style="width:450px;" placeholder="關鍵字、說明">
                            </div>
                        </div>
                        <div class="row ml-3">
                            <label for="rec_min">時間:<span class="required">*</span></label>
                            <input type="number" id="rec_min" name="rec_min" style="width:70px;" value="30" step="1" required>

                            <label for="rec_yes">成交戶:<span class="required">*</span></label>
                            <input type="number" id="rec_yes" name="rec_yes" style="width:70px;" value="0" step="1" required>

                            <label for="rec_no">未成交戶:<span class="required">*</span></label>
                            <input type="number" id="rec_no" name="rec_no" style="width:70px;" value="0" step="1" required>

                        </div>

                    </div>
                    <!-- Modal 底部 -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
                        <button type="submit" name="submit" class="btn btn-primary">存檔</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row" id="tprow">
        <div class="col-11">
            <h3 align='center'><?php echo $title; ?></h3>
        </div>
        <div class="col-1">
            <a href="#addModal" data-toggle="modal" class="btn btn-info btn-sm mt-1">新增</a>
        </div>
    </div>
    <table id="myDataTalbe" class="table table-bordered table-striped table-hove" style="width:100%">
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="12%">錄製日期</th>
                <th width="10%">業務員</th>
                <th width="10%">成交(戶)</th>
                <th width="10%">不成交(戶)</th>
                <th width="10%">長度(分)</th>

                <th width="25%">備註說明</th>

                <th width="8%">維護人</th>
                <th width="10%">操作</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require('./include/config.php');
            $sql = "select a.id,a.rec_date,a.sales_id,a.rec_min,a.rec_yes,a.rec_no,a.rec_desp,b.sales_name,a.username from mp3_rec a left join sys_sales b using(sales_id) order by created desc";
            $sth = $conn->prepare($sql);
            $sth->execute();
            while ($row = $sth->fetch()) {
                $id = $row['id'];
                $sales_id = $row['sales_id'];
            ?>
                <tr>
                    <td><?php echo $row['id'] ?></td>
                    <td><?php echo $row['rec_date'] ?></td>
                    <td><?php echo $row['sales_name'] ?></td>
                    <td><?php echo $row['rec_min'] ?></td>
                    <td><?php echo $row['rec_yes'] ?></td>
                    <td><?php echo $row['rec_no'] ?></td>
                    <td><?php echo $row['rec_desp'] ?></td>
                    <td><?php echo $row['username'] ?></td>
                    <td>
                        <a href="#updModal<?php echo $row['id']; ?>" data-toggle="modal" class="btn-sm btn-info mt-1">編修</a>&nbsp;
                        <a onclick="return confirm('確定要作廢嗎?')" href="cat/mp3_recdel.php?del=<?php echo $row['id'];   ?> ?>" class='btn-sm btn-danger mt-1'>刪除</a>
                    </td>
                </tr>
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

                                    <div class="edit-messages"></div>
                                    <input type="hidden" name="id" id="id" value="<?php echo $row['id'] ?>">
                                    <div class="form-group">
                                        <label for="categoryx" class="col-sm-5 control-label">錄音日期*:</label>
                                        <div class="col-sm-10">
                                            <input type="date" class="form-control" id="rec_datex" name="rec_datex" value="<?php echo $row['rec_date'] ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="sales_id" class="col-sm-5 control-label">業務員<span class="required">*</span>:</label>
                                        <div class="col-sm-10">
                                            <select name="sales_idx" id="sales_idx" classs="form-control" required style="width:250px;">
                                                <?php
                                                require('./include/config.php');
                                                $cql = "select sales_id,sales_name from sys_sales where (lv_date='' or isnull(lv_date)) and tp_id in('0','1') order by sales_name";
                                                $sthm = $conn->prepare($cql);
                                                $sthm->execute();
                                                while ($rows = $sthm->fetch()) {
                                                    if ($rows['sales_id'] == $sales_id) {
                                                        $selected = 'selected="selected"';
                                                    } else {
                                                        $selected = 'selected=""';
                                                    }
                                                    echo ('<option value="' . $rows['sales_id'] . '" ' . $selected . '">' . $rows['sales_name'] . '</option>');
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="rec_despx" class="col-sm-5 control-label">備註說明:</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="rec_despx" name="rec_despx" style="width:450px;" value="<?php echo $row['rec_desp']; ?>" required placeholder="關鍵字、說明">
                                        </div>
                                    </div>
                                    <div class="row  ml-3">
                                        <label for="rec_minx">時間:</label>
                                        <input type="number" id="rec_minx" name="rec_minx" style="width:70px;" value=" <?php echo $row['rec_min']; ?>" step="1" required>

                                        <label for="rec_yesx">成交戶:</label>
                                        <input type="number" id="rec_yesx" name="rec_yesx" style="width:70px;" value=" <?php echo $row['rec_yes']; ?>" step="1" required>

                                        <label for="rec_nox">未成交戶:</label>
                                        <input type="number" id="rec_nox" name="rec_nox" style="width:70px;" value=" <?php echo $row['rec_no']; ?>" step="1" required>

                                    </div>
                                    <div class="modal-footer editMemberModal">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
                                        <button type="submit" name="editsubmit" id="editsubmit" class="btn btn-primary">存檔</button>
                                    </div>
                            </form>
                        </div>
                    </div>

                </div>
            <?php } ?>
        </tbody>
    </table>
</div>
</div>
</div>
<?php
require("html/footer.html");
require("html/endcnd.html");
?>
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
                targets: [8],
                orderable: false,
                targets: [8],
                searchable: false
            }]
        });
    });
</script>
</body>

</html>