<?php
  session_start();
  require_once("conn.php");
  require_once("utils.php");

  $id = $_GET['id']; // 從 query string 拿
  $user = NULL;
  $username = NULL;

  /*先檢查是否有值在進行讀取 */
  if(!empty($_SESSION['username'])) {
      /* cookie 取 PHPSESSIONIS(token) -> 檔案內讀取 session id 內容 -> 放到$_SESSION */
    $username = $_SESSION['username'];
    $user = getUserFromUsername($username);
  };

  $stmt = $conn->prepare(
    'select * from kayla_comments where id = ?'
  );
  $stmt->bind_param('i',$id);
  $result = $stmt->execute(); // 執行stmt query
  if (!$result) {
    die('Error:' . $conn->error);
  }
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Unna&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
  <title>Message Board</title>
</head>
<body>
  <header class="board__header">
    <a class="header__title" href="#">Message<br>Board</a>
    <div class="header__button">
      <!-- 非登入狀態按鈕顯示:顯示註冊/登入按鈕 -->
      <?php if (!$username) { ?>
        <a class="header__link" href="login.php"><i class="fa fa-sign-in" aria-hidden="true"></i>Log in</a>
        <a class="header__link" href="register.php"><i class="fa fa fa-user" aria-hidden="true"></i>Sign Up</a>
      <?php } else { ?>
        <!-- 登入狀態按鈕顯示：只顯示登出按鈕 -->
        <a class="header__link" href="logout.php"><i class="fas fa-outdent" aria-hidden="true"></i>Log out</a>
      <?php } ?>
    </div>
  </header>
  <main class="board">
  
    <!-- 修改暱稱 -->
    <form class="hide board__nickname-form board__new-comment-form" method="POST" action="handle_update_user.php">
      <div class="board__nickname">
        <span>New Nickname：</span>
        <input  class="board__change-nickname" type="text" name="nickname" />
      </div>
      <input class="board__submit-btn" type="submit" value="Submit" />
     </form>

    <h1 class="board__title"><span>Comments</span></h1>

    <!-- 資料錯誤顯示 -->
    <?php if (!empty($_GET['errCode'])) {
      $code = $_GET['errCode'];
      $message = 'Error';
      if ($code === '1') { //$_GET 拿到會是字串
        $message = '資料不齊全，請重新輸入！';
      } 
      echo'<span class="error">' . $message . '</span>';
    }
    ?>
    <form class="board__new-comment-form" method="POST" action="handle_update_comment.php">
      </div>
      <!-- 拿到未修改留言＆顯示 -->
      <textarea name="content" rows="10"><?php echo $row['content'] ?></textarea>
      <!-- 傳送 id 回 sever，type hidden 不讓使用者看到 -->
      <input name="id" type="hidden" value="<?php echo $row['id']?>">
      <input class="board__submit-btn" type="submit" value="Submit" />
    </form>
  </main>
</body>
</html>