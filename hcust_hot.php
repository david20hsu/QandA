<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}
$title = "嘉南羊乳-熱點資料";
require_once("html/header.html");
require_once("html/menu.html");
$city_idx = 'A'; // 上月一日
$tp_idx = '';
if (isset($_POST['submit']) != "") {
    $city_idx = $_POST['city_idx'];
    $tp_idx = $_POST['tp_idx'];
}
if (isset($_POST["editsubmit"])) {
    require_once "./include/config0.php";
    $hot_id = $_POST['hot_id'];
    $city_id = $_POST['city_id'];
    $rold_id = $_POST['rold_id'];
    $tp_id = $_POST['tp_id'];
    $tm_id = $_POST['tm_id'];
    $hot_name = $_POST['hot_namex'];
    $hot_addr = $_POST['hot_addrx'];
    $hot_desp = $_POST['hot_despx'];
    $username = $_SESSION['username'];
    $data = [
        'city_id' => $city_id,
        'rold_id' => $rold_id,
        'tp_id' => $tp_id,
        'tm_id' => $tm_id,
        'hot_addr' => $hot_addr,
        'hot_name' => $hot_name,
        'hot_desp' => $hot_desp,
        "username" => $username,
        'hot_id' => $hot_id
    ];
    $cql = "UPDATE `hcust_hot` SET `hot_name`=:hot_name,`hot_desp`=:hot_desp,`hot_addr`=:hot_addr,`city_id`=:city_id,`rold_id`=:rold_id,`tp_id`=:tp_id,`tm_id`=:tm_id,`username`=:username where `hot_id`=:hot_id";
    $stmt = $conn->prepare($cql);
    $stmt->execute($data);
    $conn = null;
}
if (isset($_POST["addsubmit"])) {
    require_once "./include/config0.php";
    $city_id = $_POST['city_id'];
    $rold_id = $_POST['rold_id'];
    $tp_id = $_POST['tp_id'];
    $tm_id = $_POST['tm_id'];
    $hot_name = $_POST['hot_name'];
    $hot_desp = $_POST['hot_desp'];
    $username = $_SESSION['username'];
    $data = [
        'city_id' => $city_id,
        'rold_id' => $rold_id,
        'tp_id' => $tp_id,
        'tm_id' => $tm_id,
        'hot_name' => $hot_name,
        'hot_desp' => $hot_desp,
        "username" => $username,
    ];
    //  $cql = "INSERT INTO `doc_type` ('doc_id' ,`doc_name`,`username`) Values(:doc_id,:doc_name,:username)";
    $cql = "INSERT INTO `hcust_hot` (`city_id`,`rold_id`,`tp_id`,`tm_id`,`hot_name`,`hot_desp`,`username`) ";
    $cql .= " Values(:city_id,:rold_id,:tp_id,:tm_id,:hot_name,:hot_desp,:username)";
    $stmt = $conn->prepare($cql);
    $stmt->execute($data);
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
        <div class="col-4" style="margin-top:-5px;">
            <form id="submitForm" action="" method="post">
                <nobr>
                    <select name="city_idx">
                        <option value="">~行政區~</option>
                        <?php
                        require_once "./include/config0.php";
                        $sql = "select city_id,city_name  from city order by city_name";
                        $result = $conn->prepare($sql);
                        $result->execute();
                        while ($rows = $result->fetch()) {
                            if ($rows['city_id'] == $city_idx) {
                                echo '<option value="' . $rows['city_id'] . '" selected>' . $rows['city_name'] . '</option>';
                            } else {
                                echo '<option value="' . $rows["city_id"] . '">' . $rows["city_name"] . '</option>' . "\n";
                            }
                        }
                        ?>
                    </select>&emsp;
                    <select name="tp_idx">
                        <option value="">~類別~</option>
                        <?php
                        $sql = "select tp_id,tp_name  from hot_tp  order by tp_name";
                        $result = $conn->prepare($sql);
                        $result->execute();
                        while ($rows = $result->fetch()) {
                            if ($rows['tp_id'] == $tp_idx) {
                                echo '<option value="' . $rows['tp_id'] . '" selected>' . $rows['tp_name'] . '</option>';
                            } else {
                                echo '<option value="' . $rows["tp_id"] . '">' . $rows["tp_name"] . '</option>' . "\n";
                            }
                        }
                        ?>
                    </select>
                    &emsp;<input type="submit" name="submit" class="btn-sm btn-info mt-1" value="查詢">
                </nobr>
            </form>
        </div>
        <div class="col-4">
            <h3 align='center'><?php echo $title ?></h3>
        </div>
        <div class="col-4">
            <a href="#addModal" data-toggle="modal" class="btn btn-success btn-sm mt-1">新增</a>
        </div>
    </div>
    <table id="myDataTalbe" class="table table-bordered table-striped table-hove" style="width:100%">
        <thead>
            <tr>
                <th>編號</th>
                <th>熱點名稱</th>
                <th>行政區</th>
                <th>路名</th>
                <th>地點說明</th>
                <th>備註說明</th>
                <th>類別</th>
                <th>最佳時段</th>
                <th>維護人</th>
                <th>建檔日期</th>
                <th>編修日期</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $city_id = $rold_id = $tp_id = $tm_id = "";
            require('include/config0.php');
            $sql = "select distinct a.*,b.city_name,c.rold_name,d.tp_name,e.tm_name ";
            $sql .= " from hcust_hot a left join city b using(city_id) left join city_rold c on (a.rold_id=c.rold_id) ";
            $sql .= " left join hot_tp d on a.tp_id=d.tp_id left join best_tm e on a.tm_id=e.tm_id where a.hot_id<>'' ";
            if ($city_idx <> '') {
                $sql .= " and a.city_id='$city_idx' ";
            }
            if ($tp_idx <> '') {
                $sql .= " and a.tp_id='$tp_idx' ";
            }
            $sql .= " order by hot_id";
            // echo $sql;
            // exit();
            $sth = $conn->prepare($sql);
            $sth->execute();
            while ($row = $sth->fetch()) {
                $city_id = $row["city_id"];
                $rold_id = $row["rold_id"];
                $tp_id = $row['tp_id'];
                $tm_id = $row['tm_id'];
            ?>
                <tr>
                    <td><?php echo $row["hot_id"]; ?> </td>
                    <td><?php echo $row["hot_name"]; ?> </td>
                    <td><?php echo $row["city_name"]; ?> </td>
                    <td><?php echo $row["rold_name"]; ?> </td>
                    <td><?php echo $row["hot_addr"]; ?> </td>
                    <td><?php echo $row["hot_desp"]; ?> </td>
                    <td><?php echo $row["tp_name"]; ?> </td>
                    <td><?php echo $row["tm_name"]; ?> </td>
                    <td><?php echo $row["username"]; ?></td>
                    <td><?php echo substr($row["created"], 0, 10); ?></td>
                    <td><?php echo substr($row["updated"], 0, 10); ?></td>
                    <td>
                        <a href="#updModal<?php echo $row['hot_id']; ?>" data-toggle="modal" class="btn-sm btn-info mt-1">編修</a>
                        <a onclick="return confirm('確定要刪除嗎 ?')" href="cat/hcust_hotdel.php?del=<?php echo $row['hot_id']; ?>" class='btn-sm btn-danger mt-1'>刪除</a>
                    </td>

                </tr>
                <div class="modal fade" id="updModal<?php echo $row['hot_id'] ?>">
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
                                    <input type="hidden" name="hot_id" id="hot_id" value="<?php echo $row['hot_id'] ?>">
                                    <div class="form-group">
                                        <label for="hot_namex" class="col-sm-5 control-label">熱點名稱<span class="required">*</span>:</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="hot_namex" name="hot_namex" value="<?php echo $row['hot_name'] ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-10">
                                        <select name="city_id" id="city_id" required>
                                            <option value="">~行政區~</option>
                                            <?php
                                            $sql = "select city_id,city_name  from city order by city_name";
                                            $result = $conn->prepare($sql);
                                            $result->execute();
                                            while ($rows = $result->fetch()) {
                                                if ($rows['city_id'] == $city_id) {
                                                    echo '<option   value="' . $rows['city_id'] . '" selected>' . $rows['city_name'] . '</option>';
                                                } else {
                                                    echo '<option value="' . $rows["city_id"] . '">' . $rows["city_name"] . '</option>' . "\n";
                                                }
                                            }
                                            ?>
                                        </select>&ensp;
                                        <?php
                                        if ($city_id != '') { ?>
                                            <select name="rold_id" id="rold_id" required>
                                                <?php
                                                $sql = "select rold_id,rold_name  from city_rold where city_id='$city_id' order by rold_name";
                                                $result = $conn->prepare($sql);
                                                $result->execute();
                                                while ($rows = $result->fetch()) {
                                                    if ($rows['rold_id'] == $rold_id) {
                                                        echo '<option   value="' . $rows['rold_id'] . '" selected>' . $rows['rold_name'] . '</option>';
                                                    } else {
                                                        echo '<option value="' . $rows["rold_id"] . '">' . $rows["rold_name"] . '</option>' . "\n";
                                                    }
                                                }
                                                ?>
                                            </select>
                                        <?php } else { ?>
                                            <select name="rold_id" id="rold_id">
                                            </select>
                                        <?php } ?>
                                    </div>
                                    <p></p>
                                    <div class="col-sm-10">
                                        <select name="tp_id" id="tp_id" required>
                                            <option value="">~類別~</option>
                                            <?php
                                            $sql = "select tp_id,tp_name  from hot_tp order by tp_name";
                                            $result = $conn->prepare($sql);
                                            $result->execute();
                                            while ($rows = $result->fetch()) {
                                                if ($rows['tp_id'] == $tp_id) {
                                                    echo '<option   value="' . $rows['tp_id'] . '" selected>' . $rows['tp_name'] . '</option>';
                                                } else {
                                                    echo '<option value="' . $rows["tp_id"] . '">' . $rows["tp_name"] . '</option>' . "\n";
                                                }
                                            }
                                            ?>
                                        </select>&ensp;
                                        <select name="tm_id" id="tm_id" required>
                                            <option value="">~時段~</option>
                                            <?php
                                            $sql = "select tm_id,tm_name  from best_tm order by tm_name";
                                            $result = $conn->prepare($sql);
                                            $result->execute();
                                            while ($rows = $result->fetch()) {
                                                if ($rows['tm_id'] == $tm_id) {
                                                    echo '<option value="' . $rows['tm_id'] . '" selected>' . $rows['tm_name'] . '</option>';
                                                } else {
                                                    echo '<option value="' . $rows["tm_id"] . '">' . $rows["tm_name"] . '</option>' . "\n";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <p></p>

                                    <div class="form-group">
                                        <label for="hot_addrx" class="col-sm-5 control-label">地點描述<span class="required">*</span>:</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="hot_addrx" name="hot_addrx" value="<?php echo $row['hot_addr'] ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="hot_despx" class="col-sm-5 control-label">作業要點:</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="hot_despx" name="hot_despx" value="<?php echo $row['hot_desp'] ?>">
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
                            <label for="hot_name" class="col-sm-5 control-label">熱點名稱<span class="required">*</span>:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="hot_name" name="hot_name" required placeholder="熱點名稱">
                            </div>
                        </div>
                        <div class="col-sm-10">
                            <select name="city_id" id="city_id" required>
                                <option value=''>~行政區~</option>;
                                <?php
                                $xcity_id = "";
                                $sql = "select city_id,city_name  from city order by city_name";
                                $result = $conn->prepare($sql);
                                $result->execute();
                                while ($rows = $result->fetch()) {
                                    echo '<option value="' . $rows["city_id"] . '">' . $rows["city_name"] . '</option>' . "\n";
                                }
                                ?>
                            </select>&ensp;
                            <?php
                            if ($city_id != '') { ?>
                                <select name="rold_id" id="rold_id" required>
                                    <option value=''>~路名~</option>;
                                    <?php
                                    $sql = "select rold_id,rold_name  from city_rold where city_id='$xcity_id' order by rold_name";
                                    $result = $conn->prepare($sql);
                                    $result->execute();
                                    while ($rows = $result->fetch()) {

                                        echo '<option value="' . $rows["rold_id"] . '">' . $rows["rold_name"] . '</option>' . "\n";
                                    }
                                    ?>
                                </select>
                            <?php } else { ?>
                                <option value=''>~路名~</option>;
                                <select name="rold_id" id="rold_id" required>
                                </select>
                            <?php } ?>
                        </div>
                        <p></p>

                        <div class="col-sm-10">
                            <select name="tp_id" id="tp_id" required>
                                <option value="">~類別~</option>
                                <?php
                                $sql = "select tp_id,tp_name  from hot_tp order by tp_name";
                                $result = $conn->prepare($sql);
                                $result->execute();
                                while ($rows = $result->fetch()) {
                                    echo '<option value="' . $rows["tp_id"] . '">' . $rows["tp_name"] . '</option>' . "\n";
                                }
                                ?>
                            </select>&ensp;
                            <select name="tm_id" id="tm_id" required>
                                <option value="">~時段~</option>
                                <?php
                                $sql = "select tm_id,tm_name  from best_tm order by tm_name";
                                $result = $conn->prepare($sql);
                                $result->execute();
                                while ($rows = $result->fetch()) {
                                    echo '<option value="' . $rows["tm_id"] . '">' . $rows["tm_name"] . '</option>' . "\n";
                                }
                                ?>
                            </select>
                        </div>
                        <p></p>
                        <div class="form-group">
                            <label for="hot_addr" class="col-sm-5 control-label">地點描述<span class="required">*</span>:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="hot_addr" name="hot_addr" required placeholder="地點描述">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="hot_addr" class="col-sm-5 control-label">作業要點:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="hot_desp" name="hot_desp" placeholder="作業要點">
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

<script>
    $(function() {
        $('#city_id').change(function() {
            $('#rold_id').empty().append("<option value=''>~路街名~</option>");
            var i = 0;
            $.ajax({
                type: "GET",
                url: "action_rold.php",
                data: {
                    city_id: $('#city_id option:selected').val()
                },
                datatype: "json",
                success: function(result) {
                    //當第一層回到預設值時，第二層回到預設位置
                    if (result == "") {
                        $('#rold_id').val($('option:first').val());
                    }
                    //依據第一層回傳的值去改變第二層的內容
                    while (i < result.length) {
                        $("#rold_id").append("<option value='" + result[i]['rold_id'] + "'>" + result[i]['rold_name'] + "</option>");
                        i++;
                    }
                },
                error: function(xhr, status, msg) {
                    console.error(xhr);
                    console.error(msg);
                }
            });
        });
    });
</script>
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