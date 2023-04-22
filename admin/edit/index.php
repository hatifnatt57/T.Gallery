<?php
require_once('../../pdo.php');
session_start();

# Login
if ($_SESSION['logged_in'] !== 'true') {
  header('Location: ../../admin/login/');
}

# GET
if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $stmt = $pdo->prepare("SELECT * FROM pics WHERE id=?");
  $stmt->execute([$id]);
  $entry = $stmt->fetch();
  if (!$entry) {
    header('Location: ../../admin/');
    exit();
  }
}
# POST
else if (isset($_POST['title'])) {
  # Image change
  if ($_FILES['image']['name'] !== '') {
    # Delete old
    $id = $_POST['id'];
    $stmt = $pdo->prepare("SELECT format FROM pics WHERE id=?");
    $stmt->execute([$id]);
    $format = $stmt->fetch()['format'];
    unlink("../../assets/pics/$id.$format");
    unlink("../../assets/icons/$id.$format");
    $stmt = $pdo->prepare("DELETE FROM pics WHERE id=?");
    $stmt->execute([$id]);
    # Add new
    require('../../parts/addpic.php');
  }
  # No image change
  else {
    function notemptynorid($val, $key) {
      return ($val !== '' && $key !== 'id');
    }
  
    $search = join(' ', array_filter($_POST, 'notemptynorid', ARRAY_FILTER_USE_BOTH));
  
    $data = [
      'title' => $_POST['title'],
      'size' => $_POST['size'],
      'technique' => $_POST['technique'],
      'year' => $_POST['year'],
      'description' => $_POST['description'],
      'category' => $_POST['category'],
      'search' => $search,
      'id' => $_POST['id']
    ];
    $query = "UPDATE pics SET 
      title=:title,
      size=:size,
      technique=:technique,
      year=:year,
      description=:description,
      category=:category,
      search=:search
      WHERE id=:id";
    $pdo->prepare($query)->execute($data);
  }

  $_SESSION['success'] = 'Запись отредактирована!';
  header('Location: ../../admin/');
  exit();
}
# Else
else {
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
  <link rel="stylesheet" href="../../assets/css/edit.css">
  <link rel="stylesheet" href="../../assets/css/message.css">
  <script src="../../assets/js/loadimg.js"></script>
  <script src="../../assets/js/changeimageui.js" defer></script>
  <script src="../../assets/js/message.js" defer></script>
  <title>Редактирование записи</title>
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
  <h2>Редактирование записи</h2>
  <div class="container">
    <form action="../../admin/edit/" method="post" enctype="multipart/form-data" spellcheck="false" name="form">
      <button type="button" class="change-image-btn">Заменить изображение</button>
      <label for="image" class="image-input">Файл изображения:</label>
      <input type="file" name="image" id="image" accept="image/jpeg, image/png" class="image-input">
      <label for="title">Название: <span class="req">*</span></label>
      <input type="text" name="title" id="title" value="<?= $entry['title'] ?>">
      <label for="category">Категория: <span class="req">*</span></label>
      <select name="category" id="category">
        <option value="Графика" <?php if ($entry['category'] === 'Графика') echo('selected') ?>>Графика</option>
        <option value="Пастель" <?php if ($entry['category'] === 'Пастель') echo('selected') ?>>Пастель</option>
        <option value="Акрил" <?php if ($entry['category'] === 'Акрил') echo('selected') ?>>Акрил</option>
      </select>
      <label for="year">Год создания:</label>
      <input type="text" name="year" id="year" value="<?= $entry['year'] ?>">
      <label for="technique">Материалы:</label>
      <input type="text" name="technique" id="technique" value="<?= $entry['technique'] ?>">
      <label for="size">Размер:</label>
      <input type="text" name="size" id="size" value="<?= $entry['size'] ?>">
      <label for="description">Описание:</label>
      <textarea name="description" id="description" rows="5"><?= $entry['description'] ?></textarea>
      <input type="hidden" name="id" value="<?= $entry['id'] ?>">
      <button type="submit">Принять</button>
    </form>
  </div>
  <script src="../../assets/js/yearvalidation.js" defer></script>
  <script>
    // Form validation
    document.querySelector('form').onsubmit = validateForm;
    function validateForm() {
      const form = document.forms['form'];
      const required = {
        title: 'Название'
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

    // Async image loading
    async function loadTheImage(pic) {
      const src = `../../assets/pics/${pic['id']}.${pic['format']}`;
      const img = await loadImg(src);
      const figure = document.createElement('figure');
      const figcaption = document.createElement('figcaption');
      figcaption.innerHTML = 'Изображение:';
      figure.appendChild(figcaption);
      figure.appendChild(img);
      document.querySelector('.container').appendChild(figure);
    }
    const entry = <?= json_encode($entry) ?>;
    loadTheImage(entry);
  </script>
</body>
</html>