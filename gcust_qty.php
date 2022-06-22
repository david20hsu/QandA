<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}
$title = "嘉南羊乳-團戶近期交易";
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
<div class="container-fluid">
    <div class="row" id="toprow">
        <div class="col-2">
        </div>
        <div class="col-6">
            <h3 align='center'><?php echo $title; ?></h3>
        </div>
    </div>

    <table id="myDataTalbe" class="table table-bordered table-striped table-hove" style="width:100%">
        <thead>
            <tr>
                <th>區號</th>
                <th>簡稱</th>
                <th>業務員</th>
                <th>羊奶單</th>
                <th>牛奶單</th>
                <th>本月羊瓶</th>
                <th>本月牛瓶</th>
                <th>上月羊瓶</th>
                <th>上月牛瓶</th>
                <th>前月羊瓶</th>
                <th>前月牛瓶</th>
                <th>上月金額</th>
                <th>前月金額</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require('./include/config0.php');
            $sql = "CALL gcust_qty()";
            $sth = $conn->query($sql);
            //$sth->execute();
            $sth->setFetchMode(PDO::FETCH_ASSOC);
            while ($row = $sth->fetch()) {
            ?>
                <tr>
                    <td><?php echo $row["marea_id"]; ?> </td>
                    <td><?php echo $row["gcust_names"]; ?> </td>
                    <td><?php echo $row["sales_name"]; ?> </td>
                    <td style="text-align: right"><?php echo number_format($row["aqty"]); ?> </td>
                    <td style="text-align: right"><?php echo number_format($row["bqty"]); ?> </td>
                    <td style="text-align: right"><?php echo $row["aqty0"]; ?> </td>
                    <td style="text-align: right"><?php echo $row["bqty0"]; ?> </td>
                    <td style="text-align: right"><?php echo $row["aqty1"]; ?> </td>
                    <td style="text-align: right"><?php echo $row["bqty1"]; ?> </td>
                    <td style="text-align: right"><?php echo $row["aqty2"]; ?> </td>
                    <td style="text-align: right"><?php echo $row["bqty2"]; ?> </td>
                    <td style="text-align: right"><?php echo number_format($row["ant1"]); ?></td>
                    <td style="text-align: right"><?php echo number_format($row["ant2"]); ?></td>
                </tr>
            <?php  } ?>

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