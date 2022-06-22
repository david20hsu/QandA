<?php
session_start();
if (!isset($_SESSION["username"])) {
  header("Location: index.php");
  exit();
}
$title = "嘉南羊乳-問題與解答(修改)";
require_once("html/header.html");
require_once("html/menu.html");
$ym = date('Y-m-d');
$id = "";
$title = "";
$cat_id = "";
$message = "";
$username = "";
$status = "";
?>
<?php
if (isset($_POST["edit_submit"])) {
  require_once "./include/config.php";
  $id = $_POST['id'];
  $title = $_POST['title'];
  $cat_id = $_POST['cat_id'];
  $message = $_POST['message'];
  $status = $_POST['status'];
  $username = $_SESSION['username'];
  $data = [
    'title' => $title,
    "cat_id" => $cat_id,
    'message' => $message,
    'status' => $status,
    'username' => $username,
    'id' => $id
  ];
  $conn->query("set names utf8mb4");
  $cql = "UPDATE `qna_posts` SET `title`=:title,`cat_id`=:cat_id,`message`=:message,`status`=:status,`username`=:username where `id`=:id";
  $stmt = $conn->prepare($cql);
  $stmt->execute($data);
  //  $conn=null;
  unset($_POST["edit_submit"]);
}
if (isset($_GET['edit_id']) && $_GET['edit_id'] <> '') {
  $id = $_GET['edit_id'];
  if ($id == "") {
    $id = $id;
  }
  require_once "./include/config.php";
  $sql = "SELECT * FROM `qna_posts` WHERE id = :id";
  $sth = $conn->prepare($sql);
  $sth->execute(array(':id' => $id));
  $rows = $sth->rowCount();
  $row = $sth->fetch();
  if ($rows > 0) {
    $title = $row['title'];
    $cat_id = $row['cat_id'];
    $message = $row['message'];
    $status = $row['status'];
    $username = $row['username'];
  }
  unset($_GET['$edit_id']);
}
?>
<style>
  .required {
    color: red;
  }
</style>
<script src="tinymce/js/tinymce/tinymce.min.js"></script>
<form method="post" action='#'>
  <?php
  if ($id <> '') { ?>
    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>" ?>
  <?php } ?>
  <label class="btn-sm btn-primary">問題描述<span class="required">*</span>:</label>
  <input type="text" name="title" id="title" size="80" value="<?php echo $title; ?>" maxlength="200" required>
  <div class="row">
    <div class="col-4">
      <label class="btn-sm btn-primary">問題類別<span class="required">*</span>:</label>
      <select id="cat_id" name="cat_id" required>
        <?php
        require('./include/config.php');
        $sql = " SELECT cat_id,category FROM qna_category";
        $sth = $conn->prepare($sql);
        $sth->execute();
        while ($row = $sth->fetch()) {
          if ($row['cat_id'] == $cat_id) {
            $selected = 'selected="selected"';
          } else {
            $selected = '';
          }
          echo ('<option value="' . $row['cat_id'] . '" ' . $selected . '">' . $row['category'] . '</option>');
          //  echo '<option value="' . $row["cat_id"] . '">' . $row["category"] . '</option>' . "\n";
        }
        ?>
      </select>
    </div>
    <div class="col-4">
      <label class="btn-sm btn-primary">發行審查<span class="required">*</span>:</label>
      <select id="status" name="status" required>
        <?php
        require('./include/config.php');
        $sql = " SELECT COLUMN_TYPE as AllPossibleEnumValues
         FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'qna_posts'  AND COLUMN_NAME = 'status'";
        $sth = $conn->prepare($sql);
        $sth->execute();
        while ($row = $sth->fetch()) {
          preg_match('/enum\((.*)\)$/', $row[0], $matches);
          $vals = explode(",", $matches[1]);
          foreach ($vals as $val) {
            $val = substr($val, 1);
            $val = rtrim($val, "'");
            if ($val == $status) {
              echo '<option value="' . $val . '" selected="selected">' . $val . '</option>';
            } else
              echo '<option value="' . $val . '">' . $val . '</option>';
          }
        }
        ?>
      </select>
    </div>
    <div class="col-4">
      <input type="submit" class="btn-sm btn-success mt-1" name="edit_submit" value="編修"> &emsp;
      <a href="index.php" class="btn-sm btn-secondary mt-1">返回</a>
    </div>
  </div>
  <label class="btn-sm btn-primary">解答內容<span class="required">*</span>:</label>
  <textarea id="mytextarea" name='message' reauired><?php echo $message; ?></textarea>
</form>
<?php
require("./html/endcnd.html");
require("./html/footer.html");
?>
<script>
  tinymce.init({
    selector: '#mytextarea',
    language: "zh_TW",
    theme: "modern",
    height: 400,
    width: '100%',

    plugins: ["textcolor colorpicker advlist autolink link image lists charmap print preview hr anchor pagebreak searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking table contextmenu directionality emoticons paste code"],
    toolbar: "undo redo | fontsizeselect | fontselect | bold italic underline forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link unlink anchor | image media | print preview code | caption",

    image_caption: true,
    image_advtab: true,

    image_title: true,
    // 啟用由blob或數據URI表示的圖像的自動上傳
    automatic_uploads: true,
    // 僅將自定義文件選擇器添加到“圖像”對話框
    file_picker_types: 'image',
    file_picker_callback: function(cb, value, meta) {
      var input = document.createElement('input');
      input.setAttribute('type', 'file');
      input.setAttribute('accept', 'image/*');

      input.onchange = function() {
        var file = this.files[0];
        var reader = new FileReader();

        reader.onload = function() {
          var id = 'blobid' + (new Date()).getTime();
          var blobCache = tinymce.activeEditor.editorUpload.blobCache;
          var base64 = reader.result.split(',')[1];
          var blobInfo = blobCache.create(id, file, base64);
          blobCache.add(blobInfo);

          // 調用回調並使用文件名填充“標題”字段
          cb(blobInfo.blobUri(), {
            title: file.name
          });
        };
        reader.readAsDataURL(file);
      };
      //https://www.youtube.com/watch?v=TO_HKd-xNCY
      input.click();
    }
  });
</script>
</body>

</html>