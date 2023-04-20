const changeOrderBtn = document.querySelector('.change-order-btn');

changeOrderBtn.addEventListener('click', () => {
  document.body.classList.add('body__change-order-mode');
});

const ups = [...document.querySelectorAll('.li--ui--change-order-btn--up')];
const downs = [...document.querySelectorAll('.li--ui--change-order-btn--down')];

ups.forEach(up => {
  up.addEventListener('click', function() {
    const me = this.parentElement.parentElement;
    const prev = me.previousElementSibling;
    const parent = me.parentElement;
    if (prev !== null) {
      parent.insertBefore(me, prev);
    }
  });
});

downs.forEach(down => {
  down.addEventListener('click', function() {
    const me = this.parentElement.parentElement;
    const parent = me.parentElement;
    let next = null;
    if (me.nextElementSibling !== null) {
      next = me.nextElementSibling.nextElementSibling;
    }
    if (next !== null) {
      parent.insertBefore(me, next);
    }
  });
});

document.querySelector('.header--order-ui--decline').addEventListener('click', () => {
  location.reload();
});

document.querySelector('.header--order-ui--accept').addEventListener('click', () => {
  const form = document.querySelector('.change-order-form');
  const input = form.querySelector('input');
  const data = {};
  [...document.querySelectorAll('ul')].forEach(ul => {
    const cat = ul.className.slice(ul.className.lastIndexOf('-') + 1);
    const ids = [...ul.querySelectorAll('li')].map(li => li.getAttribute('data-id'));
    data[cat] = ids;
  });
  input.value = JSON.stringify(data);
  form.submit();
});
