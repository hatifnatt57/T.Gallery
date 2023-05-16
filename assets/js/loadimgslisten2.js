async function loadImgs() {
  for (let i = 0; i < data.length; i++) {
    const entry = data[i];
    const meta = [
      entry['title_en'],
      entry['year'],
      entry['technique_en'],
      entry['size_en'],
    ];
    const img = await loadImg(`../../assets/icons/${entry['id']}.${entry['format']}`);
    const figureContainer = document.createElement('li');
    figureContainer.classList.add('figure-container');
    const figure = document.createElement('figure');
    img.addEventListener('click', handleIconClick);
    figure.appendChild(img);
    const caption = document.createElement('figcaption');
    const p1 = document.createElement('p');
    p1.innerHTML = meta.filter(e => e !== '').join(' &middot; ');
    caption.appendChild(p1);
    if (entry['description'] !== '') {
      const p2 = document.createElement('p');
      p2.classList.add('description');
      p2.innerHTML = entry['description_en'];
      caption.appendChild(p2);
    }
    figure.appendChild(caption);
    figureContainer.appendChild(figure);
    document.querySelector('.results').appendChild(figureContainer);
  }
}
loadImgs();