const textarea = document.querySelector('#text');
const textareaEn = document.querySelector('#text_en');
textarea.addEventListener('input', function() {
  if (this.value.length > 270) {
    this.value = this.value.slice(0, 271);
  }
});
textareaEn.addEventListener('input', function() {
  if (this.value.length > 270) {
    this.value = this.value.slice(0, 271);
  }
});