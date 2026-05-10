(function ($) {
  "use strict";

  function spinner() {
    setTimeout(function () {
      if ($("#spinner").length > 0) {
        $("#spinner").removeClass("show").hide();
      }
    }, 1);
  }
  spinner();

  if (typeof WOW !== "undefined") {
    try {
      new WOW().init();
    } catch (e) {}
  }

  $(window).scroll(function () {
    if ($(this).scrollTop() > 45) {
      $(".navbar").addClass("sticky-top shadow-sm");
    } else {
      $(".navbar").removeClass("sticky-top shadow-sm");
    }
  });
})(jQuery);
