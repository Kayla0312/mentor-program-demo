<?php
  session_start();
  require_once('conn.php');
  require_once('utils.php');
  //如果欄位是空值，header 帶到 errCode=1，再由index.php 顯示錯誤提示
  if (
    empty($_POST['username']) ||
    empty($_POST['password'])
  ) {
    header('Location: login.php?errCode=1');
    die('資料不齊全');
  }

  $password = $_POST['password'];
  $username = $_POST['username'];

  $sql = "select * from kayla_users where username = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $username); 
  $result = $stmt->execute(); 


  if (!$result) {
    die($conn->error); // 如果 query 錯誤是空的，顯示碰到的錯誤
  }

  //  需加上get_result 才能拿回結果
  $result = $stmt->get_result();

  //num_row 判斷結果有幾筆資料，如果沒有資料（檢查是否有查到 user）
  if ($result->num_rows === 0) { 
    header("Location: login.php?errCode=2");
    exit();
  }
  // 查到有使用者
  $row = $result->fetch_assoc();
  // 如果輸入的 password 和經過 hash password 相同
  if(password_verify ( $password , $row['password'])){
    // 設定 session (產生 session id(token) -> 把 username 寫入檔案 -> set-cookie:session id)
    $_SESSION['username'] = $username;
    header("Location: index.php");
  } else {
    header("Location: login.php?errCode=2");
  }
?>
