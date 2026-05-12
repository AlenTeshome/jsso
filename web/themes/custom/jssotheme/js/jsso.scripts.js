/**
 * @file
 * JSSO Theme JavaScript behaviors.
 */

(function ($, Drupal, once) {
  'use strict';

  /**
   * Behavior for the Header Search Toggle.
   */
  Drupal.behaviors.headerSearchToggle = {
    attach: function (context, settings) {
      // 1. Target the toggle button using the Drupal 11 'once' syntax.
      // This returns an array of elements that haven't been processed yet.
      const searchToggles = once('headerSearchToggle', '#search-toggle', context);

      searchToggles.forEach(function (el) {
        const $trigger = $(el);
        const $wrapper = $('#search-form-wrapper');
        const $input = $wrapper.find('input[type="search"], input[type="text"]').first();

        // Handle the click event for the toggle button.
        $trigger.on('click', function (e) {
          e.preventDefault();
          e.stopPropagation();

          $wrapper.toggleClass('is-visible');
          const isExpanded = $wrapper.hasClass('is-visible');
          $(this).attr('aria-expanded', isExpanded);

          // Focus the input field if the search form is opening.
          if (isExpanded) {
            setTimeout(function () {
              $input.focus();
            }, 100);
          }
        });
      });

      // 2. Global Event Handlers (Click Outside & Escape Key).
      // We wrap these in 'once' targeting the document to prevent multiple
      // event listeners from being attached during AJAX refreshes.
      once('searchGlobalHandlers', 'html', context).forEach(function () {

        // Close search when clicking anywhere outside the wrapper or trigger.
        $(document).on('click', function (e) {
          const $wrapper = $('#search-form-wrapper');
          const $trigger = $('#search-toggle');

          if ($wrapper.hasClass('is-visible')) {
            if (!$wrapper.is(e.target) && $wrapper.has(e.target).length === 0 && !$trigger.is(e.target) && $trigger.has(e.target).length === 0) {
              $wrapper.removeClass('is-visible');
              $trigger.attr('aria-expanded', 'false');
            }
          }
        });

        // Close search on Escape key press.
        $(document).on('keydown', function (e) {
          if (e.key === 'Escape') {
            const $wrapper = $('#search-form-wrapper');
            if ($wrapper.hasClass('is-visible')) {
              $wrapper.removeClass('is-visible');
              $('#search-toggle').attr('aria-expanded', 'false');
            }
          }
        });
      });
    }
  };

  /**
   * Behavior for the Sticky Navigation Menu.
   */
  Drupal.behaviors.stickyMenu = {
    attach: function (context, settings) {
      // Use 'once' on the body to ensure the scroll listener is only attached once per page load.
      once('stickyMenuScroll', 'body', context).forEach(function () {
        const $window = $(window);
        const $nav = $('nav.navbar.navbar-expand-lg');

        $window.on('scroll', function () {
          // Compute 10em in pixels based on the current root font size.
          const rootFontSize = parseFloat(getComputedStyle(document.documentElement).fontSize);
          const triggerPoint = 10 * rootFontSize;

          // Toggle the 'sticky' class based on the scroll position.
          $nav.toggleClass('sticky', $window.scrollTop() > triggerPoint);
        });
      });
    }
  };

  Drupal.behaviors.navbarHoverEffect = {
    attach: function (context, settings) {
      // Target the top-level menu items that have dropdowns
      const menuItems = once('navbarHoverEffect', '.tbm-item--has-dropdown', context);

      menuItems.forEach(function (el) {
        const $item = $(el);
        const $navbar = $item.closest('nav.navbar');

        // Detect Hover
        $item.on('mouseenter', function () {
          $navbar.addClass('is-megamenu-active');
        }).on('mouseleave', function () {
          $navbar.removeClass('is-megamenu-active');
        });
      });
    }
  };


  Drupal.behaviors.navImageLogic = {
    attach: function (context) {
      // Use querySelectorAll to handle cases where there might be multiple headers 
      // (though usually there is just one)
      const headers = context.querySelectorAll('header');

      headers.forEach(header => {
        // 1. Check if the DIV with .with-image exists inside this header
        const hasImageDiv = header.querySelector('div.with-image');
        const hasVideo = header.querySelector('.full-width-video');

        // 2. Find the navbar
        const navbar = header.querySelector('nav.navbar');

        // 3. Logic: If the image div is NOT found AND the navbar exists
        if ((!hasImageDiv && navbar) && (!hasVideo && navbar)) {
          navbar.classList.add('nav-highlight');
          // console.log('No .with-image found in header. Added class to .navbar');
        } else if (navbar) {
          // Optional: Remove it if the div DOES exist (useful for AJAX/Responsive changes)
          navbar.classList.remove('nav-highlight');
        }
      });
    }
  };

})(jQuery, Drupal, once);