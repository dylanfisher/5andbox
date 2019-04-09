//////////////////////////////////////////////////////////////
// App namespace
//////////////////////////////////////////////////////////////

$ = jQuery;

window.App = window.App || {};

//////////////////////////////////////////////////////////////
// On page load
//////////////////////////////////////////////////////////////

$(function() {
  App.windowWidth    = $(window).width();
  App.windowHeight   = $(window).height();
  App.documentWidth  = $(document).width();
  App.documentHeight = $(document).height();

  App.scrollTop = $(window).scrollTop();

  App.homeUrl = $('html').attr('data-home-url');
  App.ajaxUrl = $('html').attr('data-ajax-url');
});

//////////////////////////////////////////////////////////////
// On scroll
//////////////////////////////////////////////////////////////

$(window).scroll(function() {
  App.scrollTop = $(window).scrollTop();
});

//////////////////////////////////////////////////////////////
// On resize
//////////////////////////////////////////////////////////////

$(window).resize(function() {
  App.windowWidth    = $(window).width();
  App.windowHeight   = $(window).height();
  App.documentWidth  = $(document).width();
  App.documentHeight = $(document).height();
});

App.breakpoint = function(checkIfSize) {
  // Make sure these match the breakpoint variables set in variables.scss
  var xs = 480;
  var sm = 768;
  var md = 992;
  var lg = 1200;
  var breakpoint;

  if ( App.windowWidth < xs ) {
    breakpoint = 'xs';
  } else if ( App.windowWidth >= md ) {
    breakpoint = 'lg';
  } else if ( App.windowWidth >= sm ) {
    breakpoint = 'md';
  } else {
    breakpoint = 'sm';
  }

  if ( checkIfSize !== undefined ) {
    if ( checkIfSize == 'xs' ) {
      return App.windowWidth < xs;
    } else if ( checkIfSize == 'sm' ) {
      return (App.windowWidth >= xs && App.windowWidth < sm);
    } else if ( checkIfSize == 'md' ) {
      return (App.windowWidth >= sm && App.windowWidth < md);
    } else if ( checkIfSize == 'lg' ) {
      return App.windowWidth >= md;
    }
  } else {
    return breakpoint;
  }
};

App.breakpoint.isMobile = function() {
  return ( App.breakpoint('xs') || App.breakpoint('sm') );
};

// Lazy load images

// window.lazySizesConfig = window.lazySizesConfig || {};

// window.lazySizesConfig.expand = 1000;
// window.lazySizesConfig.expFactor = 2.5;

// document.addEventListener('lazybeforeunveil', function(e) {
//   var bg = e.target.getAttribute('data-bg');

//   if ( typeof App === 'object' && App.breakpoint && App.breakpoint.isMobile() ) {
//     var srcMobile = e.target.getAttribute('data-src-mobile');

//     if ( srcMobile ) {
//       e.target.setAttribute('data-src', srcMobile);
//     }

//     bg = e.target.getAttribute('data-bg-mobile');
//   }

//   if ( bg ) {
//     e.target.style.backgroundImage = 'url(' + bg + ')';
//   }
// });
