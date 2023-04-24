<?php
require_once('../../../pdo.php');
session_start();

# Login
if ($_SESSION['logged_in'] !== 'true') {
  header('Location: ../../login/');
}

# POST
if (isset($_POST['text'])) {
  require('../../../parts/addlink.php');

  $_SESSION['success'] = 'Запись добавлена!';
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
  <title>Добавление записи</title>
</head>
<body>
  <a href="../" class="back-link">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M512 256A256 256 0 1 0 0 256a256 256 0 1 0 512 0zM231 127c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-71 71L376 232c13.3 0 24 10.7 24 24s-10.7 24-24 24l-182.1 0 71 71c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0L119 273c-9.4-9.4-9.4-24.6 0-33.9L231 127z"/></svg>
  </a>
  <h2>Добавление записи</h2>
  <form action="./" method="post" enctype="multipart/form-data" spellcheck="false" name="form">
    <label for="image">Файл изображения: <span class="req">*</span></label>
    <input type="file" name="image" accept="image/jpeg, image/png" id="image">
    <label for="text">Описание: <span class="req">*</span></label>
    <textarea name="text" id="text" rows="5"></textarea>
    <label for="resourse-url">URL внешнего ресурса: <span class="req">*</span></label>
    <input type="text" name="resourse_url" id="resourse-url">
    <label for="resourse">Файл документа: <span class="req">*</span></label>
    <input type="file" name="resourse" accept=".pdf" id="resourse">
    <button type="button" class="change-resourse-btn">Прикрепить документ</button>
    <button type="submit">Добавить запись</button>
  </form>
  <script>
    document.querySelector('form').onsubmit = validateForm;

    function validateForm() {
      const form = document.forms['form'];
      const required = {
        image: 'Файл изображения',
        text: 'Описание',
      };
      for (const field in required) {
        if (form[field].value === '') {
          alert(`Все поля обязательны для заполнения!`)
          return false;
        }
      };
      const fileInput = document.querySelector('#image');
      if (fileInput.files[0].size > 5 * 1024 * 1024) {
        alert('Размер файла изображения не должен превышать 5МБ!');
        return false;
      };
      const resourseInput = document.querySelector('#resourse');
      if (resourseInput.files[0] && resourseInput.files[0].size > 5 * 1024 * 1024) {
        alert('Размер файла документа не должен превышать 5МБ!');
        return false;
      }
    }
  </script>
  <script>
    const changeImageBtn = document.querySelector('.change-resourse-btn');
    const form = document.querySelector('form');
    changeImageBtn.addEventListener('click', () => {
      form.classList.add('form__adding-resourse');
      document.querySelector('#resourse-url').value = '';
    });
  </script>
</body>
</html>