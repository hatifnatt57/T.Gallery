<?php 
require_once('../pdo.php');

$stmt = $pdo->query("SELECT * FROM links ORDER BY orderint");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#393e46">
  <title>Галерея</title>
  <link rel="icon" type="image/jpeg" sizes="32x32" href="../assets/favicons/favicon-32x32.png">
  <link rel="icon" type="image/jpeg" sizes="16x16" href="../assets/favicons/favicon-16x16.png">
  <link rel="stylesheet" href="../assets/css/fonts.css">
  <link rel="stylesheet" href="../assets/css/default.css">
  <link rel="stylesheet" href="../assets/css/root.css">
  <link rel="stylesheet" href="../assets/css/header.css">
  <link rel="stylesheet" href="../assets/css/links_list.css">
  <script src="../assets/js/header.js" defer></script>
  <script src="../assets/js/loadimg.js"></script>
  <script src="../libs/typograf.min.js"></script>
  <script src="../libs/marked.min.js"></script>
  <script>
    const data = <?= json_encode($data) ?>;
  </script>
</head>
<body>
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
      <a href="../about/">Обо мне</a>
      <a href="../gallery/?cat=grafika">Графика</a>
      <a href="../gallery/?cat=pastel">Пастель</a>
      <a href="../gallery/?cat=akril">Акрил</a>
      <a href="./" active>НеГалерея</a>
    </nav>
  </header>
  <main>
    <!-- Server data rendering -->
  <?php if (count($data) === 0): ?>
  <h2 class="nothing-found-heading">Тут ничего нет 🙁</h2>
  <?php else: ?>
  <ul class="results"></ul>
  <?php endif ?>
  </main>
  <?php require('../parts/footer.php') ?>
  <script>
    const tp = new Typograf({
      locale: ['ru', 'en-US'],
      htmlEntity: { type: 'name' }
    });

    async function createElements() {
      const ul = document.querySelector('.results');
      if (!ul) return;
      for (let i = 0; i < data.length; i++) {
        const entry = data[i];
        const src = '../assets/linkimgs/'+ entry['id'] + '.' + entry['format'];
        const img = await loadImg(src);
        const figcaption = document.createElement('figcaption');
        const markedText = marked.parse(entry['text']).replace(/\n/g, ' ');
        figcaption.innerHTML = tp.execute(markedText).replace(/&mdash;/g, '<span class="mdash">&mdash;</span>');
        const figure = document.createElement('figure');
        figure.appendChild(img);
        figure.appendChild(figcaption);
        const a = document.createElement('a');
        a.appendChild(figure);
        const resoursePath = '../assets/resourses/';
        if (entry['resourse_url'].indexOf('http') === 0) {
          a.href = entry['resourse_url'];
        }
        else {
          a.href = resoursePath + entry['resourse_url'];
        }
        const li = document.createElement('li');
        li.classList.add('figure-container');
        li.appendChild(a);
        ul.appendChild(li);
      }
    }
    createElements();

    // const greeting = document.querySelector('.greeting');
    // greeting.innerHTML = rows
    // .map(row => tp.execute(row).replace(/&mdash;/g, '<span class="mdash">&mdash;</span>'))
    // .map(row => '<p>' + row + '</p>').join('\n');
  </script>
</body>
</html>