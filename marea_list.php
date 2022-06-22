<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}
$title = "嘉南羊乳-路條";
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
$marea_id = "PA01";
$sdate = $edate = "";
date_default_timezone_set("Asia/Taipei");
if (isset($_POST['submit']) != "") {
    $sdate = $_POST['sdate'];
    $edate = $_POST['edate'];
    $marea_id = $_POST['marea_id'];;
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
                            奶區:
                            <select name="marea_id" id="marea_id" style="background-color:lightskyblue">
                                <?php
                                require('./include/config0.php');
                                $sql = "select marea_id,marea_id from marea where group_id='1' and type_id in('1','2','3','4','5','6') order by marea_id";
                                $sth = $conn->prepare($sql);
                                $sth->execute();
                                while ($row = $sth->fetch()) {
                                    if ($row['marea_id'] == $marea_id) {
                                        echo '<option   value="' . $row['marea_id'] . '" selected>' . $row['marea_id'] . '</option>';
                                    } else {
                                        echo '<option value="' . $row["marea_id"] . '">' . $row["marea_id"] . '</option>' . "\n";
                                    }
                                }
                                ?>
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
                <th>序號</th>
                <th>口味瓶數</th>
                <th>送 貨 地 址</th>
                <th>訂戶編號</th>

            </tr>
        </thead>
        <tbody>
            <?php
            require('./include/config0.php');
            $hcust_id = $hcust_addr = $dv_ser = $h1 = $h2 = $h3 = $xtr = '';
            $sql = "CALL List_marea_list0('$sdate','$edate','$marea_id')";
            $sth = $conn->query($sql);
            //$sth->execute();
            $sth->setFetchMode(PDO::FETCH_ASSOC);
            while ($row = $sth->fetch()) {
                if ($row['hcust_id'] !== $hcust_id && $hcust_id !== '') {
                    if ($h1 !== '') {
                        $xtr = $h1;
                    }
                    if ($h2 !== '') {
                        $xtr = $xtr . $h2;
                    }
                    if ($h3 !== '') {
                        $xtr = $xtr . '*' . $h3;
                    }
                    echo '<tr><td>' . $dv_ser . '</td>';
                    echo '<td>' . $xtr . '</td>';
                    echo '<td>' . $hcust_addr . '</td>';
                    echo '<td>' . $hcust_id . '</td></tr>';

                    $hcust_id = $hcust_addr = $dv_ser = $h1 = $h2 = $h3 = $xtr = '';
                }
                if ($hcust_id == '' && $dv_ser == '') {
                    $hcust_id = $row['hcust_id'];
                    $dv_ser = $row['dv_ser'];
                    $hcust_addr = $row['xtrd'] . $row['hcust_addr'] . $row['up_floor'] . $row['box_area'] . $row['box_lock'] . " " . $row['xtmp'] . $row['bud_name'] . $row['bud_cont'];
                    if ($row['rt_date'] !== '') {
                        $hcust_addr .= $row['rt_date'];
                    }
                }
                if ($row['h1'] !== '') {
                    $h1 = $h1 . $row['h1'];
                }
                if ($row['h2'] !== '') {
                    $h2 = $h2 . $row['h2'];
                }
                if ($row['h3'] !== '') {
                    $h3 = $h3 . $row['h3'];
                }
            } ?>
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