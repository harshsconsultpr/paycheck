(function ($) {
  Drupal.behaviors.pcx_categories_catalog = {
    attach: function (context, settings) {
      $('#block-paycheck-exchange-catalogmenu li.active > ul.child-menu a').hide();

      $('.views-field-view-taxonomy-term').each(function() {
        var href = $(this).find('a').attr('href');
        $('#block-paycheck-exchange-catalogmenu li.active > ul.child-menu a[href="'+href+'"]').show();
      });
    }
  };
}(jQuery));
