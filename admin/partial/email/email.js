(function ($) {
  let $emailTest, $notEmail;

  const pageInit = function () {
    $emailTest = $('#email-test');
    //$notEmail = $('#booker-email');

    $emailTest.on('click', emailTest);
    //$bookerEmail.on('change', updateEmail);
  };

  const emailTest = function (e) {
    e.preventDefault();

    $.post(BR_AJAX_URL, {
      action: 'br_test_email_notification',
    }, function (response) {
      if (response.status === 'success') {
        toastr.success(response.message);
      } else {
        toastr.error(response.message);
      }
    });
  }

  const updateEmail = function (e) {
    $.post(BR_AJAX_URL, {
      action: 'br_update_email',
      data: e.currentTarget.val(),
    }, function (response) {
      if (response.status === 'success') {
        toastr.success(response.message);
      } else {
        toastr.error(response.message);
      }
    })
  }

  $(document).ready(function () {
    pageInit();
  });
})(jQuery);