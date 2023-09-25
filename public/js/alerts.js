(function($) {
    showSuccessToast = function(message) {
      'use strict';
      resetToastPosition();
      $.toast({
        text: message,
        showHideTransition: 'slide',
        icon: 'success',
        loaderBg: '#f96868',
        position: 'top-right',
      })
    };

    showDangerToast = function(message) {
      'use strict';
      resetToastPosition();
      $.toast({
        text: message,
        showHideTransition: 'slide',
        icon: 'error',
        loaderBg: '#f2a654',
        position: 'top-right',
      })
    };

    resetToastPosition = function() {
      $('.jq-toast-wrap').removeClass('bottom-left bottom-right top-left top-right mid-center'); // to remove previous position class
      $(".jq-toast-wrap").css({
        "top": "",
        "left": "",
        "bottom": "",
        "right": ""
      });
    }

  })(jQuery);
