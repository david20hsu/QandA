<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}
$title = "嘉南羊乳-語音類別資料";
require_once("html/header.html");
require_once("html/menu.html");
?>
<?php
if (isset($_POST["editsubmit"])) {
    require_once "./include/config.php";
    $mp3_id = $_POST['mp3_id'];
    $tp_name = $_POST['tp_namex'];
    $username = $_SESSION['username'];
    $data = [
        'tp_name' => $tp_name,
        "username" => $username,
        'mp3_id' => $mp3_id
    ];
    $cql = "UPDATE `mp3_tp` SET `tp_name`=:tp_name,`username`=:username where `mp3_id`=:mp3_id";
    $stmt = $conn->prepare($cql);
    $stmt->execute($data);
    $conn = null;
}
if (isset($_POST["addsubmit"])) {
    require_once "./include/config.php";
    $tp_name = $_POST['tp_name'];
    $username = $_SESSION['username'];
    $data = [
        'tp_name' => $tp_name,
        "username" => $username
    ];
    $cql = "INSERT INTO `mp3_tp` (`tp_name`,`username`) Value(:tp_name,:username)";
    $stmt = $conn->prepare($cql);
    $stmt->execute($data);
    $conn = null;
}
?>
<style>
    table {
        margin: auto;
    }

    .required {
        color: red;
    }

    table.dataTable thead th,
    tbody th,
    table.dataTable tbody td {
        padding: 2px 2px;
        /* e.g. change 8x to 4px here */

    }

    table.dataTable.nowrap th,
    table.dataTable.nowrap td {
        white-space: normal !important;
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
                <th>語音類別</th>
                <th>類別名稱</th>
                <th>維護人</th>
                <th>編修日期</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require('include/config.php');
            $sql = "select *  from mp3_tp order by tp_name desc";
            $sth = $conn->prepare($sql);
            $sth->execute();
            while ($row = $sth->fetch()) {
            ?>
                <tr>
                    <td><?php echo $row["mp3_id"]; ?> </td>
                    <td><?php echo $row["tp_name"]; ?> </td>
                    <td><?php echo $row["username"]; ?></td>
                    <td><?php echo substr($row["updated"], 0, 10); ?></td>
                    <td>
                        <a href="#updModal<?php echo $row['mp3_id']; ?>" data-toggle="modal" class="btn-sm btn-info mt-1">編修</a>
                        <a onclick="return confirm('確定要刪除嗎 ?')" href="cat/mp3_tpdel.php?del=<?php echo $row['mp3_id']; ?>" class='btn-sm btn-danger mt-1'>刪除</a>
                    </td>
                </tr>
                <div class="modal fade" id="updModal<?php echo $row['mp3_id'] ?>">
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
                                    <input type="hidden" name="mp3_id" id="mp3_id" value="<?php echo $row['mp3_id'] ?>">
                                    <div class="form-group">
                                        <label for="categoryx" class="col-sm-5 control-label">類別名稱*:</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="tp_namex" name="tp_namex" value="<?php echo $row['tp_name'] ?>" required placeholder="類別名稱">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer editMemberModal">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
                                    <button type="submit" name="editsubmit" id="editsubmit" class="btn btn-primary">存檔</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
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
                        <div class="add-messages"></div>
                        <div class="form-group">
                            <label for="tp_name" class="col-sm-5 control-label">類別名稱<span class="required">*</span>:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="tp_name" name="tp_name" required placeholder="類別名稱">
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
                targets: [3],
                orderable: false,
                targets: [3],
                searchable: false
            }]
        });
        //  columnDefs參數則定義最後的資料行無法排序;
    });
</script>
<script>
    $(document).ready(function() {
        $("#editsubmit").click(function() {
            var mp3_id = $('#mp3_id').val();
            var tp_name = $('#tp_namex').val();
            // alert(tp_name);
            var username = 'david';
            $.ajax({

                url: "mp3_tpedit.php",
                data: {
                    "tp_name": tp_name,
                    "username": username,
                    "mp3_id": mp3_id
                },
                method: "POST",

                error: function() {

                    alert("失敗");

                },
                success: function() {

                    alert("成功");

                }
            });
        });
    });
</script>
</body>

</html>