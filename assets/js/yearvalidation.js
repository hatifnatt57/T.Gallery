document.querySelector('#year').addEventListener('input', function() {
  this.value = this.value.replace(/\D/g, '');
});