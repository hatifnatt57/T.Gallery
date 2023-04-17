const success = document.querySelector('.success');
if (success) {
  success.classList.add('success__show');
  setTimeout(() => {
    success.classList.remove('success__show');
  }, 3000);
  setTimeout(() => {
    success.style.display = 'none';
  }, 3200);
  const closeSuccess = document.querySelector('.close-success');
  closeSuccess.addEventListener('click', () => {
    success.classList.remove('success__show');
  });
};

const error = document.querySelector('.error');
if (error) {
  error.classList.add('error__show');
  setTimeout(() => {
    error.classList.remove('error__show');
  }, 3000);
  setTimeout(() => {
    error.style.display = 'none';
  }, 3200);
  const closeError = document.querySelector('.close-error');
  closeError.addEventListener('click', () => {
    error.classList.remove('error__show');
  });
};