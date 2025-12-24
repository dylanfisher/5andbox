// Lazy load images

window.lazySizesConfig = window.lazySizesConfig || {};

window.lazySizesConfig.expand = 1000;
window.lazySizesConfig.expFactor = 2.5;

document.addEventListener('lazybeforeunveil', function(e) {
  var bg = e.target.getAttribute('data-bg');

  // if ( e.target.parentElement.classList.contains('forest-image-jump-fix') ) {
  //   e.target.parentElement.classList.add('forest-image-jump-fix--lazyloaded');
  // }

  if ( App && App.breakpoint && App.breakpoint.isMobile() ) {
    var srcMobile = e.target.getAttribute('data-src-mobile');
    var bgMobile = e.target.getAttribute('data-bg-mobile');

    if ( srcMobile ) e.target.setAttribute('data-src', srcMobile);
    if ( bgMobile ) bg = e.target.getAttribute('data-bg-mobile');
  }

  if ( bg ) {
    e.target.style.backgroundImage = 'url(' + bg + ')';
    // e.target.innerHTML = '<img src="' + bg + '" class="background-image__accessible" aria-hidden="true">';
  }
});
