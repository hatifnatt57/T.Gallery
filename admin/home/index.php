<?php
require_once('../../pdo.php');
session_start();

# Login
if ($_SESSION['logged_in'] !== 'true') {
  header('Location: ../../admin/login/');
}

# GET
if (!isset($_POST['row1'])) {
  $stmt = $pdo->query("SELECT title, category FROM pics ORDER BY category, orderint");
  $pic_names = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $stmt = $pdo->query("SELECT val FROM keyval WHERE keyfield='home_rows'");
  $rows = $stmt->fetch(PDO::FETCH_ASSOC)['val'];
  $stmt = $pdo->query("SELECT title FROM pics WHERE home_orderint IS NOT NULL ORDER BY home_orderint");
  $selected_options = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
# POST
else {
  # Rows
  function notemptyrow($val, $key) {
    return ($val !== '' && strpos($key, 'row') !== false);
  }
  $rows_json = json_encode(array_values(array_filter($_POST, 'notemptyrow', ARRAY_FILTER_USE_BOTH)));
  $query = "UPDATE keyval SET val=? WHERE keyfield='home_rows'";
  $pdo->prepare($query)->execute([$rows_json]);

  # Pics
  $pdo->query("UPDATE pics SET home_orderint=NULL");
  for ($i = 1; $i <= 3; $i++) {
    $title = $_POST["pic$i"];
    $query = "UPDATE pics SET home_orderint=? WHERE title=?";
    $pdo->prepare($query)->execute([$i, $title]);
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
  <link rel="stylesheet" href="../../assets/css/add.css">
  <link rel="stylesheet" href="../../assets/css/home_edit.css">
  <title>Редактирование главной страницы | T.Gallery</title>
</head>
<body>
  <a href="../../admin/" class="back-link">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M512 256A256 256 0 1 0 0 256a256 256 0 1 0 512 0zM231 127c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-71 71L376 232c13.3 0 24 10.7 24 24s-10.7 24-24 24l-182.1 0 71 71c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0L119 273c-9.4-9.4-9.4-24.6 0-33.9L231 127z"/></svg>
  </a>
  <h2>Редактирование главной страницы</h2>
  <div class="container">
    <form action="./" method="post" enctype="multipart/form-data" spellcheck="false" name="form">
      <button type="button" class="create-new-row-btn">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z"/></svg>
      </button>
      <button type="submit">Принять</button>
    </form>
  </div>
  <script>
    // Data
    const rows = <?= $rows ?>;
    const options = <?= json_encode($pic_names) ?>;
    const selectedOptions = <?= json_encode($selected_options) ?>;
  </script>
  <script src="../../assets/js/adminhome.js" defer></script>
</body>
</html>