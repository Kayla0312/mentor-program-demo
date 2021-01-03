<?php
  session_start();
  require_once('conn.php');
  require_once('utils.php');
  // 空值錯誤處理
  if (
    empty($_POST['content'])
  ) {
    header('Location: update_comment.php?errCode=1&id='.$_POST['id']); 
    die('資料不齊全');
  }
  $id = $_POST['id'];
  $username = $_SESSION['username'];
  $content = $_POST['content'];

  // Fix SQL injection
  $sql ="update kayla_comments set content=? where id =?"; // 更新傳來 id 的 comment
  $stmt = $conn->prepare($sql);// 把 sql 傳進給 prepare
  $stmt->bind_param('si', $content, $id); // i=integer
  $result = $stmt->execute(); // 執行stmt query

  if (!$result) {
    die($conn->error); 
  }
  header("Location: index.php");
?>
