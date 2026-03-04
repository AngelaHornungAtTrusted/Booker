(function ($) {
  let $emailTest;

  const pageInit = function () {
    console.log('Email.js');
    $emailTest = $('#email-test');

    $emailTest.on('click', function (e) {
      console.log(e);
      e.preventDefault();
      $.post(BR_AJAX_URL, {
        action: 'br_email_notification',
      }, function (response) {
        console.log(response);
      });
    });
  };

  $(document).ready(function () {
    pageInit();
  });
})(jQuery);