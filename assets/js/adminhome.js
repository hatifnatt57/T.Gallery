// Rows
function getRowHTML(content, n) {
  return `
    <label for="row${n}">Строка ${n}:</label>
    <input type="text" class="input-row" name="row${n}" id="row${n}" value="${content.replace(/"/g, '&quot;')}">
  `;
}

if (rows.length === 0) rows.push('');
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

// Select
const grafikaTitles = [];
const pastelTitles = [];
const akrilTitles = [];
options.forEach(option => {
  switch (option.category) {
    case 'Графика':
      grafikaTitles.push(option.title);
      break;
    case 'Пастель':
      pastelTitles.push(option.title);
      break;
    case 'Акрил':
      akrilTitles.push(option.title);
      break;
    default:
      break;
  }
});
const optgroupHTMLArr = [];
if (grafikaTitles.length > 0) {
  const optgroup = `
    <optgroup label="Графика">
      ${grafikaTitles
         .map(title => `<option value="${title}">${title}</option>`)
         .join('\n')}
    </optgroup>
  `;
  optgroupHTMLArr.push(optgroup);
}
if (pastelTitles.length > 0) {
  const optgroup = `
    <optgroup label="Пастель">
      ${pastelTitles
         .map(title => `<option value="${title}">${title}</option>`)
         .join('\n')}
    </optgroup>
  `;
  optgroupHTMLArr.push(optgroup);
}
if (akrilTitles.length > 0) {
  const optgroup = `
    <optgroup label="Акрил">
      ${akrilTitles
         .map(title => `<option value="${title}">${title}</option>`)
         .join('\n')}
    </optgroup>
  `;
  optgroupHTMLArr.push(optgroup);
}
const optionsString = optgroupHTMLArr.join('\n');
const selects = `
  <label for="pic1">Картина 1:</label>
  <select id="pic1" name="pic1">${optionsString}</select>
  <label for="pic2">Картина 2:</label>
  <select id="pic2" name="pic2">${optionsString}</select>
  <label for="pic3">Картина 3:</label>
  <select id="pic3" name="pic3">${optionsString}</select>
`;
document.querySelector('form [type="submit"]').insertAdjacentHTML('beforebegin', selects);

if (selectedOptions.length > 0) {
  document.querySelector(`#pic1 [value="${selectedOptions[0]['title']}"]`).setAttribute('selected', true);
  document.querySelector(`#pic2 [value="${selectedOptions[1]['title']}"]`).setAttribute('selected', true);
  document.querySelector(`#pic3 [value="${selectedOptions[2]['title']}"]`).setAttribute('selected', true);
}

// Form validation
document.querySelector('form').onsubmit = validateForm;
function validateForm() {
  const selectedOptions = [...document.querySelectorAll('select')].map(el => el.value);
  if (selectedOptions[0] === selectedOptions[1] 
  || selectedOptions[0] === selectedOptions[2] 
  || selectedOptions[1] === selectedOptions[2]) {
    alert('Нужно выбрать разные картины!');
    return false;
  }
}