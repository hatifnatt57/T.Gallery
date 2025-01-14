async function loadImgs() {
  for (let i = 0; i < data.length; i++) {
    const entry = data[i];
    const img = await loadImg(`../../assets/pics/${entry['id']}.${entry['format']}`);
    const figureContainer = document.createElement('li');
    figureContainer.classList.add('figure-container');
    const figure = document.createElement('figure');
    figure.appendChild(img);
    const caption = document.createElement('figcaption');
    const p1 = document.createElement('p');
    p1.classList.add('pic-title');
    p1.innerHTML = entry['title_en'];
    caption.appendChild(p1);
    const p2 = document.createElement('p');
    p2.innerHTML = entry['year'];
    caption.appendChild(p2);
    if (entry['technique_en'] !== '') {
      const p3 = document.createElement('p');
      p3.innerHTML = entry['technique_en'];
      caption.appendChild(p3);
    }
    if (entry['size_en'] !== '') {
      const p4 = document.createElement('p');
      p4.innerHTML = entry['size_en'];
      caption.appendChild(p4);
    }
    if (entry['description_en'] !== '') {
      const p5 = document.createElement('div');
      p5.classList.add('description');
      const description = entry['description_en'].split('\n').join('</p><p>');
      p5.innerHTML = '<p>' + description + '</p>';
      caption.appendChild(p5);
    }
    figure.appendChild(caption);
    figureContainer.appendChild(figure);
    document.querySelector('.results').appendChild(figureContainer);
  }
}
loadImgs();