// Global namespace, window variables, etc.

$ = jQuery;

var App = {
  windowWidth: $(window).width(),
  windowHeight: $(window).height(),
  documentWidth: $(document).width(),
  documentHeight: $(document).height(),
  scrollTop: $(window).scrollTop(),
  homeUrl: $('html').attr('data-home-url'),
  ajaxUrl: $('html').attr('data-ajax-url'),
};

$(window).resize(function() {
  App.windowWidth    = $(window).width();
  App.windowHeight   = $(window).height();
  App.documentWidth  = $(document).width();
  App.documentHeight = $(document).height();
});

$(window).scroll(function() {
  App.scrollTop = $(window).scrollTop();
});

App.breakpoint = function(checkIfSize) {
  var xs = 480;
  var sm = 768;
  var md = 992;
  var lg = 1200;
  var breakpoint;

  if(App.windowWidth < xs) {
    breakpoint = 'xs';
  } else if(App.windowWidth >= md) {
    breakpoint = 'lg';
  } else if(App.windowWidth >= sm) {
    breakpoint = 'md';
  } else {
    breakpoint = 'sm';
  }

  if(checkIfSize !== undefined) {
    if(checkIfSize == 'xs') {
      return App.windowWidth < xs;
    } else if(checkIfSize == 'sm') {
      return (App.windowWidth >= xs && App.windowWidth < sm);
    } else if(checkIfSize == 'md') {
      return (App.windowWidth >= sm && App.windowWidth < md);
    } else if(checkIfSize == 'lg') {
      return App.windowWidth >= md;
    }
  } else {
    return breakpoint;
  }
};

App.breakpoint.isMobile = function() {
  return ( App.breakpoint('xs') || App.breakpoint('sm') );
};
