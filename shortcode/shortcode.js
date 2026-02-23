(function ($) {
  let $eventTable, $locationSelect, $typeSelect, $dateStart, $dateEnd
  let fType

  const pageInit = function () {
    //init elements
    $eventTable = $('#eventTable')
    $locationSelect = $('#br-event-location')
    $typeSelect = $('#br-event-type')
    $dateStart = $('#br-event-date-start')
    $dateEnd = $('#br-event-date-end')

    //start page loading
    getLabels()
  }

  const getLabels = function () {
    $.get(BR_AJAX_URL, {
      action: 'br_get_labels',
    }, function (response) {
      if (response.status === 'success') {
        $('#location-placeholder').text(response.data[0].option_value)
        $('#type-placeholder').text(response.data[1].option_value)
        getevents()
      } else {
        toastr.error(response.message)
      }
    })
  }

  const getevents = function () {
    $.get(BR_AJAX_URL, {
        action: 'br_get_events',
        data: {
          'location': LOCATIONID,
          'type': TYPEID,
          'sDate': SDATE,
          'eDate': EDATE,
          'category': CATEGORYID
        }
      }, function (response) {
        if (response.status === 'success') {
          //testing ordered feature, remove after
          $eventTable.empty()

          if (response.data.length < 1) {
            $eventTable.append('<p>Sorry, no wines for fit those parameters.</p>')
          } else {
            //required as we re-use getevents() for filtering and don't want to append duplicate options
            if ($locationSelect.children().length === 1) {
              initFilters(response.data[2])
            }

            $.each(response.data[0], function (key, event) {
              buildPost(event, response.data[1][key])
            })
          }
        } else {
          toastr.error(response.message)
        }
      }
    )
  }

  const buildPost = function (event, thumbnail) {
    $eventTable.append(
      '<div class="' + ((parseInt(STYLE) === 1) ? 'col-md-3' : 'col-md') + '" style="margin-top: 25px;">' +
      '<div style="text-align: center;">' +
      '<a href="' + event[0].guid + '"><img src="' + thumbnail + '" alt="..." style="' + ((parseInt(STYLE) === 1) ? 'height: 300px;' : 'height: 400px; min-width: 130px;') + ' border-radius: 15px;"></a>' +
      '</div>' +
      ((parseInt(STYLE) === 1) ? '</br><a href="' + event[0].guid + '" style="color: black;"><h3 style="color: black; text-align: center;">' + event[0].post_title + '</h3></a>' : '') +
      '</div>'
    )
  }

  const initFilters = function (filters) {
    $.each(filters, function (key, filter) {
      switch (filter[0].taxonomy) {
        case 'BookerLocation':
          $locationSelect.append('<option class="option" value="' + filter[0].term_id + '">' + filter[0].name + '</option>')
          break
        case 'BookerType':
          $typeSelect.append('<option class="option" value="' + filter[0].term_id + '">' + filter[0].name + '</option>')
          break
        default:
          break  //ignore all others
      }
    })

    $('.br-select').off('change').on('change', function (e) {
      fType = e.currentTarget.id.split('-')[2]

      switch (fType) {
        case 'location':
          LOCATIONID = $(this).val()
          break
        case 'type':
          TYPEID = $(this).val()
          break
        case 'sdate':
          SDATE = $(this).val()
          break
        case 'edate':
          EDATE = $(this).val()
          break
        default:
          break
      }

      getevents()
    })
  }

  $(document).ready(function () {
    pageInit()
  })
})(jQuery)