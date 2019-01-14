(function ($) {
  Drupal.behaviors.pcx_checkout_flow_shipping = {
    attach: function (context, settings) {
      var $calculate_button = $('button[data-drupal-selector="edit-shipping-information-recalculate-shipping"]');
      var $clear_button = $('<input type="button" class="btn pull-right" id="clear-shipping-information" value="Clear Form" />');
      
      $clear_button.click(function() {
        $('#commerce-checkout-flow-pcx-checkout-flow-plugin input[type="text"]:enabled').val('');
        $('#commerce-checkout-flow-pcx-checkout-flow-plugin input[type="tel"]:enabled').val('');
        $('#commerce-checkout-flow-pcx-checkout-flow-plugin select').val('');
      });

      $calculate_button.text('Claim Free Shipping');
      $calculate_button.addClass('recalculate-button');

      $calculate_button.click(function() {
        $calculate_button.text('Claim Free Shipping');
        $calculate_button.addClass('recalculate-button');
      });

      $('#edit-shipping-information .panel-title').append($clear_button);
    }
  };
}(jQuery));
