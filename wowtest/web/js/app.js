$(document).ready(function () {

    var processExecutor = new ProcessExecutor();

    $('#fileupload').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: '/site/upload',
        done: function (e, data) {

            processExecutor.startProcess( JSON.parse(data.result) );

        }

    });

});

ProcessExecutor = function() {

    var processes = [];

    this.ready = function(processor) {
        if (processes.length == 1) {
            location.href = '/result/' + processor.getId();
        } else {
            processor.addResultLink();
        }

    };

    this.startProcess = function(data) {
        processes.push(new PDFProcessor(data, this.ready));
    };

};

PDFProcessor = function(data, readyHandler) {

    var id;
    var pages;

    var $component;
    var $bar;
    var $title;
    var $resultContainer;

    var that = this;

    init();

    this.getId = function() {
        return id;
    };

    this.addResultLink = function() {
        $resultContainer.html(
            '<a href="/result/' + id + '" target="_blank">Посмотреть результат</a>' +
            '&nbsp;' +
            '<a href="/download/' + id + '" target="_blank">Скачать</a>'
        );
    };

    function init() {

        id = data.id;
        pages = data.pages;

        $component = $('<div class="col-md-6 process">' +
            '<h4>' + data.name + '</h4>' +
            '<div class="progress">' +
                '<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0">' +
                    '0%' +
                '</div>' +
            '</div>' +
            '<div class="result-container">&nbsp;</div>' +
        '</div>');

        $('.processors').append($component);

        $bar = $component.find('.progress-bar');
        $title = $component.find('h4');
        $resultContainer = $component.find('.result-container');

        fetchStatus();

    }

    function fetchStatus() {

        $.ajax({
            type: "POST",
            url: '/site/process',
            data: { id: id },
            dataType: 'json',
            success: function(data) {

                var progress = Math.round( (data.page / pages) * 100);

                if (progress > 100) {
                    progress = 100;
                }

                $bar.html(progress + '%').css('width', progress + '%');

                if (progress < 100) {
                    fetchStatus();
                } else {
                    readyHandler(that);
                }

            }
        });

    }

};