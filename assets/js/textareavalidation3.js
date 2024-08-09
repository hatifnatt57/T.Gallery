const textarea = document.querySelector('#text');
const textareaEn = document.querySelector('#text_en');
textarea.addEventListener('input', function() {
  if (this.value.length > 1000) {
    this.value = this.value.slice(0, 1001);
  }
});
textareaEn.addEventListener('input', function() {
  if (this.value.length > 1000) {
    this.value = this.value.slice(0, 1001);
  }
});