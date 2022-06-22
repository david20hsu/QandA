<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}
$title = "嘉南羊乳-檢奶報表";
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
    <h2 class="text-center">檢奶查詢</h2>
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4" style="margin-top:-20px; margin-bottom:5px;">
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
                <th>爐號</th>
                <th>奶區</th>
                <th>工號</th>
                <th>姓名</th>
                <th>品項</th>
                <th>淡</th>
                <th>甜</th>
                <th>巧</th>
                <th>麥</th>
                <th>莓</th>
                <th>卵</th>
                <th>低</th>
                <th>u原</th>
                <th>u莓</th>
                <th>玻計</th>
                <th>大</th>
                <th>B淡</th>
                <th>B巧</th>
                <th>B麥</th>
                <th>B莓</th>
                <th>B卵</th>
                <th>紙計</th>
                <th>牛</th>
                <th>U小</th>
                <th>萬</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require('./include/config0.php');
            $qty0 = $qty1 = $Qty2 = $qty3 = $qty4 = $qty5 = $qty6 = $qty7 = $qtyt = 0;
            $sql = "CALL pick_qty0('$sdate','$edate')";
            $sth = $conn->query($sql);
            //$sth->execute();
            $sth->setFetchMode(PDO::FETCH_ASSOC);
            while ($row = $sth->fetch()) {
                $marea_id = $row["marea_id"];
                if ($row['dvm_yn'] == 'S') {
                    $marea_id .= ' ' . $row["dvm_yn"];
                }
            ?>
                <tr>
                    <td><?php echo $row["box_id"]; ?> </td>
                    <td><?php echo $marea_id; ?> </td>
                    <td><?php echo $row["hum_id"]; ?> </td>
                    <td><?php echo $row["sales_name"]; ?> </td>
                    <td><?php echo $row["gprod_id"]; ?></td>
                    <td style="text-align: right"><?php echo $row["qty0"]; ?></td>
                    <td style="text-align: right"><?php echo $row["qty1"]; ?></td>
                    <td style="text-align: right"><?php echo $row["qty3"]; ?></td>
                    <td style="text-align: right"><?php echo $row["qty4"]; ?></td>
                    <td style="text-align: right"><?php echo $row["qty5"]; ?></td>
                    <td style="text-align: right"><?php echo $row["qty6"]; ?></td>
                    <td style="text-align: right"><?php echo $row["qty7"]; ?></td>
                    <td style="text-align: right"><?php echo $row["bqty0"]; ?></td>
                    <td style="text-align: right"><?php echo $row["bqty5"]; ?></td>
                    <td style="text-align: right"><?php echo $row["qtyt"]; ?></td>
                    <td style="text-align: right"><?php echo $row["bqty"]; ?></td>
                    <td style="text-align: right"><?php echo $row["cqty0"]; ?></td>
                    <td style="text-align: right"><?php echo $row["cqty3"]; ?></td>
                    <td style="text-align: right"><?php echo $row["cqty4"]; ?></td>
                    <td style="text-align: right"><?php echo $row["cqty5"]; ?></td>
                    <td style="text-align: right"><?php echo $row["cqty6"]; ?></td>
                    <td style="text-align: right"><?php echo $row["qtys"]; ?></td>
                    <td style="text-align: right"><?php echo $row["dqty0"]; ?></td>
                    <td style="text-align: right"><?php echo $row["zqty"]; ?></td>
                    <td style="text-align: right"><?php echo $row["dqty1"]; ?></td>
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