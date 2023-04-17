const icons = [...document.querySelectorAll('.results img')];
const zoomOverlay = document.querySelector('.zoom-overlay');
const closeZoomBtn = document.querySelector('.close-zoom-btn');

// Loading flag
let loading = false;

function handleIconClick() {
  if (loading) {
    return;
  }
  const picUrl = this.src.replace('icons', 'pics');
  const pic = new Image();
  pic.onload = function() {
    loading = false;
    zoomOverlay.appendChild(pic);
    document.body.classList.add('overflow-hidden');
    zoomOverlay.style.display = 'flex';
    // Read operation to trigger reflow
    document.body.offsetHeight;
    zoomOverlay.style.opacity = '1';
  }
  pic.src = picUrl;
  loading = true;
}

icons.forEach(icon => {
  icon.addEventListener('click', handleIconClick);
});

closeZoomBtn.addEventListener('click', function() {
  zoomOverlay.style.opacity = '0';
  document.body.classList.remove('overflow-hidden');
  setTimeout(() => {
    zoomOverlay.style.display = 'none';
    const img = zoomOverlay.querySelector('img');
    zoomOverlay.removeChild(img);
  }, 200);
});