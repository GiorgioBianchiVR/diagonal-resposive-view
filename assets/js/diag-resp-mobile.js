// Configuration for scroll-triggered snaps
const SCROLL_SNAP_ENABLED = true;
const SCROLL_SNAP_DELAY_MS = 250; // delay before snapping on entry
const SCROLL_THRESHOLD = 0.75; // 75% visible counts as "entered"
const MOBILE_ONLY = true;

const PREFERS_REDUCED_MOTION = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

let scrollObserver = null;

function initScrollObserverIfNeeded() {
  if (!SCROLL_SNAP_ENABLED) return;
  if (PREFERS_REDUCED_MOTION) return;
  if (!('IntersectionObserver' in window)) return;

  // create observer once
  if (scrollObserver) return;

  scrollObserver = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      const inst = entry.target.__panelInstance;
      if (!inst) return;

      if (entry.intersectionRatio >= SCROLL_THRESHOLD) {
        inst.onEnter();
      } else {
        const rootBottom = entry.rootBounds ? entry.rootBounds.bottom : window.innerHeight;
        const isBottomExit = entry.boundingClientRect.bottom >= window.innerHeight;
        inst.onExit(isBottomExit);
      }
    });
  }, { threshold: SCROLL_THRESHOLD });
}

// Initialize all swipeable panel instances on the page
document.querySelectorAll('.panel-viewport').forEach(viewport => {
  const track     = viewport.querySelector('.panel-track');
  const hint      = viewport.querySelector('.swipe-hint');

  // When flipped, the track starts on the right side (panel B visible)
  // and drags in the opposite direction
  const isFlipped = track.classList.contains('flipped');

  // SNAP[0] = default starting position, SNAP[1] = alternative position (in vw)
  const SNAP = isFlipped ? [-100, 0] : [0, -100];

  let currentSnap     = 0;     // index of the current snap position
  let startX          = 0;     // pointer X at drag start
  let startTime       = 0;     // timestamp at drag start (for velocity calc)
  let isDragging      = false;
  let currentOffset   = 0;     // current translateX value in vw
  let dragStartOffset = 0;     // offset at the moment drag began

  // Timeout id for scheduled scroll-triggered snap
  let enterTimeout = null;

  // --- Utilities ---

  // Convert pixels to viewport-width units
  const pxToVw = px => (px / window.innerWidth) * 100;

  // Apply translateX to the track, with or without CSS transition
  function setOffset(vw, animate = true) {
    track.style.transition = animate
      ? 'transform 0.65s cubic-bezier(0.25, 0.46, 0.45, 0.94)'
      : 'none';
    track.style.transform = `translateX(${vw}vw)`;
    currentOffset = vw;
  }

  // Snap the track to a given index (0 or 1) with animation
  function snapTo(index) {
    currentSnap = Math.max(0, Math.min(1, index));
    setOffset(SNAP[currentSnap]);
    // Hide the hint arrow once the user has revealed the other panel
    hint.style.opacity = currentSnap === 0 ? '1' : '0';
  }

  // --- Scroll-triggered handlers ---
  function clearEnterTimeout() {
    if (enterTimeout) { clearTimeout(enterTimeout); enterTimeout = null; }
  }

  function onEnter() {
    // Don't auto-snap while user is actively dragging
    if (isDragging) return;
    clearEnterTimeout();
    enterTimeout = setTimeout(() => {
      // Snap to the alternate (final) position when entering
      snapTo(1);
      enterTimeout = null;
    }, SCROLL_SNAP_DELAY_MS);
  }

  function onExit(isBottomExit) {
    clearEnterTimeout();
    if (isDragging) return;
    if (!isBottomExit) return;
    // Snap back to initial position only when leaving by scrolling down past the bottom
    snapTo(0);
  }

  // --- Drag logic ---

  function onDragStart(clientX) {
    // Cancel any scheduled scroll snap when user starts dragging
    clearEnterTimeout();
    isDragging      = true;
    startX          = clientX;
    startTime       = Date.now();
    dragStartOffset = currentOffset;
    track.classList.add('dragging'); // disables transition + changes cursor
  }

  function onDragMove(clientX) {
    if (!isDragging) return;

    const delta = pxToVw(clientX - startX);
    let next    = dragStartOffset + delta;

    // Clamp within snap bounds + 10vw rubber-band overshoot on each side
    const lo = Math.min(SNAP[0], SNAP[1]);
    const hi = Math.max(SNAP[0], SNAP[1]);
    next = Math.max(lo - 10, Math.min(hi + 10, next));

    setOffset(next, false); // no animation while actively dragging
  }

  function onDragEnd(clientX) {
    if (!isDragging) return;
    isDragging = false;
    track.classList.remove('dragging');

    const deltaVw  = pxToVw(clientX - startX);
    const velocity = deltaVw / (Date.now() - startTime); // vw/ms, positive = rightward

    // A fast flick snaps by direction regardless of position
    const isFlick = Math.abs(velocity) > 0.3;

    if (isFlick) {
      // Normal:  rightward → show panel A (index 0), leftward → show panel B (index 1)
      // Flipped: rightward → show panel A (index 1), leftward → show panel B (index 0)
      snapTo(isFlipped ? (velocity > 0 ? 1 : 0) : (velocity > 0 ? 0 : 1));
    } else {
      // Snap by whichever side the midpoint was crossed
      const mid = (SNAP[0] + SNAP[1]) / 2;
      snapTo(currentOffset > mid ? (isFlipped ? 1 : 0) : (isFlipped ? 0 : 1));
    }
  }

  // --- Event listeners ---

  // Touch (mobile)
 /*  track.addEventListener('touchstart', e => onDragStart(e.touches[0].clientX),        { passive: true });
  track.addEventListener('touchmove',  e => onDragMove(e.touches[0].clientX),         { passive: true });
  track.addEventListener('touchend',   e => onDragEnd(e.changedTouches[0].clientX));

  // Mouse (desktop / DevTools)
  track.addEventListener('mousedown', e => { e.preventDefault(); onDragStart(e.clientX); });
  window.addEventListener('mousemove', e => onDragMove(e.clientX));
  window.addEventListener('mouseup',   e => onDragEnd(e.clientX)); */

  // Tap with minimal movement toggles between panels
  /* track.addEventListener('click', e => {
    if (Math.abs(e.clientX - startX) < 5) snapTo(currentSnap === 0 ? 1 : 0);
  });
 */
  // Expose instance API for the IntersectionObserver to call
  viewport.__panelInstance = {
    snapTo,
    onEnter,
    onExit,
    get isDragging() { return isDragging; },
    destroy() { clearEnterTimeout(); }
  };

  // Set initial position (index 0 of SNAP array)
  snapTo(0);
});

// Initialize observer and start observing viewports when appropriate
initScrollObserverIfNeeded();
if (scrollObserver) document.querySelectorAll('.panel-viewport').forEach(v => scrollObserver.observe(v));

// Cleanup on unload
window.addEventListener('beforeunload', () => {
  if (scrollObserver) scrollObserver.disconnect();
  document.querySelectorAll('.panel-viewport').forEach(v => {
    if (v.__panelInstance && v.__panelInstance.destroy) v.__panelInstance.destroy();
  });
});
