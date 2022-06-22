<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}
$title = "嘉南羊乳-月報";
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
$last = strtotime("-1 month", time());
$yy = date('Y', $last); //上个月第一天
date_default_timezone_set("Asia/Taipei");
if (isset($_POST['submit']) != "") {
    $yy = $_POST['yy'];
    $mm = $_POST['mm'];
} else {
    $yy = date('Y', $last); //上个月第一天
    $mm = date('m', $last); //上个月第一天
}
?>
<div class="container">
    <h2 class="text-center">月報查詢</h2>
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4" style="margin-top:-10px; margin-bottom:5px;">
            <form id="submitForm" action="" method="post">
                <div class="form-group">
                    <div class="row">
                        <nobr>
                            <input type="text" name="yy" id="yy" maxlength="4" size="4" value="<?php echo date('Y'); ?>" />
                            <select name="mm" id="mm" autocomplete="off" required>
                                <option value="01" <?php if ($mm == '01') {
                                                        echo 'selected="selected"';
                                                    } ?>>01月</option>
                                <option value="02">02月</option>
                                <option value="03">03月</option>
                                <option value="04">04月</option>
                                <option value="05">05月</option>
                                <option value="06" <?php if ($mm == '06') {
                                                        echo 'selected="selected"';
                                                    } ?>>06月</option>
                                <option value="07">07月</option>
                                <option value="08">08月</option>
                                <option value="09">09月</option>
                                <option value="10">10月</option>
                                <option value="11">11月</option>
                                <option value="12">12月</option>
                            </select>
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
                <th>區號</th>
                <th>組</th>
                <th>姓名</th>
                <th>回</th>
                <th>實送數</th>
                <th>期望數</th>
                <th>團瓶</th>
                <th>到達</th>
                <th>工時</th>
                <th>里程</th>
                <th>社區</th>
                <th>社戶</th>
                <th>樓戶</th>
                <th>樓瓶</th>
                <th>級</th>
                <th>結帳</th>
                <th>實送%</th>
                <th>送達%</th>
                <th>退減%</th>
                <th>客怨%</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $dvm_qty = $help_qty = $gd_qty = $bud_qty = $bud_no = $up_floor = $up_qty = 0;
            require('./include/config0.php');
            $mon = $yy . '-' . $mm . '-01';
            $sql = "select c.type_id,a.marea_id,b.sales_name,a.rtn_yn,a.end_date,a.dvm_qty,a.rt_rate,a.rcv_rate,a.cmp_rate,a.get_dg,a.dv_rate,
               a.help_qty , a.arr_time, a.work_time, a.work_km, a.gd_qty, a.bud_qty, a.bud_no, a.up_floor, a.up_qty
                 from hcust_marea_per a left join sys_sales b on a.hum_id=b.sales_id left  join marea c on (a.marea_id=c.marea_id) where a.dvm_qty > 0 and c.group_id='1' and a.dvm_date='$mon' order by c.type_id,a.marea_id";
            $sth = $conn->query($sql);
            //$sth->execute();
            $sth->setFetchMode(PDO::FETCH_ASSOC);
            while ($row = $sth->fetch()) {
                $gd = 'D';
                if ($row["get_dg"] >= 90) {
                    $gd = "A";
                } else if ($row["get_dg"] >= 80) {
                    $gd = "B";
                } else if ($row["get_dg"] >= 70) {
                    $gd = "C";
                }
                $dvm_qty = $dvm_qty + $row['dvm_qty'];
                $help_qty = $help_qty + $row['help_qty'];
                $gd_qty = $gd_qty + $row['gd_qty'];
                $bud_qty = $bud_qty + $row['bud_qty'];
                $bud_no = $bud_no + $row['bud_no'];
                $up_floor = $up_floor + $row['up_floor'];
                $up_qty = $up_qty + $row['up_qty'];
            ?>
                <tr>
                    <td><?php echo $row["marea_id"]; ?> </td>
                    <td><?php echo $row['type_id']; ?> </td>
                    <td><?php echo $row["sales_name"]; ?> </td>
                    <td><?php echo $row["rtn_yn"]; ?> </td>
                    <td style="text-align: right"><?php echo number_format($row["dvm_qty"], 0); ?></td>
                    <td style="text-align: right"><?php echo number_format($row["help_qty"], 0); ?></td>
                    <td style="text-align: right"><?php echo number_format($row["gd_qty"], 0); ?></td>
                    <td><?php echo $row["arr_time"]; ?></td>
                    <td style="text-align: right"><?php echo number_format(round($row["work_time"] / 60, 1), 1); ?></td>
                    <td style="text-align: right"><?php echo $row["work_km"]; ?></td>
                    <td style="text-align: right"><?php echo $row["bud_qty"]; ?></td>
                    <td style="text-align: right"><?php echo $row["bud_no"]; ?></td>
                    <td style="text-align: right"><?php echo $row["up_floor"]; ?></td>
                    <td style="text-align: right"><?php echo $row["up_qty"]; ?></td>
                    <td><?php echo $gd; ?></td>
                    <td><?php echo substr($row["end_date"], 5, 5); ?></td>
                    <td style="text-align: right"><?php echo number_format($row["dv_rate"], 2); ?></td>
                    <td style="text-align: right"><?php echo number_format($row["rcv_rate"], 2); ?></td>
                    <td style="text-align: right"><?php echo number_format($row["rt_rate"], 2); ?></td>
                    <td style="text-align: right"><?php echo number_format($row["cmp_rate"], 2); ?></td>
                </tr>
            <?php } ?>
            <tr>
                <td>ZZZZ</td>
                <td> </td>
                <td><?php echo '合計'; ?> </td>
                <td></td>
                <td style="text-align: right"><?php echo number_format($dvm_qty, 0); ?></td>
                <td style="text-align: right"><?php echo number_format($help_qty, 0); ?></td>
                <td style="text-align: right"><?php echo number_format($gd_qty, 0); ?></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="text-align: right"><?php echo number_format($bud_qty, 0); ?></td>
                <td style="text-align: right"><?php echo number_format($bud_no, 0); ?></td>
                <td style="text-align: right"><?php echo number_format($up_floor, 0); ?></td>
                <td style="text-align: right"><?php echo  number_format($up_qty, 0); ?></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
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