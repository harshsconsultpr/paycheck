(function ($) {
  Drupal.behaviors.pcx_checkout_flow = {
    attach: function (context, settings) {
      $('input[name="pcx_deduction[schedule]"]').change(function(e) {
        var total = $('.order-total-line-value').first().text().replace(/[$,]/g,''),
            schedule = $(this).val(),
            payment = "$" + (total / schedule).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');

        $('#edit-pcx-deduction-payment').val( payment );
      });
      $('input[name="pcx_deduction[schedule]"]').change();
    }
  };
}(jQuery));
