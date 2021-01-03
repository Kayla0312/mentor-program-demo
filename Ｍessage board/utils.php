<?php
  require_once('conn.php');
  // 產生 token 
  function generateToken() {
    $s = '';
    for($i=1; $i<=16; $i++) {
      $s .= chr(rand(65,90));
    }
    return $s;
  }
  
  function getUserFromUsername($username) {
    global $conn; //在 function 中使用變數要加上 global
    // 拿到 username
    $sql = sprintf(
      "select * from kayla_users where username = '%s'",
      $username
    );
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $username = $row['username'];

    // 從 table user 中查詢，再把表格中資料取出
    $sql = sprintf(
      "select * from kayla_users where username = '%s'",
      $username
    );
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row; // username, id, nickname
  }

  //防止 XSS 
  function escape($str) {
    return htmlspecialchars($str, ENT_QUOTES);
  };
?> 
