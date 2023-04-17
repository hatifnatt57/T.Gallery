// Menu
const menuBtn = document.querySelector('.menu-btn');
const closeMenuBtn = document.querySelector('.close-menu-btn');
const menuEl = document.querySelector('nav');

function openMenu() {
  menuEl.classList.add('nav__open');
  document.body.classList.add('overflow-hidden');
}

function closeMenu() {
  menuEl.classList.remove('nav__open');
  document.body.classList.remove('overflow-hidden');
}

menuBtn.addEventListener('click', openMenu);
closeMenuBtn.addEventListener('click', closeMenu);

// Search
if (document.querySelector('.search-btn')) {
  const openSearchBtn = document.querySelector('.search-btn');
  const closeSearchBtn = document.querySelector('.close-search-btn');
  const searchEl = document.querySelector('.search-overlay');
  const searchInput = searchEl.querySelector('input[type="text"]');

  function openSearch() {
    searchEl.classList.add('search-overlay__open');
    document.body.classList.add('overflow-hidden');
    searchInput.focus();
  }

  function closeSearch() {
    searchEl.classList.remove('search-overlay__open');
    document.body.classList.remove('overflow-hidden');
  }

  openSearchBtn.addEventListener('click', openSearch);
  closeSearchBtn.addEventListener('click', closeSearch);
}