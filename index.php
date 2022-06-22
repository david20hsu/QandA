<?php
session_start();
$role = "";
$username = "";
if (isset($_POST['login'])) {
    require("./include/config.php");
    if ($_POST['acode'] == $_POST['xcode']) {
        $username = addslashes($_POST['username']); // 處理sql injection
        $userpass = md5(addslashes($_POST['userpass'])); // 處理sql injection
        $sql = "SELECT * FROM `qna_user0` WHERE `username`=? AND `userpass`=? ";
        //print_r($username);
        // exit();
        $query = $conn->prepare($sql);
        $query->execute(array($username, $userpass));
        $row = $query->rowCount();
        $fetch = $query->fetch();
        if ($row > 0) {
            $_SESSION['username'] = $fetch['username'];
            $_SESSION['role'] = $fetch['user_role'];
            $role = $fetch['user_role'];
            $username = $fetch['username'];
            $visited = $fetch['visited'];
            $data = [
                'visited' => (int)$visited + 1,
                "username" => $username,
                "userpass" => $userpass
            ];
            $cql = "UPDATE `qna_user0` SET `visited`=:visited where `username`=:username and `userpass`=:userpass";

            $stmt = $conn->prepare($cql);
            $stmt->execute($data);
            header("location:index.php");
        } else {
            echo "<script>alert('帳號 或 密碼 不正確')</script>";
        }
    } else {
        echo "<script>alert('請正確輸入 驗證碼  !')</script>";
    }
}
$title = "嘉南羊乳-問題找解答";
require_once("html/header.html");
require_once("html/menu.html");
//https://hackmd.io/@chupai/BksFwgY3E?type=view#2-4-%E5%88%97-row 說明 bootstrap 4 的參考
?>
<style>
    table {
        margin-top: auto;
        margin-left: auto;
        margin-right: auto;
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
    <div class="row" id="toprow">
        <div class="col-2">
        </div>
        <div class="col-6">
            <h3 align='center'><?php echo $title; ?></h3>
        </div>
        <div class="col-4">
            <?php
            if ((isset($_SESSION["role"]) && ($_SESSION["role"] == '寫手' || $_SESSION["role"] == '審查員' || $_SESSION["role"] == '管理員'))) { ?>
                <a href="posts_add.php" class="btn btn-info btn-sm mt-1">新增</a>
            <?php } ?>
            &nbsp;
            <!--
        <a href="index.php" class="btn btn-secondary btn-sm mt-1">返回</a>
         -->
        </div>
    </div>

    <table id="myDataTalbe" class="table table-bordered table-striped table-hove" style="width:100%">
        <thead>
            <tr>
                <th>類別</th>
                <th>問題內容</th>
                <th>點閱</th>
                <th>狀態</th>
                <th>維護日期</th>
                <th>檢視</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require('./include/config.php');
            $sql = "select a.*,b.category from qna_posts a left join qna_category b using(cat_id) where status='發佈' order by a.hits desc";
            if ((isset($_SESSION["role"]) && ($_SESSION["role"] == '寫手' || $_SESSION["role"] == '審查員' || $_SESSION["role"] == '管理員'))) {
                $sql = "select a.*,b.category from qna_posts a left join qna_category b using(cat_id) order by a.hits desc";
            }
            $sth = $conn->prepare($sql);
            $sth->execute();
            while ($row = $sth->fetch()) {
                $id = $row['id'];
                $category = $row['category'];
                $title = $row['title'];
                $hits = $row['hits'];
                $updated = substr($row['updated'], 0, 10);
            ?>
                <tr>
                    <td><?php echo $row['category'] ?></td>
                    <td><?php echo $row['title'] ?></td>
                    <td style="text-align: right"><?php echo number_format($row['hits']) ?></td>
                    <td><?php echo $row['status'] ?></td>
                    <td><?php echo $updated ?></td>
                    <td><a href="qna_posts-read.php?id=<?php echo $row['id']; ?>" class='btn-sm btn-primary mt-1'>檢視</a>
                        <?php
                        if ((isset($_SESSION["role"]) && ($_SESSION["role"] == '寫手' || $_SESSION["role"] == '審查員' || $_SESSION["role"] == '管理員'))) {
                        ?>
                            <a href="posts_edit.php?edit_id=<?php echo $row['id']; ?>" class="btn-sm btn-success mt-1">編修</a>
                            <?php if ($row['status'] <> '封存') { ?>
                                <a onclick="return confirm('確定要封存嗎 ?')" href="cat/posts_end.php?id=<?php echo  $id; ?>" class='btn-sm btn-warning mt-1'>封存</a>
                            <?php } ?>
                            <?php if ($row['status'] = '草案' && $row['status'] <> '封存') { ?>
                                <a onclick="return confirm('確定要發佈嗎 ?')" href="cat/posts_cfm.php?id=<?php echo  $id; ?>" class='btn-sm btn-primary mt-1'>發佈</a>
                            <?php } ?>
                            <a onclick="return confirm('確定要刪除嗎 ?')" href="cat/posts_del.php?id=<?php echo  $id; ?>" class='btn-sm btn-danger mt-1'>刪除</a>
                        <?php } ?>


                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</div>
<?php
require("html/footer.html");
require("html/endcnd.html");
?>
<script type="text/javascript">
    $(function() {
        $("#myDataTalbe").DataTable({
            //   dom: 'Blfrtip',
            //   responsive: true,
            //   buttons: [
            //        'copy', {
            //           extend: 'csv',
            //           text: 'CSV',
            //            bom : true},'print'
            //         ],

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