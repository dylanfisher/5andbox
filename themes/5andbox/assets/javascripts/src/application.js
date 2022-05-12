//////////////////////////////////////////////////////////////
// App namespace
//////////////////////////////////////////////////////////////

window.App = window.App || {};

App.$window = $(window);
App.$document = $(document);

App.pageLoad = [];
App.pageResize = [];
App.pageScroll = [];
App.pageThrottledScroll = [];
App.pageDebouncedResize = [];
App.breakpointChange = [];
App.teardown = [];
App.runFunctions = function(array) {
  for (var i = array.length - 1; i >= 0; i--) {
    array[i]();
  }
};
// App.isHomePage = function() {
//   return App.$body.hasClass('controller--home_pages');
// };
App.currentBreakpoint = undefined;

//////////////////////////////////////////////////////////////
// On page load
//////////////////////////////////////////////////////////////

$(function() {
  App.scrollTop = App.$window.scrollTop();

  App.windowWidth  = App.$window.width();
  App.windowHeight = App.$window.height();

  App.$html = $('html');
  App.$body = $('body');
  App.$header = $('#header');

  App.$html.removeClass('no-js');

  App.currentBreakpoint = App.breakpoint();

  App.runFunctions(App.pageLoad);
  App.runFunctions(App.pageResize);
  App.runFunctions(App.pageDebouncedResize);
  App.runFunctions(App.pageScroll);
  App.runFunctions(App.pageThrottledScroll);

  // In some situations you may wish to add or remove functionality after a brief delay
  // on initial page load to avoid situations where CSS transitions flash into an opacity: 0 state.
  window.setTimeout(function() {
    App.$html.removeClass('js-preload');
    App.$document.trigger('app:delayed-page-load');
  }, 200);
});

//////////////////////////////////////////////////////////////
// On scroll
//////////////////////////////////////////////////////////////

App.$window.on('scroll', function() {
  App.scrollTop = App.$window.scrollTop();

  App.runFunctions(App.pageScroll);
});

App.$window.on('scroll', $.throttle(200, function() {
  App.runFunctions(App.pageThrottledScroll);
}));

//////////////////////////////////////////////////////////////
// On resize
//////////////////////////////////////////////////////////////

App.$window.on('resize', function() {
  App.windowWidth  = App.$window.width();
  App.windowHeight = App.$window.height();

  if ( App.currentBreakpoint != App.breakpoint() ) App.$document.trigger('app:breakpoint-change');
  App.currentBreakpoint = App.breakpoint();

  App.runFunctions(App.pageResize);
});

App.$window.on('resize', $.debounce(500, function() {
  App.runFunctions(App.pageDebouncedResize);
}));

//////////////////////////////////////////////////////////////
// On breakpoint change
//////////////////////////////////////////////////////////////

App.$document.on('app:breakpoint-change', function() {
  App.runFunctions(App.breakpointChange);
});

App.breakpoint = function(checkIfSize) {
  // Make sure these match the breakpoint variables set in variables.scss
  var sm = 576;
  var md = 768;
  var lg = 1100;
  var xl = 1400;
  var breakpoint;

  if ( App.windowWidth < sm) {
    breakpoint = 'xs';
  } else if ( App.windowWidth >= xl ) {
    breakpoint = 'xl';
  } else if ( App.windowWidth >= lg ) {
    breakpoint = 'lg'
  } else if ( App.windowWidth >= md ) {
    breakpoint = 'md';
  } else {
    breakpoint = 'sm';
  }

  if ( checkIfSize !== undefined ) {
    if ( checkIfSize == 'xs' ) {
      return App.windowWidth < sm;
    } else if ( checkIfSize == 'sm' ) {
      return (App.windowWidth >= sm && App.windowWidth < md);
    } else if ( checkIfSize == 'md' ) {
      return (App.windowWidth >= md && App.windowWidth < lg);
    } else if ( checkIfSize == 'lg' ) {
      return (App.windowWidth >= lg && App.windowWidth < xl);
    } else if ( checkIfSize == 'xl' ) {
      return App.windowWidth >= xl;
    }
  } else {
    return breakpoint;
  }
};

App.breakpoint.isMobile = function() {
  return ( App.breakpoint('xs') || App.breakpoint('sm') );
};
