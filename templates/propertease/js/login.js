jQuery(function() {
  jQuery(document).off('click.login').on('click.login', '#btl-panel-login-y', function() {
    jQuery('#login-popup').modal('show');
  });
});