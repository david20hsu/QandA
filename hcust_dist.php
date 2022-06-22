<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}
$title = "嘉南羊乳-發單紀錄";
require_once("html/header.html");
require_once("html/menu.html");
require('./include/config0.php');
//echo __DIR__."<br/>";
?>
<?php
date_default_timezone_set("Asia/Taipei");
//$ym = date('Y-m-01', strtotime('last month')); // 上月一日
if (isset($_POST["editsubmit"])) {
    require_once "./include/config0.php";
    $dist_ser = $_POST['dist_ser'];
    $hot_id = $_POST['hot_id'];
    $dist_date = $_POST['dist_date'];
    $adv_sers = $_POST['adv_sers'];
    $adv_sere = $_POST['adv_sere'];
    $tel_080 = $_POST['tel_080'];
    $adv_tm = $_POST['adv_tm'];
    $hum_id    = $_POST['hum_id'];
    $ord_qty    = $_POST['hum_id'];
    $adv_desp = $_POST['adv_desp'];
    $username = $_SESSION['username'];
    $data = [
        'hot_id' => $hot_id,
        'dist_date' => $dist_date,
        'adv_sers' => $adv_sers,
        'adv_sere' => $adv_sere,
        'tel_080' => $tel_080,
        'adv_tm' => $adv_tm,
        'hum_id' => $hum_id,
        'ord_qty' => $ord_qty,
        'adv_desp' => $adv_desp,
        'username' => $username,
        'dist_ser' => $dist_ser
    ];
    $cql = "UPDATE `hcust_dist` SET `hot_id`=:hot_id,";
    $cql .= "`dist_date`=:dist_date,`adv_sers`=:adv_sers,";
    $cql .= "`adv_sere`=:adv_sere,`tel_080`=:tel_080,";
    $cql .= "`adv_tm`=:adv_tm,`hum_id`=:hum_id,`ord_qty`=:ord_qty,`adv_desp`=:adv_desp,`username`=:username where `dist_ser`=:dist_ser";
    $stmt = $conn->prepare($cql);
    $stmt->execute($data);
    //   $conn = null;
}
if (isset($_POST["addsubmit"])) {
    require_once "./include/config0.php";
    $hot_id = $_POST['hot_id'];
    $dist_date = $_POST['dist_date'];
    $adv_sers = $_POST['adv_sers'];
    $adv_sere = $_POST['adv_sere'];
    $tel_080 = $_POST['tel_080'];
    $adv_tm = $_POST['adv_tm'];
    $hum_id    = $_POST['hum_id'];
    $ord_qty    = $_POST['hum_id'];
    $adv_desp = $_POST['adv_desp'];
    $username = $_SESSION['username'];
    $data = [
        'hot_id' => $hot_id,
        'dist_date' => $dist_date,
        'adv_sers' => $adv_sers,
        'adv_sere' => $adv_sere,
        'tel_080' => $tel_080,
        'adv_tm' => $adv_tm,
        'hum_id' => $hum_id,
        'ord_qty' => $ord_qty,
        'adv_desp' => $adv_desp,
        "username" => $username,
    ];
    $cql = "INSERT INTO `hcust_dist` (`hot_id`,`dist_date`,`adv_sers`,`adv_sere`,`tel_080`,`adv_tm`,`hum_id`,`ord_qty`,`adv_desp`,`username`) ";
    $cql .= " Values(:hot_id,:dist_date,:adv_sers,:adv_sere,:tel_080,:adv_tm,:hum_id,:ord_qty,:adv_desp,:username)";
    $stmt = $conn->prepare($cql);
    $stmt->execute($data);
    //   $conn = null;
}
$hum_idx = ''; // 上月一日
$hot_idx = '';
$sdate = date('Y-m-01');
$edate = date('Y-m-d');
if (isset($_POST['submit']) != "") {
    $sdate = $_POST['sdate'];
    $edate = $_POST['edate'];
    $hot_idx = $_POST['hot_idx'];
    $hum_idx = $_POST['hum_idx'];
}
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
        <div class="col-md-6" style="margin-top:-15px; margin-bottom:5px;">
            <form id="submitForm" action="" method="post">
                <div class="form-group">
                    <div class="row">
                        <nobr>
                            期間:
                            <input type="date" name="sdate" id="sdate" value="<?php echo $sdate; ?>"></input>
                            ~<input type="date" name="edate" id="edate" value="<?php echo $edate; ?>"></input>
                            <select name="hot_idx">
                                <option value="">~熱點~</option>
                                <?php
                                $sql = "select hot_id,hot_name  from hcust_hot order by hot_name";
                                $result = $conn->prepare($sql);
                                $result->execute();
                                while ($rows = $result->fetch()) {
                                    if ($rows['hot_id'] == $hot_idx) {
                                        echo '<option value="' . $rows['hot_id'] . '" selected>' . $rows['hot_name'] . '</option>';
                                    } else {
                                        echo '<option value="' . $rows["hot_id"] . '">' . $rows["hot_name"] . '</option>' . "\n";
                                    }
                                }
                                ?>
                            </select>
                            <select name="hum_idx">
                                <option value="">~發單人~</option>
                                <?php
                                $sql = "select sales_id,sales_name  from sys_sales where hcust_yn='Y' and tp_id in('0','1','2') and (lv_date='' or lv_date is null) and dept_id in('06PP')  order by sales_id";
                                $result = $conn->prepare($sql);
                                $result->execute();
                                while ($rows = $result->fetch()) {
                                    if ($rows['sales_id'] == $hum_idx) {
                                        echo '<option value="' . $rows['sales_id'] . '" selected>' . $rows['sales_name'] . '</option>';
                                    } else {
                                        echo '<option value="' . $rows["sale_id"] . '">' . $rows["sales_name"] . '</option>' . "\n";
                                    }
                                }
                                ?>
                            </select>
                            &emsp;<input type="submit" name="submit" class="btn-sm btn-info mt-1" value="查詢">

                        </nobr>
                    </div>
                </div>
            </form>

        </div>
        <div class="col-md-2" style="margin-top:-15px; margin-bottom:5px;">
            <h3 class="text-center">發單紀錄</h2>
        </div>
        &emsp; <a href="#addModal" data-toggle="modal" class="btn btn-success btn-md mt-1">新增</a>
    </div>
</div>
<div>
    <table id="myDataTalbe" class="table table-bordered table-striped table-hove" style="width:100%">
        <thead>
            <tr>
                <th>發單日期</th>
                <th>起始序號</th>
                <th>起始截止</th>
                <th>080電話</th>
                <th>熱點名稱</th>
                <th>時段</th>
                <th>瓶數</th>
                <th>發單人</th>
                <th>作業說明</th>
                <th>行政區</th>
                <th>類別</th>
                <!--
                <th>維護人</th>
                <th>建檔日期</th>
                <th>編修日期</th>
                            -->
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require('./include/config0.php');
            $sql = 'select a.*,b.hot_name,c.sales_name,d.city_name,e.tp_name from hcust_dist a left join hcust_hot b using(hot_id) left join sys_sales c on a.hum_id=c.sales_id left join city d on b.city_id=d.city_id left join hot_tp e on b.tp_id=e.tp_id ';
            $sql .= " where a.dist_ser<>'' ";
            if ($sdate <> '') {
                $sql .= " and a.dist_date >='$sdate' ";
            }
            if ($edate <> '') {
                $sql .= " and a.dist_date <='$edate' ";
            }
            if ($hum_idx <> '') {
                $sql .= " and a.hum_id='$hum_idx' ";
            }
            if ($hot_idx <> '') {
                $sql .= " and a.hot_id='$hot_idx' ";
            }
            $sql .= " order by a.dist_date";
            $sth = $conn->query($sql);
            $sth->setFetchMode(PDO::FETCH_ASSOC);
            while ($row = $sth->fetch()) {
                $hot_id0 = $row["hot_id"];
                $hum_id0 = $row["hum_id"];
            ?>
                <tr>
                    <td><?php echo $row["dist_date"]; ?> </td>
                    <td><?php echo $row["adv_sers"]; ?> </td>
                    <td><?php echo $row["adv_sere"]; ?> </td>
                    <td><?php echo $row["tel_080"]; ?> </td>
                    <td><?php echo $row["hot_name"]; ?> </td>
                    <td><?php echo $row["adv_tm"]; ?> </td>
                    <td><?php echo number_format($row["ord_qty"], 0); ?> </td>
                    <td><?php echo $row["sales_name"]; ?> </td>
                    <td><?php echo $row["adv_desp"]; ?> </td>
                    <td><?php echo $row["city_name"]; ?> </td>
                    <td><?php echo $row["tp_name"]; ?> </td>
                    <!--
                    <td><?php echo $row["username"]; ?></td>
                    <td><?php echo substr($row["created"], 0, 10); ?></td>
                    <td><?php echo substr($row["updated"], 0, 10); ?></td>
            -->
                    <td>
                        <a href="#updModal<?php echo $row['dist_ser']; ?>" data-toggle="modal" class="btn-sm btn-info mt-1">編修</a>
                        <a onclick="return confirm('確定要刪除嗎 ?')" href="cat/hcust_distdel.php?del=<?php echo $row['dist_ser']; ?>" class='btn-sm btn-danger mt-1'>刪除</a>
                    </td>
                </tr>
                <div class="modal fade" id="updModal<?php echo $row['dist_ser'] ?>">
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
                                    <input type="hidden" name="dist_ser" id="dist_ser" value="<?php echo $row['dist_ser'] ?>">
                                    <div class="form-group">
                                        <label for="hot_despx" class="col-sm-5 control-label">作業日期:<span class="required">*</span></label>
                                        <div class="col-sm-10">
                                            <input type="date" class="form-control" id="dist_date" name="dist_date" value="<?php echo $row['dist_date'] ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="dist_date" class="col-sm-5 control-label">作業時段:<span class="required">*</span></label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="adv_tm" name="adv_tm" value="<?php echo $row['adv_tm'] ?>" required placeholder="發送時間">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="adv_sers" class="col-sm-5 control-label">單據序號:<span class="required">*</span>:</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="adv_sers" name="adv_sers" maxlength="10" value="<?php echo $row['adv_sers'] ?>" required>
                                            <input type="text" class="form-control" id="adv_sere" name="adv_sere" maxlength="10" value="<?php echo $row['adv_sere'] ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-10">
                                        <select name="hot_id" id="hot_id" required>
                                            <option value="">~熱點~</option>
                                            <?php
                                            $sql = "select hot_id,hot_name  from hcust_hot order by hot_name";
                                            $result = $conn->prepare($sql);
                                            $result->execute();
                                            while ($rows = $result->fetch()) {
                                                if ($rows['hot_id'] == $row['hot_id']) {
                                                    echo '<option   value="' . $rows['hot_id'] . '" selected>' . $rows['hot_name'] . '</option>';
                                                } else {
                                                    echo '<option value="' . $rows["hot_id"] . '">' . $rows["hot_name"] . '</option>' . "\n";
                                                }
                                            }
                                            ?>
                                        </select>&ensp;
                                        &ensp;
                                        <select name="tel_080" id="tel_080" required>
                                            <option value='0809-088-992' <?php if ($row['tel_080'] == '0809-088-992') echo ' selected="selected"'; ?>>0809-088-992</option>;
                                            <option value='0809-088-997' <?php if ($row['tel_080'] == '0809-088-997') echo ' selected="selected"'; ?>>0809-088-997</option>;
                                            <option value='0809-089-202' <?php if ($row['tel_080'] == '0809-089-202') echo ' selected="selected"'; ?>>0809-089-202</option>;
                                        </select>
                                        <select name="hum_id" id="hum_id" required>
                                            <?php
                                            $sql = "select sales_id,sales_name  from sys_sales where hcust_yn='Y' and tp_id in('0','1','2') and (lv_date='' or lv_date is null) and dept_id in('06PP')  order by sales_id";
                                            $result = $conn->prepare($sql);
                                            $result->execute();
                                            while ($rows = $result->fetch()) {
                                                if ($rows['sales_id'] == $row['hum_id']) {
                                                    echo '<option   value="' . $rows['sales_id'] . '" selected>' . $rows['sales_name'] . '</option>';
                                                } else {
                                                    echo '<option value="' . $rows["sales_id"] . '">' . $rows["sales_name"] . '</option>' . "\n";
                                                }
                                            }
                                            ?>
                                        </select>
                                        &ensp;
                                        <select name="ord_qty" id="ord_qty">
                                            <option value='' <?php if ($row['ord_qty'] == '') echo ' selected="selected"'; ?>>瓶數</option>
                                            <option value='1' <?php if ($row['ord_qty'] == '1') echo ' selected="selected"'; ?>>1</option>
                                            <option value='2' <?php if ($row['ord_qty'] == '2') echo ' selected="selected"'; ?>>2</option>
                                            <option value='3' <?php if ($row['ord_qty'] == '3') echo ' selected="selected"'; ?>>3</option>
                                            <option value='4' <?php if ($row['ord_qty'] == '4') echo ' selected="selected"'; ?>>4</option>
                                            <option value='5' <?php if ($row['ord_qty'] == '5') echo ' selected="selected"'; ?>>5</option>
                                            <option value='6' <?php if ($row['ord_qty'] == '6') echo ' selected="selected"'; ?>>6</option>
                                            <option value='7' <?php if ($row['ord_qty'] == '7') echo ' selected="selected"'; ?>>7</option>
                                            <option value='8' <?php if ($row['ord_qty'] == '8') echo ' selected="selected"'; ?>>8</option>
                                            <option value='9' <?php if ($row['ord_qty'] == '9') echo ' selected="selected"'; ?>>9</option>
                                            <option value='10' <?php if ($row['ord_qty'] == '10') echo ' selected="selected"'; ?>>10</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="adv_desp" class="col-sm-5 control-label">作業概述:</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" id="adv_desp" name="adv_desp" value="<?php echo $row['adv_desp']; ?>">
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
            <?php }  ?>
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
                    <div class="add-messages"></div>

                    <div class="form-group">
                        <label for="dist_date" class="col-sm-5 control-label">作業日期:<span class="required">*</span></label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" id="dist_date" name="dist_date" value="<?php echo date('Y-m-d') ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="dist_date" class="col-sm-5 control-label">作業時段:<span class="required">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="adv_tm" name="adv_tm" value="09:00~16:00" required placeholder="發送時間">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="adv_sers" class="col-sm-5 control-label">單據序號:<span class="required">*</span>:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="adv_sers" name="adv_sers" maxlength="10" placeholder="起始序號" required>
                            <input type="text" class="form-control" id="adv_sere" name="adv_sere" maxlength="10" placeholder="截止序號" required>
                        </div>
                    </div>
                    <div class="col-sm-10">
                        <select name="hot_id" id="hot_id" required>
                            <option value=''>~熱點*~</option>;
                            <?php
                            $sql = "select hot_id,hot_name  from hcust_hot order by hot_name";
                            $result = $conn->prepare($sql);
                            $result->execute();
                            while ($rows = $result->fetch()) {
                                echo '<option value="' . $rows["hot_id"] . '">' . $rows["hot_name"] . '</option>' . "\n";
                            }
                            ?>
                        </select>&ensp;
                        <select name="tel_080" id="tel_080" required>
                            <option value='0809-088-992'>0809-088-992</option>;
                            <option value='0809-088-997'>0809-088-997</option>;
                            <option value='0809-089-202'>0809-089-202</option>;
                        </select>
                    </div>
                    <div class="col-sm-10">
                        <select name="hum_id" required>
                            <option value="">~ 發單人 *~</option>
                            <?php
                            $sql = "select sales_id,sales_name  from sys_sales where hcust_yn='Y' and tp_id in('0','1','2') and (lv_date='' or lv_date is null) and dept_id in('06PP')  order by sales_id";
                            $result = $conn->prepare($sql);
                            $result->execute();
                            while ($rows = $result->fetch()) {
                                if ($rows['sales_id'] == $hum_idx) {
                                    echo '<option value="' . $rows['sales_id'] . '" selected>' . $rows['sales_name'] . '</option>';
                                } else {
                                    echo '<option value="' . $rows["sale_id"] . '">' . $rows["sales_name"] . '</option>' . "\n";
                                }
                            }
                            ?>
                        </select>
                        &ensp;
                        <select name="ord_qty" id="ord_qty">
                            <option value=''>瓶數</option>
                            <option value='1'>1</option>
                            <option value='2'>2</option>
                            <option value='3'>3</option>
                            <option value='4'>4</option>
                            <option value='5'>5</option>
                            <option value='6'>6</option>
                            <option value='7'>7</option>
                            <option value='8'>8</option>
                            <option value='9'>9</option>
                            <option value='10'>10</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="adv_desp" class="col-sm-5 control-label">作業概述:</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="adv_desp" name="adv_desp" placeholder="作業概述">
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