<?php
require_once('../../pdo.php');
session_start();

# Utils
function notempty($var) {
  return $var !== '';
}

# List/Gallery view logic
if (!isset($_SESSION['view'])) {
  $_SESSION['view'] = 'gallery';
}

if (isset($_POST['change_view'])) {
  if ($_SESSION['view'] === 'list') {
    $_SESSION['view'] = 'gallery';
  }  
  else {
    $_SESSION['view'] = 'list';
  }

  if (isset($_POST['cat'])) {
    header("Location: ../gallery/?cat={$_POST['cat']}");
  }
  elseif (isset($_POST['q'])) {
    header("Location: ../gallery/?q={$_POST['q']}");
  }
  else {
    header('Location: ../gallery/');
  }
  exit();
}

# Parameter redirects
if ((!isset($_GET['cat']) && !isset($_GET['q']))
|| (isset($_GET['cat']) && isset($_GET['q']))
|| (isset($_GET['cat']) && $_GET['cat'] !== 'graphics' && $_GET['cat'] !== 'pastel' && $_GET['cat'] !== 'acrylic')) {
  header("Location: ../gallery/?cat=graphics");
  exit();
}

# Data fetching
## Category query
$cat = '';
if (isset($_GET['cat'])) {
  switch ($_GET['cat']) {
    case 'graphics':
      $cat = 'Графика';
      break;
    case 'pastel':
      $cat = 'Пастель';
      break;
    case 'acrylic':
      $cat = 'Акрил';
      break;
  }
  $stmt = $pdo->prepare("SELECT * FROM pics WHERE category=? AND title_en IS NOT NULL AND title_en != '' ORDER BY orderint");
  $stmt->execute([$cat]);
  $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
## Search query
if (isset($_GET['q'])) {
  if ($_GET['q'] === '') {
    $data = array();
  }
  else {
    $q_exploded = explode(' ', $_GET['q']);
    $q_words = array_values(array_filter($q_exploded, 'notempty'));
    $exec_data = array_map(function($e) { return "%$e%"; }, $q_words); 
    $sql = "SELECT * FROM pics WHERE (search_en LIKE ?";
    for ($i=1; $i < count($q_words); $i++) { 
      $sql .= "AND search_en LIKE ?";
    };
    $sql .= ") AND title_en IS NOT NULL AND title_en != ''";
    $sql .= " ORDER BY category, orderint";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($exec_data);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}

# Splitting data for server/client rendering & preparing variables
$view = $_SESSION['view'];
if ($view === 'list') {
  $load_at_start = 10;
  $change_view_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M32 32C14.3 32 0 46.3 0 64v96c0 17.7 14.3 32 32 32s32-14.3 32-32V96h64c17.7 0 32-14.3 32-32s-14.3-32-32-32H32zM64 352c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7 14.3 32 32 32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H64V352zM320 32c-17.7 0-32 14.3-32 32s14.3 32 32 32h64v64c0 17.7 14.3 32 32 32s32-14.3 32-32V64c0-17.7-14.3-32-32-32H320zM448 352c0-17.7-14.3-32-32-32s-32 14.3-32 32v64H320c-17.7 0-32 14.3-32 32s14.3 32 32 32h96c17.7 0 32-14.3 32-32V352z"/></svg>';
  $image_folder = 'icons';
  $change_view_btn_title = 'Gallery';
}
else {
  $load_at_start = 0;
  $change_view_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M40 48C26.7 48 16 58.7 16 72v48c0 13.3 10.7 24 24 24H88c13.3 0 24-10.7 24-24V72c0-13.3-10.7-24-24-24H40zM192 64c-17.7 0-32 14.3-32 32s14.3 32 32 32H480c17.7 0 32-14.3 32-32s-14.3-32-32-32H192zm0 160c-17.7 0-32 14.3-32 32s14.3 32 32 32H480c17.7 0 32-14.3 32-32s-14.3-32-32-32H192zm0 160c-17.7 0-32 14.3-32 32s14.3 32 32 32H480c17.7 0 32-14.3 32-32s-14.3-32-32-32H192zM16 232v48c0 13.3 10.7 24 24 24H88c13.3 0 24-10.7 24-24V232c0-13.3-10.7-24-24-24H40c-13.3 0-24 10.7-24 24zM40 368c-13.3 0-24 10.7-24 24v48c0 13.3 10.7 24 24 24H88c13.3 0 24-10.7 24-24V392c0-13.3-10.7-24-24-24H40z"/></svg>';
  $image_folder = 'pics';
  $change_view_btn_title = 'Catalogue';
}
$data_start = array_slice($data, 0, $load_at_start);
$data_rem = array_slice($data, $load_at_start);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#393e46">
  <title>Gallery | T.Gallery</title>
  <link rel="icon" type="image/jpeg" sizes="32x32" href="../../assets/favicons/favicon-32x32.png">
  <link rel="icon" type="image/jpeg" sizes="16x16" href="../../assets/favicons/favicon-16x16.png">
  <link rel="stylesheet" href="../../assets/css/fonts.css">
  <link rel="stylesheet" href="../../assets/css/default.css">
  <link rel="stylesheet" href="../../assets/css/root.css">
  <link rel="stylesheet" href="../../assets/css/header3.css">
  <link rel="stylesheet" href="../../assets/css/<?= $view ?>2.css">
  <link rel="stylesheet" href="../../assets/css/tooltip.css">
  <script src="../../assets/js/header.js" defer></script>
  <script src="../../assets/js/loadimg.js"></script>
  <script>
    const data = <?= json_encode($data_rem) ?>;
  </script>
</head>
<body>
  <!-- Header -->
  <header>
    <button type="button" class="menu-btn">
      <svg class="menu-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M0 96C0 78.3 14.3 64 32 64H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 128 0 113.7 0 96zM0 256c0-17.7 14.3-32 32-32H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H416c17.7 0 32 14.3 32 32z"/></svg>
    </button>
    <a href="../">
      <div class="logo">
      <span class="logo--long">T.Gallery</span> 
      <span class="logo--short">T.G</span> 
      </div>
    </a>
    <nav>
      <button type="button" class="close-menu-btn">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"/></svg>
      </button>
      <a href="../" class="header-home-link">Home</a>
      <a href="../about/">About me</a>
      <a href="../gallery/?cat=graphics" <?= $cat === 'Графика' ? 'active' : '' ?>>Graphics</a>
      <a href="../gallery/?cat=pastel" <?= $cat === 'Пастель' ? 'active' : '' ?>>Pastel</a>
      <a href="../gallery/?cat=acrylic" <?= $cat === 'Акрил' ? 'active' : '' ?>>Acrylic</a>
      <a href="../notgallery/">NonGallery</a>
    </nav>
    <div class="header-buttons">
      <form action="../gallery/" method="post" class="change-view-form">
        <?php 
          if (isset($_GET['cat'])) {
            echo('<input type="hidden" name="cat" value="'.$_GET['cat'].'">');
          }
          else {
            echo('<input type="hidden" name="q" value="'.$_GET['q'].'">');
          }
        ?>
        <input type="hidden" name="change_view" value="true">
        <button type="submit" id="change-view-btn"><?= $change_view_svg ?></button>
        <div id="tooltip">
          <?= $change_view_btn_title ?>
          <div id="arrow" data-popper-arrow></div>
        </div>
      </form>
      <button type="button" class="search-btn" title="Search">
        <svg class="zoom-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/></svg>
      </button>
    </div>
  </header>
  <!-- Search overlay -->
  <div class="search-overlay">
    <button type="button" class="close-search-btn">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"/></svg>
    </button>
    <form action="../gallery/" method="get">
      <input type="text" name="q" spellcheck="false">
      <button type="submit">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/></svg>
      </button>
    </form>
  </div>
  <!-- Server data rendering -->
  <?php if (count($data) === 0): ?>
  <h2 class="nothing-found-heading">Nothing found 🙁</h2>
  <?php else: ?>
  <ul class="results">
  <?php foreach ($data_start as $pic): ?>
    <li class="figure-container">
      <figure>
        <img src="../../assets/<?= $image_folder ?>/<?= $pic['id'].'.'.$pic['format'] ?>">
        <figcaption>
        <?php 
          if ($view === 'list') {
            $picmeta = array(
              $pic['title_en'],
              $pic['year'],
              $pic['technique_en'],
              $pic['size_en']
            );
            $first_line = join(' &middot; ', array_filter($picmeta, 'notempty'));
            echo("<p>$first_line</p>");
            if (notempty($pic['description_en'])) {
              echo("<p class=\"description\">{$pic['description_en']}</p>");
            }
          }
          else {
            echo("<p class=\"pic-title\">{$pic['title_en']}</p>");
            echo("<p>{$pic['year']}</p>");
            if (notempty($pic['technique_en'])) {
              echo("<p>{$pic['technique_en']}</p>");
            }
            if (notempty($pic['size_en'])) {
              echo("<p>{$pic['size_en']}</p>");
            }
            if (notempty($pic['description_en'])) {
              echo("<p class=\"description\">{$pic['description_en']}</p>");
            }
          }
        ?>
        </figcaption>
      </figure>
    </li>
  <?php endforeach ?>
  </ul>
  <?php endif ?>
  <?php if ($view === 'list'): ?>
  <!-- Zoom -->
  <div class="zoom-overlay">
    <button class="close-zoom-btn">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"/></svg>
    </button>
  </div>
  <script src="../../assets/js/zoom.js"></script>
  <!-- Async data rendering -->
  <script src="../../assets/js/loadimgslisten2.js" defer></script>
  <?php else: ?>
    <script src="../../assets/js/loadimgsgalleryen.js" defer></script>
  <?php endif ?>
  <?php require('../../parts/footer.php') ?>
  <script src="https://unpkg.com/@popperjs/core@2"></script>
  <script src="../../assets/js/tooltip.js" defer></script>
  <script>
    const textInput = document.querySelector('input[name="q"]');
    if (textInput) {
      const limit = 100;
      textInput.addEventListener('input', function() {
        if (this.value.length > limit) {
          this.value = this.value.slice(0, limit + 1);
        }
      });
    }
  </script>
</body>
</html>