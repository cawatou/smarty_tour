$(document).ready(function() {
    $("#feedback_extended_date_staying").datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        changeYear:  true,
        dateFormat:  "dd.mm.yy"
    });

    var $dropzone_wrapper = $('#dropzone-wrapper');
    var $dropzone = $('.dropzone');
    var dropzone;

    if ($dropzone.length) {
        dropzone = new Dropzone($dropzone.get(0), {
            url:       $dropzone.attr('data-href'),
            paramName: 'files',

            autoDiscover: false,

            createImageThumbnails: false,
            maxThumbnailFilesize:  0,
            thumbnailWidth:        0,
            thumbnailHeight:       0,
            parallelUploads:       1,

            previewTemplate: "\
<div class=\"dz-preview dz-file-preview\">\n\
  <div class=\"dz-details\">\n\
    <div class=\"dz-filename\">\n\
        <img data-dz-image=\"\" alt=\"\" />\n\
        <span data-dz-name=\"\"></span>\n\
    </div>\n\
  </div>\n\
  <div class=\"dz-progress\">\n\
    <span class=\"dz-upload\" data-dz-uploadprogress=\"\"></span>\n\
  </div>\n\
</div>",

            uploadMultiple: true,

            maxFilesize: 5,
            maxFiles:    10,

            addRemoveLinks: true,

            dictDefaultMessage:           "Максимум — 10 файлов, каждый не более 5мб.",
            dictFallbackMessage:          "Ваш браузер не поддерживает drag'n'drop для загрузки файлов.",
            dictFallbackText:             "Обновите свой браузер, чтобы воспользоваться загрузкой файлов.",
            dictFileTooBig:               "Размер выбранного файла слишком большой ({{filesize}} мб). Максимально допустимый размер: {{maxFilesize}} мб.",
            dictInvalidFileType:          "Файлы такого типа загружать нельзя.",
            dictResponseError:            "Сервер ответил кодом {{statusCode}}.",
            dictCancelUpload:             "Отмена",
            dictCancelUploadConfirmation: "Точно отменить загрузку?",
            dictRemoveFile:               "Удалить",
            dictRemoveFileConfirmation:   "Точно удалить этот файл?",
            dictMaxFilesExceeded:         "Больше файлов загружать нельзя.",

            fallback: function () {
                $dropzone_wrapper.parents('.form-field-files').remove();
            }
        });

        dropzone.on('uploadprogress', function (File, percentage, bytesSent) {
            $(File.previewElement).find('.dz-progress .dz-upload').css('width', percentage +'%');
            File.previewElement.offsetWidth; // Force reflow
        });

        dropzone.on('success', function (File, response) {
            try {
                response = $.parseJSON(response);
            } catch (e) {
                return e;
            }

            if (response.code != 'OK' || response.files[File.name].code != 'OK') {
                File.previewElement.parentNode.removeChild(File.previewElement);

                return false;
            }

            if ($dropzone_wrapper.find('> input[type="hidden"][data-source-name="'+ File.name +'"]').length === 0) {
                $dropzone_wrapper.append('<input type="hidden" data-source-name="'+ File.name +'" name="'+ $dropzone_wrapper.children('.dropzone').attr('data-name') +'[]" value="'+ response.files[File.name].name +'" />');
            }

            $(File.previewElement).find('[data-dz-image]').attr('src', response.files[File.name].full_path);

            File.previewElement.title = File.name;
        });

        dropzone.on('removedfile', function (File) {
            $dropzone_wrapper.find('> input[type="hidden"][data-source-name="'+ File.name +'"]').remove();
        });

        dropzone.on('cancelled', function (File) {
            $dropzone_wrapper.find('> input[type="hidden"][data-source-name="'+ File.name +'"]').remove();
        });

        dropzone.on('error', function (File, error) {
            $dropzone_wrapper.find('> input[type="hidden"][data-source-name="'+ File.name +'"]').remove();

            var $element = $(File.previewElement).attr('title', error);

            setTimeout(function () {
                $element.animate(
                    {
                        opacity: 0
                    },
                    500,
                    function () {
                        dropzone.removeFile(File);
                    }
                );
            }, 10000);
        });
    }
});