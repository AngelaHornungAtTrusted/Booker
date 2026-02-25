(function($){
  let $form;
  const pageInit = function(){
    $form = $('#booker-registration-form');

    console.log(LOCATIONID);

    $('#eventId').val(EVENTID);
    handleRegistrationForm();
  }

  const handleRegistrationForm = function(){
    $form.validate({
      focusInvalid: true,
      rules: {},
      message: {},

      submitHandler: function(f, e){
        e.preventDefault();
        $.post(BR_AJAX_URL, {
          action: 'br_registration',
          data: $form.serializeObject(),
        }, function (response) {
          if (response.status === 'success') {
            toastr.success(response.message);
          } else {
            toastr.error(response.message);
          }
        }).always(function() {
          $form.trigger('reset');
        });
      }
    });
  }

  // secret sauce method, thanks Paul Colella
  jQuery.fn.serializeObject = function () {
    let arrayData, objectData;
    arrayData = this.serializeArray();

    objectData = {};

    $.each(arrayData, function () {
      let value;

      if (this.value != null) {
        value = this.value;
      } else {
        value = '';
      }

      if (objectData[this.name] != null) {
        if (! objectData[this.name].push) {
          objectData[this.name] = [objectData[this.name]];
        }

        objectData[this.name].push(value);
      } else {
        objectData[this.name] = value;
      }
    });

    return objectData;
  };

  $(document).ready(function(){
    pageInit();
  });
})(jQuery);