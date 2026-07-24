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
      const headers = context.querySelectorAll('header');
      const hasHeroView = document.querySelector('.view-page-hero-image .with-image') !== null || document.querySelector('.banner-video-row') !== null;
      const isInitiativeNode = document.body.classList.contains('page-node-type-initiatives') || document.body.classList.contains('page-node-type-initiative');

      headers.forEach(header => {
        const hasImageDiv = header.querySelector('div.with-image');
        const hasVideo = header.querySelector('.full-width-video');
        const navbar = header.querySelector('nav.navbar');

        if (!hasImageDiv && !hasVideo && !hasHeroView && !isInitiativeNode && navbar) {
          navbar.classList.add('nav-highlight');
          document.body.classList.add('has-nav-highlight');
        } else if (navbar) {
          navbar.classList.remove('nav-highlight');
          document.body.classList.remove('has-nav-highlight');
        }
      });
    }
  };

  Drupal.behaviors.bannerSwiper = {
    attach: function (context) {
      const swipers = once('bannerSwiperInit', '.swiper-container-banner', context);
      
      swipers.forEach(function (el) {
        new Swiper(el, {
          slidesPerView: 1,
          spaceBetween: 16,
          loop: false,
          navigation: {
            nextEl: '.swiper-button-next-banner',
            prevEl: '.swiper-button-prev-banner',
          },
          pagination: {
            el: '.swiper-pagination-banner',
            clickable: true,
          },
          breakpoints: {
            576: {
              slidesPerView: 2,
              spaceBetween: 20,
            },
            768: {
              slidesPerView: 2,
              spaceBetween: 24,
            },
            1200: {
              slidesPerView: 3,
              spaceBetween: 24,
            }
          }
        });
      });
    }
  };

  Drupal.behaviors.scrollReveal = {
    attach: function (context) {
      const revealElements = once('scrollRevealInit', '.reveal-on-scroll', context);

      if (revealElements.length === 0) return;

      const revealOptions = {
        threshold: 0.15,
        rootMargin: "0px 0px -50px 0px"
      };

      let revealDelayIndex = 0;
      let revealTimeout = null;

      const revealObserver = new IntersectionObserver(function (entries, observer) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            // Apply a sequential delay based on the index
            entry.target.style.transitionDelay = (revealDelayIndex * 0.15) + 's';
            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
            revealDelayIndex++;
          }
        });

        // Reset the index after a short timeout so the next batch of scroll items starts from 0
        clearTimeout(revealTimeout);
        revealTimeout = setTimeout(function() {
          revealDelayIndex = 0;
        }, 100);

      }, revealOptions);

      revealElements.forEach(function (el) {
        revealObserver.observe(el);
      });
    }
  };

  Drupal.behaviors.parallaxBanner = {
    attach: function (context) {
      const parallaxElements = once('parallaxInit', '.banner-video .full-width-video', context);

      if (parallaxElements.length === 0) return;

      $(window).on('scroll', function () {
        const scrolled = $(window).scrollTop();
        // Move the video down at half the scroll speed
        $(parallaxElements).css('transform', 'translateY(' + (scrolled * 0.4) + 'px)');
      });
    }
  };

  /**
   * Behavior for Exposed Filter Accordions.
   */
  Drupal.behaviors.exposedFilterAccordion = {
    attach: function (context) {
      // Find all fieldsets inside the views exposed form
      const filterFieldsets = once('exposedFilterAccordionInit', '.views-exposed-form fieldset', context);

      filterFieldsets.forEach(function (fieldset) {
        const $fieldset = $(fieldset);
        const $legend = $fieldset.find('legend').first();
        const $wrapper = $fieldset.find('.fieldset-wrapper').first();

        // Check if there are any checked checkboxes inside to keep it open initially
        const hasChecked = $wrapper.find('input[type="checkbox"]:checked').length > 0;

        // Add a class to the fieldset for CSS styling
        $fieldset.addClass('filter-accordion');
        $legend.addClass('filter-accordion-toggle');

        // Initial state
        if (!hasChecked) {
          $wrapper.hide();
          $fieldset.removeClass('is-open');
        } else {
          $fieldset.addClass('is-open');
        }

        // Toggle on click
        $legend.on('click', function (e) {
          e.preventDefault();
          $fieldset.toggleClass('is-open');
          $wrapper.slideToggle(200);
        });
      });
    }
  };

})(jQuery, Drupal, once);