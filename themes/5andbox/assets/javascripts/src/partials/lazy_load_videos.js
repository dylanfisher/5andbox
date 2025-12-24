import throttle from 'lodash/throttle'
import debounce from 'lodash/debounce'
import * as viewport from "../utilities/viewport";

export class Video {
  static _uid = 0;

  constructor(placeholderEl = null, callbackOrOptions = null) {
    // Per-instance event namespace
    this._ns = `.lazyLoadVideo.${++Video._uid}`;

    // Optional callback: function or { onLoadedmetadata: fn, scrollContainer: el|selector|jQuery|window }
    this._onLoadedmetadata =
      typeof callbackOrOptions === 'function'
        ? callbackOrOptions
        : (callbackOrOptions && typeof callbackOrOptions.onLoadedmetadata === 'function'
            ? callbackOrOptions.onLoadedmetadata
            : null);

    const options = (callbackOrOptions && typeof callbackOrOptions === 'object') ? callbackOrOptions : null;
    this._scrollContainer = options?.scrollContainer || window;
    if (typeof this._scrollContainer === 'string') this._scrollContainer = document.querySelector(this._scrollContainer);
    if (this._scrollContainer instanceof jQuery) this._scrollContainer = this._scrollContainer.get(0);
    if (!this._scrollContainer) this._scrollContainer = window;
    this.$scrollContainer = (this._scrollContainer === window) ? App.$window : $(this._scrollContainer);

    // If a placeholder element is passed, only operate on that one
    this.$placeholders = placeholderEl
      ? $(placeholderEl).filter('.lazy-video-placeholder')
      : $('.lazy-video-placeholder');

    if (!this.$placeholders.length) return;

    this._insertedVideos = [];
    this.initialize();
    this.removePersistedVideos();

    document.addEventListener('turbo:before-cache', this.turnOffEventListeners);
    // document.addEventListener('turbo:before-visit', this.turnOffEventListeners);
    document.addEventListener('turbo:before-render', this.turnOffEventListeners);
  }

  removePersistedVideos() {
    document.querySelectorAll('.turbo-persist-video').forEach((video) => {
      video.pause();
      video.src = '';
      video.remove();
      video = null;
    });
  }

  turnOffEventListeners() {
    // Clean up only this instance's videos/listeners
    if (this._insertedVideos && this._insertedVideos.length) {
      this._insertedVideos.forEach((video) => {
        if (!video || video.classList.contains('turbo-persist-video')) return;
        try {
          video.pause();
          video.src = '';
          video.remove();
        } catch (_) {}
      });
    }
    this._insertedVideos = [];
    this.$placeholders && this.$placeholders.removeClass('lazy-video-initialized');

    // Remove only this instance's scroll/resize listeners
    App.$window.off('resize' + this._ns);
    this.$scrollContainer && this.$scrollContainer.off('scroll' + this._ns);
    if (this._resizeObserver) {
      try {
        this._resizeObserver.disconnect();
      } catch (_) {}
    }
    this._resizeObserver = null;

    // Clean up any other regular <video> tags
    // document.querySelectorAll('video').forEach(video => {
    //   try {
    //     // Stop playback
    //     video.pause();

    //     // Clear the source(s)
    //     video.removeAttribute('src');
    //     if (video.querySelector('source')) {
    //       video.querySelectorAll('source').forEach(source => {
    //         source.removeAttribute('src');
    //       });
    //     }

    //     // Force the video element to unload
    //     video.load();

    //     // Remove from DOM if you want to be absolutely sure
    //     // video.remove();
    //   } catch (e) {
    //     console.warn('Video cleanup error:', e);
    //   }
    // });


    // Do NOT remove the global document click handler here; it is set outside the class.
    // App.$document.off('click.lazyLoadVideo', '.lazy-video'); // (intentionally removed)
  }

  initialize() {
    if (!this.$placeholders.length) return;

    this.$placeholders.each((_, el) => {
      const $placeholder = $(el);
      if ($placeholder.hasClass('lazy-video-initialized')) return;
      if ($placeholder.hasClass('lazy-video-delay-initialization')) return;

      if ($placeholder.closest('.d-landscape').length && !App.isLandscape()) return;
      if ($placeholder.closest('.d-portrait').length && !App.isPortrait()) return;

      $placeholder.addClass('lazy-video-initialized');
    });

    const createVideoForPlaceholder = ($placeholder) => {
      // Avoid duplicate instantiation
      if ($placeholder.data('lazy-video-created')) return $placeholder.data('lazy-video-created');

      const videoHTML = $placeholder.attr('data-video-tag-html');
      const $videoOrJumpFix = $(videoHTML);
      const $video = $videoOrJumpFix.is('video') ? $videoOrJumpFix : $videoOrJumpFix.find('.sandbox-video');
      if (!$video.length) return null;

      const srcFull = $video.attr('data-src');
      const srcMobile = $video.attr('data-src-mobile');
      const src = (App.breakpoint.isMobile() && srcMobile) ? srcMobile : srcFull;
      const placeholderHeight = $placeholder.height();

      $video.addClass('lazy-video').attr('src', src).css({ height: placeholderHeight });
      $placeholder.hide().after($videoOrJumpFix);

      const videoEl = $video[0];
      this._insertedVideos.push(videoEl);

      $video.one('loadedmetadata', () => {
        $video.css({ height: '' });

        // User-provided callback, if any
        if (typeof this._onLoadedmetadata === 'function') {
          try {
            this._onLoadedmetadata(videoEl, $video, $placeholder[0]);
          } catch (e) {
            console.warn('onLoadedmetadata callback threw an error:', e);
          }
        }
      });

      $placeholder.data('lazy-video-created', $video);
      return $video;
    };

    const getVideoForPlaceholder = ($placeholder) => {
      const existing = $placeholder.data('lazy-video-created');
      if (existing && existing.length) return existing;

      const $next = $placeholder.next();
      if ($next.hasClass('sandbox-video-jump-fix')) {
        const $v = $next.find('.sandbox-video');
        if ($v.length) {
          $placeholder.data('lazy-video-created', $v);
          return $v;
        }
      } else if ($next.is('video')) {
        $placeholder.data('lazy-video-created', $next);
        return $next;
      }
      return null;
    };

    const checkForVisibility = () => {
      if (!this.$placeholders || !this.$placeholders.length) return;

      const getScrollTop = () => {
        if (this._scrollContainer === window) return App.scrollTop;
        return this._scrollContainer?.scrollTop || 0;
      };

      const getViewportHeight = () => {
        if (this._scrollContainer === window) return App.windowHeight;
        return this._scrollContainer?.clientHeight || 0;
      };

      this.$placeholders.each((_, placeholderEl) => {
        const $placeholder = $(placeholderEl);

        // Ensure we have a video element only when visible
        let $video = getVideoForPlaceholder($placeholder);
        if (!$video || !$video.length) {
          if (!viewport.isVisible($placeholder[0], this._scrollContainer === window ? null : this._scrollContainer)) return;
          $video = createVideoForPlaceholder($placeholder);
          if (!$video || !$video.length) return;
        }

        // Don't try to check visibility for videos that explicitly disable the functionality
        if ($video.attr('data-ignore-video-visibility-listeners')) return;

        const video = $video[0];
        const autoplay = $video.attr('autoplay');
        const loop = $video.attr('loop');
        const isInHero = $video.closest('#hero-viewport-container').length;
        let isVisible = false;

        if (!autoplay || autoplay == 'false') {
          $video.data('lazy-load-video-autoplay-disabled', true);
          // return;
        }

        if (isInHero) {
          if (getScrollTop() < getViewportHeight() / 2) {
            isVisible = true;
          }
        } else if (viewport.isVisible(video, this._scrollContainer === window ? null : this._scrollContainer)) {
          if ($video.data('has-been-paused-by-user')) return;
          if ($video.data('hasFinishedPlaying') == true && ( !loop || loop == 'false' )) return; // existing logic retained
          isVisible = true;
        }

        if (isVisible && !$video.is(':visible')) {
          isVisible = false;
        }

        if (isVisible) {
          var playPromise = video.play();
          if (playPromise !== undefined) {
            playPromise.then(function() {
              // Automatic playback started
            }).catch(function(error) {
              // Auto-play was prevented
              console.warn('Error playing video', error);
            });
          }
        } else {
          video.pause();
        }
      });
    };

    // Per-instance, namespaced listeners
    App.$window.on('resize' + this._ns, debounce(checkForVisibility, 500));
    this.$scrollContainer.on('scroll' + this._ns, throttle(checkForVisibility, 250));
    if (this._scrollContainer !== window && typeof ResizeObserver !== 'undefined') {
      this._resizeObserver = new ResizeObserver(debounce(checkForVisibility, 250));
      this._resizeObserver.observe(this._scrollContainer);
    }
    checkForVisibility();

    var $disabledVideos = this.$placeholders.filter(function() {
      const $ph = $(this);
      const $v = $ph.data('lazy-video-created');
      return $v && $v.data('lazy-load-video-autoplay-disabled') == true;
    });

    if (this.$placeholders.length == $disabledVideos.length) {
      this.turnOffEventListeners();
    }
  }
}

// Export an init function that looks for and instantiates the module on pageload
export default (() => {
  App.pageLoad.push(() => {
    // Backwards compatibility: still auto-initialize all placeholders on pageload
    $('.lazy-video-placeholder').each(function() {
      new Video(this, { scrollContainer: (document.getElementById('featured-project-slides') || window) });
    });
  });

  document.addEventListener('turbo:frame-load', function (event) {
    const frame = event.target;
    if (!(frame instanceof HTMLElement) || frame.tagName !== 'TURBO-FRAME') return;

    $(frame).find('.lazy-video-placeholder').each(function() {
      new Video(this, { scrollContainer: (document.getElementById('featured-project-slides') || window) });
    });
  });

  $(function() {
    App.$document.on('app:orientation-change', function() {
      $('.lazy-video-placeholder').each(function() {
        new Video(this, { scrollContainer: (document.getElementById('featured-project-slides') || window) });
      });
    });

    App.$document.on('click.lazyLoadVideo', '.sandbox-video', function() {
      var $video = $(this);

      if ( $video.attr('controls') ) return;
      if ( $video.hasClass('lightboxable') ) return;

      // Don't pause videos that have a ignore-video-visibility-listeners attribute
      if ($video.closest('[data-ignore-video-visibility-listeners]').length) return;

      if ($video[0].paused) {
        $video.data('has-been-paused-by-user', false);
        $video[0].play();
      } else {
        $video.data('has-been-paused-by-user', true);
        $video[0].pause();
      }
    });

    App.$document.on('click.lazyLoadVideoSoundToggle', '[data-video-sound-toggle]', function() {
      var $toggle = $(this);
      var $scope = $toggle.closest('[data-video-scope]');
      if (!$scope.length) return;

      var $video = $scope.find('video').first();
      if (!$video.length) return;

      var nextMuted = !$video.prop('muted');
      $video.prop('muted', nextMuted);
      $toggle.text(nextMuted ? 'Sound on' : 'Sound off');
    });
  });
})();
