<?php
  session_start();
  require_once('conn.php');
  require_once('utils.php');

  if (
    empty($_POST['nickname'])
  ) {
    header('Location: index.php?errCode=1');
    die('資料不齊全');
  }

  $username = $_SESSION['username'];
  $nickname = $_POST['nickname'];
  $sql = "update kayla_users set nickname=? where username=?"; //更新 users table，更新 nickname，找到 username 欄位
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ss', $nickname, $username);
  $result = $stmt->execute();
  if (!$result) {
    die($conn->error);
  }

  header("Location: index.php");
?>