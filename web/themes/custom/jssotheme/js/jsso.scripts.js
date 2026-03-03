(function ($, Drupal) {
    Drupal.behaviors.headerSearchToggle = {
        attach: function (context, settings) {

            // Select elements using 'context' to support AJAX
            var $trigger = $(context).find('#search-toggle');
            var $wrapper = $(context).find('#search-form-wrapper');
            var $input = $wrapper.find('input[type="search"], input[type="text"]').first();

            // Ensure we don't attach the event listener twice
            $trigger.once('headerSearchToggle').on('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                // Toggle visibility class
                $wrapper.toggleClass('is-visible');

                // Toggle ARIA attribute for accessibility
                var isExpanded = $wrapper.hasClass('is-visible');
                $(this).attr('aria-expanded', isExpanded);

                // Focus the input if opening
                if (isExpanded) {
                    setTimeout(function () {
                        $input.focus();
                    }, 100);
                }
            });

            // Close when clicking outside
            $(document).on('click', function (e) {
                if (!$wrapper.is(e.target) && $wrapper.has(e.target).length === 0 && !$trigger.is(e.target) && $trigger.has(e.target).length === 0) {
                    $wrapper.removeClass('is-visible');
                    $trigger.attr('aria-expanded', 'false');
                }
            });

            // Close on Escape key
            $(document).on('keydown', function (e) {
                if (e.key === 'Escape') {
                    $wrapper.removeClass('is-visible');
                    $trigger.attr('aria-expanded', 'false');
                }
            });
        }
    };



//sticky menu Js

  Drupal.behaviors.stickyMenu = {
    attach: function (context, settings) {

      // Run once per page load.
      $(window).on('scroll', function () {

        // Convert 10em to pixels (based on computed root font size)
        const rootFont = parseFloat(getComputedStyle(document.documentElement).fontSize);
        const triggerPoint = 10 * rootFont; // 10em in px

        if ($(this).scrollTop() > triggerPoint) {

          // Add your new class to .navigation
          $('nav.navbar.navbar-expand-lg', context).addClass("sticky");

        } else {

          // Remove the new class
          $('nav.navbar.navbar-expand-lg', context).removeClass("sticky");

        }

      });

    }
  };


})(jQuery, Drupal);