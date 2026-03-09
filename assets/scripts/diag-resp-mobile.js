document.querySelectorAll('.panel-viewport').forEach(viewport => {
  const track = viewport.querySelector('.panel-track');
  const hint  = viewport.querySelector('.swipe-hint');

  const SNAP = [0, -100];
  let currentSnap    = 0;
  let startX         = 0;
  let startTime      = 0;
  let isDragging     = false;
  let currentOffset  = 0;
  let dragStartOffset = 0;

  function vwToPx(vw) { return (vw / 100) * window.innerWidth; }
  function pxToVw(px) { return (px / window.innerWidth) * 100; }

  function setOffset(vw, animate = true) {
    track.style.transition = animate
      ? 'transform 0.45s cubic-bezier(0.25, 0.46, 0.45, 0.94)'
      : 'none';
    track.style.transform = `translateX(${vw}vw)`;
    currentOffset = vw;
  }

  function snapTo(index) {
    currentSnap = Math.max(0, Math.min(1, index));
    setOffset(SNAP[currentSnap]);
    hint.style.opacity = currentSnap === 0 ? '1' : '0';
  }

  function onDragStart(clientX) {
    isDragging = true;
    startX = clientX;
    startTime = Date.now();
    dragStartOffset = currentOffset;
    track.classList.add('dragging');
  }

  function onDragMove(clientX) {
    if (!isDragging) return;
    let next = dragStartOffset + pxToVw(clientX - startX);
    next = Math.max(SNAP[1] - 10, Math.min(SNAP[0] + 10, next));
    setOffset(next, false);
  }

  function onDragEnd(clientX) {
    if (!isDragging) return;
    isDragging = false;
    track.classList.remove('dragging');
    const deltaVw  = pxToVw(clientX - startX);
    const velocity = deltaVw / (Date.now() - startTime);
    const isFlick  = Math.abs(velocity) > 0.3;
    if (isFlick) {
      snapTo(velocity > 0 ? 0 : 1);
    } else {
      snapTo(currentOffset > (SNAP[0] + SNAP[1]) / 2 ? 0 : 1);
    }
  }

  track.addEventListener('touchstart', e => onDragStart(e.touches[0].clientX), { passive: true });
  track.addEventListener('touchmove',  e => onDragMove(e.touches[0].clientX),  { passive: true });
  track.addEventListener('touchend',   e => onDragEnd(e.changedTouches[0].clientX));

  track.addEventListener('mousedown', e => { e.preventDefault(); onDragStart(e.clientX); });
  window.addEventListener('mousemove', e => onDragMove(e.clientX));
  window.addEventListener('mouseup',   e => onDragEnd(e.clientX));

  track.addEventListener('click', e => {
    if (Math.abs(e.clientX - startX) < 5) snapTo(currentSnap === 0 ? 1 : 0);
  });

  snapTo(0);
});
