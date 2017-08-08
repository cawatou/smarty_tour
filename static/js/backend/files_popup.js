function DxFilesDialogMultiple()
{
    this.$container = undefined;

    this.init  = function($container) {
        var _this = this;
        this.$container = $container;

        this.$container.find('.js-files-checkbox-toggle')
            .click(function(e) {
                e.stopPropagation();
            })
            .change(function() {
                var _toggle = this;
                _this.$container.find('.js-files-checkbox').each(function() {
                    var $this = $(this);
                    $this.prop('checked', _toggle.checked);
                    _this.markItem($this, _toggle.checked);
                });
                _this.checkStatusOf();
            });

        this.$container.find('.js-files-checkbox').change(function() {
            _this.markItem($(this), this.checked);
            _this.checkStatusOf();
        });
    }

    this.markItem = function(inp, checked) {
        var dialog = this.getDialog();
        if (checked) {
            dialog.res[inp.attr('id')] = inp.val();
        } else {
            delete dialog.res[inp.attr('id')];
        }
    }

    this.checkStatusOf = function() {
        var dialog = this.getDialog();

        dialog.btn.prop('disabled', true);

        for (var i in dialog.res) {
            dialog.btn.prop('disabled', false);
            break;
        }
        var mark_visible = false,
            mark_checked = true;
        this.$container.find('.js-files-checkbox').each(function() {
            mark_visible = true;
            if (!this.checked) {
                mark_checked = false;
                return false;
            }
        });

        if (mark_visible) {
            this.$container.find('.js-files-checkbox-toggle').css('visibility', 'visible').prop('checked', mark_checked);
        } else {
            this.$container.find('.js-files-checkbox-toggle').css('visibility', 'hidden');
        }
    }

    this.getDialog = function() {
        return window.dialog;
    }
}

function DxFilesDialogSingle()
{
    this.$container = undefined;

    this.init  = function($container) {
        var _this = this;
        this.$container = $container;

        this.$container.find('.js-files-checkbox').change(function() {
            var curr_checked = this.checked;
            _this.$container.find('.js-files-checkbox').prop('checked', false);
            $(this).prop('checked', curr_checked);

            _this.markItem($(this), curr_checked);
            _this.checkStatusOf();
        });
    }

    this.markItem = function(inp, checked) {
        var dialog = this.getDialog();
        dialog.res = [];
        if (checked) {
            dialog.res[inp.attr('id')] = inp.val();
        }
    }

    this.checkStatusOf = function() {
        var dialog = this.getDialog();

        dialog.btn.prop('disabled', true);

        for (var i in dialog.res) {
            dialog.btn.prop('disabled', false);
            break;
        }
    }

    this.getDialog = function() {
        return window.dialog;
    }
}

function DxFilesDialog()
{
    this.inp = undefined;
    this.btn = undefined;
    this.res = [];

    this.show = function(url, inp) {
        var
            _this = this,
            w = 1000,
            h = 500,
            modal_id = 'dx_files_dialog';

        $('#' + modal_id).remove();

        $('<div class="modal fade files-dialog-popup" id="' + modal_id + '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">' +
              '<div class="modal-dialog">' +
                  '<div class="modal-content">' +
                      '<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title">Select the files</h4></div>' +
                      '<div class="modal-body"><iframe src="' + url + '" width="' + w + '" height="' + h + '" scrolling="no"></iframe></div>' +
                      '<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button><button type="button" class="btn btn-primary" disabled>Ok</button></div></div></div></div>').appendTo('body');


        this.inp = $('#' + inp);
        this.btn = $('#' + modal_id).find('.btn-primary');
        this.btn.click(function() {
            _this.submit();
        })

        $('#' + modal_id).find('iframe').load(function() {
            var
                $contents = $(this).contents(),
                $container_files = $contents.find('.container-files'),
                modal_title = $container_files.data('modal-title'),
                ok_title = $container_files.data('ok-title'),
                mode = $container_files.data('mode');
            if (modal_title) {
                $('#' + modal_id).find('.modal-title').text(modal_title);
            }
            if (ok_title) {
                _this.btn.text(ok_title);
            }

            if (mode == 'MULTIPLE') {
                var d = new DxFilesDialogMultiple();
                d.init($container_files);
            } else if (mode == 'SINGLE') {
                var d = new DxFilesDialogSingle();
                d.init($container_files);
            }
        });

        $('#' + modal_id).modal('toggle');
        $('.files-dialog-popup a.close, .files-dialog-backdrop').click(function() {
            return _this.hide();
        });
        return false;
    }

    this.submit = function() {
        var path = ''
        for (var i in this.res) {
            path = path + this.res[i] + ';';
        }
        this.inp.val(path.substr(0, path.length-1));
        return this.hide();
    }

    this.hide = function() {
        $('#dx_files_dialog').modal('hide');
        return false;
    }
}

if (typeof DxFilesDialog == 'function') {
    window.dialog = new DxFilesDialog();
}