<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}
$title = "嘉南羊乳-奶區新增續異動";
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

    .required {
        color: red;
    }
</style>
<?php
$sdate = $edate = "";
date_default_timezone_set("Asia/Taipei");
if (isset($_POST['submit']) != "") {
    $sdate = $_POST['sdate'];
    $edate = $_POST['edate'];
} else {
    $sdate = date('Y-m-d', strtotime('1 day'));
    if ($w = date('w') == 6) {
        $sdate = date('Y-m-d', strtotime('2 day'));
        $edate = date('Y-m-d', strtotime('2 day'));
    } else {
        $sdate = date('Y-m-d', strtotime('1 day'));
        $edate = date('Y-m-d', strtotime('1 day'));
    }
    if ($w = date(('w'), strtotime($sdate)) == 5) {
        $edate = date('Y-m-d', strtotime('1 day'));
    }
} ?>
<div class="container">
    <h2 class="text-center">新增續異動</h2>
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4" style="margin-top:-5px; margin-bottom:0px;">
            <form id="submitForm" action="" method="post">
                <div class="form-group">
                    <div class="row">
                        <nobr>
                            期間:<input type="date" name="sdate" id="sdate" value="<?php echo $sdate; ?>" placeholder="檢奶日期"> →
                            <input type="date" name="edate" id="edate" value="<?php echo $edate; ?>" placeholder="檢奶日期"> &emsp;
                            <input type="submit" name="submit" class="btn-sm btn-info mt-1" value="查詢">
                        </nobr>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div>
    <table id="myDataTalbe" class="table table-bordered table-striped table-hove" style="width:100%">
        <thead>
            <tr>
                <th>奶區</th>
                <th>客戶代號</th>
                <th>客戶姓名</th>
                <th>日期</th>
                <th>異動</th>
                <th>品項</th>
                <th>口味瓶</th>
                <th>休式</th>
                <th>送奶地址</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require('./include/config0.php');
            $sql = "CALL htrd_list('$sdate','$edate')";
            $sth = $conn->query($sql);
            //$sth->execute();
            $sth->setFetchMode(PDO::FETCH_ASSOC);
            while ($row = $sth->fetch()) {
            ?>
                <tr>
                    <td><?php echo $row["marea_id"]; ?> </td>
                    <td><?php echo $row["hcust_id"]; ?> </td>
                    <td><?php echo $row["name"]; ?> </td>
                    <td><?php echo $row["xdate"]; ?> </td>
                    <td><?php echo $row["tr_name"]; ?> </td>
                    <td><?php echo $row["prod"]; ?></td>
                    <td><?php echo $row["kv"]; ?></td>
                    <td><?php echo $row["rest_name"]; ?></td>
                    <td><?php echo $row["addr"]; ?></td>
                </tr>
            <?php  } ?>
        </tbody>
    </table>

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
                targets: [6],
                orderable: false,
                targets: [6],
                searchable: false
            }]
        });
        //  columnDefs參數則定義最後的資料行無法排序;
    });
</script>
</body>

</html>