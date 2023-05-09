<?php
require_once('../pdo.php');
session_start();

if ($_SESSION['logged_in'] !== 'true') {
  header('Location: ../admin/login/');
  exit();
}

$stmt = $pdo->query('SELECT * FROM pics ORDER BY orderint');
$allPics = $stmt->fetchAll(PDO::FETCH_ASSOC);

$grafika = array();
$pastel = array();
$akril = array();

foreach ($allPics as $pic) {
  switch ($pic['category']) {
    case 'Графика':
      array_push($grafika, $pic);
      break;
    case 'Пастель':
      array_push($pastel, $pic);
      break;
    case 'Акрил':
      array_push($akril, $pic);
      break;
  }
}

function notempty($var) {
  return $var !== '';
}

function generateElement($pic) {
  $picmeta = array(
    $pic['title'],
    $pic['year'],
    $pic['technique'],
    $pic['size']
  );
  $first_line = join(' &middot; ', array_filter($picmeta, 'notempty'));
  $second_line = '';
  if ($pic['description'] !== '') {
    $second_line = "<p>{$pic['description']}</p>";
  }
return <<<ELEM
  <li data-id="{$pic['id']}">
    <img src="../assets/icons/{$pic['id']}.{$pic['format']}">
    <div class="caption">
      <p>$first_line</p>
      $second_line
    </div>
    <div class="ui">
      <form action="../admin/edit/" method="get">
        <input type="hidden" name="id" value="{$pic['id']}">
        <button type="submit">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z"/></svg>
        </button>
      </form>
      <form action="../admin/delete/" method="post">
        <input type="hidden" name="id" value="{$pic['id']}">
        <button type="submit">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"/></svg>
        </button>
      </form>
      <button type="button" class="change-order-mode li--ui--change-order-btn--up">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M182.6 137.4c-12.5-12.5-32.8-12.5-45.3 0l-128 128c-9.2 9.2-11.9 22.9-6.9 34.9s16.6 19.8 29.6 19.8H288c12.9 0 24.6-7.8 29.6-19.8s2.2-25.7-6.9-34.9l-128-128z"/></svg>      </button>
      <button type="button" class="change-order-mode li--ui--change-order-btn--down">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M137.4 374.6c12.5 12.5 32.8 12.5 45.3 0l128-128c9.2-9.2 11.9-22.9 6.9-34.9s-16.6-19.8-29.6-19.8L32 192c-12.9 0-24.6 7.8-29.6 19.8s-2.2 25.7 6.9 34.9l128 128z"/></svg>
      </button>
    </div>
  </li>
ELEM;
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#393e46">
  <link rel="icon" type="image/jpeg" sizes="32x32" href="../assets/favicons/favicon-32x32.png">
  <link rel="icon" type="image/jpeg" sizes="16x16" href="../assets/favicons/favicon-16x16.png">
  <link rel="stylesheet" href="../assets/css/default.css">
  <link rel="stylesheet" href="../assets/css/root.css">
  <link rel="stylesheet" href="../assets/css/fonts.css">
  <link rel="stylesheet" href="../assets/css/admin3.css">
  <link rel="stylesheet" href="../assets/css/message.css">
  <script src="../assets/js/message.js" defer></script>
  <title>Администрация | T.Gallery</title>
</head>
<body>
  <?php
    if (isset($_SESSION['success'])) {
      echo('
      <div class="success">'.$_SESSION['success'].'<button class="close-success">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
        </button>
      </div>
      ');
      unset($_SESSION['success']);
    }
  ?>
  <header>
    <div class="menu-container">
      <button type="button" class="menu-btn" title="Другие разделы">
        <svg class="menu-btn--bars" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M0 96C0 78.3 14.3 64 32 64H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 128 0 113.7 0 96zM0 256c0-17.7 14.3-32 32-32H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H416c17.7 0 32 14.3 32 32z"/></svg>
        <svg class="menu-btn--x" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
      </button>
    </div>
    <a href="../admin/add/" class="add-link" title="Добавить запись">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 344V280H168c-13.3 0-24-10.7-24-24s10.7-24 24-24h64V168c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24H280v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"/></svg>
    </a>
    <button type="button" class="change-order-btn" title="Настроить порядок отображения">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M137.4 41.4c12.5-12.5 32.8-12.5 45.3 0l128 128c9.2 9.2 11.9 22.9 6.9 34.9s-16.6 19.8-29.6 19.8H32c-12.9 0-24.6-7.8-29.6-19.8s-2.2-25.7 6.9-34.9l128-128zm0 429.3l-128-128c-9.2-9.2-11.9-22.9-6.9-34.9s16.6-19.8 29.6-19.8H288c12.9 0 24.6 7.8 29.6 19.8s2.2 25.7-6.9 34.9l-128 128c-12.5 12.5-32.8 12.5-45.3 0z"/></svg>
    </button>
    <button type="button" class="change-order-mode header--order-ui-btn header--order-ui--decline" title="Отклонить изменения">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
    </button>
    <button type="button" class="change-order-mode header--order-ui-btn header--order-ui--accept" title="Принять изменения">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg>
    </button>
    <form action="./changeorder/" method="post" class="change-order-form">
      <input type="hidden" name="order">
    </form>
  </header>
  <nav>
    <a href="./home/">
      <div class="nav--item">Главная страница</div>
    </a>
    <a href="./about/">
      <div class="nav--item">Обо мне</div>
    </a>
    <a href="./notgallery/">
      <div class="nav--item">НеГалерея</div>
    </a>
  </nav>
  <?php
    if (count($allPics) === 0) {
      echo '<h3>Записей нет</h3>';
    }
    
    if (count($grafika) !== 0) {
      echo '<h3>Графика</h3>';
      echo '<ul class="ul-grafika">';
      foreach ($grafika as $pic) { echo(generateElement($pic)); }
      echo '</ul>';
    }

    if (count($pastel) !== 0) {
      echo '<h3>Пастель</h3>';
      echo '<ul class="ul-pastel">';
      foreach ($pastel as $pic) { echo(generateElement($pic)); }
      echo '</ul>';
    }

    if (count($akril) !== 0) {
      echo '<h3>Акрил</h3>';
      echo '<ul class="ul-akril">';
      foreach ($akril as $pic) { echo(generateElement($pic)); }
      echo '</ul>';
    }
  ?>
  <script>
    [...document.querySelectorAll('form[action*="delete"]')].forEach(form => {
      form.onsubmit = function() {
        return confirm('Вы точно хотите удалить эту запись?');
      };
    });

    document.querySelector('.menu-btn').addEventListener('click', () => {
      document.querySelector('.menu-btn').classList.toggle('menu-btn__x');
      document.querySelector('nav').classList.toggle('nav__open');
    });
  </script>
  <script src="../assets/js/changeorder.js" defer></script>
</body>
</html>