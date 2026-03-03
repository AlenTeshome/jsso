/**
 * Polyfill for jQuery 4.0 compatibility with legacy plugins.
 */
(function (window, jQuery) {
  if (jQuery && typeof jQuery.isFunction !== 'function') {
    jQuery.isFunction = function (obj) {
      return typeof obj === 'function';
    };
  }
  // Colorbox often uses $.type too, which is also removed in 4.0
  if (jQuery && typeof jQuery.type !== 'function') {
    jQuery.type = function (obj) {
      return obj == null ? String(obj) : Object.prototype.toString.call(obj).replace(/^\[object /i, "").replace(/\]$/, "").toLowerCase();
    };
  }
})(window, window.jQuery);