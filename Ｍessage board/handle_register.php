<?php
  session_start();
  require_once('conn.php');
  //如果欄位是空值，header 帶到 errCode=1，再由index.php 顯示錯誤提示
  if (
    empty($_POST['nickname']) ||
    empty($_POST['username']) ||
    empty($_POST['password'])
  ) {
    header('Location: register.php?errCode=1');
    die('資料不齊全');
  }

  $nickname = $_POST['nickname'];
  $username = $_POST['username'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT); //hash password 處理-> password_hash(值, 預設寫法)

  // Fix SQL injection
  $sql = "insert into kayla_users(nickname, username, password) values(?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('sss', $nickname, $username, $password); 
  $result = $stmt->execute();
  if (!$result) {
    $code = $conn->error;
    if ($code === "1062") {
      header('Location: register.php?errCode=2');
    }
    die($conn->error); 
  }
  
  //當註冊成功->首頁已是登入狀態
  $_SESSION['username'] = $username;
  header("Location: index.php");
?>
