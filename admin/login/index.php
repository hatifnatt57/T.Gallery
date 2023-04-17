<?php 
session_start();

if (isset($_SESSION['logged_in'])) {
  if ($_SESSION['logged_in'] === 'true') {
    header('Location: ../../admin/');
    exit();
  }
}

require('../../parts/logincredentials.php');

if (isset($_POST['login']) && isset($_POST['password'])) {
  if ($_POST['login'] !== $login || $_POST['password'] !== $password) {
    $_SESSION['error'] = 'Неправильное имя пользователя или пароль';
    header('Location: ../../admin/login/');
    exit();
  }
  else {
    $_SESSION['logged_in'] = 'true';
    header('Location: ../../admin/');
    exit();
  }
};

?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#393e46">
  <link rel="icon" type="image/jpeg" sizes="32x32" href="../../assets/favicons/favicon-32x32.png">
  <link rel="icon" type="image/jpeg" sizes="16x16" href="../../assets/favicons/favicon-16x16.png">
  <link rel="stylesheet" href="../../assets/css/root.css">
  <link rel="stylesheet" href="../../assets/css/default.css">
  <link rel="stylesheet" href="../../assets/css/fonts.css">
  <link rel="stylesheet" href="../../assets/css/login.css">
  <link rel="stylesheet" href="../../assets/css/message.css">
  <script src="../../assets/js/message.js" defer></script>
  <title>Авторизация</title>
</head>
<body>
  <?php
    if (isset($_SESSION['error'])) {
      echo('
      <div class="error">'.$_SESSION['error'].'<button class="close-error">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
        </button>
      </div>
      ');
      unset($_SESSION['error']);
    }
  ?>
  <form action="../../admin/login/" method="post">
    <label for="login">Имя пользователя:</label>
    <input type="text" name="login" id="login">
    <label for="password">Пароль:</label>
    <input type="password" name="password" id="password">
    <button type="submit">Войти</button>
  </form>
</body>
</html>