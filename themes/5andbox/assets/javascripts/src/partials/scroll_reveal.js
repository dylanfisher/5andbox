// Scroll reveal triggers

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

let opacityTriggers = [];
let hasBoundHandlers = false;

const getScroller = () => document.getElementById('scroller-target') || window;
const getScrollTop = () => {
  const scroller = getScroller();
  if (scroller === window) return window.scrollY || 0;
  return scroller.scrollTop || 0;
};
let skipDelayUntil = 0;
let isInitialScrollLoad = false;
const delayClassName = 'scroll-reveal--delay';
const delayExtra = 0.4;

const isInViewport = (el) => {
  const scroller = getScroller();
  const rect = el.getBoundingClientRect();

  if (scroller === window) {
    return rect.bottom >= 0 && rect.top <= window.innerHeight;
  }

  const scrollerRect = scroller.getBoundingClientRect();
  return rect.bottom >= scrollerRect.top && rect.top <= scrollerRect.bottom;
};

const animateReveal = (elements, options) => {
  const { shouldSkipDelay, useStagger } = options;
  const baseDelay = shouldSkipDelay ? 0 : 0.2;
  const staggerDelay = shouldSkipDelay ? 0 : 0.2;

  elements.forEach((el, index) => {
    const extraDelay = (!shouldSkipDelay && el.classList.contains(delayClassName)) ? delayExtra : 0;
    const delay = baseDelay + (useStagger ? staggerDelay * index : 0) + extraDelay;
    gsap.to(el, {
      duration: 2,
      // ease: 'power2.in',
      delay,
      opacity: 1
    });
  });
};

const clearScrollTriggers = () => {
  const triggerArrays = [opacityTriggers];

  triggerArrays.forEach((triggers) => {
    triggers.forEach((trigger) => trigger.kill());
    triggers.length = 0; // Clear array
  });
};

const initOpacityTriggers = () => {
  opacityTriggers = ScrollTrigger.batch('.scroll-reveal--opacity', {
    start: 'top 95%',
    scroller: getScroller(),

    // When element enters viewport (downward)
    onEnter: (batch) => {
      const inView = batch.filter((el) => isInViewport(el));
      if (!inView.length) return;
      const shouldSkipDelay = skipDelayUntil && performance.now() <= skipDelayUntil;
      animateReveal(inView, { shouldSkipDelay, useStagger: true });
    },

    // When re-entering from below (scrolling up)
    onEnterBack: (batch) => {
      const inView = batch.filter((el) => isInViewport(el));
      if (!inView.length) return;
      animateReveal(inView, { shouldSkipDelay: false, useStagger: false });
    },
  });
};

const initializeScrollTriggers = () => {
  if (
    !document.querySelector('.scroll-reveal--opacity')
  ) return;

  isInitialScrollLoad = getScrollTop() > 0;
  if (isInitialScrollLoad) {
    skipDelayUntil = performance.now() + 500;
  }

  initOpacityTriggers();
  window.setTimeout(() => {
    isInitialScrollLoad = false;
  }, 0);
};

const setupScrollTriggerHandlers = () => {
  if (hasBoundHandlers) return;
  hasBoundHandlers = true;
  window.addEventListener('beforeunload', () => {
    hasBoundHandlers = false;
  }, { once: true });
  document.addEventListener('turbo:before-render', clearScrollTriggers, { once: true });
  document.addEventListener('turbo:before-cache', clearScrollTriggers, { once: true });

  document.addEventListener('turbo:load', () => {
    clearScrollTriggers();
    initializeScrollTriggers();
  });
};

// Initial Page Load
document.addEventListener('DOMContentLoaded', () => {
  initializeScrollTriggers();
  setupScrollTriggerHandlers();
});

document.addEventListener('app:reflow', () => {
  clearScrollTriggers();
  // Removed: no ScrollTrigger.refresh() calls per request
  initializeScrollTriggers();
});
