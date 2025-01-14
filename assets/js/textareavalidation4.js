const textarea = document.querySelector('#text');
const textareaEn = document.querySelector('#text_en');
if (textarea) {
  textarea.addEventListener('input', function() {
    if (this.value.length > 1000) {
      this.value = this.value.slice(0, 1001);
    }
  });
}
if (textareaEn) {
  textareaEn.addEventListener('input', function() {
    if (this.value.length > 1000) {
      this.value = this.value.slice(0, 1001);
    }
  });
}