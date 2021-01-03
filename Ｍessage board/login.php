<?php
  require_once("conn.php");
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
      <a class="header__link" href="index.php"><i class="fa fa-angle-double-left" aria-hidden="true"></i>BACK</a>
      <a class="header__link" href="register.php"><i class="fa fa fa-user" aria-hidden="true"></i>Sign Up</a>
    </div>
  </header>
  <main class="board__register">
    <form class="board__register-form" method="POST" action="handle_login.php">
      <h1 class="signup-title">Log in</h1>
      <div class="board__username">
        <span>Username：</span>
        <input type="text" name="username" placeholder="Your username"/>
      </div>
      <div class="board__password">
        <span>Password：</span>
        <input type="password" name="password" placeholder="Your password"/>
      </div>
        <button class="signup__submit-btn" type="submit">SUBMIT</button>
      <?php
      if (!empty($_GET['errCode'])) {
        $code = $_GET['errCode'];
        $msg = 'Error';
        if ($code === '1') {
          $msg = '資料不齊全';
        } else if ($code === '2') {
          $msg = '帳號或密碼錯誤';
        }
        echo '<h2 class="error">錯誤：' . $msg . '</h2>';
      }
      ?>
    </form>
    <p class="notice">注意！此為練習用網站，</br>註冊請勿使用真實帳號或密碼</p>
  </main>
</body>
</html>