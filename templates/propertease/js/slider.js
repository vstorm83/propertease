jQuery(function() {
  jQuery('body').on('click', '.prev-button', function() {
    var $slider = jQuery('#slider87');
    var left = parseInt($slider.css('left'));
    if (left != -2672) {
      var left = left - 1319;
      $slider.css('left', left + "px");
    }
    return false;
  });
  
  jQuery('body').on('click', '.next-button', function() {
    var $slider = jQuery('#slider87');
    var left = parseInt($slider.css('left'));
    if (left != -34) {
      var left = left + 1319;
      $slider.css('left', left + "px");
    }
    return false;
  });  
});