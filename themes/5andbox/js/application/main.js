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
  // $(document).on('pjax:start', function() {
  //   console.log("starting pjax");
  // });

  // pjax end
  $(document).on('pjax:end', function() {
    // console.log("ending pjax");
    app.init();
  });

})(jQuery);
