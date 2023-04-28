// Rows
function getRowHTML(content, n) {
  return `
    <label for="row${n}">Строка ${n}:</label>
    <input type="text" class="input-row" name="row${n}" id="row${n}" value="${content}">
  `;
}

const form = document.querySelector('form');
let rowsHTML = '';
rows.forEach((row, i) => {
  const rowNum = i + 1;
  const rowHTML = getRowHTML(row, rowNum);
  rowsHTML += rowHTML;
});
form.insertAdjacentHTML('afterbegin', rowsHTML);

// Add row button
document.querySelector('.create-new-row-btn').addEventListener('click', function() {
  const nextN = document.querySelectorAll('.input-row').length + 1;
  const rowHTML = getRowHTML('', nextN);
  this.insertAdjacentHTML('beforebegin', rowHTML);
  const inputsRow = document.querySelectorAll('.input-row');
  inputsRow[inputsRow.length - 1].focus();
});