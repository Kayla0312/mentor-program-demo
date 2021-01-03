<?php
  session_start();
  require_once("conn.php");
  require_once("utils.php");

  $user = NULL;
  $username = NULL;

  /* 先檢查是否有值在進行讀取 */
  if(!empty($_SESSION['username'])) {
      /* cookie 取 PHPSESSIONIS(token) -> 檔案內讀取 session id 內容 -> 放到$_SESSION */
    $username = $_SESSION['username'];
    $user = getUserFromUsername($username);
  };

  /* page setting */
  $page = 1; // 預設第一頁
  $per_page = 5; // 每頁五筆留言  
  $offset = ($page-1) * $per_page; 
  

  if (!empty($_GET['page'])) {
    $page = intval($_GET['page']);
  }
  
  $stmt = $conn->prepare(
    'select '.
      'C.id as id, C.content as content, '.
      'C.created_at as created_at, U.nickname as nickname, U.username as username '.
    'from kayla_comments as C ' .
    'left join kayla_users as U on C.username = U.username '.
    'where C.is_deleted IS NULL '.
    'order by C.id desc ' .
    'limit ? offset ? '
  );
  $stmt->bind_param('ii', $per_page, $offset);
  $result = $stmt->execute();
  if (!$result) {
    die('Error:' . $conn->error);
  }
  $result = $stmt->get_result();
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
        <a class="header__link  update-nickname"><i class="fas fa-edit"></i>Edit Nickname</a>
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
    <form class="board__new-comment-form" method="POST" action="handle_add_comment.php">
      </div>
      <textarea name="content" rows="10" placeholder="Leave your message here......"></textarea>
      <!-- 如果不是登入狀態，取消 submite 按鈕 -->
      <?php if ($username) { ?>
        <input class="board__submit-btn" type="submit" value="Submit" />
      <?php } else { ?>
        <h2>Log in and leave your message here......</h2>
      <?php } ?>
    </form>
    <section class="message__board">
      <?php
        while($row = $result->fetch_assoc()) {
      ?>
      <div class="card">
        <div class="card__avatar"></div>
        <div class="card__body">
            <div class="card__info">
              <span class="card__author"><?php echo escape($row['nickname']); ?></span>
              <span class="card__time"><?php echo $row['created_at']; ?></span>
              <!-- user 和留言者相同時，才出現編輯 -->
              <?php if($username === $row['username'] ) {?>
                <a href="update_comment.php?id=<?php echo $row['id']?>"><i class="fas fa-edit"></i></a>
                <a href="handle_delete_comment.php?id=<?php echo $row['id']?>"><i class="far fa-trash-alt"></i></a>
              <? }?>
              <p class="card__content"><?php echo escape($row['content']); ?></p>
            </div>
        </div>
      </div>
      <hr>
      <?php } ?>
    </section>
    <!-- page  -->
    <?php
      $stmt = $conn->prepare(
        'select count(id) as count from kayla_comments where is_deleted IS NULL'
      );
      $result = $stmt->execute(); 
      $result = $stmt->get_result();
      $row = $result->fetch_assoc();
      $count = $row['count']; // 總共幾筆留言
      $total_page = ceil($count / $per_page); // 總頁數，ceil 取整數
    ?>
    <div class="page__info">
      <span>Total：<?php echo $count ?> Ｍessages</span>
      <span>Pages：<?php echo $page ?> / <?php echo $total_page ?></span>
    </div>
    <div class="page__display"> 
      <!-- page1 -->
      <?php if ($page != 1) { ?>
        <a class="page__link" href="index.php?page=1"><i class="fas fa-arrow-to-left"></i>First Page</a>
        <a class="page__link" href="index.php?page=<?php echo $page-1?>"><i class="fas fa-chevron-left"></i>Previous Page</a>
      <?php }?>
      <?php if ($page != $total_page) { ?>
        <a class="page__link" href="index.php?page=<?php echo $page+1?>">Next Page<i class="fas fa-chevron-right"></i></a>
        <a class="page__link" href="index.php?pag=<?php echo $total_page?>">Last Page<i class="fas fa-arrow-to-right"></i></a>
      <?php }?>
    </div>


  </main>
  <script>
    // update nickname toggle
    const btn = document.querySelector('.update-nickname')
    btn.addEventListener('click', function() {
      const form = document.querySelector('.board__nickname-form')
      form.classList.toggle('hide')
    })
  </script>
</body>
</html>