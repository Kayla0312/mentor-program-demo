<?php
  session_start();
  require_once('conn.php');
  require_once('utils.php');
  //如果欄位是空值，header 帶到 errCode=1，再由index.php 顯示錯誤提示
  if (
    empty($_POST['content'])
  ) {
    header('Location: index.php?errCode=1');
    die('資料不齊全');
  }
 
  $username = $_SESSION['username'];
  $content = $_POST['content'];

  // Fix SQL injection
  $sql ="insert into kayla_comments(username, content) values(?,?)"; // 要帶入的值用？表示
  $stmt = $conn->prepare($sql);// 把 sql 傳進給 prepare
  $stmt->bind_param('ss', $username, $content); //傳入參數，SS代表兩個參數，後面接要帶入的參數
  $result = $stmt->execute(); // 執行stmt query

  if (!$result) {
    die($conn->error); //如果 query 錯誤是空的，顯示碰到的錯誤
  }
  header("Location: index.php");
?>
