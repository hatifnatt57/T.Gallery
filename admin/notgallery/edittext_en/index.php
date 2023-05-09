<?php
require_once('../../../pdo.php');
session_start();

# Login
if ($_SESSION['logged_in'] !== 'true') {
  header('Location: ../../login/');
  exit();
}

# GET
if (!isset($_POST['row1'])) {
  $stmt = $pdo->query("SELECT val FROM keyval WHERE keyfield='notgallery_rows_en'");
  $rows = $stmt->fetch(PDO::FETCH_ASSOC)['val'];
}
# POST
else {
  function notemptyrow($val, $key) {
    return ($val !== '' && strpos($key, 'row') !== false);
  }
  $rows_json = json_encode(array_values(array_filter($_POST, 'notemptyrow', ARRAY_FILTER_USE_BOTH)));
  $query = "UPDATE keyval SET val=? WHERE keyfield='notgallery_rows_en'";
  $pdo->prepare($query)->execute([$rows_json]);

  $_SESSION['success'] = 'Раздел отредактирован!';
  header('Location: ../');
  exit();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#393e46">
  <link rel="icon" type="image/jpeg" sizes="32x32" href="../../../assets/favicons/favicon-32x32.png">
  <link rel="icon" type="image/jpeg" sizes="16x16" href="../../../assets/favicons/favicon-16x16.png">
  <link rel="stylesheet" href="../../../assets/css/default.css">
  <link rel="stylesheet" href="../../../assets/css/root.css">
  <link rel="stylesheet" href="../../../assets/css/fonts.css">
  <link rel="stylesheet" href="../../../assets/css/add.css">
  <link rel="stylesheet" href="../../../assets/css/home_edit.css">
  <link rel="stylesheet" href="../../../assets/css/legend.css">
  <title>Редактирование английской версии вступительного текста в НеГалерее | T.Gallery</title>
</head>
<body>
  <a href="../" class="back-link">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M512 256A256 256 0 1 0 0 256a256 256 0 1 0 512 0zM231 127c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-71 71L376 232c13.3 0 24 10.7 24 24s-10.7 24-24 24l-182.1 0 71 71c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0L119 273c-9.4-9.4-9.4-24.6 0-33.9L231 127z"/></svg>
  </a>
  <h2>Редактирование английской версии вступительного текста в НеГалерее</h2>
  <div class="container">
    <form action="./" method="post" enctype="multipart/form-data" spellcheck="false" name="form">
      <button type="button" class="create-new-row-btn">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z"/></svg>
      </button>
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
  <script>
    // Data
    const rows = <?= $rows ?>;
  </script>
  <script src="../../../assets/js/adminnotgallerytext2.js" defer></script>
  <script src="../../../assets/js/legend.js" defer></script>
</body>
</html>
