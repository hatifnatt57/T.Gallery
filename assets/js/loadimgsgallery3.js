async function loadImgs() {
  for (let i = 0; i < data.length; i++) {
    const entry = data[i];
    const img = await loadImg(`../assets/pics/${entry['id']}.${entry['format']}`);
    const figureContainer = document.createElement('li');
    figureContainer.classList.add('figure-container');
    const figure = document.createElement('figure');
    figure.appendChild(img);
    const caption = document.createElement('figcaption');
    const p1 = document.createElement('p');
    p1.classList.add('pic-title');
    p1.innerHTML = entry['title'];
    caption.appendChild(p1);
    const p2 = document.createElement('p');
    p2.innerHTML = entry['year'];
    caption.appendChild(p2);
    if (entry['technique'] !== '') {
      const p3 = document.createElement('p');
      p3.innerHTML = entry['technique'];
      caption.appendChild(p3);
    }
    if (entry['size'] !== '') {
      const p4 = document.createElement('p');
      p4.innerHTML = entry['size'];
      caption.appendChild(p4);
    }
    if (entry['description'] !== '') {
      const p5 = document.createElement('div');
      p5.classList.add('description');
      const description = entry['description'].split('\n').join('</p><p>');
      p5.innerHTML = '<p>' + description + '</p>';
      caption.appendChild(p5);
    }
    figure.appendChild(caption);
    figureContainer.appendChild(figure);
    document.querySelector('.results').appendChild(figureContainer);
  }
}
loadImgs();