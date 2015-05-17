NProgress.configure({ showSpinner: false });

(function($){

  var app = (function() {

    return {
      init: function() {
        // console.log('app initialized');
      }
    };

  }());

  app.init();

  // pjax start
  $(document).on('pjax:start', function() {
    NProgress.start();
  });

  // pjax end
  $(document).on('pjax:end', function() {
    NProgress.done();
    app.init();
  });

})(jQuery);
