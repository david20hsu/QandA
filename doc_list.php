<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}
ini_set('upload_max_filesize', '20M');
$title = "嘉南羊乳-文件資料";
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
    $extension = array("doc", "docx", "ppt", "pptx", "jpeg", "jpg", "png", "gif", "pdf");
    $name = $_FILES['photo']['name'];
    $size = $_FILES['photo']['size'];
    $type = $_FILES['photo']['type'];
    $temp = $_FILES['photo']['tmp_name'];
    $username = $_POST['author'];
    $doc_id = $_POST['doc_id'];
    $file_name = $_POST['file_name'];
    $doc_keyword = $_POST['doc_keyword'];
    $ext = substr($name, strrpos($name, '.') + 1);
    if (!in_array(strtolower($ext), $extension)) {
        $_SESSION['error'] = '檔案格式不符合:' . $ext;
    } else {
        $xfile = $doc_id . date('Ymd') . date('H') . date('i') . date('s') . '.' . $ext;
        //move_uploaded_file($temp, "upload/" . $name);
        move_uploaded_file($temp, "upload_doc/" . $xfile);
        $data = array();
        $data = [
            "file_id" => $xfile,
            "file_name" => trim($_POST['file_name']),
            "doc_id" => trim($_POST['doc_id']),
            "doc_keyword" => trim($_POST['doc_keyword']),
            "username" => $username
        ];
        $cql = "INSERT INTO doc_file (file_id,file_name,doc_id,doc_keyword,username) ";
        $cql = $cql . " VALUES(:file_id,:file_name,:doc_id,:doc_keyword,:username)";
        $stmt = $conn->prepare($cql);
        $stmt->execute($data);
    }
}
// 編修
//echo ‘<script language=”JavaScript”>;alert(“這是”;location.href=”index.htm”;</script>;’;
//ALTER TABLE `doc_file` ADD COLUMN `updated` datetime NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '編修時間' ;
if (isset($_POST["editsubmit"])) {
    require_once "./include/config.php";
    $doc_id = $_POST['doc_idx'];
    $file_id = $_POST['file_idx'];
    $extension = array("doc", "docx", "ppt", "pptx", "jpeg", "jpg", "png", "gif", "pdf");
    $name = $_FILES['photoA']['name'];
    $size = $_FILES['photoA']['size'];
    $type = $_FILES['photoA']['type'];
    $temp = $_FILES['photoA']['tmp_name'];
    $ext = substr($name, strrpos($name, '.') + 1);
    $xfile = "";
    if (!in_array(strtolower($ext), $extension)) {
        echo "<script>alert('檔案格式不符合')</script>";
        echo '<meta http-equiv=REFRESH CONTENT=1;url=./doc_list.php>';
        exit();
    } else {
        if ($_POST['file_idx'] <> '') {
            $dir = './upload_doc/' . $file_id;
            if (file_exists($dir)) {
                unlink($dir);
            }
        }
        $xfile = $doc_id . date('Ymd') . date('H') . date('i') . date('s') . '.' . $ext;
        move_uploaded_file($temp, "upload_doc/" . $xfile);
    }
    $doc_keyword = $_POST['doc_keywordx'];
    $file_name = $_POST['file_namex'];
    $username = $_SESSION['username'];
    $id = $_POST['id'];
    $data = [
        'file_id' => $xfile,
        'file_name' => $file_name,
        'doc_keyword' => $doc_keyword,
        'doc_id' => $doc_id,
        'username' => $username,
        'id' => $id
    ];

    $cql = "UPDATE `doc_file` SET `file_id`=:file_id,`file_name`=:file_name,`doc_keyword`=:doc_keyword,`doc_id`=:doc_id,`username`=:username where `id`=:id";
    //$cql = "UPDATE `doc_file` SET `file_id`=:file_id,`file_name`=:file_name,`doc_keyword`=:doc_keyword where `id`=:id";
    //print_r($cql);
    //exit(); 
    $stmt = $conn->prepare($cql);
    $stmt->execute($data);
    $conn = null;
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
</style>
<div class="container-fluid">
    <div class="row">
        <?php
        if (isset($_SESSION['error'])) {
            echo
            "
					<div class='alert alert-danger text-center'>
						<button class='close'>&times;</button>
						" . $_SESSION['error'] . "
					</div>
					";
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo
            "
					<div class='alert alert-success text-center'>
						<button class='close'>&times;</button>
						" . $_SESSION['success'] . "
					</div>
					";
            unset($_SESSION['success']);
        }
        ?>
    </div>
    <div class="row" id="toprow">
        <div class="card bg-secondary text-white" style="width: 100%; margin-top:0px;">
            <h5 style="text-align:center;">本文件包含和民宅食有限公司機密資訊及個人資料，任何人不得任意傳佈或揭露，以共同善盡資訊安全與個資保護責任</h5>
            <form enctype="multipart/form-data" action="" method="post">
                <table style="width:60%; margin-left: 5px;">
                    <tr>
                        <td style="width:10%; text-align:right;">文件名稱<span class="required">*</span>:</td>
                        <td>
                            <input type="text" name="file_name" id="file_name" style="margin-left: 5px;" classs="form-control" required placeholder="文件名稱">
                        </td>
                        <td style="width:10%; text-align:right;">文件類別<span class="required">*</span>:</td>
                        <td>
                            <select name="doc_id" id=" doc_id" style="margin-left: 5px;" classs="form-control" required style="width:250px;">
                                <?php
                                require('./include/config.php');
                                $cql = "select doc_id,doc_name from doc_type order by doc_id";
                                $sthm = $conn->prepare($cql);
                                $sthm->execute();
                                while ($rows = $sthm->fetch()) { ?>
                                    <option value="<?php echo $rows['doc_id']; ?>"><?php echo $rows['doc_name']; ?></option>
                                <?php }   ?>
                            </select>
                        </td>
                        <!--
                    本文件包含萬凌工業股份有限公司機密資訊及個人資料，任何人不得任意傳佈或揭露，以共同善盡資訊安全與個資保護責任
                             -->
                    </tr>
                    <tr>
                        <td style="width:10%; text-align:right;">關鍵字</td>
                        <td colspan="3">
                            <input type="text" name="doc_keyword" id="doc_keyword" style="margin-left: 5px;" placeholder="文件查詢關鍵字" required>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:10%; text-align:right;">文件編審<span class="required">*</span>:</td>
                        <td>
                            <input type="text" name="author" id="author" style="margin-left: 5px;" placeholder="文件編審" required value=<?php echo $_SESSION['username']; ?>>
                        </td>
                        <td style="width:10%; text-align:right;">
                            上傳檔案<span class="required">*</span>:
                        </td>
                        <td>
                            <input type="file" name="photo" id="photo" style="margin-left: 5px;" class="form-control-file" style="width:400px;height:30px;border:2px blue none;" required>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:10%; text-align:right;">
                            <input type="submit" name="submit" style="height:25px;border:2px blue none;background-color:pink;left:30px;" value="送出表單">
                        </td>
                        <td>
                        <td style="width:10%; text-align:right;">
                            <font color="Yellow">檔案格式</font>
                        </td>
                        <td style="margin-left: 5px;">

                            <font color="white">pdf,doc,docx,ppt,pptx,jpeg,jpg,png,gif
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <hr>

        <div class="col-8">
            <h3 align='center'><?php echo $title; ?></h3>
        </div>
        <!--
  <div class="col-4">
       <a href="index.php" class="btn btn-secondary btn-sm mt-1">返回</a>
</div>
                             -->
    </div>
    <table id="myDataTalbe" class="table table-bordered table-striped table-hove" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>文件名稱</th>
                <th>文件類別</th>
                <th>搜尋關鍵字</th>
                <th>維護人</th>
                <th>維護日期</th>
                <th>操作</th>
                <!--
            <th>檢視</th>
            <th>下載</th>
            <th>刪除</th>
            -->
            </tr>
        </thead>
        <tbody>
            <?php
            require('./include/config.php');
            $sql = "select a.*,b.doc_name from doc_file a left join doc_type b using(doc_id) ";
            $sth = $conn->prepare($sql);
            $sth->execute();
            while ($row = $sth->fetch()) {
                $id = $row['id'];
                $doc_id = $row['doc_id'];
            ?>
                <tr>
                    <td><?php echo $row['id'] ?></td>
                    <td><?php echo $row['file_name'] ?></td>
                    <td><?php echo $row['doc_name'] ?></td>
                    <td><?php echo $row['doc_keyword'] ?></td>
                    <td><?php echo $row['username'] ?></td>
                    <td><?php echo substr($row['updated'], 0, 10) ?></td>
                    <td><a href="upload_doc/<?php echo $row['file_id']; ?>" target="_blank" class='btn-sm btn-primary mt-1'>檢視</a>&nbsp;
                        <a href="upload_doc/<?php echo $row['file_id']; ?>" download class='btn-sm btn-info mt-1'>下載</a>&nbsp;

                        <a href="#updModal<?php echo $row['id']; ?>" data-toggle="modal" class="btn-sm btn-info mt-1">編修</a>&nbsp;
                        <a href="cat/doc_del.php?del=<?php echo $row['id'] . "&file_id=" .  $row['file_id'];   ?>" class='btn-sm btn-danger mt-1'>刪除</a>
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
                                        <label for="file_namex" class="col-sm-5 control-label">文件名稱*:</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="file_namex" name="file_namex" value="<?php echo $row['file_name'] ?>" required placeholder="文件名稱">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="file_namex" class="col-sm-5 control-label">文件類別*:</label>
                                        <div class="col-sm-10">
                                            <select name="doc_idx" id=" doc_idx" classs="form-control" required style="width:250px;">
                                                <?php
                                                require('./include/config.php');
                                                $cql = "select doc_id,doc_name from doc_type order by doc_id";
                                                $sthm = $conn->prepare($cql);
                                                $sthm->execute();
                                                while ($rows = $sthm->fetch()) {
                                                    if ($rows['doc_id'] == $doc_id) {
                                                        $selected = 'selected="selected"';
                                                    } else {
                                                        $selected = '';
                                                    }
                                                    echo ('<option value="' . $rows['doc_id'] . '" ' . $selected . '">' . $rows['doc_name'] . '</option>');
                                                } ?>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="doc_keywordx" class="col-sm-5 control-label">關鍵字:</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="doc_keywordx" name="doc_keywordx" value="<?php echo $row['doc_keyword'] ?>" required placeholder="關鍵字">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="photo" class="col-sm-5 control-label">上傳檔案:</label>
                                        <div class="col-sm-10">
                                            <!--
                                    <input type="file" required  name="photoA" id="photo_A"  class="form-control-file" >
                                    -->
                                            <input type="file" required name="photoA" id="photo_A" style="margin-left: 5px;" class="form-control-file" style="width:400px;height:30px;border:2px blue none;">

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