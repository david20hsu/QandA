<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}
ini_set('upload_max_filesize', '20M');
$title = "嘉南羊乳-語音資料";
require_once("html/header.html");
require_once("html/menu.html");
//ini_set('display_errors', 'On');
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

    #doc_keyword {
        width: 446px;
    }

    .required {
        color: red;
    }
</style>
<?php
date_default_timezone_set("Asia/Taipei");
if (isset($_POST['submit']) != "") {
    require('./include/config.php');
    $extension = array("mp3", "wav");
    $name = $_FILES['audo']['name'];
    $size = $_FILES['audo']['size'];
    $type = $_FILES['audo']['type'];
    $temp = $_FILES['audo']['tmp_name'];
    $username = $_POST['author'];
    $mp3_id = $_POST['mp3_id'];
    $mp3_rv = $_POST['mp3_rv'];
    $mp3_yn = $_POST['mp3_yn'];
    $file_name = $_POST['file_name'];
    $mp3_keyword = $_POST['mp3_keyword'];
    $ext = substr($name, strrpos($name, '.') + 1);
    if (!in_array(strtolower($ext), $extension)) {
        $_SESSION['error'] = '檔案格式不符合:' . $ext;
    } else {
        $xfile = $mp3_id . date('Ymd') . date('H') . date('i') . date('s') . '.' . $ext;
        //move_uploaded_file($temp, "upload/" . $name);
        move_uploaded_file($temp, "upload_mp3/" . $xfile);
        $data = array();
        $data = [
            "file_id" => $xfile,
            "file_name" => trim($_POST['file_name']),
            "mp3_id" => trim($_POST['mp3_id']),
            "mp3_yn" => trim($_POST['mp3_yn']),
            "mp3_rv" => trim($_POST['mp3_rv']),
            "mp3_keyword" => trim($_POST['mp3_keyword']),
            "username" => $username
        ];
        $cql = "INSERT INTO mp3_file (file_id,file_name,mp3_id,mp3_yn,mp3_rv,mp3_keyword,username) ";
        $cql = $cql . " VALUES(:file_id,:file_name,:mp3_id,:mp3_yn,:mp3_rv,:mp3_keyword,:username)";
        $stmt = $conn->prepare($cql);
        $stmt->execute($data);
    }
    $_POST['submit'] = "";
}
if (isset($_POST["editsubmit"])) {
    require_once "./include/config.php";
    $mp3_id = $_POST['mp3_idx'];
    $mp3_yn = $_POST['mp3_ynx'];
    $mp3_rv = $_POST['mp3_rvx'];
    $file_id = $_POST['file_idx'];
    $extension = array("mp3", "wav");
    $name = $_FILES['audoA']['name'];
    $size = $_FILES['audoA']['size'];
    $type = $_FILES['audoA']['type'];
    $temp = $_FILES['audoA']['tmp_name'];
    $ext = substr($name, strrpos($name, '.') + 1);
    $xfile = "";
    if (!in_array(strtolower($ext), $extension)) {
        echo "<script>alert('檔案格式不符合')</script>";
        echo '<meta http-equiv=REFRESH CONTENT=1;url=./doc_list.php>';
        exit();
    } else {
        if ($_POST['file_idx'] <> '') {
            $dir = './upload_mp3/' . $file_id;
            if (file_exists($dir)) {
                unlink($dir);
            }
        }
        $xfile = $mp3_id . date('Ymd') . date('H') . date('i') . date('s') . '.' . $ext;
        move_uploaded_file($temp, "upload_mp3/" . $xfile);
    }
    $mp3_keyword = $_POST['mp3_keywordx'];
    $file_name = $_POST['file_namex'];
    $username = $_SESSION['username'];
    $id = $_POST['id'];
    $data = [
        'file_id' => $xfile,
        'file_name' => $file_name,
        'mp3_keyword' => $mp3_keyword,
        'mp3_id' => $mp3_id,
        'mp3_yn' => $mp3_yn,
        'mp3_rv' => $mp3_rv,
        'username' => $username,
        'id' => $id
    ];
    $cql = "UPDATE `mp3_file` SET `file_id`=:file_id,`file_name`=:file_name,`mp3_keyword`=:mp3_keyword,`mp3_id`=:mp3_id,`mp3_yn`=:mp3_yn,`mp3_rv`=:mp3_rv,`username`=:username where `id`=:id";
    //$cql = "UPDATE `doc_file` SET `file_id`=:file_id,`file_name`=:file_name,`doc_keyword`=:doc_keyword where `id`=:id";
    //print_r($cql);
    //exit(); 
    $stmt = $conn->prepare($cql);
    $stmt->execute($data);
    $conn = null;
}
// 編修
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
        padding: -2px -2px;
        /* e.g. change 8x to 4px here */
    }

    table.dataTable.nowrap th,
    table.dataTable.nowrap td {
        white-space: normal !important;
    }
</style>
<div class="container-fluid">
    <!-- Add_Modal 本體 -->
    <div class="modal fade" id="addModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><?php echo $title . '-新增' ?></h4>
                    <!--
                    <button data-dismiss="modal">&times;</button>
                   type="button" class="close" -->
                </div>
                <!-- Modal 身部 -->
                <form enctype="multipart/form-data" class="form-horizontal" action="#" method="POST" name="add_Form" id="add_Form">
                    <div class="modal-body">
                        <div class="edit-messages"></div>
                        <div class="form-group">
                            <label for="file_name" class="col-sm-5 control-label">語音檔名<span class="required">*</span>:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="file_name" name="file_name" required placeholder="語音檔名">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="mp3_idx" class="col-sm-5 control-label">語音類別<span class="required">*</span>:</label>
                            <div class="col-sm-10">
                                <select name="mp3_id" id="mp3_id" classs="form-control" required style="width:250px;">
                                    <?php
                                    require('./include/config.php');
                                    $cql = "select mp3_id,tp_name from mp3_tp order by mp3_id";
                                    $sthm = $conn->prepare($cql);
                                    $sthm->execute();
                                    while ($rows = $sthm->fetch()) { ?>
                                        <option value="<?php echo $rows['mp3_id']; ?>"><?php echo $rows['tp_name']; ?></option>
                                    <?php }   ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="mp3_rv" class="col-sm-5 control-label">預收<span class="required">*</span>:</label>
                                <select name="mp3_rv" id="mp3_rv" required style="width:80px;">
                                    <option value="1">是</option>
                                    <option value="0">否</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="mp3_yn" class="col-sm-5 control-label">成交<span class="required">*</span>:</label>
                                <select name="mp3_yn" id="mp3_yn" required style="width:80px;">
                                    <option value="1">是</option>
                                    <option value="2">否</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="mp3_keyword" class="col-sm-5 control-label">關鍵字<span class="required">*</span>:</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="mp3_keyword" name="mp3_keyword" required placeholder="關鍵字、說明">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="photo" class="col-sm-5 control-label">上傳檔案<span class="required">*</span>:</label>
                            <div class="col-sm-10">
                                <!--
                                    <input type="file" required  name="photoA" id="photo_A"  class="form-control-file" >
                                   style="margin-left: 5px;" class="form-control-file" style="width:400px;height:30px;border:2px blue none;"
                                    -->
                                <input type="file" id="audo" name="audo" accept="audio/mp3,audio/wav" required>

                            </div>
                        </div>
                        <div class="form-group">
                            <label for="mp3_keyword" class="col-sm-5 control-label">編審人<span class="required">*</span>:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="author" name="author" value=<?php echo $_SESSION['username']; ?> required placeholder="編審人帳號">
                            </div>
                        </div>
                    </div>
                    <!-- Modal 底部 -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
                        <button type="submit" name="submit" class="btn btn-primary">存檔</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row mb-1" id="tprow">
        <div class="col-12">
            <h5 style="text-align:center;color:red;">本文件、語音檔包含和民宅食有限公司機密資訊及個人資料，任何人不得任意傳佈或揭露，以共同善盡資訊安全與個資保護責任</h5>
        </div>

        <div class="col-11 mb-1">
            <h4 align='center'><?php echo $title; ?></h4>
        </div>
        <div class="col-1">
            <a href="#addModal" data-toggle="modal" class="btn btn-info btn-sm mb-1">新增</a>
        </div>
    </div>
    <table id="myDataTalbe" class="table table-bordered table-striped table-hove" style="width:100%">
        <thead>
            <tr>
                <th width="4%">ID</th>
                <th width="15%">語音檔名</th>
                <th width="10%">語音類別</th>
                <th width="4%">預收</th>
                <th width="4%">成交</th>
                <th width="19%">搜尋關鍵字</th>
                <th width="5%">審查</th>
                <th width="6%">維護日期</th>
                <th width="20%" style="text-align: center;">聆聽播放</th>
                <th width="10%">操作</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require('./include/config.php');
            $sql = "select a.*,b.tp_name from mp3_file a left join mp3_tp b using(mp3_id) order by created desc";
            $sth = $conn->prepare($sql);
            $sth->execute();
            while ($row = $sth->fetch()) {
                $id = $row['id'];
                $mp3_id = $row['mp3_id'];
                $mp3_rv = $row['mp3_rv'];
                $mp3_yn = $row['mp3_yn'];
                $mp3_rvx = "是";
                $mp3_ynx = "是";
                if ($row['mp3_rv'] == '0') $mp3_rvx = '否';
                if ($row['mp3_yn'] == '2') $mp3_ynx = '否';
            ?>
                <tr>
                    <td><?php echo $row['id'] ?></td>
                    <td><?php echo $row['file_name'] ?></td>
                    <td><?php echo $row['tp_name'] ?></td>
                    <td><?php echo $mp3_rvx ?></td>
                    <td><?php echo $mp3_ynx ?></td>
                    <td><?php echo $row['mp3_keyword'] ?></td>
                    <td><?php echo $row['username'] ?></td>
                    <td><?php echo substr($row['updated'], 0, 10) ?></td>
                    <td>
                        <audio controls height="15" preload="none">
                            <source src="upload_mp3/<?php echo $row['file_id']; ?>" type="audio/mpeg">
                        </audio>
                    </td>
                    <td>
                        <a href="#updModal<?php echo $row['id']; ?>" data-toggle="modal" class="btn-sm btn-info mt-1">編修</a>&nbsp;
                        <!--
                        <a href="cat/mp3_del.php?del=<?php echo $row['id'] . "&file_id=" .  $row['file_id'];   ?>" class='btn-sm btn-danger mt-1'>刪除</a>
            -->
                        <a onclick="return confirm('確定要作廢嗎?')" href="cat/mp3_del.php?del=<?php echo $row['id'] . "&file_id=" .  $row['file_id'];   ?> ?>" class='btn-sm btn-danger mt-1'>刪除</a>
                    </td>

                </tr>
                <div class="modal fade" id="updModal<?php echo $row['id'] ?>">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <!--upd Modal 頭部 -->
                            <div class="modal-header">
                                <h4 class="modal-title"><?php echo $title . '-編修'; ?></h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <form enctype="multipart/form-data" class="form-horizontal" action="#" method="POST" name="edit_Form" id="edit_Form">
                                <div class="modal-body">
                                    <div class="edit-messages"></div>
                                    <input type="hidden" name="id" id="id" value="<?php echo $row['id'] ?>">
                                    <input type="hidden" name="file_idx" id="file_idx" value="<?php echo $row['file_id'] ?>">
                                    <div class="form-group">
                                        <label for="file_namex" class="col-sm-5 control-label">語音檔名*:</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="file_namex" name="file_namex" value="<?php echo $row['file_name'] ?>" required placeholder="文件名稱">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="mp3_idx" class="col-sm-5 control-label">語音類別*:</label>
                                        <div class="col-sm-10">
                                            <select name="mp3_idx" id="mp3_idx" classs="form-control" required style="width:250px;">
                                                <?php
                                                require('./include/config.php');
                                                $cql = "select mp3_id,tp_name from mp3_tp order by mp3_id";
                                                $sthm = $conn->prepare($cql);
                                                $sthm->execute();
                                                while ($rows = $sthm->fetch()) {
                                                    if ($rows['mp3_id'] == $mp3_id) {
                                                        $selected = 'selected="selected"';
                                                    } else {
                                                        $selected = '';
                                                    }
                                                    echo ('<option value="' . $rows['mp3_id'] . '" ' . $selected . '">' . $rows['tp_name'] . '</option>');
                                                } ?>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="mp3_rvx" class="col-sm-5 control-label">預收<span class="required">*</span>:</label>
                                            <select name="mp3_rvx" id="mp3_rvx" required style="width:80px;">
                                                <option value="1" <?php if ($mp3_rv == '1') echo 'selected="selected"' ?>>是</option>
                                                <option value="0" <?php if ($mp3_rv == '0') echo 'selected="selected"' ?>>否</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="mp3_ynx" class="col-sm-5 control-label">成交<span class="required">*</span>:</label>
                                            <select name="mp3_ynx" id="mp3_ynx" required style="width:80px;">
                                                <option value="1" <?php if ($mp3_yn == '1') echo 'selected="selected"' ?>>是</option>
                                                <option value="2" <?php if ($mp3_yn == '2') echo 'selected="selected"' ?>>否</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="mp3_keywordx" class="col-sm-5 control-label">關鍵字<span class="required">*</span>:</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="mp3_keywordx" name="mp3_keywordx" value="<?php echo $row['mp3_keyword'] ?>" required placeholder="關鍵字、說明">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="photo" class="col-sm-5 control-label">上傳檔案:</label>
                                        <div class="col-sm-10">
                                            <!--
                                    <input type="file" required  name="photoA" id="photo_A"  class="form-control-file" >
                                    --> <?php

                                        $sur = "<source src='upload_mp3/";
                                        $sur .=  $row['file_id'];
                                        $sur .= "'type='audio/mpeg>"; ?>
                                            <input type="file" required name="audoA" id="audoA" accept="audio/mp3,audio/wav" value="<?php echo $sur ?>" style="margin-left: 5px;" class="form-control-file" style="width:400px;height:30px;border:2px blue none;">

                                        </div>
                                        <div class="form-group">
                                            <label for="mp3_keyword" class="col-sm-5 control-label">編審人<span class="required">*</span>:</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="author" name="author" value=<?php echo $_SESSION['username']; ?> required placeholder="編審人帳號">
                                            </div>
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
            <?php } ?>
        </tbody>
    </table>
</div>
</div>
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
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, "All"]
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
                targets: [8, 9],
                orderable: false,
                targets: [8, 9],
                searchable: false
            }]
        });
    });
</script>
</body>

</html>