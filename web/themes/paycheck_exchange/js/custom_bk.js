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
          var getFormat = function() {
              if (typeof(Storage) !== "undefined" && localStorage.getItem("resultsFormat")) {
                return localStorage.getItem("resultsFormat");
              }
              return 'grid';
            },
            toggleFormat = function(format) {
              $('#grid-toggle, #list-toggle, .product-view').removeClass('active view-grid view-list');
              $('#'+format+'-toggle').addClass('active');
              $('.product-view').addClass('view-'+format);

              if (typeof(Storage) !== "undefined")
                localStorage.setItem("resultsFormat", format);

              console.log(format);
            };

          $("#list-toggle").click(function() {
            toggleFormat('list');
          });

          $("#grid-toggle").click(function() {
            toggleFormat('grid');
          });

          toggleFormat( getFormat() );
        }
    };



} (Drupal, jQuery, this));
