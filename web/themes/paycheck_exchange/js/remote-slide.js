/**
 * @file
 * A JavaScript file for the theme.
 *
 * In order for this JavaScript to be loaded on pages, see the instructions in
 * the README.txt next to this file.
 */

// JavaScript should be made compatible with libraries other than jQuery by
// wrapping it with an "anonymous closure". See:
// - https://drupal.org/node/1446420
// - http://www.adequatelygood.com/2010/3/JavaScript-Module-Pattern-In-Depth
(function (Drupal, $, window) {

    // To understand behaviors, see https://www.drupal.org/node/2269515
    Drupal.behaviors.basic = {
        attach: function (context, settings) {
            
            // Flex Slider
            $(window).load(function() {
                $('#commerce-image-nav').flexslider({
                    animation: "slide",
                    controlNav: false,
                    animationLoop: false,
                    slideshow: false,
                    itemWidth: 1,
                    itemMargin: 0,
                    maxItems: 0,
                    minItems: 0,
                    move: 0,
                    asNavFor: '#commerce-image-main'
                    
                });

                $('#commerce-image-main').flexslider({
                    animation: "fade",
                    controlNav: false,
                    animationLoop: false,
                    slideshow: false,
                    controlNav: false,
                    sync: "#commerce-image-nav",
                    start: function(slider){
                      $('body').removeClass('loading');
                    }
                });
            });

        }
    };



} (Drupal, jQuery, this));