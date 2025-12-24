(function($) {
  // Remove event handlers before assigning them. This ensures that the event
  // will not stack when using turbolinks. Use a namespaced handler to
  // avoid removing all events assigned to the element.
  // http://api.jquery.com/off/
  // $.fn.offOn = function(event, selector, callback) {
  //   if ( event.indexOf('.') == -1 ) {
  //     console.warn('You are disabling all "' + event + '" events attached to "' + this.selector + '".\nConsider namespacing your event, e.g. $("element").on("click.myNamespace", function(){});');
  //   }
  //   return this.off(event).on(event, selector, callback);
  // };

  // jQuery nextWrap and prevWrap selectors.
  // Usage: $('.element').nextWrap()
  $.fn.nextWrap = function(selector) {
    var $el = $(this);

    var $next = $el.next( selector );

    if ( ! $next.length ) {
      $next = $el.nextAll( selector ).first();
    }

    if ( ! $next.length ) {
      $next = $el.parent().children( selector ).first();
    }

    return $next;
  };

  $.fn.prevWrap = function(selector) {
    var $el = $(this);

    var $previous = $el.prev( selector );

    if ( ! $previous.length ) {
      $previous = $el.prevAll( selector ).first();
    }

    if ( ! $previous.length ) {
      $previous = $el.parent().children( selector ).last();
    }

    return $previous;
  };
}(jQuery));
