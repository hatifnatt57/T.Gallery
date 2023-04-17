const legendBtn = document.querySelector('.legend-btn');
const legendOverlay = document.querySelector('.legend-overlay');
const closeOverlayBtn = document.querySelector('.close-overlay-btn');
legendBtn.addEventListener('click', () => {
  legendOverlay.style.visibility = 'visible';
  legendOverlay.style.opacity = '1';
  document.body.style.pointerEvents = 'none'; 
});
closeOverlayBtn.addEventListener('click', () => {
  document.body.style.pointerEvents = 'initial'; 
  legendOverlay.style.opacity = '0';
  setTimeout(() => {
    legendOverlay.style.visibility = 'hidden';
  }, 200);
});