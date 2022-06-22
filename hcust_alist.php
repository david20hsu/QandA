<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}
$title = "嘉南羊乳-儲值單轉PDF";
require_once("html/header.html");
require_once("html/menu.html");
require('./include/config0.php');
//echo __DIR__."<br/>";
?>
<?php
date_default_timezone_set("Asia/Taipei");
//$ym = date('Y-m-01', strtotime('last month')); // 上月一日
$status = ''; // 上月一日
$atm = '';
$sdate = date('Y-m-01');
$edate = date('Y-m-d');
if (isset($_POST['submit']) != "") {
    $sdate = $_POST['sdate'];
    $edate = $_POST['edate'];
    $status = $_POST['status'];
    $atm = $_POST['atm'];
}
?>
<div class="modal fade" id="addModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo $title . '-超商單' ?></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal 身部 -->
            <form class="form-horizontal" method="POST" action="prt/hcust_pre_prt.php" id="insert_form">
                <div class="modal-body">
                    <div class="add-messages"></div>

                    <div class="form-group">
                        <label for="sdate" class="col-sm-5 control-label">期間(起)<span class="required">*</span>:</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" id="sdate" name="sdate" value="<?php echo $sdate; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edate" class="col-sm-5 control-label">期間(迄)<span class="required">*</span>:</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" id="edate" name="edate" value="<?php echo $edate; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="atm_yn" class="col-sm-5 control-label">收費方式<span class="required">*</span>:</label>
                        <div class="col-sm-10">
                            <select name="atm_yn">
                                <option value="">選擇方式</option>
                                <option value="A" <?php if ($atm == 'A') echo ' selected="selected"'; ?>>超商</option>
                                <option value="Y" <?php if ($atm == 'Y') echo ' selected="selected"'; ?>>ATM</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-10">
                            飲用明細:
                            <select name="xdt" id="xdt" required>
                                <option value="N">不印</option>
                                <option value="Y">列印</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="pre_no" class="col-sm-5 control-label">預收單號:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="pre_no" name="pre_no" maxlength="10" placeholder="有單號,類別無效,單一預收單號 (10碼)">
                        </div>
                    </div>

                </div>
                <!-- Modal 底部 -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
                    <button type="submit" name="addsubmit" class="btn btn-primary">預收單</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="updModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo $title . '-收據' ?></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal 身部 -->
            <form class="form-horizontal" method="POST" action="prt/hcust_681_prt.php" id="insert_form">
                <div class="modal-body">
                    <div class="add-messages"></div>

                    <div class="form-group">
                        <label for="sdate" class="col-sm-5 control-label">期間(起)<span class="required">*</span>:</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" id="sdate" name="sdate" value="<?php echo $sdate; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edate" class="col-sm-5 control-label">期間(迄)<span class="required">*</span>:</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" id="edate" name="edate" value="<?php echo $edate; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="atm_yn" class="col-sm-5 control-label">收費方式<span class="required">*</span>:</label>
                        <div class="col-sm-10">
                            <select name="atm_yn">
                                <option value="">選擇方式</option>
                                <option value="A" <?php if ($atm == 'A') echo ' selected="selected"'; ?>>超商</option>
                                <option value="C" <?php if ($atm == 'C') echo ' selected="selected"'; ?>>刷卡</option>
                                <option value="Y" <?php if ($atm == 'Y') echo ' selected="selected"'; ?>>ATM</option>
                                <option value="S" <?php if ($atm == 'N') echo ' selected="selected"'; ?>>業務</option>
                                <option value="N" <?php if ($atm == 'N') echo ' selected="selected"'; ?>>人工</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="pre_no" class="col-sm-5 control-label">儲值單號:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="pre_no" name="pre_no" maxlength="10" placeholder="有單號,類別無效,單一預收單號 (10碼)">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-10">
                            飲用明細:
                            <select name="detail" id="detial" required>
                                <option value="N">不印</option>
                                <option value="Y">列印</option>
                            </select>
                        </div>

                    </div>
                </div>
                <!-- Modal 底部 -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
                    <button type="submit" name="addsubmit" class="btn btn-primary">產生通知單</button>
                </div>
            </form>
        </div>
    </div>
</div>
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

    table.dataTable.nowrap th,
    table.dataTable.nowrap td {
        white-space: normal !important;
    }

    table.dataTable thead th {
        text-align: center;
    }

    table.dataTable tbody td {
        text-align: center;
    }

    #toprow {
        margin-top: -15px;
    }

    .required {
        color: red;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10" style="margin-top:-15px; margin-bottom:5px;">
            <form id="submitForm" action="" method="post">
                <div class="form-group">
                    <div class="row">
                        <nobr>
                            期間:
                            <input type="date" name="sdate" id="sdate" value="<?php echo $sdate; ?>"></input>
                            ~<input type="date" name="edate" id="edate" value="<?php echo $edate; ?>"></input>
                            <select name="status">
                                <option value="">狀態</option>
                                <option value="0" <?php if ($status == '0') echo ' selected="selected"'; ?>>未收</option>
                                <option value="1" <?php if ($status == '1') echo ' selected="selected"'; ?>>已收</option>
                                <option value="X" <?php if ($status == 'X') echo ' selected="selected"'; ?>>作廢</option>
                            </select>
                            <select name="atm">
                                <option value="">類別</option>
                                <option value="A" <?php if ($atm == 'A') echo ' selected="selected"'; ?>>超商</option>
                                <option value="C" <?php if ($atm == 'C') echo ' selected="selected"'; ?>>刷卡</option>
                                <option value="Y" <?php if ($atm == 'Y') echo ' selected="selected"'; ?>>ATM</option>
                                <option value="S" <?php if ($atm == 'N') echo ' selected="selected"'; ?>>業務</option>
                                <option value="N" <?php if ($atm == 'N') echo ' selected="selected"'; ?>>人工</option>
                            </select>
                            &emsp;<input type="submit" name="submit" class="btn-sm btn-info mt-1" value="查詢">
                            &emsp;<a href="#addModal" data-toggle="modal" class="btn-sm btn-success  mt-1">超商PDF</a>
                            &emsp;<a href="#updModal" data-toggle="modal" class="btn-sm btn-success  mt-1">收據PDF</a>
                        </nobr>
                    </div>
                </div>
            </form>

        </div>
        <div class="col-md-2" style="margin-top:-15px; margin-bottom:5px;">
            <h3 class="text-center">宅配儲值款資料</h2>
        </div>
    </div>
</div>
<div>
    <table id="myDataTalbe" class="table table-bordered table-striped table-hove" style="width:100%">
        <thead>
            <tr>
                <th>預收單號</th>
                <th>開單日期</th>
                <th>費區</th>
                <th>類別</th>
                <th>客戶編號</th>
                <th>姓名</th>
                <th>預收金額</th>
                <th>累計金額</th>
                <th>收回日期</th>
                <th>信用卡號</th>
                <th>統編/載具</th>
                <th>狀況</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $tot = 0;
            require('./include/config0.php');
            $sql = "Select a.pre_no,a.hcust_id,a.pre_date,a.pre_amt,a.rcv_date,a.atm_yn,a.narea_id,a.tax_id,a.pre_status,d.hcust_name,d.comp_idno,d.mb_code,d.invoice_tp,e.card_id from hcust_amt_pre a left join  hcust d on a.hcust_id=d.hcust_id left join hcust_amt_rcv e on a.pre_no=e.pre_no where a.pre_amt >0 ";
            if ($status <> '') {
                $sql .= " and a.pre_status='$status' ";
            }
            if ($atm <> '') {
                $sql .= " and a.atm_yn='$atm' ";
            }
            $sql .= " and  a.pre_date between '$sdate' and '$edate'";
            // echo $sql;
            // exit();
            $sth = $conn->query($sql);
            $sth->setFetchMode(PDO::FETCH_ASSOC);
            while ($row = $sth->fetch()) {
                $atm_yn = $xstatus = $comp_idno = '';
                if ($row["rcv_date"] <> "") {
                    $rcv_date = $row["rcv_date"];
                }
                $xstatus = '未收';
                if ($row["pre_status"] == "X") {
                    $xstatus = '作廢';
                }
                if ($row["pre_status"] == "1") {
                    $xstatus = '收回';
                }
                if ($row["comp_idno"]) {
                    $comp_idno = $row["comp_idno"];
                }
                if ($row["mb_code"]) {
                    $comp_idno = $row["mb_code"];
                }
                if ($row["atm_yn"] == 'A') $atm_yn = "超商";
                if ($row["atm_yn"] == 'C') $atm_yn = "刷卡";
                if ($row["atm_yn"] == 'Y') $atm_yn = "ATM";
                if ($row["atm_yn"] == 'N') $atm_yn = "人工";
                if ($row["atm_yn"] == 'S') $atm_yn = "業務";
                $tot = $tot + (int)$row["pre_amt"];
                echo '<tr><td>' . $row["pre_no"] . '</td>';
                echo '<td>' . $row["pre_date"] . '</td>';
                echo '<td>' . $row["narea_id"] . '</td>';
                echo '<td>' . $atm_yn . '</td>';
                echo '<td>' . $row["hcust_id"] . '</td>';
                echo '<td>' . $row["hcust_name"] . '</td>';
                echo '<td>' . number_format($row["pre_amt"], 0) . '</td>';
                echo '<td>' . number_format($tot, 0) . '</td>';
                echo '<td>' . $row["rcv_date"] . '</td>';
                echo '<td>' . $row["card_id"] . '</td>';
                echo '<td>' . $row["comp_idno"] . '</td>';
                if ($row["pre_status"] == "X") {
                    echo '<td class="btn-sm btn-outline-danger">' . $xstatus . '</td>';
                } elseif ($row["pre_status"] == "1") {
                    echo '<td class="btn-sm btn-outline-success">' . $xstatus . '</td>';
                } else {
                    echo '<td class="btn-sm btn-outline-primary">' . $xstatus . '</td>';
                }
                echo '</tr>';
            }
            ?>
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
            //"scrollX": true, // 控制.thead.
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