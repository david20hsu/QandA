<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}
$title = "嘉南羊乳-帳單轉PDF";
require_once("html/header.html");
require_once("html/menu.html");
require('./include/config0.php');
//echo __DIR__."<br/>";
?>
<?php
date_default_timezone_set("Asia/Taipei");
$xdesp = $xdesp = $hcust_idx = "";
$ym = date('Y-m-01', strtotime('last month')); // 上月一日
if (isset($_POST['submit']) != "") {
    $ym = $_POST['xym'];
}
$xql = "select * from hcust_close where close_ym>='" . $ym . "' order by close_ym desc";
$sth = $conn->prepare($xql);
$sth->execute();
while ($row = $sth->fetch()) {
    $xdesp = $row['close_desp'];
    $xdesp0 = $row['close_desp0'];
}
?>
<div class="modal fade" id="addModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo $title . '-儲值單' ?></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal 身部 -->
            <form class="form-horizontal" method="POST" action="prt/hcust_plist_prt.php" id="insert_form">
                <div class="modal-body">
                    <div class="add-messages"></div>
                    <div class="form-group">
                        <div class="form-group">
                            <label for="ym" class="col-sm-5 control-label">期間<span class="required">*</span>:</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control" id="ym" name="ym" value="<?php echo $ym; ?>">
                            </div>
                        </div>
                        <input type="text" class="form-control" id="xdesp" name="xdesp" value="<?php echo $xdesp; ?>">
                        <input type="text" class="form-control" id="xdesp0" name="xdesp0" value="<?php echo $xdesp0; ?>">
                        <div class="form-group">
                            <label for="narea_ids" class="col-sm-5 control-label">區號<span class="required">*</span>:</label>
                            <div class="col-sm-10">
                                <select name="narea_ids" id="narea_ids" style="background-color:lightskyblue" required>
                                    <?php
                                    // require('./include/config0.php');
                                    $sql = "select marea_id from marea where group_id='1' and type_id in('1','2','3','4','5','6','9') order by marea_id";
                                    $sth = $conn->prepare($sql);
                                    $sth->execute();
                                    while ($row = $sth->fetch()) {
                                        echo '<option value="' . $row["marea_id"] . '">' . $row["marea_id"] . '</option>' . "\n";
                                    }
                                    ?>
                                </select> ->
                                <select name="narea_ide" id="narea_ide" style="background-color:lightskyblue" required>
                                    <?php
                                    // require('./include/config0.php');
                                    $sql = "select marea_id from marea where group_id='1' and type_id in('1','2','3','4','5','6','9') order by marea_id";
                                    $sth = $conn->prepare($sql);
                                    $sth->execute();
                                    while ($row = $sth->fetch()) {
                                        echo '<option value="' . $row["marea_id"] . '">' . $row["marea_id"] . '</option>' . "\n";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="hcust_idx" class="col-sm-5 control-label">客戶編號:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="hcust_idx" name="hcust_idx" maxlength="8" value="<?php echo $hcust_idx; ?>" placeholder="有客編,區號無效,單一客戶編號 (8碼)">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal 底部 -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
                    <button type="submit" name="addsubmit" class="btn btn-primary">儲值單</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="bddModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo $title . '-超商單' ?></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal 身部 -->
            <form class="form-horizontal" method="POST" action="prt/hcust_alist_prt.php" id="insert_form">
                <div class="modal-body">
                    <div class="add-messages"></div>
                    <div class="form-group">
                        <label for="ym" class="col-sm-5 control-label">期間<span class="required">*</span>:</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" id="ym" name="ym" value="<?php echo $ym; ?>">
                        </div>
                    </div>
                    <input type="text" class="form-control" id="xdesp" name="xdesp" value="<?php echo $xdesp; ?>">
                    <input type="text" class="form-control" id="xdesp0" name="xdesp0" value="<?php echo $xdesp0; ?>">
                    <div class="form-group">
                        <label for="narea_ids" class="col-sm-5 control-label">區號<span class="required">*</span>:</label>
                        <div class="col-sm-10">
                            <select name="narea_ids" id="narea_ids" style="background-color:lightskyblue" required>
                                <?php
                                // require('./include/config0.php');
                                $sql = "select marea_id from marea where group_id='1' and marea_id not in('6801','6901') and type_id in('1','2','3','4','5','6','9') order by marea_id";
                                $sth = $conn->prepare($sql);
                                $sth->execute();
                                while ($row = $sth->fetch()) {
                                    echo '<option value="' . $row["marea_id"] . '">' . $row["marea_id"] . '</option>' . "\n";
                                }
                                ?>
                            </select> ->
                            <select name="narea_ide" id="narea_ide" style="background-color:lightskyblue" required>
                                <?php
                                // require('./include/config0.php');
                                $sql = "select marea_id from marea where group_id='1' and marea_id not in('6801','6901')  and type_id in('1','2','3','4','5','6','9') order by marea_id";
                                $sth = $conn->prepare($sql);
                                $sth->execute();
                                while ($row = $sth->fetch()) {
                                    echo '<option value="' . $row["marea_id"] . '">' . $row["marea_id"] . '</option>' . "\n";
                                }
                                ?>
                            </select>
                            代送區:
                            <select name="xdt" id="xdt" required>
                                <option value="A">全部</option>
                                <option value="Y">代送</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="hcust_idx" class="col-sm-5 control-label">客戶編號:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="hcust_idx" name="hcust_idx" maxlength="8" value="<?php echo $hcust_idx; ?>" placeholder="有客編,區號無效,單一客戶編號 (8碼)">
                        </div>
                    </div>
                </div>
                <!-- Modal 底部 -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
                    <button type="submit" name="addsubmit" class="btn btn-primary">超商單</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="cddModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo $title . '-扣款單' ?></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal 身部 -->
            <form class="form-horizontal" method="POST" action="prt/hcust_6801_prt.php" id="insert_form">
                <div class="modal-body">
                    <div class="add-messages"></div>
                    <div class="form-group">
                        <label for="ym" class="col-sm-5 control-label">期間:<span class="required">*</span>:</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" id="ym" name="ym" value="<?php echo $ym; ?>">
                        </div>
                    </div>

                    <input type="text" class="form-control" id="xdesp" name="xdesp" value="<?php echo $xdesp; ?>">
                    <input type="text" class="form-control" id="xdesp0" name="xdesp0" value="<?php echo $xdesp0; ?>">

                    <div class="form-group">
                        <label for="narea_ids" class="col-sm-5 control-label">區號<span class="required">*</span>:</label>
                        <div class="col-sm-10">
                            <input type="text" id='narea_id' name="narea_id" value="6801" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="hcust_idx" class="col-sm-5 control-label">客戶編號:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="hcust_idx" name="hcust_idx" value="<?php echo $hcust_idx; ?>" maxlength="8" placeholder="有客編,區號無效,單一客戶編號 (8碼)">
                        </div>
                    </div>
                </div>
                <!-- Modal 底部 -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
                    <button type="submit" name="addsubmit" class="btn btn-primary">刷卡單</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="dddModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo $title . '-回傳點擊' ?></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal 身部 -->
            <form class="form-horizontal" method="POST" action="prt/hcust_ret.php" id="insert_form">
                <div class="modal-body">
                    <div class="add-messages"></div>
                    <div class="form-group">
                        <label for="ym" class="col-sm-5 control-label">期間<span class="required">*</span>:</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" id="ym" name="ym" value="<?php echo $ym; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="tp" class="col-sm-5 control-label">類別<span class="required">*</span>:</label>
                        <div class="col-sm-10">
                            <select name="tp" id="tp" required>
                                <option value="0">對帳單</option>
                                <option value="1">超商單</option>
                                <option value="8">扣款單</option>
                            </select>
                        </div>
                    </div>

                </div>
                <!-- Modal 底部 -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
                    <button type="submit" name="addsubmit" class="btn btn-primary">執行回傳</button>
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
    <h3 class="text-center">宅配帳單</h2>
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-5" style="margin-top:-15px; margin-bottom:5px;">
                <form id="submitForm" action="" method="post">
                    <div class="form-group">
                        <div class="row">
                            <nobr>
                                期間:
                                <select name="xym" id="xym" style="background-color:lightskyblue">
                                    <?php
                                    // require('./include/config0.php');
                                    $sql = "select close_ym from hcust_close order by close_ym desc  LIMIT 12";
                                    $sth = $conn->prepare($sql);
                                    $sth->execute();
                                    while ($row = $sth->fetch()) {
                                        if ($row['close_ym'] == $ym) {
                                            echo '<option   value="' . $row['close_ym'] . '" selected>' . $row['close_ym'] . '</option>';
                                        } else {
                                            echo '<option value="' . $row["close_ym"] . '">' . $row["close_ym"] . '</option>' . "\n";
                                        }
                                    }
                                    ?>
                                </select>
                                &emsp;<input type="submit" name="submit" class="btn-sm btn-info mt-1" value="查詢">
                            </nobr>
                            &emsp;<a href="#addModal" data-toggle="modal" class="btn btn-success btn-sm mt-1">儲值單</a>
                            &ensp;<a href="#bddModal" data-toggle="modal" class="btn btn-success btn-sm mt-1">超商單</a>
                            &ensp;<a href="#cddModal" data-toggle="modal" class="btn btn-success btn-sm mt-1">6801</a>
                            &ensp; <a href="#dddModal" data-toggle="modal" class="btn btn-danger btn-sm mt-1">回傳點擊</a>
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
                <th>收款員</th>
                <th>預收單</th>
                <th>應收單</th>
                <th>小計</th>
                <th>預收率</th>
                <th>預人工</th>
                <th>應人工</th>
                <th>人工小計</th>
                <th>預PDF</th>
                <th>應PDF</th>
                <th>PDF小計</th>
            </tr>
        </thead>
        <tbody>
            <?php

            $tot = 0;
            $tota = 0;
            $totb = 0;
            $totx = $totax = $totbx = 0;
            $tots = $totas = $totbs = 0;
            require('./include/config0.php');
            $sql = "select a.narea_id,b.sales_name,a.rcv_yn,a.pno,a.rcv_no,a.pno+a.rcv_no as xno,a.rcv_nos,a.pno_s,a.rcv_nos+a.pno_s as sno ";
            $sql .= " from hcust_narea_ant a left join sys_sales b on a.hum_id_rcv=b.sales_id where a.narea_ym='" . $ym . "' order by a.narea_id ";
            //echo $sql;
            //exit();
            $sth = $conn->query($sql);
            $sth->setFetchMode(PDO::FETCH_ASSOC);
            while ($row = $sth->fetch()) {
                $pno_x = (int)$row["pno"] - (int)$row["pno_s"];  // 人工
                $rcv_nox = (int)$row["rcv_no"] - (int)$row["rcv_nos"]; // 人工
                $xxno = $pno_x + $rcv_nox;  // 人工小計
                $xrate = 0.00;
                $tots = 0;
                $tot = (int)$row["rcv_no"] + (int)$row["pno"];
                $xrate = round((int)$row["pno"] / $tot * 100, 2);
                $tota = $tota + (int)$row["pno"];
                $totb = $totb + (int)$row["rcv_no"];
                $totas = $totas + (int)$row["pno_s"];
                $totbs = $totbs + (int)$row["rcv_nos"];

                $totx = $totx + $pno_x + $rcv_nox;
                $totax = $totax + $pno_x;
                $totbx = $totbx + $rcv_nox;

                echo '<tr><td>' . $row["narea_id"] . '</td>';
                echo '<td>' . $row["sales_name"] . '</td>';
                echo '<td>' . $row["pno"] . '</td>';
                echo '<td>' . $row["rcv_no"] . '</td>';
                echo '<td>' . $tot . '</td>';
                echo '<td>' . number_format($xrate, 2) . '</td>';
                echo '<td>' .  $pno_x  . '</td>';
                echo '<td>' .  $rcv_nox . '</td>';
                echo '<td>' .   $xxno  . '</td>';
                echo '<td>' . $row["pno_s"] . '</td>';
                echo '<td>' . $row["rcv_nos"] . '</td>';
                echo '<td>' . $row["sno"] . '</td></tr>';
            }

            $tot = $tota + $totb;
            $xrate = round($tota / $tot * 100, 2);
            $tots = $totas + $totbs;
            echo "<tr><td>ZZZZ</td>";
            echo "<td>小計</td>";
            echo "<td>" . number_format($tota, 0) . "</td>";
            echo "<td>" . number_format($totb, 0) . "</td>";
            echo "<td>" . number_format($tot, 0) . "</td>";
            echo "<td>" . number_format($xrate, 2) . "</td>";
            echo "<td>" . number_format($totax, 0) . "</td>";
            echo "<td>" . number_format($totbx, 0) . "</td>";
            echo "<td>" . number_format($totx, 0) . "</td>";
            echo "<td>" . number_format($totas, 0) . "</td>";
            echo "<td>" . number_format($totbs, 0) . "</td>";
            echo "<td>" . number_format($tots, 0) . "</tr>";
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