<?php
  session_start();
  require_once('conn.php');
  require_once('utils.php');
  // 空值錯誤處理
  if (
    empty($_GET['id'])
  ) {
    header('Location: index.php?errCode=1');
    die('資料不齊全');
  }
  $id = $_GET['id'];


  // Fix SQL injection ＋ soft delete
  $sql ="update kayla_comments set is_deleted=1 where id=?"; // 把要刪除的資料設成  is_delete=1
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('i', $id);
  $result = $stmt->execute();

  if (!$result) {
    die($conn->error); 
  }
  header("Location: index.php");
?>
