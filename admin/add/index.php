<?php
require_once('../../pdo.php');
session_start();

# Login
if ($_SESSION['logged_in'] !== 'true') {
  header('Location: ../../admin/login/');
  exit();
}

# POST
if (isset($_POST['title'])) {
  require('../../parts/addpic.php');

  $_SESSION['success'] = 'Запись добавлена!';
  header('Location: ../../admin/');
  exit();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="uft-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#393e46">
  <link rel="icon" type="image/jpeg" sizes="32x32" href="../../assets/favicons/favicon-32x32.png">
  <link rel="icon" type="image/jpeg" sizes="16x16" href="../../assets/favicons/favicon-16x16.png">
  <link rel="stylesheet" href="../../assets/css/default.css">
  <link rel="stylesheet" href="../../assets/css/root.css">
  <link rel="stylesheet" href="../../assets/css/fonts.css">
  <link rel="stylesheet" href="../../assets/css/add.css">
  <link rel="stylesheet" href="../../assets/css/message.css">
  <link rel="stylesheet" href="../../assets/css/frasl2.css">
  <title>Добавление записи | T.Gallery</title>
  <script src="../../assets/js/message.js" defer></script>
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
  <a href="../../admin/" class="back-link">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M512 256A256 256 0 1 0 0 256a256 256 0 1 0 512 0zM231 127c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-71 71L376 232c13.3 0 24 10.7 24 24s-10.7 24-24 24l-182.1 0 71 71c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0L119 273c-9.4-9.4-9.4-24.6 0-33.9L231 127z"/></svg>
  </a>
  <h2>Добавление записи</h2>
  <form action="../../admin/add/" method="post" enctype="multipart/form-data" spellcheck="false" name="form">
    <label for="image">Файл изображения: <span class="req">*</span></label>
    <input type="file" name="image" accept="image/jpeg, image/png" id="image">
    <label for="title">Название: <span class="req">*</span></label>
    <input type="text" name="title" id="title">
    <label for="year">Год создания: <span class="req">*</span></label>
    <input type="text" name="year" id="year">
    <label for="category">Категория: <span class="req">*</span></label>
    <select name="category" id="category">
      <option value="Графика">Графика</option>
      <option value="Пастель">Пастель</option>
      <option value="Акрил">Акрил</option>
    </select>
    <label for="technique">Материалы:</label>
    <input type="text" name="technique" id="technique">
    <label for="size">Размер:</label>
    <input type="text" name="size" id="size">
    <label for="description">Комментарий:</label>
    <textarea name="description" id="description" rows="5"></textarea>
    <label for="title_en">Название (en):</label>
    <input type="text" name="title_en" id="title_en">
    <label for="technique_en">Материалы (en):</label>
    <input type="text" name="technique_en" id="technique_en">
    <label for="size_en">Размер (en):</label>
    <div class="size-input-container">
        <input type="text" name="size_en" id="size_en">
        <button type="button" class="frasl-btn">/</button>
      </div>
    <label for="description_en">Комментарий (en):</label>
    <textarea name="description_en" id="description_en" rows="5"></textarea>
    <button type="submit">Добавить</button>
  </form>
  <script src="../../assets/js/yearvalidation.js" defer></script>
  <script src="../../assets/js/frasl.js" defer></script>
  <script>
    document.querySelector('form').onsubmit = validateForm;

    function validateForm() {
      const form = document.forms['form'];
      const required = {
        image: 'Файл изображения',
        title: 'Название',
        year: 'Год создания'
      };
      for (const field in required) {
        if (form[field].value === '') {
          alert(`Поле "${required[field]}" обязательно для заполнения!`)
          return false;
        }
      };
      const fileInput = document.querySelector('#image');
      if (fileInput.files[0].size > 10 * 1024 * 1024) {
        alert('Размер файла изображения не должен превышать 10МБ!');
        return false;
      }
    }
  </script>
</body>
</html>