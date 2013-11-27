(function () {
    "use strict";
    var a = function (e, b) {
        var c = this;
        var f = $("#adminDataBlock td:has(#fileEditorContainer)");
        var h = function () {
            $("body").ajaxComplete(function (k, i, m) {
                var j = g(m.data),
                    l = $(".CodeMirror");
                if (l.size() > 0) {
                    l.remove();
                    f.removeClass("editor_added");
                }
                d(j);
            });
        };
        var g = function (k) {
            var i, j;
            j = /%22file%22%3A\+%22(.*?)%22/ig.exec(k);
            if (j === null || j.length < 2) {
                j = /filename=(.*?)&/ig.exec(location.search);
            }
            if (j === null || j.length < 2) {
                return 0;
            }
            i = /[^.]+$/.exec(j[1]);
            if (!i.length) {
                return 0;
            }
            return i[0];
        };
        var d = function (i) {
            if (!/(css|js|tpl|ini)/i.test(i)) {
                return false;
            }
            if (i === "tpl") {
                i = "text/html";
            } else {
                if (i === "js") {
                    i = "javascript";
                } else {
                    if (i === "ini") {
                        i = "text/x-ini";
                    }
                }
            }
            f.addClass("editor_added");
            var j = CodeMirror.fromTextArea(c.textarea, {
                lineNumbers: true,
                mode: i,
                lineWrapping: true,
                styleActiveLine: true,
                tabMode: "indent"
            });
            j.on("change", function (k) {
                $(c.textarea).val(k.getValue());
            });
            if (c.editorType === "static") {
                $(c.textarea).parent().addClass("editor_static_added");
            }
            j.setSize(e, b);
            return true;
        };
        this.init = function (i, m) {
            c.textarea = m;
            c.editorType = i;
            if (i === "dynamic") {
                h();
            } else {
                if (i === "static") {
                    var k, j = location.search;
                    k = g(j);
                    if (k !== 0) {
                        d(k);
                    }
                }
            }
        };
    };
    $(function () {
        var c = $("#fileEditorSelector"),
            b = false;
        if (c.size() === 1) {
            b = "dynamic";
        } else {
            c = $('textarea[name="filebody"]');
            if (c.size() === 1) {
                b = "static";
            }
        }
        var d = new a(c.width(), c.height());
        if (b) {
            d.init(b, c.get(0));
        }
    });
})();