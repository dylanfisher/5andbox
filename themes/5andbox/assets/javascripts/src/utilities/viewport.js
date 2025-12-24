// import app from "../app";

export const isVisible = (el, root = null) => {
  el = (el instanceof jQuery) ? el.get(0) : el;
  root = (root instanceof jQuery) ? root.get(0) : root;

  const rect = el.getBoundingClientRect();
  const rootRect = root ? root.getBoundingClientRect() : null;
  const windowHeight = (window.innerHeight || document.documentElement.clientHeight);
  const windowWidth = (window.innerWidth || document.documentElement.clientWidth);
  const viewportTop = rootRect ? rootRect.top : 0;
  const viewportBottom = rootRect ? rootRect.bottom : windowHeight;
  const viewportLeft = rootRect ? rootRect.left : 0;
  const viewportRight = rootRect ? rootRect.right : windowWidth;

  // http://stackoverflow.com/questions/325933/determine-whether-two-date-ranges-overlap
  const vertInView = (rect.top <= viewportBottom) && ((rect.top + rect.height) >= viewportTop);
  const horInView = (rect.left <= viewportRight) && ((rect.left + rect.width) >= viewportLeft);

  return (vertInView && horInView);
};
