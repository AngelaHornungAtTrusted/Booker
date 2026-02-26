(function ($) {
  let $eventSelect, $registrationTable, $export;

  const pageInit = function () {
    $eventSelect = $('#event-select');
    $registrationTable = $('#registration-table');
    $export = $('#export-registrations');

    $export.on('click', exportData);
    loadEvents();
  };

  const loadEvents = function () {
    $.get(BR_AJAX_URL, {
      action: 'br_get_events',
      data: {
        location: 0,
        type: 0,
        category: 0,
        sDate: '0000-00-00',
        eDate: '9999-99-99'
      }
    }, function (response) {
      if (response.status === 'success') {
        initEventOptions(response.data[0]);
      } else {
        toastr.error(response.message);
      }
    });
  };

  const initEventOptions = function (events) {
    $.each(events, function (i, event) {
      $eventSelect.append('<option value="' + event[0].ID + '">' + event[0].post_title + '</option>');
    });

    $eventSelect.off('change').on('change', getRegistrations);
  };

  const getRegistrations = function () {
    $registrationTable.empty();

    $.get(BR_AJAX_URL, {
      action: 'br_get_registrations',
      data: $eventSelect.val()
    }, function (response) {
      if (response.status === 'success') {
        buildRegistrationsTable(response.data);
      } else {
        toastr.error(response.message);
      }
    });
  };

  const buildRegistrationsTable = function (registrations) {
    $.each(registrations, function (i, registration) {
      $registrationTable.append('<tr id="row-' + registration.id + '">' +
        '<td>' + registration.fname + ' ' + registration.lname + '</td>' +
        '<td>' + registration.email + '</td>' +
        '<td>' + registration.pnumber + '</td>' +
        '<td>' + registration.pcount + '</td>' +
        '<td>' + registration.create_date + '</td>' +
        '<td><span id="status-' + registration.id + '">' + ((parseInt(registration.approved) === 1) ? "Approved" : "Unapproved") + '</span></td>' +
        '<td><a class="btn btn-small btn-success br-registration-management" id="approve-' + registration.id + '"><i class="fa fa-check"></i></a>' +
        '<a class="btn btn-small btn-warning br-registration-management" id="deny-' + registration.id + '" style="margin-left: 5px;"><i class="fa fa-close"></i></a>' +
        '<a class="btn btn-small btn-danger br-registration-management" id="delete-' + registration.id + '" style="margin-left: 5px;"><i class="fa fa-trash"></i></a></td>');
    });

    manageRegistrations();
  };

  const manageRegistrations = function () {
    $('.br-registration-management').off('click').on('click', function (e) {
      e.preventDefault();
      $.post(BR_AJAX_URL, {
        action: 'br_manage_registration',
        data: {
          'registration_id': e.currentTarget.id.split('-')[1],
          'type': e.currentTarget.id.split('-')[0],
        }
      }, function (response) {
        if (response.status === 'success') {
          if (response.message === 'Registration Approved') {
            toastr.success(response.message);
          } else if (response.message === 'Registration Unapproved') {
            toastr.warning(response.message);
          } else {
            toastr.error(response.message);
          }
        } else {
          toastr.error(response.message);
        }
      }).always(function () {
        //check if delete
        if (e.currentTarget.id.split('-')[0] === "delete") {
          $('#row-' + e.currentTarget.id.split('-')[1]).remove();
        } else {
          $('#status-' + e.currentTarget.id.split('-')[1]).html(($('#status-' + e.currentTarget.id.split('-')[1]).text() === 'Unapproved') ? "Approved" : "Unapproved");
        }
      });
    });
  };

  const exportData = function () {
    $.get(BR_AJAX_URL, {
      action: "br_get_registrations",
    }, function (response) {
      if (response.status === 'success') {
        toastr.success(response.message);
        buildCSV(response.data);
      } else {
        toastr.error(response.message);
      }
    })
  }

  const buildCSV = function(regs) {
    let csvContent = "data:text/csv;charset=utf-8,";

    //registration section
    csvContent += "Registrations \r\n First Name, Last Name, Party Count, Email, Phone Number, Event ID, Approved, Registration Date \r\n";
    $.each(regs, function(key, reg){
      console.log(reg);
      csvContent += reg.fname + "," + reg.lname + "," + reg.pcount + "," + reg.email + "," + reg.pnumber + "," + reg.event_id + "," + reg.approved + "," + reg.create_date + "\r\n";
    });

    downloadCSV(csvContent);
  }

  const downloadCSV = function(csvContent) {
    var encodedUri = encodeURI(csvContent);
    var link = document.createElement('a');
    link.setAttribute('href', encodedUri);
    link.setAttribute('download', "registrations.csv");
    document.body.appendChild(link);

    link.click();
  }

  $(document).ready(function () {
    pageInit();
  });
})(jQuery);