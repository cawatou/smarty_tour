var discountsMany = manyRows.extend({
    addRow: function ($source) {
        var
            $row = this.$tpl.clone(),
            id   = '_'+ new Date().getTime(),
            that = this;

        $row.attr('data-counter', id).insertAfter($source);

        $row.find('input, textarea, select').each(function () {
            $(this).attr('name', $(this).attr('name').replace(/#ID#/ig, id));
        });

        $row.find('[data-counter]').each(function () {
            $(this).attr('data-counter', id);
        });

        this.initRow($row);
    },

    hideBtn: function($row, sel) {
        $row.find(sel).closest('li').addClass('disabled');
    },

    showBtn: function($row, sel) {
        $row.find(sel).closest('li').removeClass('disabled');
    },

    countUpRow: function () {
        var that = this;

        var $rows = this.$container.find('.row-component-discount');
        var total = $rows.length;

        $rows.each(function (index) {
            var $row = $(this);

            if (total == 1) {
                that.hideUp($row);
                that.hideDown($row);
                that.hideDel($row);
            } else if (index == 0) { // first line
                that.hideUp($row);
                that.showDown($row);
                that.showDel($row);
            } else if (index == total - 1) { // last line
                that.showUp($row);
                that.hideDown($row);
                that.showDel($row);
            } else {
                that.showUp($row);
                that.showDown($row);
                that.showDel($row);
            }

            $row.find('label').show();

            if (index !== 0) {
                $row.find('label').hide();
            }
        });
    }
});

$(document).ready(function () {
    var discountObj = new discountsMany(DISCOUNTS_STORAGE);
});