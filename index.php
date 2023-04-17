<?php 
require_once('./pdo.php');

$stmt = $pdo->query('SELECT * FROM pics ORDER BY year DESC, orderint LIMIT 3');
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#393e46">
  <title>Галерея</title>
  <link rel="icon" type="image/jpeg" sizes="32x32" href="./assets/favicons/favicon-32x32.png">
  <link rel="icon" type="image/jpeg" sizes="16x16" href="./assets/favicons/favicon-16x16.png">
  <link rel="stylesheet" href="./assets/css/fonts.css">
  <link rel="stylesheet" href="./assets/css/default.css">
  <link rel="stylesheet" href="./assets/css/root.css">
  <link rel="stylesheet" href="./assets/css/header.css">
  <link rel="stylesheet" href="./assets/css/home.css">
  <script src="./assets/js/header.js" defer></script>
  <script src="./assets/js/loadimg.js"></script>
  <script>
    const data = <?= json_encode($data) ?>;
  </script>
</head>
<body>
  <header>
    <button type="button" class="menu-btn">
      <svg class="menu-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M0 96C0 78.3 14.3 64 32 64H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 128 0 113.7 0 96zM0 256c0-17.7 14.3-32 32-32H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H416c17.7 0 32 14.3 32 32z"/></svg>
    </button>
    <a href="./">
      <div class="logo">
      <span class="logo--long">T.Gallery</span> 
      <span class="logo--short">T.G</span> 
      </div>
    </a>
    <nav>
      <button type="button" class="close-menu-btn">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"/></svg>
      </button>
      <a href="./about/">Обо мне</a>
      <a href="./gallery/?cat=grafika">Графика</a>
      <a href="./gallery/?cat=pastel">Пастель</a>
      <a href="./gallery/?cat=akril">Акрил</a>
      <a href="#">НеГалерея</a>
    </nav>
  </header>
  <main>
    <h1>Виртуальная арт-галерея Олега Трактуева</h1>
    <div class="greeting">
      <p>Добро пожаловать, дорогие друзья и&nbsp;родственники!</p>
      <p>Надеюсь, на&nbsp;этом сайте вы&nbsp;найдете ответ на&nbsp;ваши частые вопросы &laquo;Что нового нарисовал?&raquo;, &laquo;Как проводишь время на&nbsp;пенсии?&raquo; и&nbsp;т.&nbsp;д.</p>
    </div>
    <h2>Из недавнего</h2>
    <div class="imgs-container"></div>
  </main>
  <?php require('./parts/footer.php') ?>
  <script>
    async function loadImgs() {
      const imgsContainer = document.querySelector('.imgs-container');
      const imgs = [];
      for (let i = 0; i < data.length; i++) {
        const entry = data[i];
        const src = './assets/pics/'+ entry['id'] + '.' + entry['format'];
        const img = await loadImg(src);
        const a = document.createElement('a');
        a.appendChild(img);
        let cat;
        switch (entry['category']) {
          case 'Графика':
            cat = 'grafika';
            break;
          case 'Пастель':
            cat = 'pastel';
            break;
          case 'Акрил':
            cat = 'akril';
            break;
        }
        a.href = './gallery/?cat=' + cat;
        imgs.push(a);
      }
      imgs.forEach(a => { imgsContainer.appendChild(a) });
    }
    loadImgs();
  </script>
</body>
</html>