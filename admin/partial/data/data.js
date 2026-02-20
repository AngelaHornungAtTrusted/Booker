(function ($) {

    let iButton, eButton, lLabel, tLabel, dLabel;
    let type;
    const pageInit = function () {
        //init elements
        //iButton = $('#br-import-button');
        //eButton = $('#br-export-button');
        lLabel = $('#location-label');
        tLabel = $('#type-label');
        dLabel = $('#date-label');

        //set actions
        getLabels();
        //importData();
        //eButton.on('click', exportData);
    }

    const getLabels = function () {
        $.get(BR_AJAX_URL, {
            action: 'br_get_labels',
        }, function (response) {
            if (response.status === 'success') {
                console.log(response.data);
                manageLabels(response.data);
            } else {
                toastr.error(response.message);
            }
        });
    }

    const manageLabels = function (data) {
        lLabel.val(data[0].option_value);
        tLabel.val(data[1].option_value);
        dLabel.val(data[2].option_value);

        $('.label-input').on('change', function (e) {
            type = ((e.currentTarget.id.split('-')[0] === 'location') ? 1 : (e.currentTarget.id.split('-')[0] === 'type') ? 2 : 3);
            $.post(BR_AJAX_URL, {
                action: 'br_update_labels',
                data: {
                    type: type,
                    content: e.currentTarget.value
                }
            }, function (response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            });
        });
    }

    const importData = function () {
        iButton.on('change', async function (e) {
            if (this.files) {
                var myFile = this.files[0];
                var reader = new FileReader();

                reader.addEventListener('load', function (e) {
                    let data = e.target.result;

                    //send to server once data's compiled
                    $.post(br_AJAX_URL, {
                        action: "br_import_data",
                        data: {
                            'importValues': data
                        }
                    }, function (response) {
                        iButton.val('');
                        if (response.status === 'success') {
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    });
                });
                reader.readAsText(myFile);
            }
        });
    }

    const exportData = function () {
        $.get(BR_AJAX_URL, {
            action: "br_export_data",
        }, function (response) {
            if (response.status === 'success') {
                toastr.success(response.message);
                buildExport(response.data);
            } else {
                toastr.error(response.message);
            }
        })
    }

    const buildExport = function (data) {
        let content = "data:text/json;charset=utf-8,";

        content += "[";
        $.each(data, function (key1, exportData) {
            $.each(exportData, function (key2, value) {
                content += ((!exportData[0][0]) ? JSON.stringify(value) + "," : '');
                if (key1 >= 2) {
                    $.each(value, function (key3, meta) {
                        content += JSON.stringify(meta) + ",";
                    });
                }
            });
        });
        content += "]";

        downloadCSV(content);
    }

    const downloadCSV = function (content) {
        var link = document.createElement('a');
        link.setAttribute('href', encodeURI(content));
        link.setAttribute('download', "productportfolioexport.json");
        document.body.appendChild(link);

        link.click();
    }

    $(document).ready(function () {
        pageInit();
    });
})(jQuery);