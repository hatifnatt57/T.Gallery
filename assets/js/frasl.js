document.querySelector('.frasl-btn').addEventListener('click', () => {
  const input = document.querySelector('#size_en');
  const selectionStart = input.selectionStart;
  input.value = input.value.slice(0, selectionStart) + '&frasl;' + input.value.slice(selectionStart);
  input.focus();
});