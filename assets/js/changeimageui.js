const changeImageBtn = document.querySelector('.change-image-btn');
const form = document.querySelector('form');
changeImageBtn.addEventListener('click', () => {
  form.classList.add('form__adding-image');
});