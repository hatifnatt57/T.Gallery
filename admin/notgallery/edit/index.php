<?php
require_once('../../../pdo.php');
session_start();

# Login
if ($_SESSION['logged_in'] !== 'true') {
  header('Location: ../../login/');
}

# GET
if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $stmt = $pdo->prepare("SELECT * FROM links WHERE id=?");
  $stmt->execute([$id]);
  $entry = $stmt->fetch();
  if (!$entry) {
    header('Location: ../');
    exit();
  }
}
# POST
else if (isset($_POST['text'])) {
  $query = "UPDATE links SET text=? WHERE id=?";
  $pdo->prepare($query)->execute([$_POST['text'], $_POST['id']]);

  $_SESSION['success'] = 'Запись отредактирована!';
  header('Location: ../');
  exit();
}
else {
  header('Location: ../');
  exit();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="uft-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#393e46">
  <link rel="icon" type="image/jpeg" sizes="32x32" href="../../../assets/favicons/favicon-32x32.png">
  <link rel="icon" type="image/jpeg" sizes="16x16" href="../../../assets/favicons/favicon-16x16.png">
  <link rel="stylesheet" href="../../../assets/css/default.css">
  <link rel="stylesheet" href="../../../assets/css/root.css">
  <link rel="stylesheet" href="../../../assets/css/fonts.css">
  <link rel="stylesheet" href="../../../assets/css/add.css">
  <link rel="stylesheet" href="../../../assets/css/edit.css">
  <link rel="stylesheet" href="../../../assets/css/admin_notgallery.css">
  <title>Редактирование записи</title>
</head>
<body>
  <a href="../" class="back-link">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M512 256A256 256 0 1 0 0 256a256 256 0 1 0 512 0zM231 127c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-71 71L376 232c13.3 0 24 10.7 24 24s-10.7 24-24 24l-182.1 0 71 71c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0L119 273c-9.4-9.4-9.4-24.6 0-33.9L231 127z"/></svg>
  </a>
  <h2>Редактирование записи</h2>
  <form action="./" method="post" spellcheck="false" name="form">
    <label for="text">Описание: <span class="req">*</span></label>
    <textarea name="text" id="text" rows="5"><?= $entry['text'] ?></textarea>
    <input type="hidden" name="id" value="<?= $entry['id'] ?>">
    <button type="submit">Принять</button>
  </form>
  <script>
    const textarea = document.querySelector('#text');
    textarea.addEventListener('input', function() {
      if (this.value.length > 270) {
        this.value = this.value.slice(0, 271);
      }
    });
  </script>
</body>
</html>