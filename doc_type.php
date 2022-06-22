<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}
$title = "嘉南羊乳-文件類別";
require_once("html/header.html");
require_once("html/menu.html");
$ym = date('Y-m-d');
?>
<?php
if (isset($_POST["editsubmit"])) {
    require_once "./include/config.php";
    $doc_id = $_POST['doc_id'];
    $doc_name = $_POST['doc_namex'];
    $username = $_SESSION['username'];
    $data = [
        'doc_name' => $doc_name,
        "username" => $username,
        'doc_id' => $doc_id
    ];
    $cql = "UPDATE `doc_type` SET `doc_name`=:doc_name,`username`=:username where `doc_id`=:doc_id";
    $stmt = $conn->prepare($cql);
    $stmt->execute($data);
    $conn = null;
}
function chk_key($sql)
{
    require('./include/config.php');
    $sth = $conn->prepare($sql);
    $sth->execute();
    $count = $sth->rowCount();
    $conn = null;
    //  return $count;
}
if (isset($_POST["addsubmit"])) {
    require_once "./include/config.php";
    $doc_id = $_POST['doc_id'];
    $sql = "select `doc_name` from `doc_type` where doc_id='$doc_id'";
    $rowcount = chk_key($sql);
    if ($rowcount == 0) {
        $doc_id = $doc_id;
        $doc_name = $_POST['doc_name'];
        $username = $_SESSION['username'];
        $data = [
            'doc_id' => $doc_id,
            'doc_name' => $doc_name,
            "username" => $username
        ];
        //  $cql = "INSERT INTO `doc_type` ('doc_id' ,`doc_name`,`username`) Values(:doc_id,:doc_name,:username)";
        $cql = "INSERT INTO `doc_type` (`doc_id`,`doc_name`,`username`) Values(:doc_id,:doc_name,:username)";
        $stmt = $conn->prepare($cql);
        $stmt->execute($data);
        //     $conn = null;
    } else {
        echo  '<script>alert("類別代號:' . $doc_id . ' 已建檔..不能重覆!!")</script>';
    }
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

    </div>
    <table id="myDataTalbe" class="table table-bordered table-striped table-hove" style="width:90%">
        <thead>
            <tr>
                <th>類別編號</th>
                <th>類別名稱</th>
                <th>維護人</th>
                <th>建檔日期</th>
                <th>編修日期</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require('include/config.php');
            $sql = "select *  from doc_type order by doc_id";
            $sth = $conn->prepare($sql);
            $sth->execute();
            while ($row = $sth->fetch()) {
            ?>
                <tr>
                    <td><?php echo $row["doc_id"]; ?> </td>
                    <td><?php echo $row["doc_name"]; ?> </td>
                    <td><?php echo $row["username"]; ?></td>
                    <td><?php echo substr($row["created"], 0, 10); ?></td>
                    <td><?php echo substr($row["updated"], 0, 10); ?></td>
                    <td>
                        <a href="#updModal<?php echo $row['doc_id']; ?>" data-toggle="modal" class="btn-sm btn-info mt-1">編修</a>
                        <a onclick="return confirm('確定要刪除嗎 ?')" href="cat/doc_typedel.php?del=<?php echo $row['doc_id']; ?>" class='btn-sm btn-danger mt-1'>刪除</a>
                    </td>
                </tr>
                <div class="modal fade" id="updModal<?php echo $row['doc_id'] ?>">
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
                                    <input type="hidden" name="doc_id" id="doc_id" value="<?php echo $row['doc_id'] ?>">
                                    <div class="form-group">
                                        <label for="doc_namex" class="col-sm-5 control-label">類別名稱<span class="required">*</span>:</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="doc_namex" name="doc_namex" value="<?php echo $row['doc_name'] ?>" required placeholder="類別名稱">
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
                <div class="modal-header">
                    <h4 class="modal-title"><?php echo $title . '-新增' ?></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal 身部 -->
                <form class="form-horizontal" method="POST" id="insert_form">
                    <div class="modal-body">
                        <div class="add-messages"></div>

                        <div class="form-group">
                            <label for="doc_id" class="col-sm-5 control-label">類別代號<span class="required">*</span>:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="doc_id" name="doc_id" maxlength="3" required placeholder="類別代號">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="doc_name" class="col-sm-5 control-label">類別名稱<span class="required">*</span>:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="doc_name" name="doc_name" required placeholder="類別名稱">
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
                targets: [5],
                orderable: false,
                targets: [5],
                searchable: false
            }]
        });
        //  columnDefs參數則定義最後的資料行無法排序;
    });
</script>
</body>

</html>