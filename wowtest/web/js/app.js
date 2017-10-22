$(document).ready(function () {

    $('#fileupload').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: '/site/upload',
        done: function (e, data) {

            var result = JSON.parse(data.result);

            console.log(result);

            var processor = new PDFProcessor(result.id, result.pages);
        }

    });

});

var PDFProcessor = function(id, pages) {

    var $component;
    var $bar;

    init();

    function init() {

        $component = $('<div class="col-md-6">' +
            '<div class="progress">' +
                '<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0">' +
                    '0%' +
                '</div>' +
            '</div>' +
        '</div>');

        $('.processors').append($component);

        $bar = $component.find('.progress-bar');

        fetchStatus();

    }

    function fetchStatus() {

        $.ajax({
            type: "POST",
            url: '/site/process',
            data: { id: id },
            dataType: 'json',
            success: function(data) {
                console.log(data);

                var progress = Math.round( (data.page / pages) * 100);

                if (progress > 100) {
                    progress = 100;
                }

                $bar.html(progress + '%').css('width', progress + '%');

                if (progress < 100) {
                    fetchStatus();
                }

            }
        });

    }

};