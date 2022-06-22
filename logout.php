<?php
session_start();
unset($_SESSION["username"]);
unset($_SESSION["role"]);
echo '<meta http-equiv=REFRESH CONTENT=1;url=index.php>';