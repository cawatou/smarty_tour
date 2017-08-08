var manyRows = Base.extend({
    $container: undefined,
    $tpl:       undefined,
    row_class:  undefined,
    add_class:  undefined,
    del_class:  undefined,
    up_class:   undefined,
    down_class: undefined,

    constructor: function (params) {
        this.$container = $(params.container);
        this.$tpl = this.$container.find(params.tpl).clone().removeClass('hidden').removeClass(params.tpl.substr(1));
        this.$container.find(params.tpl).remove();
        this.row_class = params.row;
        this.add_class = params.add;
        this.del_class = params.del;
        this.up_class = params.up;
        this.down_class = params.down;

        this.initialize();
    },

    initialize: function () {
        var _this = this;

        this.$container.find(this.row_class).each(function() {
            _this.initRow($(this));
        });
    },

    initRow: function ($row) {
        var _this = this;

        $row.find(this.add_class).click(function () {
            var $this = $(this);

            if ($this.parent().hasClass('disabled')) {
                return false;
            }

            var _$row = $this.closest(_this.row_class);
            _this.addRow(_$row);
            $this.closest('.dropdown-menu').prev().dropdown('toggle');

            return false;
        });

        $row.find(this.del_class).click(function() {
            var $this = $(this);

            if ($this.parent().hasClass('disabled')) {
                return false;
            }

            if (confirm('Вы уверены?')) {
                $this.closest(_this.row_class).remove();
                _this.countUpRow();
            }

            $(this).closest('.dropdown-menu').prev().dropdown('toggle');

            return false;
        });

        $row.find(this.up_class).click(function() {
            var $this = $(this);

            if ($this.parent().hasClass('disabled')) {
                return false;
            }

            var _$row = $this.closest(_this.row_class);
            _$row.insertBefore(_$row.prev());
            $this.closest('.dropdown-menu').prev().dropdown('toggle');

            _this.countUpRow();

            return false;
        });

        $row.find(this.down_class).click(function() {
            var $this = $(this);

            if ($this.parent().hasClass('disabled')) {
                return false;
            }

            var _$row = $this.closest(_this.row_class);
            _$row.insertAfter(_$row.next());
            $this.closest('.dropdown-menu').prev().dropdown('toggle');

            _this.countUpRow();

            return false;
        });

        this.countUpRow();
    },

    addRow: function ($source) {
        var
            $row = this.$tpl.clone(),
            id = new Date().getTime();

        $row.insertAfter($source);

        $row.find('input, textarea, select').each(function() {
            $(this).attr('name', $(this).attr('name').replace(/#ID#/ig, id));
        });

        this.initRow($row);
    },

    countUpRow: function () {
        var
            _this = this,
            rows = this.$container.find(this.row_class),
            total = rows.length;

        rows.each(function(index) {
            var $row = $(this);

            if (total == 1) {
                _this.hideUp($row);
                _this.hideDown($row);
                _this.hideDel($row);
            } else if (index == 0) { // first line
                _this.hideUp($row);
                _this.showDown($row);
                _this.showDel($row);
            } else if (index == total - 1) { // last line
                _this.showUp($row);
                _this.hideDown($row);
                _this.showDel($row);
            } else {
                _this.showUp($row);
                _this.showDown($row);
                _this.showDel($row);
            }
        });
    },

    hideBtn: function ($row, sel) {
        $row.find(sel).addClass('hidden');
    },

    showBtn: function ($row, sel) {
        $row.find(sel).removeClass('hidden');
    },

    hideUp:   function ($row) { this.hideBtn($row, this.up_class);  },
    showUp:   function ($row) { this.showBtn($row, this.up_class);  },
    hideDown: function ($row) { this.hideBtn($row, this.down_class); },
    showDown: function ($row) { this.showBtn($row, this.down_class); },
    hideAdd:  function ($row) { this.hideBtn($row, this.add_class);  },
    showAdd:  function ($row) { this.showBtn($row, this.add_class);  },
    hideDel:  function ($row) { this.hideBtn($row, this.del_class);  },
    showDel:  function ($row) { this.showBtn($row, this.del_class);  }
});