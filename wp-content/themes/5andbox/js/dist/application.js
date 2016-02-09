// Global namespace, window variables, etc.

$ = jQuery;

var App = {
  window: {
    width: $(window).width(),
    height: $(window).height(),
    scrollTop: $(window).scrollTop(),
  }
};

$(window).resize(function() {
  App.window.width  = $(window).width();
  App.window.height = $(window).height();
});

$(window).scroll(function() {
  App.window.scrollTop = $(window).scrollTop();
});