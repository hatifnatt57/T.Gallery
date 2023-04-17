<?php
require_once('../../pdo.php');
session_start();

# Login
if ($_SESSION['logged_in'] !== 'true') {
  header('Location: ../../admin/login/');
}

# GET
if (!isset($_POST['text'])) {
  $stmt = $pdo->query('SELECT * FROM about WHERE keyfield="text"');
  $textarr = $stmt->fetch(PDO::FETCH_ASSOC);
  if (count($textarr) > 0) {
    $textval = $textarr['val'];
  }
  else {
    $textval = '';
  }
}

# POST
if (isset($_POST['text'])) {
  $query = "UPDATE about SET val=? WHERE keyfield='text'";
  $pdo->prepare($query)->execute([$_POST['text']]);
  # Image add/change
  if ($_FILES['image']['name'] !== '') {
    clearstatcache();
    if (file_exists('../../assets/imgs/about.jpg')) {
      unlink('../../assets/imgs/about.jpg');
    }
    if (file_exists('../../assets/imgs/about.png')) {
      unlink('../../assets/imgs/about.png');
    }

    $image_name = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $format;
    if (strpos($image_name, '.jpg') !== false
    || strpos($image_name, '.jpeg') !== false) $format = 'jpg';
    else $format = 'png';
    $dest = "../../assets/imgs/about.$format";
    move_uploaded_file($image_tmp_name, $dest);

    $query = "UPDATE about SET val=? WHERE keyfield='format'";
    $pdo->prepare($query)->execute([$format]);
  }

  $_SESSION['success'] = 'Раздел отредактирован!';
  header('Location: ../../admin/');
  exit();
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#393e46">
  <link rel="icon" type="image/jpeg" sizes="32x32" href="../../assets/favicons/favicon-32x32.png">
  <link rel="icon" type="image/jpeg" sizes="16x16" href="../../assets/favicons/favicon-16x16.png">
  <link rel="stylesheet" href="../../assets/css/default.css">
  <link rel="stylesheet" href="../../assets/css/root.css">
  <link rel="stylesheet" href="../../assets/css/fonts.css">
  <link rel="stylesheet" href="../../assets/css/about_edit.css">
  <title>Редактирование раздела &laquo;Обо мне&raquo;</title>
</head>
<body>
  <a href="../../admin/" class="back-link">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M512 256A256 256 0 1 0 0 256a256 256 0 1 0 512 0zM231 127c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-71 71L376 232c13.3 0 24 10.7 24 24s-10.7 24-24 24l-182.1 0 71 71c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0L119 273c-9.4-9.4-9.4-24.6 0-33.9L231 127z"/></svg>
  </a>
  <h2>Редактирование раздела &laquo;Обо мне&raquo;</h2>
  <div class="container">
    <form action="./" method="post" enctype="multipart/form-data" spellcheck="false">
      <button type="button" class="change-image-btn">Заменить изображение</button>
      <label for="image" class="image-input">Файл изображения:</label>
      <input type="file" name="image" id="image" accept="image/jpeg, image/png" class="image-input">
      <label for="text">Текст:</label>
      <textarea name="text" id="text"><?= $textval ?></textarea>
      <div class="buttons-low">
        <button type="submit">Принять</button>
        <button type="button" class="legend-btn">Легенда</button>
      </div>
    </form>
  </div>
  <div class="legend-overlay">
    <p>*<em>текст курсивом</em>*</p>
    <p>**<strong>жирный текст</strong>**</p>
    <p>***<strong><em>текст жирным курсивом</em></strong>***</p>
    <p>[текст ссылки](url "подсказка")</p>
    <p>Например: Для перехода в гугл нажмите [сюда](https://google.com/ "Это гугл")</p>
    <p>Параграфы отделять двумя энтерами</p>
    <button type="button" class="close-overlay-btn">ОК</button>
  </div>
  <script src="../../assets/js/changeimageui.js" defer></script>
  <script src="../../assets/js/adminabout.js" defer></script>
</body>
</html>