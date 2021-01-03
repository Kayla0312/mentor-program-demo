<?php
  session_start();
  session_destroy();  //清除
  header("Location: index.php");
?>
