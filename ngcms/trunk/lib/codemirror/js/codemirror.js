window.CodeMirror = function () {
    "use strict";

    function w(a, c) {
        if (!(this instanceof w)) return new w(a, c);
        this.options = c = c || {};
        for (var d in Qc)!c.hasOwnProperty(d) && Qc.hasOwnProperty(d) && (c[d] = Qc[d]);
        I(c);
        var e = "string" == typeof c.value ? 0 : c.value.first,
            f = this.display = x(a, e);
        f.wrapper.CodeMirror = this, F(this), c.autofocus && !o && Fb(this), this.state = {
            keyMaps: [],
            overlays: [],
            modeGen: 0,
            overwrite: !1,
            focused: !1,
            suppressEdits: !1,
            pasteIncoming: !1,
            draggingText: !1,
            highlight: new He
        }, D(this), c.lineWrapping && (this.display.wrapper.className += " CodeMirror-wrap");
        var g = c.value;
        "string" == typeof g && (g = new Vd(c.value, c.mode)), xb(this, Zd)(this, g), b && setTimeout(Re(Eb, this, !0), 20), Hb(this);
        var h;
        try {
            h = document.activeElement == f.input
        } catch (i) {}
        h || c.autofocus && !o ? setTimeout(Re(bc, this), 20) : cc(this), xb(this, function () {
            for (var a in Pc) Pc.propertyIsEnumerable(a) && Pc[a](this, c[a], Sc);
            for (var b = 0; Wc.length > b; ++b) Wc[b](this)
        })()
    }

    function x(a, b) {
        var d = {}, f = d.input = We("textarea", null, null, "position: absolute; padding: 0; width: 1px; height: 1em; outline: none;");
        e ? f.style.width = "1000px" : f.setAttribute("wrap", "off"), n && (f.style.border = "1px solid black"), f.setAttribute("autocorrect", "off"), f.setAttribute("autocapitalize", "off"), d.inputDiv = We("div", [f], null, "overflow: hidden; position: relative; width: 3px; height: 0px;"), d.scrollbarH = We("div", [We("div", null, null, "height: 1px")], "CodeMirror-hscrollbar"), d.scrollbarV = We("div", [We("div", null, null, "width: 1px")], "CodeMirror-vscrollbar"), d.scrollbarFiller = We("div", null, "CodeMirror-scrollbar-filler"), d.lineDiv = We("div"), d.selectionDiv = We("div", null, null, "position: relative; z-index: 1"), d.cursor = We("div", "\u00a0", "CodeMirror-cursor"), d.otherCursor = We("div", "\u00a0", "CodeMirror-cursor CodeMirror-secondarycursor"), d.measure = We("div", null, "CodeMirror-measure"), d.lineSpace = We("div", [d.measure, d.selectionDiv, d.lineDiv, d.cursor, d.otherCursor], null, "position: relative; outline: none"), d.mover = We("div", [We("div", [d.lineSpace], "CodeMirror-lines")], null, "position: relative"), d.sizer = We("div", [d.mover], "CodeMirror-sizer"), d.heightForcer = We("div", null, null, "position: absolute; height: " + Fe + "px; width: 1px;"), d.gutters = We("div", null, "CodeMirror-gutters"), d.lineGutter = null;
        var g = We("div", [d.sizer, d.heightForcer, d.gutters], null, "position: relative; min-height: 100%");
        return d.scroller = We("div", [g], "CodeMirror-scroll"), d.scroller.setAttribute("tabIndex", "-1"), d.wrapper = We("div", [d.inputDiv, d.scrollbarH, d.scrollbarV, d.scrollbarFiller, d.scroller], "CodeMirror"), c && (d.gutters.style.zIndex = -1, d.scroller.style.paddingRight = 0), a.appendChild ? a.appendChild(d.wrapper) : a(d.wrapper), n && (f.style.width = "0px"), e || (d.scroller.draggable = !0), j ? (d.inputDiv.style.height = "1px", d.inputDiv.style.position = "absolute") : c && (d.scrollbarH.style.minWidth = d.scrollbarV.style.minWidth = "18px"), d.viewOffset = d.lastSizeC = 0, d.showingFrom = d.showingTo = b, d.lineNumWidth = d.lineNumInnerWidth = d.lineNumChars = null, d.prevInput = "", d.alignWidgets = !1, d.pollingFast = !1, d.poll = new He, d.draggingText = !1, d.cachedCharWidth = d.cachedTextHeight = null, d.measureLineCache = [], d.measureLineCachePos = 0, d.inaccurateSelection = !1, d.maxLine = null, d.maxLineLength = 0, d.maxLineChanged = !1, d.wheelDX = d.wheelDY = d.wheelStartX = d.wheelStartY = null, d
    }

    function y(a) {
        a.doc.mode = w.getMode(a.options, a.doc.modeOption), a.doc.iter(function (a) {
            a.stateAfter && (a.stateAfter = null), a.styles && (a.styles = null)
        }), a.doc.frontier = a.doc.first, _(a, 100), a.state.modeGen++, a.curOp && Ab(a)
    }

    function z(a) {
        a.options.lineWrapping ? (a.display.wrapper.className += " CodeMirror-wrap", a.display.sizer.style.minWidth = "") : (a.display.wrapper.className = a.display.wrapper.className.replace(" CodeMirror-wrap", ""), H(a)), B(a), Ab(a), kb(a), setTimeout(function () {
            J(a.display, a.doc.height)
        }, 100)
    }

    function A(a) {
        var b = sb(a.display),
            c = a.options.lineWrapping,
            d = c && Math.max(5, a.display.scroller.clientWidth / tb(a.display) - 3);
        return function (e) {
            return ud(a.doc, e) ? 0 : c ? (Math.ceil(e.text.length / d) || 1) * b : b
        }
    }

    function B(a) {
        var b = a.doc,
            c = A(a);
        b.iter(function (a) {
            var b = c(a);
            b != a.height && be(a, b)
        })
    }

    function C(a) {
        var b = $c[a.options.keyMap].style;
        a.display.wrapper.className = a.display.wrapper.className.replace(/\s*cm-keymap-\S+/g, "") + (b ? " cm-keymap-" + b : "")
    }

    function D(a) {
        a.display.wrapper.className = a.display.wrapper.className.replace(/\s*cm-s-\S+/g, "") + a.options.theme.replace(/(^|\s)\s*/g, " cm-s-"), kb(a)
    }

    function E(a) {
        F(a), Ab(a)
    }

    function F(a) {
        var b = a.display.gutters,
            c = a.options.gutters;
        Xe(b);
        for (var d = 0; c.length > d; ++d) {
            var e = c[d],
                f = b.appendChild(We("div", null, "CodeMirror-gutter " + e));
            "CodeMirror-linenumbers" == e && (a.display.lineGutter = f, f.style.width = (a.display.lineNumWidth || 1) + "px")
        }
        b.style.display = d ? "" : "none"
    }

    function G(a, b) {
        if (0 == b.height) return 0;
        for (var d, c = b.text.length, e = b; d = rd(e);) {
            var f = d.find();
            e = $d(a, f.from.line), c += f.from.ch - f.to.ch
        }
        for (e = b; d = sd(e);) {
            var f = d.find();
            c -= e.text.length - f.from.ch, e = $d(a, f.to.line), c += e.text.length - f.to.ch
        }
        return c
    }

    function H(a) {
        var b = a.display,
            c = a.doc;
        b.maxLine = $d(c, c.first), b.maxLineLength = G(c, b.maxLine), b.maxLineChanged = !0, c.iter(function (a) {
            var d = G(c, a);
            d > b.maxLineLength && (b.maxLineLength = d, b.maxLine = a)
        })
    }

    function I(a) {
        for (var b = !1, c = 0; a.gutters.length > c; ++c) "CodeMirror-linenumbers" == a.gutters[c] && (a.lineNumbers ? b = !0 : a.gutters.splice(c--, 1));
        !b && a.lineNumbers && a.gutters.push("CodeMirror-linenumbers")
    }

    function J(a, b) {
        var c = b + 2 * db(a);
        a.sizer.style.minHeight = a.heightForcer.style.top = c + "px";
        var d = Math.max(c, a.scroller.scrollHeight),
            e = a.scroller.scrollWidth > a.scroller.clientWidth,
            f = d > a.scroller.clientHeight;
        f ? (a.scrollbarV.style.display = "block", a.scrollbarV.style.bottom = e ? cf(a.measure) + "px" : "0", a.scrollbarV.firstChild.style.height = d - a.scroller.clientHeight + a.scrollbarV.clientHeight + "px") : a.scrollbarV.style.display = "", e ? (a.scrollbarH.style.display = "block", a.scrollbarH.style.right = f ? cf(a.measure) + "px" : "0", a.scrollbarH.firstChild.style.width = a.scroller.scrollWidth - a.scroller.clientWidth + a.scrollbarH.clientWidth + "px") : a.scrollbarH.style.display = "", e && f ? (a.scrollbarFiller.style.display = "block", a.scrollbarFiller.style.height = a.scrollbarFiller.style.width = cf(a.measure) + "px") : a.scrollbarFiller.style.display = "", k && 0 === cf(a.measure) && (a.scrollbarV.style.minWidth = a.scrollbarH.style.minHeight = l ? "18px" : "12px")
    }

    function K(a, b, c) {
        var d = a.scroller.scrollTop,
            e = a.wrapper.clientHeight;
        "number" == typeof c ? d = c : c && (d = c.top, e = c.bottom - c.top), d = Math.floor(d - db(a));
        var f = Math.ceil(d + e);
        return {
            from: de(b, d),
            to: de(b, f)
        }
    }

    function L(a) {
        var b = a.display;
        if (b.alignWidgets || b.gutters.firstChild && a.options.fixedGutter) {
            for (var c = O(b) - b.scroller.scrollLeft + a.doc.scrollLeft, d = b.gutters.offsetWidth, e = c + "px", f = b.lineDiv.firstChild; f; f = f.nextSibling)
                if (f.alignable)
                    for (var g = 0, h = f.alignable; h.length > g; ++g) h[g].style.left = e;
            a.options.fixedGutter && (b.gutters.style.left = c + d + "px")
        }
    }

    function M(a) {
        if (!a.options.lineNumbers) return !1;
        var b = a.doc,
            c = N(a.options, b.first + b.size - 1),
            d = a.display;
        if (c.length != d.lineNumChars) {
            var e = d.measure.appendChild(We("div", [We("div", c)], "CodeMirror-linenumber CodeMirror-gutter-elt")),
                f = e.firstChild.offsetWidth,
                g = e.offsetWidth - f;
            return d.lineGutter.style.width = "", d.lineNumInnerWidth = Math.max(f, d.lineGutter.offsetWidth - g), d.lineNumWidth = d.lineNumInnerWidth + g, d.lineNumChars = d.lineNumInnerWidth ? c.length : -1, d.lineGutter.style.width = d.lineNumWidth + "px", !0
        }
        return !1
    }

    function N(a, b) {
        return a.lineNumberFormatter(b + a.firstLineNumber) + ""
    }

    function O(a) {
        return $e(a.scroller).left - $e(a.sizer).left
    }

    function P(a, b, c) {
        var d = a.display.showingFrom,
            e = a.display.showingTo,
            f = Q(a, b, c);
        return f && (Ce(a, "update", a), (a.display.showingFrom != d || a.display.showingTo != e) && Ce(a, "viewportChange", a, a.display.showingFrom, a.display.showingTo)), X(a), J(a.display, a.doc.height), f
    }

    function Q(a, b, d) {
        var e = a.display,
            f = a.doc;
        if (!e.wrapper.clientWidth) return e.showingFrom = e.showingTo = f.first, e.viewOffset = 0, void 0;
        var g = K(e, f, d);
        if (!(0 == b.length && g.from > e.showingFrom && g.to < e.showingTo)) {
            M(a) && (b = [{
                from: f.first,
                to: f.first + f.size
            }]);
            var h = e.sizer.style.marginLeft = e.gutters.offsetWidth + "px";
            e.scrollbarH.style.left = a.options.fixedGutter ? h : "0";
            var i = 1 / 0;
            if (a.options.lineNumbers)
                for (var j = 0; b.length > j; ++j)
                    if (b[j].diff) {
                        i = b[j].from;
                        break
                    }
            var k = f.first + f.size,
                l = Math.max(g.from - a.options.viewportMargin, f.first),
                m = Math.min(k, g.to + a.options.viewportMargin);
            if (l > e.showingFrom && 20 > l - e.showingFrom && (l = Math.max(f.first, e.showingFrom)), e.showingTo > m && 20 > e.showingTo - m && (m = Math.min(k, e.showingTo)), v)
                for (l = ce(td(f, $d(f, l))); k > m && ud(f, $d(f, m));)++m;
            var n = [{
                from: Math.max(e.showingFrom, f.first),
                to: Math.min(e.showingTo, k)
            }];
            if (n = n[0].from >= n[0].to ? [] : S(n, b), v)
                for (var j = 0; n.length > j; ++j)
                    for (var p, o = n[j]; p = sd($d(f, o.to - 1));) {
                        var q = p.find().from.line;
                        if (!(q > o.from)) {
                            n.splice(j--, 1);
                            break
                        }
                        o.to = q
                    }
            for (var r = 0, j = 0; n.length > j; ++j) {
                var o = n[j];
                l > o.from && (o.from = l), o.to > m && (o.to = m), o.from >= o.to ? n.splice(j--, 1) : r += o.to - o.from
            }
            if (r == m - l && l == e.showingFrom && m == e.showingTo) return R(a), void 0;
            n.sort(function (a, b) {
                return a.from - b.from
            });
            var s = document.activeElement;.7 * (m - l) > r && (e.lineDiv.style.display = "none"), U(a, l, m, n, i), e.lineDiv.style.display = "", document.activeElement != s && s.offsetHeight && s.focus();
            var t = l != e.showingFrom || m != e.showingTo || e.lastSizeC != e.wrapper.clientHeight;
            t && (e.lastSizeC = e.wrapper.clientHeight), e.showingFrom = l, e.showingTo = m, _(a, 100);
            for (var x, u = e.lineDiv.offsetTop, w = e.lineDiv.firstChild; w; w = w.nextSibling)
                if (w.lineObj) {
                    if (c) {
                        var y = w.offsetTop + w.offsetHeight;
                        x = y - u, u = y
                    } else {
                        var z = $e(w);
                        x = z.bottom - z.top
                    }
                    var A = w.lineObj.height - x;
                    if (2 > x && (x = sb(e)), A > .001 || -.001 > A) {
                        be(w.lineObj, x);
                        var B = w.lineObj.widgets;
                        if (B)
                            for (var j = 0; B.length > j; ++j) B[j].height = B[j].node.offsetHeight
                    }
                }
            return R(a), K(e, f, d).to > m && Q(a, [], d), !0
        }
    }

    function R(a) {
        var b = a.display.viewOffset = ee(a, $d(a.doc, a.display.showingFrom));
        a.display.mover.style.top = b + "px"
    }

    function S(a, b) {
        for (var c = 0, d = b.length || 0; d > c; ++c) {
            for (var e = b[c], f = [], g = e.diff || 0, h = 0, i = a.length; i > h; ++h) {
                var j = a[h];
                e.to <= j.from && e.diff ? f.push({
                    from: j.from + g,
                    to: j.to + g
                }) : e.to <= j.from || e.from >= j.to ? f.push(j) : (e.from > j.from && f.push({
                    from: j.from,
                    to: e.from
                }), e.to < j.to && f.push({
                    from: e.to + g,
                    to: j.to + g
                }))
            }
            a = f
        }
        return a
    }

    function T(a) {
        for (var b = a.display, c = {}, d = {}, e = b.gutters.firstChild, f = 0; e; e = e.nextSibling, ++f) c[a.options.gutters[f]] = e.offsetLeft, d[a.options.gutters[f]] = e.offsetWidth;
        return {
            fixedPos: O(b),
            gutterTotalWidth: b.gutters.offsetWidth,
            gutterLeft: c,
            gutterWidth: d,
            wrapperWidth: b.wrapper.clientWidth
        }
    }

    function U(a, b, c, d, f) {
        function l(b) {
            var c = b.nextSibling;
            return e && p && a.display.currentWheelTarget == b ? (b.style.display = "none", b.lineObj = null) : b.parentNode.removeChild(b), c
        }
        var g = T(a),
            h = a.display,
            i = a.options.lineNumbers;
        d.length || e && a.display.currentWheelTarget || Xe(h.lineDiv);
        var j = h.lineDiv,
            k = j.firstChild,
            m = d.shift(),
            n = b;
        for (a.doc.iter(b, c, function (b) {
            if (m && m.to == n && (m = d.shift()), ud(a.doc, b)) {
                if (0 != b.height && be(b, 0), b.widgets && k.previousSibling)
                    for (var c = 0; b.widgets.length > c; ++c)
                        if (b.widgets[c].showIfHidden) {
                            var e = k.previousSibling;
                            if (/pre/i.test(e.nodeName)) {
                                var h = We("div", null, null, "position: relative");
                                e.parentNode.replaceChild(h, e), h.appendChild(e), e = h
                            }
                            var o = e.appendChild(We("div", [b.widgets[c].node], "CodeMirror-linewidget"));
                            W(b.widgets[c], o, e, g)
                        }
            } else if (m && n >= m.from && m.to > n) {
                for (; k.lineObj != b;) k = l(k);
                i && n >= f && k.lineNumber && Ze(k.lineNumber, N(a.options, n)), k = k.nextSibling
            } else {
                if (b.widgets)
                    for (var r, p = 0, q = k; q && 20 > p; ++p, q = q.nextSibling)
                        if (q.lineObj == b && /div/i.test(q.nodeName)) {
                            r = q;
                            break
                        }
                var s = V(a, b, n, g, r);
                if (s != r) j.insertBefore(s, k);
                else {
                    for (; k != r;) k = l(k);
                    k = k.nextSibling
                }
                s.lineObj = b
            }++n
        }); k;) k = l(k)
    }

    function V(a, b, d, e, f) {
        var j, g = Ld(a, b),
            h = b.gutterMarkers,
            i = a.display;
        if (!(a.options.lineNumbers || h || b.bgClass || b.wrapClass || b.widgets)) return g;
        if (f) {
            f.alignable = null;
            for (var n, k = !0, l = 0, m = f.firstChild; m; m = n)
                if (n = m.nextSibling, /\bCodeMirror-linewidget\b/.test(m.className)) {
                    for (var o = 0, p = !0; b.widgets.length > o; ++o) {
                        var q = b.widgets[o],
                            r = !1;
                        if (q.above || (r = p, p = !1), q.node == m.firstChild) {
                            W(q, m, f, e), ++l, r && f.insertBefore(g, m);
                            break
                        }
                    }
                    if (o == b.widgets.length) {
                        k = !1;
                        break
                    }
                } else f.removeChild(m);
            k && l == b.widgets.length && (j = f, f.className = b.wrapClass || "")
        }
        if (j || (j = We("div", null, b.wrapClass, "position: relative"), j.appendChild(g)), b.bgClass && j.insertBefore(We("div", null, b.bgClass + " CodeMirror-linebackground"), j.firstChild), a.options.lineNumbers || h) {
            var s = j.insertBefore(We("div", null, null, "position: absolute; left: " + (a.options.fixedGutter ? e.fixedPos : -e.gutterTotalWidth) + "px"), j.firstChild);
            if (a.options.fixedGutter && (j.alignable || (j.alignable = [])).push(s), !a.options.lineNumbers || h && h["CodeMirror-linenumbers"] || (j.lineNumber = s.appendChild(We("div", N(a.options, d), "CodeMirror-linenumber CodeMirror-gutter-elt", "left: " + e.gutterLeft["CodeMirror-linenumbers"] + "px; width: " + i.lineNumInnerWidth + "px"))), h)
                for (var t = 0; a.options.gutters.length > t; ++t) {
                    var u = a.options.gutters[t],
                        v = h.hasOwnProperty(u) && h[u];
                    v && s.appendChild(We("div", [v], "CodeMirror-gutter-elt", "left: " + e.gutterLeft[u] + "px; width: " + e.gutterWidth[u] + "px"))
                }
        }
        if (c && (j.style.zIndex = 2), b.widgets && j != f)
            for (var o = 0, w = b.widgets; w.length > o; ++o) {
                var q = w[o],
                    x = We("div", [q.node], "CodeMirror-linewidget");
                W(q, x, j, e), q.above ? j.insertBefore(x, a.options.lineNumbers && 0 != b.height ? s : g) : j.appendChild(x), Ce(q, "redraw")
            }
        return j
    }

    function W(a, b, c, d) {
        if (a.noHScroll) {
            (c.alignable || (c.alignable = [])).push(b);
            var e = d.wrapperWidth;
            b.style.left = d.fixedPos + "px", a.coverGutter || (e -= d.gutterTotalWidth, b.style.paddingLeft = d.gutterTotalWidth + "px"), b.style.width = e + "px"
        }
        a.coverGutter && (b.style.zIndex = 5, b.style.position = "relative", a.noHScroll || (b.style.marginLeft = -d.gutterTotalWidth + "px"))
    }

    function X(a) {
        var b = a.display,
            c = rc(a.doc.sel.from, a.doc.sel.to);
        c || a.options.showCursorWhenSelecting ? Y(a) : b.cursor.style.display = b.otherCursor.style.display = "none", c ? b.selectionDiv.style.display = "none" : Z(a);
        var d = nb(a, a.doc.sel.head, "div"),
            e = $e(b.wrapper),
            f = $e(b.lineDiv);
        b.inputDiv.style.top = Math.max(0, Math.min(b.wrapper.clientHeight - 10, d.top + f.top - e.top)) + "px", b.inputDiv.style.left = Math.max(0, Math.min(b.wrapper.clientWidth - 10, d.left + f.left - e.left)) + "px"
    }

    function Y(a) {
        var b = a.display,
            c = nb(a, a.doc.sel.head, "div");
        b.cursor.style.left = c.left + "px", b.cursor.style.top = c.top + "px", b.cursor.style.height = Math.max(0, c.bottom - c.top) * a.options.cursorHeight + "px", b.cursor.style.display = "", c.other ? (b.otherCursor.style.display = "", b.otherCursor.style.left = c.other.left + "px", b.otherCursor.style.top = c.other.top + "px", b.otherCursor.style.height = .85 * (c.other.bottom - c.other.top) + "px") : b.otherCursor.style.display = "none"
    }

    function Z(a) {
        function h(a, b, c, d) {
            0 > b && (b = 0), e.appendChild(We("div", null, "CodeMirror-selected", "position: absolute; left: " + a + "px; top: " + b + "px; width: " + (null == c ? f - a : c) + "px; height: " + (d - b) + "px"))
        }

        function i(b, d, e, i) {
            function m(c) {
                return mb(a, qc(b, c), "div", j)
            }
            var j = $d(c, b),
                k = j.text.length,
                l = i ? 1 / 0 : -1 / 0;
            return kf(fe(j), d || 0, null == e ? k : e, function (a, b, c) {
                var j = m("rtl" == c ? b - 1 : a),
                    n = m("rtl" == c ? a : b - 1),
                    o = j.left,
                    p = n.right;
                n.top - j.top > 3 && (h(o, j.top, null, j.bottom), o = g, j.bottom < n.top && h(o, j.bottom, null, n.top)), null == e && b == k && (p = f), null == d && 0 == a && (o = g), l = i ? Math.min(n.top, l) : Math.max(n.bottom, l), g + 1 > o && (o = g), h(o, n.top, p - o, n.bottom)
            }), l
        }
        var b = a.display,
            c = a.doc,
            d = a.doc.sel,
            e = document.createDocumentFragment(),
            f = b.lineSpace.offsetWidth,
            g = eb(a.display);
        if (d.from.line == d.to.line) i(d.from.line, d.from.ch, d.to.ch);
        else {
            for (var l, n, j = $d(c, d.from.line), k = j, m = [d.from.line, d.from.ch]; l = sd(k);) {
                var o = l.find();
                if (m.push(o.from.ch, o.to.line, o.to.ch), o.to.line == d.to.line) {
                    m.push(d.to.ch), n = !0;
                    break
                }
                k = $d(c, o.to.line)
            }
            if (n)
                for (var p = 0; m.length > p; p += 3) i(m[p], m[p + 1], m[p + 2]);
            else {
                var q, r, s = $d(c, d.to.line);
                q = d.from.ch ? i(d.from.line, d.from.ch, null, !1) : ee(a, j) - b.viewOffset, r = d.to.ch ? i(d.to.line, rd(s) ? null : 0, d.to.ch, !0) : ee(a, s) - b.viewOffset, r > q && h(g, q, null, r)
            }
        }
        Ye(b.selectionDiv, e), b.selectionDiv.style.display = ""
    }

    function $(a) {
        var b = a.display;
        clearInterval(b.blinker);
        var c = !0;
        b.cursor.style.visibility = b.otherCursor.style.visibility = "", b.blinker = setInterval(function () {
            b.cursor.offsetHeight && (b.cursor.style.visibility = b.otherCursor.style.visibility = (c = !c) ? "" : "hidden")
        }, a.options.cursorBlinkRate)
    }

    function _(a, b) {
        a.doc.mode.startState && a.doc.frontier < a.display.showingTo && a.state.highlight.set(b, Re(ab, a))
    }

    function ab(a) {
        var b = a.doc;
        if (b.frontier < b.first && (b.frontier = b.first), !(b.frontier >= a.display.showingTo)) {
            var f, c = +new Date + a.options.workTime,
                d = Xc(b.mode, cb(a, b.frontier)),
                e = [];
            b.iter(b.frontier, Math.min(b.first + b.size, a.display.showingTo + 500), function (g) {
                if (b.frontier >= a.display.showingFrom) {
                    var h = g.styles;
                    g.styles = Gd(a, g, d);
                    for (var i = !h || h.length != g.styles.length, j = 0; !i && h.length > j; ++j) i = h[j] != g.styles[j];
                    i && (f && f.end == b.frontier ? f.end++ : e.push(f = {
                        start: b.frontier,
                        end: b.frontier + 1
                    })), g.stateAfter = Xc(b.mode, d)
                } else Id(a, g, d), g.stateAfter = 0 == b.frontier % 5 ? Xc(b.mode, d) : null;
                return ++b.frontier, +new Date > c ? (_(a, a.options.workDelay), !0) : void 0
            }), e.length && xb(a, function () {
                for (var a = 0; e.length > a; ++a) Ab(this, e[a].start, e[a].end)
            })()
        }
    }

    function bb(a, b) {
        for (var c, d, e = a.doc, f = b, g = b - 100; f > g; --f) {
            if (e.first >= f) return e.first;
            var h = $d(e, f - 1);
            if (h.stateAfter) return f;
            var i = Ie(h.text, null, a.options.tabSize);
            (null == d || c > i) && (d = f - 1, c = i)
        }
        return d
    }

    function cb(a, b) {
        var c = a.doc,
            d = a.display;
        if (!c.mode.startState) return !0;
        var e = bb(a, b),
            f = e > c.first && $d(c, e - 1).stateAfter;
        return f = f ? Xc(c.mode, f) : Yc(c.mode), c.iter(e, b, function (g) {
            Id(a, g, f);
            var h = e == b - 1 || 0 == e % 5 || e >= d.showingFrom && d.showingTo > e;
            g.stateAfter = h ? Xc(c.mode, f) : null, ++e
        }), f
    }

    function db(a) {
        return a.lineSpace.offsetTop
    }

    function eb(a) {
        var b = Ye(a.measure, We("pre", null, null, "text-align: left")).appendChild(We("span", "x"));
        return b.offsetLeft
    }

    function fb(a, b, c, d) {
        var e = -1;
        d = d || hb(a, b);
        for (var f = c;; f += e) {
            var g = d[f];
            if (g) break;
            0 > e && 0 == f && (e = 1)
        }
        return {
            left: c > f ? g.right : g.left,
            right: f > c ? g.left : g.right,
            top: g.top,
            bottom: g.bottom
        }
    }

    function gb(a, b) {
        for (var c = a.display.measureLineCache, d = 0; c.length > d; ++d) {
            var e = c[d];
            if (e.text == b.text && e.markedSpans == b.markedSpans && a.display.scroller.clientWidth == e.width && e.classes == b.textClass + "|" + b.bgClass + "|" + b.wrapClass) return e.measure
        }
    }

    function hb(a, b) {
        var c = gb(a, b);
        if (!c) {
            c = ib(a, b);
            var d = a.display.measureLineCache,
                e = {
                    text: b.text,
                    width: a.display.scroller.clientWidth,
                    markedSpans: b.markedSpans,
                    measure: c,
                    classes: b.textClass + "|" + b.bgClass + "|" + b.wrapClass
                };
            16 == d.length ? d[++a.display.measureLineCachePos % 16] = e : d.push(e)
        }
        return c
    }

    function ib(a, e) {
        var f = a.display,
            g = Qe(e.text.length),
            h = Ld(a, e, g);
        if (b && !c && !a.options.lineWrapping && h.childNodes.length > 100) {
            for (var i = document.createDocumentFragment(), j = 10, k = h.childNodes.length, l = 0, m = Math.ceil(k / j); m > l; ++l) {
                for (var n = We("div", null, null, "display: inline-block"), o = 0; j > o && k; ++o) n.appendChild(h.firstChild), --k;
                i.appendChild(n)
            }
            h.appendChild(i)
        }
        Ye(f.measure, h);
        var p = $e(f.lineDiv),
            q = [],
            r = Qe(e.text.length),
            s = h.offsetHeight;
        d && f.measure.first != h && Ye(f.measure, h);
        for (var t, l = 0; g.length > l; ++l)
            if (t = g[l]) {
                for (var u = $e(t), v = Math.max(0, u.top - p.top), w = Math.min(u.bottom - p.top, s), o = 0; q.length > o; o += 2) {
                    var x = q[o],
                        y = q[o + 1];
                    if (!(x > w || v > y) && (v >= x && y >= w || x >= v && w >= y || Math.min(w, y) - Math.max(v, x) >= w - v >> 1)) {
                        q[o] = Math.min(v, x), q[o + 1] = Math.max(w, y);
                        break
                    }
                }
                o == q.length && q.push(v, w);
                var z = u.right;
                t.measureRight && (z = $e(t.measureRight).left), r[l] = {
                    left: u.left - p.left,
                    right: z - p.left,
                    top: o
                }
            }
        for (var t, l = 0; r.length > l; ++l)
            if (t = r[l]) {
                var A = t.top;
                t.top = q[A], t.bottom = q[A + 1]
            }
        return r
    }

    function jb(a, b) {
        var c = !1;
        if (b.markedSpans)
            for (var d = 0; b.markedSpans > d; ++d) {
                var e = b.markedSpans[d];
                !e.collapsed || null != e.to && e.to != b.text.length || (c = !0)
            }
        var f = !c && gb(a, b);
        if (f) return fb(a, b, b.text.length, f).right;
        var g = Ld(a, b),
            h = g.appendChild(ef(a.display.measure));
        return Ye(a.display.measure, g), $e(h).right - $e(a.display.lineDiv).left
    }

    function kb(a) {
        a.display.measureLineCache.length = a.display.measureLineCachePos = 0, a.display.cachedCharWidth = a.display.cachedTextHeight = null, a.display.maxLineChanged = !0, a.display.lineNumChars = null
    }

    function lb(a, b, c, d) {
        if (b.widgets)
            for (var e = 0; b.widgets.length > e; ++e)
                if (b.widgets[e].above) {
                    var f = Ad(b.widgets[e]);
                    c.top += f, c.bottom += f
                }
        if ("line" == d) return c;
        d || (d = "local");
        var g = ee(a, b);
        if ("local" != d && (g -= a.display.viewOffset), "page" == d) {
            var h = $e(a.display.lineSpace);
            g += h.top + (window.pageYOffset || (document.documentElement || document.body).scrollTop);
            var i = h.left + (window.pageXOffset || (document.documentElement || document.body).scrollLeft);
            c.left += i, c.right += i
        }
        return c.top += g, c.bottom += g, c
    }

    function mb(a, b, c, d) {
        return d || (d = $d(a.doc, b.line)), lb(a, d, fb(a, d, b.ch), c)
    }

    function nb(a, b, c, d, e) {
        function f(b, f) {
            var g = fb(a, d, b, e);
            return f ? g.left = g.right : g.right = g.left, lb(a, d, g, c)
        }
        d = d || $d(a.doc, b.line), e || (e = hb(a, d));
        var g = fe(d),
            h = b.ch;
        if (!g) return f(h);
        for (var i, j, k = g[0].level, l = 0; g.length > l; ++l) {
            var o, p, m = g[l],
                n = m.level % 2;
            if (h > m.from && m.to > h) return f(h, n);
            var q = n ? m.to : m.from,
                r = n ? m.from : m.to;
            if (q == h) p = l && m.level < (o = g[l - 1]).level ? f(o.level % 2 ? o.from : o.to - 1, !0) : f(n && m.from != m.to ? h - 1 : h), n == k ? i = p : j = p;
            else if (r == h) {
                var o = g.length - 1 > l && g[l + 1];
                if (!n && o && o.from == o.to) continue;
                p = o && m.level < o.level ? f(o.level % 2 ? o.to - 1 : o.from) : f(n ? h : h - 1, !0), n == k ? i = p : j = p
            }
        }
        return k && !h && (j = f(g[0].to - 1)), i ? (j && (i.other = j), i) : j
    }

    function ob(a, b, c) {
        var d = new qc(a, b);
        return c && (d.outside = !0), d
    }

    function pb(a, b, c) {
        var d = a.doc;
        if (c += a.display.viewOffset, 0 > c) return ob(d.first, 0, !0);
        var e = de(d, c),
            f = d.first + d.size - 1;
        if (e > f) return ob(d.first + d.size - 1, $d(d, f).text.length, !0);
        for (0 > b && (b = 0);;) {
            var g = $d(d, e),
                h = qb(a, g, e, b, c),
                i = sd(g),
                j = i && i.find();
            if (!(i && h.ch >= j.from.ch)) return h;
            e = j.to.line
        }
    }

    function qb(a, b, c, d, e) {
        function j(d) {
            var e = nb(a, qc(c, d), "line", b, i);
            return g = !0, f > e.bottom ? Math.max(0, e.left - h) : e.top > f ? e.left + h : (g = !1, e.left)
        }
        var f = e - ee(a, b),
            g = !1,
            h = a.display.wrapper.clientWidth,
            i = hb(a, b),
            k = fe(b),
            l = b.text.length,
            m = nf(b),
            n = of(b),
            o = j(m),
            p = g,
            q = j(n),
            r = g;
        if (d > q) return ob(c, n, r);
        for (;;) {
            if (k ? n == m || n == rf(b, m, 1) : 1 >= n - m) {
                for (var s = q - d > d - o, t = s ? m : n; Ve.test(b.text.charAt(t));)++t;
                var u = ob(c, t, s ? p : r);
                return u.after = s, u
            }
            var v = Math.ceil(l / 2),
                w = m + v;
            if (k) {
                w = m;
                for (var x = 0; v > x; ++x) w = rf(b, w, 1)
            }
            var y = j(w);
            y > d ? (n = w, q = y, (r = g) && (q += 1e3), l -= v) : (m = w, o = y, p = g, l = v)
        }
    }

    function sb(a) {
        if (null != a.cachedTextHeight) return a.cachedTextHeight;
        if (null == rb) {
            rb = We("pre");
            for (var b = 0; 49 > b; ++b) rb.appendChild(document.createTextNode("x")), rb.appendChild(We("br"));
            rb.appendChild(document.createTextNode("x"))
        }
        Ye(a.measure, rb);
        var c = rb.offsetHeight / 50;
        return c > 3 && (a.cachedTextHeight = c), Xe(a.measure), c || 1
    }

    function tb(a) {
        if (null != a.cachedCharWidth) return a.cachedCharWidth;
        var b = We("span", "x"),
            c = We("pre", [b]);
        Ye(a.measure, c);
        var d = b.offsetWidth;
        return d > 2 && (a.cachedCharWidth = d), d || 10
    }

    function vb(a) {
        a.curOp = {
            changes: [],
            updateInput: null,
            userSelChange: null,
            textChanged: null,
            selectionChanged: !1,
            updateMaxLine: !1,
            updateScrollPos: !1,
            id: ++ub
        }, Be++ || (Ae = [])
    }

    function wb(a) {
        var b = a.curOp,
            c = a.doc,
            d = a.display;
        if (a.curOp = null, b.updateMaxLine && H(a), d.maxLineChanged && !a.options.lineWrapping) {
            var e = jb(a, d.maxLine);
            d.sizer.style.minWidth = Math.max(0, e + 3 + Fe) + "px", d.maxLineChanged = !1;
            var f = Math.max(0, d.sizer.offsetLeft + d.sizer.offsetWidth - d.scroller.clientWidth);
            c.scrollLeft > f && !b.updateScrollPos && Rb(a, Math.min(d.scroller.scrollLeft, f), !0)
        }
        var g, h;
        if (b.updateScrollPos) g = b.updateScrollPos;
        else if (b.selectionChanged && d.scroller.clientHeight) {
            var i = nb(a, c.sel.head);
            g = Gc(a, i.left, i.top, i.left, i.bottom)
        }(b.changes.length || g && null != g.scrollTop) && (h = P(a, b.changes, g && g.scrollTop)), !h && b.selectionChanged && X(a), b.updateScrollPos ? (d.scroller.scrollTop = d.scrollbarV.scrollTop = c.scrollTop = g.scrollTop, d.scroller.scrollLeft = d.scrollbarH.scrollLeft = c.scrollLeft = g.scrollLeft, L(a)) : g && Dc(a), b.selectionChanged && $(a), a.state.focused && b.updateInput && Eb(a, b.userSelChange);
        var j = b.maybeHiddenMarkers,
            k = b.maybeUnhiddenMarkers;
        if (j)
            for (var l = 0; j.length > l; ++l) j[l].lines.length || ze(j[l], "hide");
        if (k)
            for (var l = 0; k.length > l; ++l) k[l].lines.length && ze(k[l], "unhide");
        var m;
        if (--Be || (m = Ae, Ae = null), b.textChanged && ze(a, "change", a, b.textChanged), b.selectionChanged && ze(a, "cursorActivity", a), m)
            for (var l = 0; m.length > l; ++l) m[l]()
    }

    function xb(a, b) {
        return function () {
            var c = a || this,
                d = !c.curOp;
            d && vb(c);
            try {
                var e = b.apply(c, arguments)
            } finally {
                d && wb(c)
            }
            return e
        }
    }

    function yb(a) {
        return function () {
            var c, b = this.cm && !this.cm.curOp;
            b && vb(this.cm);
            try {
                c = a.apply(this, arguments)
            } finally {
                b && wb(this.cm)
            }
            return c
        }
    }

    function zb(a, b) {
        var d, c = !a.curOp;
        c && vb(a);
        try {
            d = b()
        } finally {
            c && wb(a)
        }
        return d
    }

    function Ab(a, b, c, d) {
        null == b && (b = a.doc.first), null == c && (c = a.doc.first + a.doc.size), a.curOp.changes.push({
            from: b,
            to: c,
            diff: d
        })
    }

    function Bb(a) {
        a.display.pollingFast || a.display.poll.set(a.options.pollInterval, function () {
            Db(a), a.state.focused && Bb(a)
        })
    }

    function Cb(a) {
        function c() {
            var d = Db(a);
            d || b ? (a.display.pollingFast = !1, Bb(a)) : (b = !0, a.display.poll.set(60, c))
        }
        var b = !1;
        a.display.pollingFast = !0, a.display.poll.set(20, c)
    }

    function Db(a) {
        var c = a.display.input,
            d = a.display.prevInput,
            e = a.doc,
            f = e.sel;
        if (!a.state.focused || gf(c) || Gb(a)) return !1;
        var g = c.value;
        if (g == d && rc(f.from, f.to)) return !1;
        if (b && g && 0 === c.selectionStart) return Eb(a, !0), !1;
        var h = !a.curOp;
        h && vb(a), f.shift = !1;
        for (var i = 0, j = Math.min(d.length, g.length); j > i && d[i] == g[i];)++i;
        var k = f.from,
            l = f.to;
        d.length > i ? k = qc(k.line, k.ch - (d.length - i)) : a.state.overwrite && rc(k, l) && !a.state.pasteIncoming && (l = qc(l.line, Math.min($d(e, l.line).text.length, l.ch + (g.length - i))));
        var m = a.curOp.updateInput;
        return jc(a.doc, {
            from: k,
            to: l,
            text: ff(g.slice(i)),
            origin: a.state.pasteIncoming ? "paste" : "+input"
        }, "end"), a.curOp.updateInput = m, g.length > 1e3 ? c.value = a.display.prevInput = "" : a.display.prevInput = g, h && wb(a), a.state.pasteIncoming = !1, !0
    }

    function Eb(a, b) {
        var c, d, e = a.doc;
        rc(e.sel.from, e.sel.to) ? b && (a.display.prevInput = a.display.input.value = "") : (a.display.prevInput = "", c = hf && (e.sel.to.line - e.sel.from.line > 100 || (d = a.getSelection()).length > 1e3), a.display.input.value = c ? "-" : d || a.getSelection(), a.state.focused && Me(a.display.input)), a.display.inaccurateSelection = c
    }

    function Fb(a) {
        "nocursor" == a.options.readOnly || o && document.activeElement == a.display.input || a.display.input.focus()
    }

    function Gb(a) {
        return a.options.readOnly || a.doc.cantEdit
    }

    function Hb(a) {
        function c() {
            a.state.focused && setTimeout(Re(Fb, a), 0)
        }

        function d() {
            b.cachedCharWidth = b.cachedTextHeight = null, kb(a), zb(a, Re(Ab, a))
        }

        function e() {
            for (var a = b.wrapper.parentNode; a && a != document.body; a = a.parentNode);
            a ? setTimeout(e, 5e3) : ye(window, "resize", d)
        }

        function f(b) {
            a.options.onDragEvent && a.options.onDragEvent(a, re(b)) || ue(b)
        }

        function g() {
            b.inaccurateSelection && (b.prevInput = "", b.inaccurateSelection = !1, b.input.value = a.getSelection(), Me(b.input))
        }
        var b = a.display;
        xe(b.scroller, "mousedown", xb(a, Mb)), xe(b.scroller, "dblclick", xb(a, se)), xe(b.lineSpace, "selectstart", function (a) {
            Ib(b, a) || se(a)
        }), t || xe(b.scroller, "contextmenu", function (b) {
            ec(a, b)
        }), xe(b.scroller, "scroll", function () {
            Qb(a, b.scroller.scrollTop), Rb(a, b.scroller.scrollLeft, !0), ze(a, "scroll", a)
        }), xe(b.scrollbarV, "scroll", function () {
            Qb(a, b.scrollbarV.scrollTop)
        }), xe(b.scrollbarH, "scroll", function () {
            Rb(a, b.scrollbarH.scrollLeft)
        }), xe(b.scroller, "mousewheel", function (b) {
            Ub(a, b)
        }), xe(b.scroller, "DOMMouseScroll", function (b) {
            Ub(a, b)
        }), xe(b.scrollbarH, "mousedown", c), xe(b.scrollbarV, "mousedown", c), xe(b.wrapper, "scroll", function () {
            b.wrapper.scrollTop = b.wrapper.scrollLeft = 0
        }), xe(window, "resize", d), setTimeout(e, 5e3), xe(b.input, "keyup", xb(a, function (b) {
            a.options.onKeyEvent && a.options.onKeyEvent(a, re(b)) || 16 == b.keyCode && (a.doc.sel.shift = !1)
        })), xe(b.input, "input", Re(Cb, a)), xe(b.input, "keydown", xb(a, _b)), xe(b.input, "keypress", xb(a, ac)), xe(b.input, "focus", Re(bc, a)), xe(b.input, "blur", Re(cc, a)), a.options.dragDrop && (xe(b.scroller, "dragstart", function (b) {
            Pb(a, b)
        }), xe(b.scroller, "dragenter", f), xe(b.scroller, "dragover", f), xe(b.scroller, "drop", xb(a, Nb))), xe(b.scroller, "paste", function (c) {
            Ib(b, c) || (Fb(a), Cb(a))
        }), xe(b.input, "paste", function () {
            a.state.pasteIncoming = !0, Cb(a)
        }), xe(b.input, "cut", g), xe(b.input, "copy", g), j && xe(b.sizer, "mouseup", function () {
            document.activeElement == b.input && b.input.blur(), Fb(a)
        })
    }

    function Ib(a, b) {
        for (var c = ve(b); c != a.wrapper; c = c.parentNode) {
            if (!c) return !0;
            if (/\bCodeMirror-(?:line)?widget\b/.test(c.className) || c.parentNode == a.sizer && c != a.mover) return !0
        }
    }

    function Jb(a, b, c) {
        var d = a.display;
        if (!c) {
            var e = ve(b);
            if (e == d.scrollbarH || e == d.scrollbarH.firstChild || e == d.scrollbarV || e == d.scrollbarV.firstChild || e == d.scrollbarFiller) return null
        }
        var f, g, h = $e(d.lineSpace);
        try {
            f = b.clientX, g = b.clientY
        } catch (b) {
            return null
        }
        return pb(a, f - h.left, g - h.top)
    }

    function Mb(a) {
        function p(a) {
            if ("single" == j) return yc(c.doc, vc(f, h), a), void 0;
            if (n = vc(f, n), o = vc(f, o), "double" == j) {
                var b = Nc($d(f, a.line).text, a);
                sc(a, n) ? yc(c.doc, b.from, o) : yc(c.doc, n, b.to)
            } else "triple" == j && (sc(a, n) ? yc(c.doc, o, vc(f, qc(a.line, 0))) : yc(c.doc, n, vc(f, qc(a.line + 1, 0))))
        }

        function s(a) {
            var b = ++r,
                e = Jb(c, a, !0);
            if (e)
                if (rc(e, l)) {
                    var h = a.clientY < q.top ? -20 : a.clientY > q.bottom ? 20 : 0;
                    h && setTimeout(xb(c, function () {
                        r == b && (d.scroller.scrollTop += h, s(a))
                    }), 50)
                } else {
                    c.state.focused || bc(c), l = e, p(e);
                    var g = K(d, f);
                    (e.line >= g.to || e.line < g.from) && setTimeout(xb(c, function () {
                        r == b && s(a)
                    }), 150)
                }
        }

        function u(a) {
            r = 1 / 0;
            var b = Jb(c, a);
            b && p(b), se(a), Fb(c), ye(document, "mousemove", v), ye(document, "mouseup", w)
        }
        var c = this,
            d = c.display,
            f = c.doc,
            g = f.sel;
        if (g.shift = a.shiftKey, Ib(d, a)) return e || (d.scroller.draggable = !1, setTimeout(function () {
            d.scroller.draggable = !0
        }, 100)), void 0;
        if (!Ob(c, a)) {
            var h = Jb(c, a);
            switch (we(a)) {
            case 3:
                return t && ec.call(c, c, a), void 0;
            case 2:
                return h && yc(c.doc, h), setTimeout(Re(Fb, c), 20), se(a), void 0
            }
            if (!h) return ve(a) == d.scroller && se(a), void 0;
            c.state.focused || bc(c);
            var i = +new Date,
                j = "single";
            if (Lb && Lb.time > i - 400 && rc(Lb.pos, h)) j = "triple", se(a), setTimeout(Re(Fb, c), 20), Oc(c, h.line);
            else if (Kb && Kb.time > i - 400 && rc(Kb.pos, h)) {
                j = "double", Lb = {
                    time: i,
                    pos: h
                }, se(a);
                var k = Nc($d(f, h.line).text, h);
                yc(c.doc, k.from, k.to)
            } else Kb = {
                time: i,
                pos: h
            };
            var l = h;
            if (c.options.dragDrop && _e && !Gb(c) && !rc(g.from, g.to) && !sc(h, g.from) && !sc(g.to, h) && "single" == j) {
                var m = xb(c, function (b) {
                    e && (d.scroller.draggable = !1), c.state.draggingText = !1, ye(document, "mouseup", m), ye(d.scroller, "drop", m), 10 > Math.abs(a.clientX - b.clientX) + Math.abs(a.clientY - b.clientY) && (se(b), yc(c.doc, h), Fb(c))
                });
                return e && (d.scroller.draggable = !0), c.state.draggingText = m, d.scroller.dragDrop && d.scroller.dragDrop(), xe(document, "mouseup", m), xe(d.scroller, "drop", m), void 0
            }
            se(a), "single" == j && yc(c.doc, vc(f, h));
            var n = g.from,
                o = g.to,
                q = $e(d.wrapper),
                r = 0,
                v = xb(c, function (a) {
                    b || we(a) ? s(a) : u(a)
                }),
                w = xb(c, u);
            xe(document, "mousemove", v), xe(document, "mouseup", w)
        }
    }

    function Nb(a) {
        var b = this;
        if (!(Ib(b.display, a) || b.options.onDragEvent && b.options.onDragEvent(b, re(a)))) {
            se(a);
            var c = Jb(b, a, !0),
                d = a.dataTransfer.files;
            if (c && !Gb(b))
                if (d && d.length && window.FileReader && window.File)
                    for (var e = d.length, f = Array(e), g = 0, h = function (a, d) {
                            var h = new FileReader;
                            h.onload = function () {
                                f[d] = h.result, ++g == e && (c = vc(b.doc, c), jc(b.doc, {
                                    from: c,
                                    to: c,
                                    text: ff(f.join("\n")),
                                    origin: "paste"
                                }, "around"))
                            }, h.readAsText(a)
                        }, i = 0; e > i; ++i) h(d[i], i);
                else {
                    if (b.state.draggingText && !sc(c, b.doc.sel.from) && !sc(b.doc.sel.to, c)) return b.state.draggingText(a), setTimeout(Re(Fb, b), 20), void 0;
                    try {
                        var f = a.dataTransfer.getData("Text");
                        if (f) {
                            var j = b.doc.sel.from,
                                k = b.doc.sel.to;
                            Ac(b.doc, c, c), b.state.draggingText && pc(b.doc, "", j, k, "paste"), b.replaceSelection(f, null, "paste"), Fb(b), bc(b)
                        }
                    } catch (a) {}
                }
        }
    }

    function Ob(a, b) {
        var c = a.display;
        try {
            var d = b.clientX,
                e = b.clientY
        } catch (b) {
            return !1
        }
        if (d >= Math.floor($e(c.gutters).right)) return !1;
        if (se(b), !Ee(a, "gutterClick")) return !0;
        var f = $e(c.lineDiv);
        if (e > f.bottom) return !0;
        e -= f.top - c.viewOffset;
        for (var g = 0; a.options.gutters.length > g; ++g) {
            var h = c.gutters.childNodes[g];
            if (h && $e(h).right >= d) {
                var i = de(a.doc, e),
                    j = a.options.gutters[g];
                Ce(a, "gutterClick", a, i, j, b);
                break
            }
        }
        return !0
    }

    function Pb(a, b) {
        if (!Ib(a.display, b)) {
            var c = a.getSelection();
            if (b.dataTransfer.setData("Text", c), b.dataTransfer.setDragImage) {
                var d = We("img", null, null, "position: fixed; left: 0; top: 0;");
                h && (d.width = d.height = 1, a.display.wrapper.appendChild(d), d._top = d.offsetTop), i && (a.display.dragImg ? d = a.display.dragImg : (a.display.dragImg = d, d.src = "data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==", a.display.wrapper.appendChild(d))), b.dataTransfer.setDragImage(d, 0, 0), h && d.parentNode.removeChild(d)
            }
        }
    }

    function Qb(b, c) {
        2 > Math.abs(b.doc.scrollTop - c) || (b.doc.scrollTop = c, a || P(b, [], c), b.display.scroller.scrollTop != c && (b.display.scroller.scrollTop = c), b.display.scrollbarV.scrollTop != c && (b.display.scrollbarV.scrollTop = c), a && P(b, []))
    }

    function Rb(a, b, c) {
        (c ? b == a.doc.scrollLeft : 2 > Math.abs(a.doc.scrollLeft - b)) || (b = Math.min(b, a.display.scroller.scrollWidth - a.display.scroller.clientWidth), a.doc.scrollLeft = b, L(a), a.display.scroller.scrollLeft != b && (a.display.scroller.scrollLeft = b), a.display.scrollbarH.scrollLeft != b && (a.display.scrollbarH.scrollLeft = b))
    }

    function Ub(b, c) {
        var d = c.wheelDeltaX,
            f = c.wheelDeltaY;
        if (null == d && c.detail && c.axis == c.HORIZONTAL_AXIS && (d = c.detail), null == f && c.detail && c.axis == c.VERTICAL_AXIS ? f = c.detail : null == f && (f = c.wheelDelta), f && p && e)
            for (var g = c.target; g != j; g = g.parentNode)
                if (g.lineObj) {
                    b.display.currentWheelTarget = g;
                    break
                }
        var i = b.display,
            j = i.scroller;
        if (d && !a && !h && null != Tb) return f && Qb(b, Math.max(0, Math.min(j.scrollTop + f * Tb, j.scrollHeight - j.clientHeight))), Rb(b, Math.max(0, Math.min(j.scrollLeft + d * Tb, j.scrollWidth - j.clientWidth))), se(c), i.wheelStartX = null, void 0;
        if (f && null != Tb) {
            var k = f * Tb,
                l = b.doc.scrollTop,
                m = l + i.wrapper.clientHeight;
            0 > k ? l = Math.max(0, l + k - 50) : m = Math.min(b.doc.height, m + k + 50), P(b, [], {
                top: l,
                bottom: m
            })
        }
        20 > Sb && (null == i.wheelStartX ? (i.wheelStartX = j.scrollLeft, i.wheelStartY = j.scrollTop, i.wheelDX = d, i.wheelDY = f, setTimeout(function () {
            if (null != i.wheelStartX) {
                var a = j.scrollLeft - i.wheelStartX,
                    b = j.scrollTop - i.wheelStartY,
                    c = b && i.wheelDY && b / i.wheelDY || a && i.wheelDX && a / i.wheelDX;
                i.wheelStartX = i.wheelStartY = null, c && (Tb = (Tb * Sb + c) / (Sb + 1), ++Sb)
            }
        }, 200)) : (i.wheelDX += d, i.wheelDY += f))
    }

    function Vb(a, b, c) {
        if ("string" == typeof b && (b = Zc[b], !b)) return !1;
        a.display.pollingFast && Db(a) && (a.display.pollingFast = !1);
        var d = a.doc,
            e = d.sel.shift,
            f = !1;
        try {
            Gb(a) && (a.state.suppressEdits = !0), c && (d.sel.shift = !1), f = b(a) != Ge
        } finally {
            d.sel.shift = e, a.state.suppressEdits = !1
        }
        return f
    }

    function Wb(a) {
        var b = a.state.keyMaps.slice(0);
        return b.push(a.options.keyMap), a.options.extraKeys && b.unshift(a.options.extraKeys), b
    }

    function Yb(a, b) {
        var c = _c(a.options.keyMap),
            e = c.auto;
        clearTimeout(Xb), e && !bd(b) && (Xb = setTimeout(function () {
            _c(a.options.keyMap) == c && (a.options.keyMap = e.call ? e.call(null, a) : e)
        }, 50));
        var f = cd(b, !0),
            g = !1;
        if (!f) return !1;
        var h = Wb(a);
        return g = b.shiftKey ? ad("Shift-" + f, h, function (b) {
            return Vb(a, b, !0)
        }) || ad(f, h, function (b) {
            return "string" == typeof b && /^go[A-Z]/.test(b) ? Vb(a, b) : void 0
        }) : ad(f, h, function (b) {
            return Vb(a, b)
        }), "stop" == g && (g = !1), g && (se(b), $(a), d && (b.oldKeyCode = b.keyCode, b.keyCode = 0)), g
    }

    function Zb(a, b, c) {
        var d = ad("'" + c + "'", Wb(a), function (b) {
            return Vb(a, b, !0)
        });
        return d && (se(b), $(a)), d
    }

    function _b(a) {
        var c = this;
        if (c.state.focused || bc(c), b && 27 == a.keyCode && (a.returnValue = !1), !c.options.onKeyEvent || !c.options.onKeyEvent(c, re(a))) {
            var d = a.keyCode;
            c.doc.sel.shift = 16 == d || a.shiftKey;
            var e = Yb(c, a);
            h && ($b = e ? d : null, !e && 88 == d && !hf && (p ? a.metaKey : a.ctrlKey) && c.replaceSelection(""))
        }
    }

    function ac(a) {
        var b = this;
        if (!b.options.onKeyEvent || !b.options.onKeyEvent(b, re(a))) {
            var c = a.keyCode,
                d = a.charCode;
            if (h && c == $b) return $b = null, se(a), void 0;
            if (!(h && (!a.which || 10 > a.which) || j) || !Yb(b, a)) {
                var e = String.fromCharCode(null == d ? c : d);
                this.options.electricChars && this.doc.mode.electricChars && this.options.smartIndent && !Gb(this) && this.doc.mode.electricChars.indexOf(e) > -1 && setTimeout(xb(b, function () {
                    Jc(b, b.doc.sel.to.line, "smart")
                }), 75), Zb(b, a, e) || Cb(b)
            }
        }
    }

    function bc(a) {
        "nocursor" != a.options.readOnly && (a.state.focused || (ze(a, "focus", a), a.state.focused = !0, -1 == a.display.wrapper.className.search(/\bCodeMirror-focused\b/) && (a.display.wrapper.className += " CodeMirror-focused"), Eb(a, !0)), Bb(a), $(a))
    }

    function cc(a) {
        a.state.focused && (ze(a, "blur", a), a.state.focused = !1, a.display.wrapper.className = a.display.wrapper.className.replace(" CodeMirror-focused", "")), clearInterval(a.display.blinker), setTimeout(function () {
            a.state.focused || (a.doc.sel.shift = !1)
        }, 150)
    }

    function ec(a, c) {
        function k() {
            if (e.inputDiv.style.position = "relative", e.input.style.cssText = j, d && (e.scrollbarV.scrollTop = e.scroller.scrollTop = i), Bb(a), null != e.input.selectionStart && (!b || d)) {
                clearTimeout(dc);
                var c = e.input.value = " " + (rc(f.from, f.to) ? "" : e.input.value),
                    g = 0;
                e.prevInput = " ", e.input.selectionStart = 1, e.input.selectionEnd = c.length;
                var h = function () {
                    " " == e.prevInput && 0 == e.input.selectionStart ? xb(a, Zc.selectAll)(a) : 10 > g++ ? dc = setTimeout(h, 500) : Eb(a)
                };
                dc = setTimeout(h, 200)
            }
        }
        var e = a.display,
            f = a.doc.sel;
        if (!Ib(e, c)) {
            var g = Jb(a, c),
                i = e.scroller.scrollTop;
            if (g && !h) {
                (rc(f.from, f.to) || sc(g, f.from) || !sc(g, f.to)) && xb(a, Ac)(a.doc, g, g);
                var j = e.input.style.cssText;
                if (e.inputDiv.style.position = "absolute", e.input.style.cssText = "position: fixed; width: 30px; height: 30px; top: " + (c.clientY - 5) + "px; left: " + (c.clientX - 5) + "px; z-index: 1000; background: white; outline: none;" + "border-width: 0; outline: none; overflow: hidden; opacity: .05; -ms-opacity: .05; filter: alpha(opacity=5);", Fb(a), Eb(a, !0), rc(f.from, f.to) && (e.input.value = e.prevInput = " "), t) {
                    ue(c);
                    var l = function () {
                        ye(window, "mouseup", l), setTimeout(k, 20)
                    };
                    xe(window, "mouseup", l)
                } else setTimeout(k, 50)
            }
        }
    }

    function fc(a) {
        return qc(a.from.line + a.text.length - 1, Le(a.text).length + (1 == a.text.length ? a.from.ch : 0))
    }

    function gc(a, b, c) {
        if (!sc(b.from, c)) return vc(a, c);
        var d = b.text.length - 1 - (b.to.line - b.from.line);
        if (c.line > b.to.line + d) {
            var e = c.line - d,
                f = a.first + a.size - 1;
            return e > f ? qc(f, $d(a, f).text.length) : wc(c, $d(a, e).text.length)
        }
        if (c.line == b.to.line + d) return wc(c, Le(b.text).length + (1 == b.text.length ? b.from.ch : 0) + $d(a, b.to.line).text.length - b.to.ch);
        var g = c.line - b.from.line;
        return wc(c, b.text[g].length + (g ? 0 : b.from.ch))
    }

    function hc(a, b, c) {
        if (c && "object" == typeof c) return {
            anchor: gc(a, b, c.anchor),
            head: gc(a, b, c.head)
        };
        if ("start" == c) return {
            anchor: b.from,
            head: b.from
        };
        var d = fc(b);
        if ("around" == c) return {
            anchor: b.from,
            head: d
        };
        if ("end" == c) return {
            anchor: d,
            head: d
        };
        var e = function (a) {
            if (sc(a, b.from)) return a;
            if (!sc(b.to, a)) return d;
            var c = a.line + b.text.length - (b.to.line - b.from.line) - 1,
                e = a.ch;
            return a.line == b.to.line && (e += d.ch - b.to.ch), qc(c, e)
        };
        return {
            anchor: e(a.sel.anchor),
            head: e(a.sel.head)
        }
    }

    function ic(a, b) {
        var c = {
            canceled: !1,
            from: b.from,
            to: b.to,
            text: b.text,
            origin: b.origin,
            update: function (b, c, d, e) {
                b && (this.from = vc(a, b)), c && (this.to = vc(a, c)), d && (this.text = d), void 0 !== e && (this.origin = e)
            },
            cancel: function () {
                this.canceled = !0
            }
        };
        return ze(a, "beforeChange", a, c), a.cm && ze(a.cm, "beforeChange", a.cm, c), c.canceled ? null : {
            from: c.from,
            to: c.to,
            text: c.text,
            origin: c.origin
        }
    }

    function jc(a, b, c, d) {
        if (a.cm) {
            if (!a.cm.curOp) return xb(a.cm, jc)(a, b, c, d);
            if (a.cm.state.suppressEdits) return
        }
        if (!(Ee(a, "beforeChange") || a.cm && Ee(a.cm, "beforeChange")) || (b = ic(a, b))) {
            var e = u && !d && pd(a, b.from, b.to);
            if (e) {
                for (var f = e.length - 1; f >= 1; --f) kc(a, {
                    from: e[f].from,
                    to: e[f].to,
                    text: [""]
                });
                e.length && kc(a, {
                    from: e[0].from,
                    to: e[0].to,
                    text: b.text
                }, c)
            } else kc(a, b, c)
        }
    }

    function kc(a, b, c) {
        var d = hc(a, b, c);
        je(a, b, d, a.cm ? a.cm.curOp.id : 0 / 0), nc(a, b, d, nd(a, b));
        var e = [];
        Yd(a, function (a, c) {
            c || -1 != Ne(e, a.history) || (pe(a.history, b), e.push(a.history)), nc(a, b, null, nd(a, b))
        })
    }

    function lc(a, b) {
        var c = a.history,
            d = ("undo" == b ? c.done : c.undone).pop();
        if (d) {
            c.dirtyCounter += "undo" == b ? -1 : 1;
            var e = {
                changes: [],
                anchorBefore: d.anchorAfter,
                headBefore: d.headAfter,
                anchorAfter: d.anchorBefore,
                headAfter: d.headBefore
            };
            ("undo" == b ? c.undone : c.done).push(e);
            for (var f = d.changes.length - 1; f >= 0; --f) {
                var g = d.changes[f];
                g.origin = b, e.changes.push(ie(a, g));
                var h = f ? hc(a, g, null) : {
                    anchor: d.anchorBefore,
                    head: d.headBefore
                };
                nc(a, g, h, od(a, g));
                var i = [];
                Yd(a, function (a, b) {
                    b || -1 != Ne(i, a.history) || (pe(a.history, g), i.push(a.history)), nc(a, g, null, od(a, g))
                })
            }
        }
    }

    function mc(a, b) {
        function c(a) {
            return qc(a.line + b, a.ch)
        }
        a.first += b, a.cm && Ab(a.cm, a.first, a.first, b), a.sel.head = c(a.sel.head), a.sel.anchor = c(a.sel.anchor), a.sel.from = c(a.sel.from), a.sel.to = c(a.sel.to)
    }

    function nc(a, b, c, d) {
        if (a.cm && !a.cm.curOp) return xb(a.cm, nc)(a, b, c, d);
        if (b.to.line < a.first) return mc(a, b.text.length - 1 - (b.to.line - b.from.line)), void 0;
        if (!(b.from.line > a.lastLine())) {
            if (b.from.line < a.first) {
                var e = b.text.length - 1 - (a.first - b.from.line);
                mc(a, e), b = {
                    from: qc(a.first, 0),
                    to: qc(b.to.line + e, b.to.ch),
                    text: [Le(b.text)],
                    origin: b.origin
                }
            }
            var f = a.lastLine();
            b.to.line > f && (b = {
                from: b.from,
                to: qc(f, $d(a, f).text.length),
                text: [b.text[0]],
                origin: b.origin
            }), c || (c = hc(a, b, null)), a.cm ? oc(a.cm, b, d, c) : Rd(a, b, d, c)
        }
    }

    function oc(a, b, c, d) {
        var e = a.doc,
            f = a.display,
            g = b.from,
            h = b.to,
            i = !1,
            j = g.line;
        a.options.lineWrapping || (j = ce(td(e, $d(e, g.line))), e.iter(j, h.line + 1, function (a) {
            return a == f.maxLine ? (i = !0, !0) : void 0
        })), Rd(e, b, c, d, A(a)), a.options.lineWrapping || (e.iter(j, g.line + b.text.length, function (a) {
            var b = G(e, a);
            b > f.maxLineLength && (f.maxLine = a, f.maxLineLength = b, f.maxLineChanged = !0, i = !1)
        }), i && (a.curOp.updateMaxLine = !0)), e.frontier = Math.min(e.frontier, g.line), _(a, 400);
        var k = b.text.length - (h.line - g.line) - 1;
        if (Ab(a, g.line, h.line + 1, k), Ee(a, "change")) {
            var l = {
                from: g,
                to: h,
                text: b.text,
                origin: b.origin
            };
            if (a.curOp.textChanged) {
                for (var m = a.curOp.textChanged; m.next; m = m.next);
                m.next = l
            } else a.curOp.textChanged = l
        }
    }

    function pc(a, b, c, d, e) {
        if (d || (d = c), sc(d, c)) {
            var f = d;
            d = c, c = f
        }
        "string" == typeof b && (b = ff(b)), jc(a, {
            from: c,
            to: d,
            text: b,
            origin: e
        }, null)
    }

    function qc(a, b) {
        return this instanceof qc ? (this.line = a, this.ch = b, void 0) : new qc(a, b)
    }

    function rc(a, b) {
        return a.line == b.line && a.ch == b.ch
    }

    function sc(a, b) {
        return a.line < b.line || a.line == b.line && a.ch < b.ch
    }

    function tc(a) {
        return qc(a.line, a.ch)
    }

    function uc(a, b) {
        return Math.max(a.first, Math.min(b, a.first + a.size - 1))
    }

    function vc(a, b) {
        if (b.line < a.first) return qc(a.first, 0);
        var c = a.first + a.size - 1;
        return b.line > c ? qc(c, $d(a, c).text.length) : wc(b, $d(a, b.line).text.length)
    }

    function wc(a, b) {
        var c = a.ch;
        return null == c || c > b ? qc(a.line, b) : 0 > c ? qc(a.line, 0) : a
    }

    function xc(a, b) {
        return b >= a.first && a.first + a.size > b
    }

    function yc(a, b, c, d) {
        if (a.sel.shift || a.sel.extend) {
            var e = a.sel.anchor;
            if (c) {
                var f = sc(b, e);
                f != sc(c, e) ? (e = b, b = c) : f != sc(b, c) && (b = c)
            }
            Ac(a, e, b, d)
        } else Ac(a, b, c || b, d);
        a.cm && (a.cm.curOp.userSelChange = !0)
    }

    function zc(a, b, c) {
        var d = {
            anchor: b,
            head: c
        };
        return ze(a, "beforeSelectionChange", a, d), a.cm && ze(a.cm, "beforeSelectionChange", a.cm, d), d.anchor = vc(a, d.anchor), d.head = vc(a, d.head), d
    }

    function Ac(a, b, c, d, e) {
        if (!e && Ee(a, "beforeSelectionChange") || a.cm && Ee(a.cm, "beforeSelectionChange")) {
            var f = zc(a, b, c);
            c = f.head, b = f.anchor
        }
        var g = a.sel;
        if (g.goalColumn = null, (e || !rc(b, g.anchor)) && (b = Cc(a, b, d, "push" != e)), (e || !rc(c, g.head)) && (c = Cc(a, c, d, "push" != e)), !rc(g.anchor, b) || !rc(g.head, c)) {
            g.anchor = b, g.head = c;
            var h = sc(c, b);
            g.from = h ? c : b, g.to = h ? b : c, a.cm && (a.cm.curOp.updateInput = a.cm.curOp.selectionChanged = !0), Ce(a, "cursorActivity", a)
        }
    }

    function Bc(a) {
        Ac(a.doc, a.doc.sel.from, a.doc.sel.to, null, "push")
    }

    function Cc(a, b, c, d) {
        var e = !1,
            f = b,
            g = c || 1;
        a.cantEdit = !1;
        a: for (;;) {
            var i, h = $d(a, f.line);
            if (h.markedSpans) {
                for (var j = 0; h.markedSpans.length > j; ++j) {
                    var k = h.markedSpans[j],
                        l = k.marker;
                    if ((null == k.from || (l.inclusiveLeft ? k.from <= f.ch : k.from < f.ch)) && (null == k.to || (l.inclusiveRight ? k.to >= f.ch : k.to > f.ch))) {
                        if (d && l.clearOnEnter) {
                            (i || (i = [])).push(l);
                            continue
                        }
                        if (!l.atomic) continue;
                        var m = l.find()[0 > g ? "from" : "to"];
                        if (rc(m, f) && (m.ch += g, 0 > m.ch ? m = m.line > a.first ? vc(a, qc(m.line - 1)) : null : m.ch > h.text.length && (m = m.line < a.first + a.size - 1 ? qc(m.line + 1, 0) : null), !m)) {
                            if (e) return d ? (a.cantEdit = !0, qc(a.first, 0)) : Cc(a, b, c, !0);
                            e = !0, m = b, g = -g
                        }
                        f = m;
                        continue a
                    }
                }
                if (i)
                    for (var j = 0; i.length > j; ++j) i[j].clear()
            }
            return f
        }
    }

    function Dc(a) {
        var b = Ec(a, a.doc.sel.head);
        if (a.state.focused) {
            var c = a.display,
                d = $e(c.sizer),
                e = null;
            if (0 > b.top + d.top ? e = !0 : b.bottom + d.top > (window.innerHeight || document.documentElement.clientHeight) && (e = !1), null != e && !m) {
                var f = "none" == c.cursor.style.display;
                f && (c.cursor.style.display = "", c.cursor.style.left = b.left + "px", c.cursor.style.top = b.top - c.viewOffset + "px"), c.cursor.scrollIntoView(e), f && (c.cursor.style.display = "none")
            }
        }
    }

    function Ec(a, b) {
        for (;;) {
            var c = !1,
                d = nb(a, b),
                e = Gc(a, d.left, d.top, d.left, d.bottom),
                f = a.doc.scrollTop,
                g = a.doc.scrollLeft;
            if (null != e.scrollTop && (Qb(a, e.scrollTop), Math.abs(a.doc.scrollTop - f) > 1 && (c = !0)), null != e.scrollLeft && (Rb(a, e.scrollLeft), Math.abs(a.doc.scrollLeft - g) > 1 && (c = !0)), !c) return d
        }
    }

    function Fc(a, b, c, d, e) {
        var f = Gc(a, b, c, d, e);
        null != f.scrollTop && Qb(a, f.scrollTop), null != f.scrollLeft && Rb(a, f.scrollLeft)
    }

    function Gc(a, b, c, d, e) {
        var f = a.display,
            g = db(f);
        c += g, e += g;
        var h = f.scroller.clientHeight - Fe,
            i = f.scroller.scrollTop,
            j = {}, k = a.doc.height + 2 * g,
            l = g + 10 > c,
            m = e + g > k - 10;
        i > c ? j.scrollTop = l ? 0 : Math.max(0, c) : e > i + h && (j.scrollTop = (m ? k : e) - h);
        var n = f.scroller.clientWidth - Fe,
            o = f.scroller.scrollLeft;
        b += f.gutters.offsetWidth, d += f.gutters.offsetWidth;
        var p = f.gutters.offsetWidth,
            q = p + 10 > b;
        return o + p > b || q ? (q && (b = 0), j.scrollLeft = Math.max(0, b - 10 - p)) : d > n + o - 3 && (j.scrollLeft = d + 10 - n), j
    }

    function Hc(a, b, c) {
        a.curOp.updateScrollPos = {
            scrollLeft: b,
            scrollTop: c
        }
    }

    function Ic(a, b, c) {
        var d = a.curOp.updateScrollPos || (a.curOp.updateScrollPos = {
            scrollLeft: a.doc.scrollLeft,
            scrollTop: a.doc.scrollTop
        }),
            e = a.display.scroller;
        d.scrollTop = Math.max(0, Math.min(e.scrollHeight - e.clientHeight, d.scrollTop + c)), d.scrollLeft = Math.max(0, Math.min(e.scrollWidth - e.clientWidth, d.scrollLeft + b))
    }

    function Jc(a, b, c, d) {
        var e = a.doc;
        if (c || (c = "add"), "smart" == c)
            if (a.doc.mode.indent) var f = cb(a, b);
            else c = "prev";
        var k, g = a.options.tabSize,
            h = $d(e, b),
            i = Ie(h.text, null, g),
            j = h.text.match(/^\s*/)[0];
        if ("smart" == c && (k = a.doc.mode.indent(f, h.text.slice(j.length), h.text), k == Ge)) {
            if (!d) return;
            c = "prev"
        }
        "prev" == c ? k = b > e.first ? Ie($d(e, b - 1).text, null, g) : 0 : "add" == c ? k = i + a.options.indentUnit : "subtract" == c && (k = i - a.options.indentUnit), k = Math.max(0, k);
        var l = "",
            m = 0;
        if (a.options.indentWithTabs)
            for (var n = Math.floor(k / g); n; --n) m += g, l += "	";
        k > m && (l += Ke(k - m)), l != j && pc(a.doc, l, qc(b, 0), qc(b, j.length), "+input"), h.stateAfter = null
    }

    function Kc(a, b, c) {
        var d = b,
            e = b,
            f = a.doc;
        return "number" == typeof b ? e = $d(f, uc(f, b)) : d = ce(b), null == d ? null : c(e, d) ? (Ab(a, d, d + 1), e) : null
    }

    function Lc(a, b, c, d, e) {
        function j() {
            var b = f + c;
            return a.first > b || b >= a.first + a.size ? i = !1 : (f = b, h = $d(a, b))
        }

        function k(a) {
            var b = (e ? rf : sf)(h, g, c, !0);
            if (null == b) {
                if (a || !j()) return i = !1;
                g = e ? (0 > c ? of : nf)(h) : 0 > c ? h.text.length : 0
            } else g = b;
            return !0
        }
        var f = b.line,
            g = b.ch,
            h = $d(a, f),
            i = !0;
        if ("char" == d) k();
        else if ("column" == d) k(!0);
        else if ("word" == d || "wordBoundary" == d)
            for (var l = !1, m = "wordBoundary" == d, n = null; !(0 > c) || k();) {
                var o = Te(h.text.charAt(g)),
                    p = !1;
                if (m ? null == n ? n = o : p = n != o : o ? l = !0 : p = l, p) {
                    0 > c && (c = 1, k());
                    break
                }
                if (c > 0 && !k()) break
            }
        var q = Cc(a, qc(f, g), c, !0);
        return i || (q.hitSide = !0), q
    }

    function Mc(a, b, c, d) {
        var g, e = a.doc,
            f = b.left;
        if ("page" == d) {
            var h = Math.min(a.display.wrapper.clientHeight, window.innerHeight || document.documentElement.clientHeight);
            g = b.top + c * (h - (0 > c ? 1.5 : .5) * sb(a.display))
        } else "line" == d && (g = c > 0 ? b.bottom + 3 : b.top - 3);
        for (;;) {
            var i = pb(a, f, g);
            if (!i.outside) break;
            if (0 > c ? 0 >= g : g >= e.height) {
                i.hitSide = !0;
                break
            }
            g += 5 * c
        }
        return i
    }

    function Nc(a, b) {
        var c = b.ch,
            d = b.ch;
        if (a) {
            b.after === !1 || d == a.length ? --c : ++d;
            for (var e = a.charAt(c), f = Te(e) ? Te : /\s/.test(e) ? function (a) {
                    return /\s/.test(a)
                } : function (a) {
                    return !/\s/.test(a) && !Te(a)
                }; c > 0 && f(a.charAt(c - 1));)--c;
            for (; a.length > d && f(a.charAt(d));)++d
        }
        return {
            from: qc(b.line, c),
            to: qc(b.line, d)
        }
    }

    function Oc(a, b) {
        yc(a.doc, qc(b, 0), vc(a.doc, qc(b + 1, 0)))
    }

    function Rc(a, b, c, d) {
        w.defaults[a] = b, c && (Pc[a] = d ? function (a, b, d) {
            d != Sc && c(a, b, d)
        } : c)
    }

    function Xc(a, b) {
        if (b === !0) return b;
        if (a.copyState) return a.copyState(b);
        var c = {};
        for (var d in b) {
            var e = b[d];
            e instanceof Array && (e = e.concat([])), c[d] = e
        }
        return c
    }

    function Yc(a, b, c) {
        return a.startState ? a.startState(b, c) : !0
    }

    function _c(a) {
        return "string" == typeof a ? $c[a] : a
    }

    function ad(a, b, c) {
        function d(b) {
            b = _c(b);
            var e = b[a];
            if (e === !1) return "stop";
            if (null != e && c(e)) return !0;
            if (b.nofallthrough) return "stop";
            var f = b.fallthrough;
            if (null == f) return !1;
            if ("[object Array]" != Object.prototype.toString.call(f)) return d(f);
            for (var g = 0, h = f.length; h > g; ++g) {
                var i = d(f[g]);
                if (i) return i
            }
            return !1
        }
        for (var e = 0; b.length > e; ++e) {
            var f = d(b[e]);
            if (f) return f
        }
    }

    function bd(a) {
        var b = jf[a.keyCode];
        return "Ctrl" == b || "Alt" == b || "Shift" == b || "Mod" == b
    }

    function cd(a, b) {
        var c = jf[a.keyCode];
        return null == c || a.altGraphKey ? !1 : (a.altKey && (c = "Alt-" + c), (s ? a.metaKey : a.ctrlKey) && (c = "Ctrl-" + c), (s ? a.ctrlKey : a.metaKey) && (c = "Cmd-" + c), !b && a.shiftKey && (c = "Shift-" + c), c)
    }

    function dd(a, b) {
        this.pos = this.start = 0, this.string = a, this.tabSize = b || 8, this.lastColumnPos = this.lastColumnValue = 0
    }

    function ed(a, b) {
        this.lines = [], this.type = b, this.doc = a
    }

    function fd(a, b, c, d, e) {
        if (d && d.shared) return hd(a, b, c, d, e);
        if (a.cm && !a.cm.curOp) return xb(a.cm, fd)(a, b, c, d, e);
        var f = new ed(a, e);
        if ("range" == e && !sc(b, c)) return f;
        d && Pe(d, f), f.replacedWith && (f.collapsed = !0, f.replacedWith = We("span", [f.replacedWith], "CodeMirror-widget")), f.collapsed && (v = !0);
        var i, j, l, g = b.line,
            h = 0,
            k = a.cm;
        if (a.iter(g, c.line + 1, function (d) {
            k && f.collapsed && !k.options.lineWrapping && td(a, d) == k.display.maxLine && (l = !0);
            var e = {
                from: null,
                to: null,
                marker: f
            };
            h += d.text.length, g == b.line && (e.from = b.ch, h -= b.ch), g == c.line && (e.to = c.ch, h -= d.text.length - c.ch), f.collapsed && (g == c.line && (j = qd(d, c.ch)), g == b.line ? i = qd(d, b.ch) : be(d, 0)), kd(d, e), ++g
        }), f.collapsed && a.iter(b.line, c.line + 1, function (b) {
            ud(a, b) && be(b, 0)
        }), f.readOnly && (u = !0, (a.history.done.length || a.history.undone.length) && a.clearHistory()), f.collapsed) {
            if (i != j) throw Error("Inserting collapsed marker overlapping an existing one");
            f.size = h, f.atomic = !0
        }
        return k && (l && (k.curOp.updateMaxLine = !0), (f.className || f.startStyle || f.endStyle || f.collapsed) && Ab(k, b.line, c.line + 1), f.atomic && Bc(k)), f
    }

    function gd(a, b) {
        this.markers = a, this.primary = b;
        for (var c = 0, d = this; a.length > c; ++c) a[c].parent = this, xe(a[c], "clear", function () {
            d.clear()
        })
    }

    function hd(a, b, c, d, e) {
        d = Pe(d), d.shared = !1;
        var f = [fd(a, b, c, d, e)],
            g = f[0],
            h = d.replacedWith;
        return Yd(a, function (a) {
            h && (d.replacedWith = h.cloneNode(!0)), f.push(fd(a, vc(a, b), vc(a, c), d, e));
            for (var i = 0; a.linked.length > i; ++i)
                if (a.linked[i].isParent) return;
            g = Le(f)
        }), new gd(f, g)
    }

    function id(a, b) {
        if (a)
            for (var c = 0; a.length > c; ++c) {
                var d = a[c];
                if (d.marker == b) return d
            }
    }

    function jd(a, b) {
        for (var c, d = 0; a.length > d; ++d) a[d] != b && (c || (c = [])).push(a[d]);
        return c
    }

    function kd(a, b) {
        a.markedSpans = a.markedSpans ? a.markedSpans.concat([b]) : [b], b.marker.attachLine(a)
    }

    function ld(a, b, c) {
        if (a)
            for (var e, d = 0; a.length > d; ++d) {
                var f = a[d],
                    g = f.marker,
                    h = null == f.from || (g.inclusiveLeft ? b >= f.from : b > f.from);
                if (h || "bookmark" == g.type && f.from == b && (!c || !f.marker.insertLeft)) {
                    var i = null == f.to || (g.inclusiveRight ? f.to >= b : f.to > b);
                    (e || (e = [])).push({
                        from: f.from,
                        to: i ? null : f.to,
                        marker: g
                    })
                }
            }
        return e
    }

    function md(a, b, c) {
        if (a)
            for (var e, d = 0; a.length > d; ++d) {
                var f = a[d],
                    g = f.marker,
                    h = null == f.to || (g.inclusiveRight ? f.to >= b : f.to > b);
                if (h || "bookmark" == g.type && f.from == b && (!c || f.marker.insertLeft)) {
                    var i = null == f.from || (g.inclusiveLeft ? b >= f.from : b > f.from);
                    (e || (e = [])).push({
                        from: i ? null : f.from - b,
                        to: null == f.to ? null : f.to - b,
                        marker: g
                    })
                }
            }
        return e
    }

    function nd(a, b) {
        var c = xc(a, b.from.line) && $d(a, b.from.line).markedSpans,
            d = xc(a, b.to.line) && $d(a, b.to.line).markedSpans;
        if (!c && !d) return null;
        var e = b.from.ch,
            f = b.to.ch,
            g = rc(b.from, b.to),
            h = ld(c, e, g),
            i = md(d, f, g),
            j = 1 == b.text.length,
            k = Le(b.text).length + (j ? e : 0);
        if (h)
            for (var l = 0; h.length > l; ++l) {
                var m = h[l];
                if (null == m.to) {
                    var n = id(i, m.marker);
                    n ? j && (m.to = null == n.to ? null : n.to + k) : m.to = e
                }
            }
        if (i)
            for (var l = 0; i.length > l; ++l) {
                var m = i[l];
                if (null != m.to && (m.to += k), null == m.from) {
                    var n = id(h, m.marker);
                    n || (m.from = k, j && (h || (h = [])).push(m))
                } else m.from += k, j && (h || (h = [])).push(m)
            }
        var o = [h];
        if (!j) {
            var q, p = b.text.length - 2;
            if (p > 0 && h)
                for (var l = 0; h.length > l; ++l) null == h[l].to && (q || (q = [])).push({
                    from: null,
                    to: null,
                    marker: h[l].marker
                });
            for (var l = 0; p > l; ++l) o.push(q);
            o.push(i)
        }
        return o
    }

    function od(a, b) {
        var c = le(a, b),
            d = nd(a, b);
        if (!c) return d;
        if (!d) return c;
        for (var e = 0; c.length > e; ++e) {
            var f = c[e],
                g = d[e];
            if (f && g) a: for (var h = 0; g.length > h; ++h) {
                for (var i = g[h], j = 0; f.length > j; ++j)
                    if (f[j].marker == i.marker) continue a;
                f.push(i)
            } else g && (c[e] = g)
        }
        return c
    }

    function pd(a, b, c) {
        var d = null;
        if (a.iter(b.line, c.line + 1, function (a) {
            if (a.markedSpans)
                for (var b = 0; a.markedSpans.length > b; ++b) {
                    var c = a.markedSpans[b].marker;
                    !c.readOnly || d && -1 != Ne(d, c) || (d || (d = [])).push(c)
                }
        }), !d) return null;
        for (var e = [{
            from: b,
            to: c
        }], f = 0; d.length > f; ++f)
            for (var g = d[f], h = g.find(), i = 0; e.length > i; ++i) {
                var j = e[i];
                if (!sc(j.to, h.from) && !sc(h.to, j.from)) {
                    var k = [i, 1];
                    (sc(j.from, h.from) || !g.inclusiveLeft && rc(j.from, h.from)) && k.push({
                        from: j.from,
                        to: h.from
                    }), (sc(h.to, j.to) || !g.inclusiveRight && rc(j.to, h.to)) && k.push({
                        from: h.to,
                        to: j.to
                    }), e.splice.apply(e, k), i += k.length - 1
                }
            }
        return e
    }

    function qd(a, b) {
        var d, c = v && a.markedSpans;
        if (c)
            for (var e, f = 0; c.length > f; ++f) e = c[f], e.marker.collapsed && (null == e.from || b > e.from) && (null == e.to || e.to > b) && (!d || d.width < e.marker.width) && (d = e.marker);
        return d
    }

    function rd(a) {
        return qd(a, -1)
    }

    function sd(a) {
        return qd(a, a.text.length + 1)
    }

    function td(a, b) {
        for (var c; c = rd(b);) b = $d(a, c.find().from.line);
        return b
    }

    function ud(a, b) {
        var c = v && b.markedSpans;
        if (c)
            for (var d, e = 0; c.length > e; ++e)
                if (d = c[e], d.marker.collapsed) {
                    if (null == d.from) return !0;
                    if (0 == d.from && d.marker.inclusiveLeft && vd(a, b, d)) return !0
                }
    }

    function vd(a, b, c) {
        if (null == c.to) {
            var d = c.marker.find().to,
                e = $d(a, d.line);
            return vd(a, e, id(e.markedSpans, c.marker))
        }
        if (c.marker.inclusiveRight && c.to == b.text.length) return !0;
        for (var f, g = 0; b.markedSpans.length > g; ++g)
            if (f = b.markedSpans[g], f.marker.collapsed && f.from == c.to && (f.marker.inclusiveLeft || c.marker.inclusiveRight) && vd(a, b, f)) return !0
    }

    function wd(a) {
        var b = a.markedSpans;
        if (b) {
            for (var c = 0; b.length > c; ++c) b[c].marker.detachLine(a);
            a.markedSpans = null
        }
    }

    function xd(a, b) {
        if (b) {
            for (var c = 0; b.length > c; ++c) b[c].marker.attachLine(a);
            a.markedSpans = b
        }
    }

    function zd(a) {
        return function () {
            var b = !this.cm.curOp;
            b && vb(this.cm);
            try {
                var c = a.apply(this, arguments)
            } finally {
                b && wb(this.cm)
            }
            return c
        }
    }

    function Ad(a) {
        return null != a.height ? a.height : (a.node.parentNode && 1 == a.node.parentNode.nodeType || Ye(a.cm.display.measure, We("div", [a.node], null, "position: relative")), a.height = a.node.offsetHeight)
    }

    function Bd(a, b, c, d) {
        var e = new yd(a, c, d);
        return e.noHScroll && (a.display.alignWidgets = !0), Kc(a, b, function (b) {
            if ((b.widgets || (b.widgets = [])).push(e), e.line = b, !ud(a.doc, b) || e.showIfHidden) {
                var c = ee(a, b) < a.display.scroller.scrollTop;
                be(b, b.height + Ad(e)), c && Ic(a, 0, e.height)
            }
            return !0
        }), e
    }

    function Cd(a, b, c) {
        var d = {
            text: a
        };
        return xd(d, b), d.height = c ? c(d) : 1, d
    }

    function Dd(a, b, c, d) {
        a.text = b, a.stateAfter && (a.stateAfter = null), a.styles && (a.styles = null), null != a.order && (a.order = null), wd(a), xd(a, c);
        var e = d ? d(a) : 1;
        e != a.height && be(a, e), Ce(a, "change")
    }

    function Ed(a) {
        a.parent = null, wd(a)
    }

    function Fd(a, b, c, d, e) {
        var f = c.flattenSpans;
        null == f && (f = a.options.flattenSpans);
        var g = "",
            h = null,
            i = new dd(b, a.options.tabSize);
        for ("" == b && c.blankLine && c.blankLine(d); !i.eol();) {
            var j = c.token(i, d);
            i.pos > 5e3 && (f = !1, i.pos = Math.min(b.length, i.start + 5e4), j = null);
            var k = i.current();
            i.start = i.pos, f && h == j ? g += k : (g && e(g, h), g = k, h = j)
        }
        g && e(g, h)
    }

    function Gd(a, b, c) {
        var d = [a.state.modeGen];
        Fd(a, b.text, a.doc.mode, c, function (a, b) {
            d.push(a, b)
        });
        for (var e = 0; a.state.overlays.length > e; ++e) {
            var f = a.state.overlays[e],
                g = 1;
            Fd(a, b.text, f.mode, !0, function (a, b) {
                for (var c = g, e = a.length; e;) {
                    var h = d[g],
                        i = h.length;
                    e >= i ? e -= i : (d.splice(g, 1, h.slice(0, e), d[g + 1], h.slice(e)), e = 0), g += 2
                }
                if (b)
                    if (f.opaque) d.splice(c, g - c, a, b), g = c + 2;
                    else
                        for (; g > c; c += 2) {
                            var h = d[c + 1];
                            d[c + 1] = h ? h + " " + b : b
                        }
            })
        }
        return d
    }

    function Hd(a, b) {
        return b.styles && b.styles[0] == a.state.modeGen || (b.styles = Gd(a, b, b.stateAfter = cb(a, ce(b)))), b.styles
    }

    function Id(a, b, c) {
        var d = a.doc.mode,
            e = new dd(b.text, a.options.tabSize);
        for ("" == b.text && d.blankLine && d.blankLine(c); !e.eol() && 5e3 >= e.pos;) d.token(e, c), e.start = e.pos
    }

    function Kd(a) {
        return a ? Jd[a] || (Jd[a] = "cm-" + a.replace(/ +/g, " cm-")) : null
    }

    function Ld(a, c, d) {
        for (var e, g, h, f = c, i = !0; e = rd(f);) i = !1, f = $d(a.doc, e.find().from.line), g || (g = f);
        var j = {
            pre: We("pre"),
            col: 0,
            pos: 0,
            display: !d,
            measure: null,
            addedOne: !1,
            cm: a
        };
        f.textClass && (j.pre.className = f.textClass);
        do {
            j.measure = f == c && d, j.pos = 0, j.addToken = j.measure ? Od : Nd, d && h && f != c && !j.addedOne && (d[0] = j.pre.appendChild(ef(a.display.measure)), j.addedOne = !0);
            var k = Qd(f, j, Hd(a, f));
            h = f == g, k && (f = $d(a.doc, k.to.line), i = !1)
        } while (k);
        d && !j.addedOne && (d[0] = j.pre.appendChild(i ? We("span", "\u00a0") : ef(a.display.measure))), j.pre.firstChild || ud(a.doc, c) || j.pre.appendChild(document.createTextNode("\u00a0"));
        var l;
        if (d && b && (l = fe(f))) {
            var m = l.length - 1;
            l[m].from == l[m].to && --m;
            var n = l[m],
                o = l[m - 1];
            if (n.from + 1 == n.to && o && n.level < o.level) {
                var p = d[j.pos - 1];
                p && p.parentNode.insertBefore(p.measureRight = ef(a.display.measure), p.nextSibling)
            }
        }
        return j.pre
    }

    function Nd(a, b, c, d, e) {
        if (b) {
            if (Md.test(b))
                for (var f = document.createDocumentFragment(), g = 0;;) {
                    Md.lastIndex = g;
                    var h = Md.exec(b),
                        i = h ? h.index - g : b.length - g;
                    if (i && (f.appendChild(document.createTextNode(b.slice(g, g + i))), a.col += i), !h) break;
                    if (g += i + 1, "	" == h[0]) {
                        var j = a.cm.options.tabSize,
                            k = j - a.col % j;
                        f.appendChild(We("span", Ke(k), "cm-tab")), a.col += k
                    } else {
                        var l = We("span", "\u2022", "cm-invalidchar");
                        l.title = "\\u" + h[0].charCodeAt(0).toString(16), f.appendChild(l), a.col += 1
                    }
                } else {
                    a.col += b.length;
                    var f = document.createTextNode(b)
                } if (c || d || e || a.measure) {
                    var m = c || "";
                    return d && (m += d), e && (m += e), a.pre.appendChild(We("span", [f], m))
                }
            a.pre.appendChild(f)
        }
    }

    function Od(a, c, d, e, f) {
        for (var g = a.cm.options.lineWrapping, h = 0; c.length > h; ++h) {
            var i = c.charAt(h),
                j = 0 == h;
            i >= "\ud800" && "\udbff" > i && c.length - 1 > h ? (i = c.slice(h, h + 2), ++h) : h && g && af.test(c.slice(h - 1, h + 1)) && a.pre.appendChild(We("wbr"));
            var k = a.measure[a.pos] = Nd(a, i, d, j && e, h == c.length - 1 && f);
            b && g && " " == i && h && !/\s/.test(c.charAt(h - 1)) && c.length - 1 > h && !/\s/.test(c.charAt(h + 1)) && (k.style.whiteSpace = "normal"), a.pos += i.length
        }
        c.length && (a.addedOne = !0)
    }

    function Pd(a, b, c) {
        c && (a.display || (c = c.cloneNode(!0)), a.pre.appendChild(c), a.measure && b && (a.measure[a.pos] = c, a.addedOne = !0)), a.pos += b
    }

    function Qd(a, b, c) {
        var d = a.markedSpans;
        if (d)
            for (var j, l, m, n, o, f = a.text, g = f.length, h = 0, e = 1, i = "", k = 0;;) {
                if (k == h) {
                    l = m = n = "", o = null, k = 1 / 0;
                    for (var p = null, q = 0; d.length > q; ++q) {
                        var r = d[q],
                            s = r.marker;
                        h >= r.from && (null == r.to || r.to > h) ? (null != r.to && k > r.to && (k = r.to, m = ""), s.className && (l += " " + s.className), s.startStyle && r.from == h && (n += " " + s.startStyle), s.endStyle && r.to == k && (m += " " + s.endStyle), s.collapsed && (!o || o.marker.width < s.width) && (o = r)) : r.from > h && k > r.from && (k = r.from), "bookmark" == s.type && r.from == h && s.replacedWith && (p = s.replacedWith)
                    }
                    if (o && (o.from || 0) == h && (Pd(b, (null == o.to ? g : o.to) - h, null != o.from && o.marker.replacedWith), null == o.to)) return o.marker.find();
                    p && !o && Pd(b, 0, p)
                }
                if (h >= g) break;
                for (var t = Math.min(g, k);;) {
                    if (i) {
                        var u = h + i.length;
                        if (!o) {
                            var v = u > t ? i.slice(0, t - h) : i;
                            b.addToken(b, v, j ? j + l : l, n, h + v.length == k ? m : "")
                        }
                        if (u >= t) {
                            i = i.slice(t - h), h = t;
                            break
                        }
                        h = u, n = ""
                    }
                    i = c[e++], j = Kd(c[e++])
                }
            } else
                for (var e = 1; c.length > e; e += 2) b.addToken(b, c[e], Kd(c[e + 1]))
    }

    function Rd(a, b, c, d, e) {
        function f(a) {
            return c ? c[a] : null
        }
        var g = b.from,
            h = b.to,
            i = b.text,
            j = $d(a, g.line),
            k = $d(a, h.line),
            l = Le(i),
            m = f(i.length - 1),
            n = h.line - g.line;
        if (0 == g.ch && 0 == h.ch && "" == l) {
            for (var o = 0, p = i.length - 1, q = []; p > o; ++o) q.push(Cd(i[o], f(o), e));
            Dd(k, k.text, m, e), n && a.remove(g.line, n), q.length && a.insert(g.line, q)
        } else if (j == k)
            if (1 == i.length) Dd(j, j.text.slice(0, g.ch) + l + j.text.slice(h.ch), m, e);
            else {
                for (var q = [], o = 1, p = i.length - 1; p > o; ++o) q.push(Cd(i[o], f(o), e));
                q.push(Cd(l + j.text.slice(h.ch), m, e)), Dd(j, j.text.slice(0, g.ch) + i[0], f(0), e), a.insert(g.line + 1, q)
            } else if (1 == i.length) Dd(j, j.text.slice(0, g.ch) + i[0] + k.text.slice(h.ch), f(0), e), a.remove(g.line + 1, n);
        else {
            Dd(j, j.text.slice(0, g.ch) + i[0], f(0), e), Dd(k, l + k.text.slice(h.ch), m, e);
            for (var o = 1, p = i.length - 1, q = []; p > o; ++o) q.push(Cd(i[o], f(o), e));
            n > 1 && a.remove(g.line + 1, n - 1), a.insert(g.line + 1, q)
        }
        Ce(a, "change", a, b), Ac(a, d.anchor, d.head, null, !0)
    }

    function Sd(a) {
        this.lines = a, this.parent = null;
        for (var b = 0, c = a.length, d = 0; c > b; ++b) a[b].parent = this, d += a[b].height;
        this.height = d
    }

    function Td(a) {
        this.children = a;
        for (var b = 0, c = 0, d = 0, e = a.length; e > d; ++d) {
            var f = a[d];
            b += f.chunkSize(), c += f.height, f.parent = this
        }
        this.size = b, this.height = c, this.parent = null
    }

    function Yd(a, b, c) {
        function d(a, e, f) {
            if (a.linked)
                for (var g = 0; a.linked.length > g; ++g) {
                    var h = a.linked[g];
                    if (h.doc != e) {
                        var i = f && h.sharedHist;
                        (!c || i) && (b(h.doc, i), d(h.doc, a, i))
                    }
                }
        }
        d(a, null, !0)
    }

    function Zd(a, b) {
        if (b.cm) throw Error("This document is already in use.");
        a.doc = b, b.cm = a, B(a), y(a), a.options.lineWrapping || H(a), a.options.mode = b.modeOption, Ab(a)
    }

    function $d(a, b) {
        for (b -= a.first; !a.lines;)
            for (var c = 0;; ++c) {
                var d = a.children[c],
                    e = d.chunkSize();
                if (e > b) {
                    a = d;
                    break
                }
                b -= e
            }
        return a.lines[b]
    }

    function _d(a, b, c) {
        var d = [],
            e = b.line;
        return a.iter(b.line, c.line + 1, function (a) {
            var f = a.text;
            e == c.line && (f = f.slice(0, c.ch)), e == b.line && (f = f.slice(b.ch)), d.push(f), ++e
        }), d
    }

    function ae(a, b, c) {
        var d = [];
        return a.iter(b, c, function (a) {
            d.push(a.text)
        }), d
    }

    function be(a, b) {
        for (var c = b - a.height, d = a; d; d = d.parent) d.height += c
    }

    function ce(a) {
        if (null == a.parent) return null;
        for (var b = a.parent, c = Ne(b.lines, a), d = b.parent; d; b = d, d = d.parent)
            for (var e = 0; d.children[e] != b; ++e) c += d.children[e].chunkSize();
        return c + b.first
    }

    function de(a, b) {
        var c = a.first;
        a: do {
            for (var d = 0, e = a.children.length; e > d; ++d) {
                var f = a.children[d],
                    g = f.height;
                if (g > b) {
                    a = f;
                    continue a
                }
                b -= g, c += f.chunkSize()
            }
            return c
        } while (!a.lines);
        for (var d = 0, e = a.lines.length; e > d; ++d) {
            var h = a.lines[d],
                i = h.height;
            if (i > b) break;
            b -= i
        }
        return c + d
    }

    function ee(a, b) {
        b = td(a.doc, b);
        for (var c = 0, d = b.parent, e = 0; d.lines.length > e; ++e) {
            var f = d.lines[e];
            if (f == b) break;
            c += f.height
        }
        for (var g = d.parent; g; d = g, g = d.parent)
            for (var e = 0; g.children.length > e; ++e) {
                var h = g.children[e];
                if (h == d) break;
                c += h.height
            }
        return c
    }

    function fe(a) {
        var b = a.order;
        return null == b && (b = a.order = tf(a.text)), b
    }

    function ge() {
        return {
            done: [],
            undone: [],
            undoDepth: 1 / 0,
            lastTime: 0,
            lastOp: null,
            lastOrigin: null,
            dirtyCounter: 0
        }
    }

    function he(a, b, c, d) {
        var e = b["spans_" + a.id],
            f = 0;
        a.iter(Math.max(a.first, c), Math.min(a.first + a.size, d), function (c) {
            c.markedSpans && ((e || (e = b["spans_" + a.id] = {}))[f] = c.markedSpans), ++f
        })
    }

    function ie(a, b) {
        var c = {
            from: b.from,
            to: fc(b),
            text: _d(a, b.from, b.to)
        };
        return he(a, c, b.from.line, b.to.line + 1), Yd(a, function (a) {
            he(a, c, b.from.line, b.to.line + 1)
        }, !0), c
    }

    function je(a, b, c, d) {
        var e = a.history;
        e.undone.length = 0;
        var f = +new Date,
            g = Le(e.done);
        if (g && (e.lastOp == d || e.lastOrigin == b.origin && b.origin && ("+" == b.origin.charAt(0) && e.lastTime > f - 600 || "*" == b.origin.charAt(0)))) {
            var h = Le(g.changes);
            rc(b.from, b.to) && rc(b.from, h.to) ? h.to = fc(b) : g.changes.push(ie(a, b)), g.anchorAfter = c.anchor, g.headAfter = c.head
        } else {
            for (g = {
                changes: [ie(a, b)],
                anchorBefore: a.sel.anchor,
                headBefore: a.sel.head,
                anchorAfter: c.anchor,
                headAfter: c.head
            }, e.done.push(g); e.done.length > e.undoDepth;) e.done.shift();
            0 > e.dirtyCounter ? e.dirtyCounter = 0 / 0 : e.dirtyCounter++
        }
        e.lastTime = f, e.lastOp = d, e.lastOrigin = b.origin
    }

    function ke(a) {
        if (!a) return null;
        for (var c, b = 0; a.length > b; ++b) a[b].marker.explicitlyCleared ? c || (c = a.slice(0, b)) : c && c.push(a[b]);
        return c ? c.length ? c : null : a
    }

    function le(a, b) {
        var c = b["spans_" + a.id];
        if (!c) return null;
        for (var d = 0, e = []; b.text.length > d; ++d) e.push(ke(c[d]));
        return e
    }

    function me(a, b) {
        for (var c = 0, d = []; a.length > c; ++c) {
            var e = a[c],
                f = e.changes,
                g = [];
            d.push({
                changes: g,
                anchorBefore: e.anchorBefore,
                headBefore: e.headBefore,
                anchorAfter: e.anchorAfter,
                headAfter: e.headAfter
            });
            for (var h = 0; f.length > h; ++h) {
                var j, i = f[h];
                if (g.push({
                    from: i.from,
                    to: i.to,
                    text: i.text
                }), b)
                    for (var k in i)(j = k.match(/^spans_(\d+)$/)) && Ne(b, Number(j[1])) > -1 && (Le(g)[k] = i[k], delete i[k])
            }
        }
        return d
    }

    function ne(a, b, c, d) {
        a.line > c ? a.line += d : a.line > b && (a.line = b, a.ch = 0)
    }

    function oe(a, b, c, d) {
        for (var e = 0; a.length > e; ++e) {
            for (var f = a[e], g = !0, h = 0; f.changes.length > h; ++h) {
                var i = f.changes[h];
                if (f.copied || (i.from = tc(i.from), i.to = tc(i.to)), i.from.line > c) i.from.line += d, i.to.line += d;
                else if (i.to.line >= b) {
                    g = !1;
                    break
                }
            }
            f.copied || (f.anchorBefore = tc(f.anchorBefore), f.headBefore = tc(f.headBefore), f.anchorAfter = tc(f.anchorAfter), f.readAfter = tc(f.headAfter), f.copied = !0), g ? (ne(f.anchorBefore), ne(f.headBefore), ne(f.anchorAfter), ne(f.headAfter)) : (a.splice(0, e + 1), e = 0)
        }
    }

    function pe(a, b) {
        var c = b.from.line,
            d = b.to.line,
            e = b.text.length - (d - c) - 1;
        oe(a.done, c, d, e), oe(a.undone, c, d, e)
    }

    function qe() {
        ue(this)
    }

    function re(a) {
        return a.stop || (a.stop = qe), a
    }

    function se(a) {
        a.preventDefault ? a.preventDefault() : a.returnValue = !1
    }

    function te(a) {
        a.stopPropagation ? a.stopPropagation() : a.cancelBubble = !0
    }

    function ue(a) {
        se(a), te(a)
    }

    function ve(a) {
        return a.target || a.srcElement
    }

    function we(a) {
        var b = a.which;
        return null == b && (1 & a.button ? b = 1 : 2 & a.button ? b = 3 : 4 & a.button && (b = 2)), p && a.ctrlKey && 1 == b && (b = 3), b
    }

    function xe(a, b, c) {
        if (a.addEventListener) a.addEventListener(b, c, !1);
        else if (a.attachEvent) a.attachEvent("on" + b, c);
        else {
            var d = a._handlers || (a._handlers = {}),
                e = d[b] || (d[b] = []);
            e.push(c)
        }
    }

    function ye(a, b, c) {
        if (a.removeEventListener) a.removeEventListener(b, c, !1);
        else if (a.detachEvent) a.detachEvent("on" + b, c);
        else {
            var d = a._handlers && a._handlers[b];
            if (!d) return;
            for (var e = 0; d.length > e; ++e)
                if (d[e] == c) {
                    d.splice(e, 1);
                    break
                }
        }
    }

    function ze(a, b) {
        var c = a._handlers && a._handlers[b];
        if (c)
            for (var d = Array.prototype.slice.call(arguments, 2), e = 0; c.length > e; ++e) c[e].apply(null, d)
    }

    function Ce(a, b) {
        function e(a) {
            return function () {
                a.apply(null, d)
            }
        }
        var c = a._handlers && a._handlers[b];
        if (c) {
            var d = Array.prototype.slice.call(arguments, 2);
            Ae || (++Be, Ae = [], setTimeout(De, 0));
            for (var f = 0; c.length > f; ++f) Ae.push(e(c[f]))
        }
    }

    function De() {
        --Be;
        var a = Ae;
        Ae = null;
        for (var b = 0; a.length > b; ++b) a[b]()
    }

    function Ee(a, b) {
        var c = a._handlers && a._handlers[b];
        return c && c.length > 0
    }

    function He() {
        this.id = null
    }

    function Ie(a, b, c, d, e) {
        null == b && (b = a.search(/[^\s\u00a0]/), -1 == b && (b = a.length));
        for (var f = d || 0, g = e || 0; b > f; ++f) "	" == a.charAt(f) ? g += c - g % c : ++g;
        return g
    }

    function Ke(a) {
        for (; a >= Je.length;) Je.push(Le(Je) + " ");
        return Je[a]
    }

    function Le(a) {
        return a[a.length - 1]
    }

    function Me(a) {
        n ? (a.selectionStart = 0, a.selectionEnd = a.value.length) : a.select()
    }

    function Ne(a, b) {
        if (a.indexOf) return a.indexOf(b);
        for (var c = 0, d = a.length; d > c; ++c)
            if (a[c] == b) return c;
        return -1
    }

    function Oe(a, b) {
        function c() {}
        c.prototype = a;
        var d = new c;
        return b && Pe(b, d), d
    }

    function Pe(a, b) {
        b || (b = {});
        for (var c in a) a.hasOwnProperty(c) && (b[c] = a[c]);
        return b
    }

    function Qe(a) {
        for (var b = [], c = 0; a > c; ++c) b.push(void 0);
        return b
    }

    function Re(a) {
        var b = Array.prototype.slice.call(arguments, 1);
        return function () {
            return a.apply(null, b)
        }
    }

    function Te(a) {
        return /\w/.test(a) || a > "\u0080" && (a.toUpperCase() != a.toLowerCase() || Se.test(a))
    }

    function Ue(a) {
        for (var b in a)
            if (a.hasOwnProperty(b) && a[b]) return !1;
        return !0
    }

    function We(a, b, c, d) {
        var e = document.createElement(a);
        if (c && (e.className = c), d && (e.style.cssText = d), "string" == typeof b) Ze(e, b);
        else if (b)
            for (var f = 0; b.length > f; ++f) e.appendChild(b[f]);
        return e
    }

    function Xe(a) {
        if (b)
            for (; a.firstChild;) a.removeChild(a.firstChild);
        else a.innerHTML = "";
        return a
    }

    function Ye(a, b) {
        return Xe(a).appendChild(b)
    }

    function Ze(a, b) {
        d ? (a.innerHTML = "", a.appendChild(document.createTextNode(b))) : a.textContent = b
    }

    function $e(a) {
        return a.getBoundingClientRect()
    }

    function cf(a) {
        if (null != bf) return bf;
        var b = We("div", null, null, "width: 50px; height: 50px; overflow-x: scroll");
        return Ye(a, b), b.offsetWidth && (bf = b.offsetHeight - b.clientHeight), bf || 0
    }

    function ef(a) {
        if (null == df) {
            var b = We("span", "\u200b");
            Ye(a, We("span", [b, document.createTextNode("x")])), 0 != a.firstChild.offsetHeight && (df = 1 >= b.offsetWidth && b.offsetHeight > 2 && !c)
        }
        return df ? We("span", "\u200b") : We("span", "\u00a0", null, "display: inline-block; width: 1px; margin-right: -1px")
    }

    function kf(a, b, c, d) {
        if (!a) return d(b, c, "ltr");
        for (var e = 0; a.length > e; ++e) {
            var f = a[e];
            (c > f.from && f.to > b || b == c && f.to == b) && d(Math.max(f.from, b), Math.min(f.to, c), 1 == f.level ? "rtl" : "ltr")
        }
    }

    function lf(a) {
        return a.level % 2 ? a.to : a.from
    }

    function mf(a) {
        return a.level % 2 ? a.from : a.to
    }

    function nf(a) {
        var b = fe(a);
        return b ? lf(b[0]) : 0
    }

    function of(a) {
        var b = fe(a);
        return b ? mf(Le(b)) : a.text.length
    }

    function pf(a, b) {
        var c = $d(a.doc, b),
            d = td(a.doc, c);
        d != c && (b = ce(d));
        var e = fe(d),
            f = e ? e[0].level % 2 ? of(d) : nf(d) : 0;
        return qc(b, f)
    }

    function qf(a, b) {
        for (var c, d; c = sd(d = $d(a.doc, b));) b = c.find().to.line;
        var e = fe(d),
            f = e ? e[0].level % 2 ? nf(d) : of(d) : d.text.length;
        return qc(b, f)
    }

    function rf(a, b, c, d) {
        var e = fe(a);
        if (!e) return sf(a, b, c, d);
        for (var f = d ? function (b, c) {
                do b += c; while (b > 0 && Ve.test(a.text.charAt(b)));
                return b
            } : function (a, b) {
                return a + b
            }, g = e[0].level, h = 0; e.length > h; ++h) {
            var i = e[h],
                j = i.level % 2 == g;
            if (b > i.from && i.to > b || j && (i.from == b || i.to == b)) break
        }
        for (var k = f(b, i.level % 2 ? -c : c); null != k;)
            if (i.level % 2 == g) {
                if (!(i.from > k || k > i.to)) break;
                i = e[h += c], k = i && (c > 0 == i.level % 2 ? f(i.to, -1) : f(i.from, 1))
            } else if (k == lf(i)) i = e[--h], k = i && mf(i);
        else {
            if (k != mf(i)) break;
            i = e[++h], k = i && lf(i)
        }
        return 0 > k || k > a.text.length ? null : k
    }

    function sf(a, b, c, d) {
        var e = b + c;
        if (d)
            for (; e > 0 && Ve.test(a.text.charAt(e));) e += c;
        return 0 > e || e > a.text.length ? null : e
    }
    var a = /gecko\/\d/i.test(navigator.userAgent),
        b = /MSIE \d/.test(navigator.userAgent),
        c = b && (null == document.documentMode || 8 > document.documentMode),
        d = b && (null == document.documentMode || 9 > document.documentMode),
        e = /WebKit\//.test(navigator.userAgent),
        f = e && /Qt\/\d+\.\d+/.test(navigator.userAgent),
        g = /Chrome\//.test(navigator.userAgent),
        h = /Opera\//.test(navigator.userAgent),
        i = /Apple Computer/.test(navigator.vendor),
        j = /KHTML\//.test(navigator.userAgent),
        k = /Mac OS X 1\d\D([7-9]|\d\d)\D/.test(navigator.userAgent),
        l = /Mac OS X 1\d\D([8-9]|\d\d)\D/.test(navigator.userAgent),
        m = /PhantomJS/.test(navigator.userAgent),
        n = /AppleWebKit/.test(navigator.userAgent) && /Mobile\/\w+/.test(navigator.userAgent),
        o = n || /Android|webOS|BlackBerry|Opera Mini|Opera Mobi|IEMobile/i.test(navigator.userAgent),
        p = n || /Mac/.test(navigator.platform),
        q = /windows/i.test(navigator.platform),
        r = h && navigator.userAgent.match(/Version\/(\d*\.\d*)/);
    r && (r = Number(r[1]));
    var rb, Kb, Lb, s = p && (f || h && (null == r || 12.11 > r)),
        t = a || b && !d,
        u = !1,
        v = !1,
        ub = 0,
        Sb = 0,
        Tb = null;
    b ? Tb = -.53 : a ? Tb = 15 : g ? Tb = -.7 : i && (Tb = -1 / 3);
    var Xb, dc, $b = null;
    w.Pos = qc, w.prototype = {
        focus: function () {
            window.focus(), Fb(this), bc(this), Cb(this)
        },
        setOption: function (a, b) {
            var c = this.options,
                d = c[a];
            (c[a] != b || "mode" == a) && (c[a] = b, Pc.hasOwnProperty(a) && xb(this, Pc[a])(this, b, d))
        },
        getOption: function (a) {
            return this.options[a]
        },
        getDoc: function () {
            return this.doc
        },
        addKeyMap: function (a) {
            this.state.keyMaps.push(a)
        },
        removeKeyMap: function (a) {
            for (var b = this.state.keyMaps, c = 0; b.length > c; ++c)
                if (("string" == typeof a ? b[c].name : b[c]) == a) return b.splice(c, 1), !0
        },
        addOverlay: xb(null, function (a, b) {
            var c = a.token ? a : w.getMode(this.options, a);
            if (c.startState) throw Error("Overlays may not be stateful.");
            this.state.overlays.push({
                mode: c,
                modeSpec: a,
                opaque: b && b.opaque
            }), this.state.modeGen++, Ab(this)
        }),
        removeOverlay: xb(null, function (a) {
            for (var b = this.state.overlays, c = 0; b.length > c; ++c)
                if (b[c].modeSpec == a) return b.splice(c, 1), this.state.modeGen++, Ab(this), void 0
        }),
        indentLine: xb(null, function (a, b, c) {
            "string" != typeof b && (b = null == b ? this.options.smartIndent ? "smart" : "prev" : b ? "add" : "subtract"), xc(this.doc, a) && Jc(this, a, b, c)
        }),
        indentSelection: xb(null, function (a) {
            var b = this.doc.sel;
            if (rc(b.from, b.to)) return Jc(this, b.from.line, a);
            for (var c = b.to.line - (b.to.ch ? 0 : 1), d = b.from.line; c >= d; ++d) Jc(this, d, a)
        }),
        getTokenAt: function (a) {
            var b = this.doc;
            a = vc(b, a);
            for (var c = cb(this, a.line), d = this.doc.mode, e = $d(b, a.line), f = new dd(e.text, this.options.tabSize); f.pos < a.ch && !f.eol();) {
                f.start = f.pos;
                var g = d.token(f, c)
            }
            return {
                start: f.start,
                end: f.pos,
                string: f.current(),
                className: g || null,
                type: g || null,
                state: c
            }
        },
        getStateAfter: function (a) {
            var b = this.doc;
            return a = uc(b, null == a ? b.first + b.size - 1 : a), cb(this, a + 1)
        },
        cursorCoords: function (a, b) {
            var c, d = this.doc.sel;
            return c = null == a ? d.head : "object" == typeof a ? vc(this.doc, a) : a ? d.from : d.to, nb(this, c, b || "page")
        },
        charCoords: function (a, b) {
            return mb(this, vc(this.doc, a), b || "page")
        },
        coordsChar: function (a) {
            var b = $e(this.display.lineSpace),
                c = window.pageYOffset || (document.documentElement || document.body).scrollTop,
                d = window.pageXOffset || (document.documentElement || document.body).scrollLeft;
            return pb(this, a.left - b.left - d, a.top - b.top - c)
        },
        defaultTextHeight: function () {
            return sb(this.display)
        },
        setGutterMarker: xb(null, function (a, b, c) {
            return Kc(this, a, function (a) {
                var d = a.gutterMarkers || (a.gutterMarkers = {});
                return d[b] = c, !c && Ue(d) && (a.gutterMarkers = null), !0
            })
        }),
        clearGutter: xb(null, function (a) {
            var b = this,
                c = b.doc,
                d = c.first;
            c.iter(function (c) {
                c.gutterMarkers && c.gutterMarkers[a] && (c.gutterMarkers[a] = null, Ab(b, d, d + 1), Ue(c.gutterMarkers) && (c.gutterMarkers = null)), ++d
            })
        }),
        addLineClass: xb(null, function (a, b, c) {
            return Kc(this, a, function (a) {
                var d = "text" == b ? "textClass" : "background" == b ? "bgClass" : "wrapClass";
                if (a[d]) {
                    if (RegExp("\\b" + c + "\\b").test(a[d])) return !1;
                    a[d] += " " + c
                } else a[d] = c;
                return !0
            })
        }),
        removeLineClass: xb(null, function (a, b, c) {
            return Kc(this, a, function (a) {
                var d = "text" == b ? "textClass" : "background" == b ? "bgClass" : "wrapClass",
                    e = a[d];
                if (!e) return !1;
                if (null == c) a[d] = null;
                else {
                    var f = e.replace(RegExp("^" + c + "\\b\\s*|\\s*\\b" + c + "\\b"), "");
                    if (f == e) return !1;
                    a[d] = f || null
                }
                return !0
            })
        }),
        addLineWidget: xb(null, function (a, b, c) {
            return Bd(this, a, b, c)
        }),
        removeLineWidget: function (a) {
            a.clear()
        },
        lineInfo: function (a) {
            if ("number" == typeof a) {
                if (!xc(this.doc, a)) return null;
                var b = a;
                if (a = $d(this.doc, a), !a) return null
            } else {
                var b = ce(a);
                if (null == b) return null
            }
            return {
                line: b,
                handle: a,
                text: a.text,
                gutterMarkers: a.gutterMarkers,
                textClass: a.textClass,
                bgClass: a.bgClass,
                wrapClass: a.wrapClass,
                widgets: a.widgets
            }
        },
        getViewport: function () {
            return {
                from: this.display.showingFrom,
                to: this.display.showingTo
            }
        },
        addWidget: function (a, b, c, d, e) {
            var f = this.display;
            a = nb(this, vc(this.doc, a));
            var g = a.bottom,
                h = a.left;
            if (b.style.position = "absolute", f.sizer.appendChild(b), "over" == d) g = a.top;
            else if ("above" == d || "near" == d) {
                var i = Math.max(f.wrapper.clientHeight, this.doc.height),
                    j = Math.max(f.sizer.clientWidth, f.lineSpace.clientWidth);
                ("above" == d || a.bottom + b.offsetHeight > i) && a.top > b.offsetHeight ? g = a.top - b.offsetHeight : i >= a.bottom + b.offsetHeight && (g = a.bottom), h + b.offsetWidth > j && (h = j - b.offsetWidth)
            }
            b.style.top = g + db(f) + "px", b.style.left = b.style.right = "", "right" == e ? (h = f.sizer.clientWidth - b.offsetWidth, b.style.right = "0px") : ("left" == e ? h = 0 : "middle" == e && (h = (f.sizer.clientWidth - b.offsetWidth) / 2), b.style.left = h + "px"), c && Fc(this, h, g, h + b.offsetWidth, g + b.offsetHeight)
        },
        triggerOnKeyDown: xb(null, _b),
        execCommand: function (a) {
            return Zc[a](this)
        },
        findPosH: function (a, b, c, d) {
            var e = 1;
            0 > b && (e = -1, b = -b);
            for (var f = 0, g = vc(this.doc, a); b > f && (g = Lc(this.doc, g, e, c, d), !g.hitSide); ++f);
            return g
        },
        moveH: xb(null, function (a, b) {
            var d, c = this.doc.sel;
            d = c.shift || c.extend || rc(c.from, c.to) ? Lc(this.doc, c.head, a, b, this.options.rtlMoveVisually) : 0 > a ? c.from : c.to, yc(this.doc, d, d, a)
        }),
        deleteH: xb(null, function (a, b) {
            var c = this.doc.sel;
            rc(c.from, c.to) ? pc(this.doc, "", c.from, Lc(this.doc, c.head, a, b, !1), "+delete") : pc(this.doc, "", c.from, c.to, "+delete"), this.curOp.userSelChange = !0
        }),
        findPosV: function (a, b, c, d) {
            var e = 1,
                f = d;
            0 > b && (e = -1, b = -b);
            for (var g = 0, h = vc(this.doc, a); b > g; ++g) {
                var i = nb(this, h, "div");
                if (null == f ? f = i.left : i.left = f, h = Mc(this, i, e, c), h.hitSide) break
            }
            return h
        },
        moveV: xb(null, function (a, b) {
            var c = this.doc.sel,
                d = nb(this, c.head, "div");
            null != c.goalColumn && (d.left = c.goalColumn);
            var e = Mc(this, d, a, b);
            "page" == b && Ic(this, 0, mb(this, e, "div").top - d.top), yc(this.doc, e, e, a), c.goalColumn = d.left
        }),
        toggleOverwrite: function () {
            (this.state.overwrite = !this.state.overwrite) ? this.display.cursor.className += " CodeMirror-overwrite" : this.display.cursor.className = this.display.cursor.className.replace(" CodeMirror-overwrite", "")
        },
        scrollTo: xb(null, function (a, b) {
            Hc(this, a, b)
        }),
        getScrollInfo: function () {
            var a = this.display.scroller,
                b = Fe;
            return {
                left: a.scrollLeft,
                top: a.scrollTop,
                height: a.scrollHeight - b,
                width: a.scrollWidth - b,
                clientHeight: a.clientHeight - b,
                clientWidth: a.clientWidth - b
            }
        },
        scrollIntoView: function (a) {
            "number" == typeof a && (a = qc(a, 0)), a && null == a.line ? Fc(this, a.left, a.top, a.right, a.bottom) : (a = a ? vc(this.doc, a) : this.doc.sel.head, Ec(this, a))
        },
        setSize: function (a, b) {
            function c(a) {
                return "number" == typeof a || /^\d+$/.test(a + "") ? a + "px" : a
            }
            null != a && (this.display.wrapper.style.width = c(a)), null != b && (this.display.wrapper.style.height = c(b)), this.refresh()
        },
        on: function (a, b) {
            xe(this, a, b)
        },
        off: function (a, b) {
            ye(this, a, b)
        },
        operation: function (a) {
            return zb(this, a)
        },
        refresh: xb(null, function () {
            kb(this), Hc(this, this.doc.scrollLeft, this.doc.scrollTop), Ab(this)
        }),
        swapDoc: xb(null, function (a) {
            var b = this.doc;
            return b.cm = null, Zd(this, a), kb(this), Hc(this, a.scrollLeft, a.scrollTop), b
        }),
        getInputField: function () {
            return this.display.input
        },
        getWrapperElement: function () {
            return this.display.wrapper
        },
        getScrollerElement: function () {
            return this.display.scroller
        },
        getGutterElement: function () {
            return this.display.gutters
        }
    };
    var Pc = w.optionHandlers = {}, Qc = w.defaults = {}, Sc = w.Init = {
            toString: function () {
                return "CodeMirror.Init"
            }
        };
    Rc("value", "", function (a, b) {
        a.setValue(b)
    }, !0), Rc("mode", null, function (a, b) {
        a.doc.modeOption = b, y(a)
    }, !0), Rc("indentUnit", 2, y, !0), Rc("indentWithTabs", !1), Rc("smartIndent", !0), Rc("tabSize", 4, function (a) {
        y(a), kb(a), Ab(a)
    }, !0), Rc("electricChars", !0), Rc("rtlMoveVisually", !q), Rc("theme", "default", function (a) {
        D(a), E(a)
    }, !0), Rc("keyMap", "default", C), Rc("extraKeys", null), Rc("onKeyEvent", null), Rc("onDragEvent", null), Rc("lineWrapping", !1, z, !0), Rc("gutters", [], function (a) {
        I(a.options), E(a)
    }, !0), Rc("fixedGutter", !0, function (a, b) {
        a.display.gutters.style.left = b ? O(a.display) + "px" : "0", a.refresh()
    }, !0), Rc("lineNumbers", !1, function (a) {
        I(a.options), E(a)
    }, !0), Rc("firstLineNumber", 1, E, !0), Rc("lineNumberFormatter", function (a) {
        return a
    }, E, !0), Rc("showCursorWhenSelecting", !1, X, !0), Rc("readOnly", !1, function (a, b) {
        "nocursor" == b ? (cc(a), a.display.input.blur()) : b || Eb(a, !0)
    }), Rc("dragDrop", !0), Rc("cursorBlinkRate", 530), Rc("cursorHeight", 1), Rc("workTime", 100), Rc("workDelay", 100), Rc("flattenSpans", !0), Rc("pollInterval", 100), Rc("undoDepth", 40, function (a, b) {
        a.doc.history.undoDepth = b
    }), Rc("viewportMargin", 10, function (a) {
        a.refresh()
    }, !0), Rc("tabindex", null, function (a, b) {
        a.display.input.tabIndex = b || ""
    }), Rc("autofocus", null);
    var Tc = w.modes = {}, Uc = w.mimeModes = {};
    w.defineMode = function (a, b) {
        if (w.defaults.mode || "null" == a || (w.defaults.mode = a), arguments.length > 2) {
            b.dependencies = [];
            for (var c = 2; arguments.length > c; ++c) b.dependencies.push(arguments[c])
        }
        Tc[a] = b
    }, w.defineMIME = function (a, b) {
        Uc[a] = b
    }, w.resolveMode = function (a) {
        if ("string" == typeof a && Uc.hasOwnProperty(a)) a = Uc[a];
        else if ("string" == typeof a && /^[\w\-]+\/[\w\-]+\+xml$/.test(a)) return w.resolveMode("application/xml");
        return "string" == typeof a ? {
            name: a
        } : a || {
            name: "null"
        }
    }, w.getMode = function (a, b) {
        b = w.resolveMode(b);
        var c = Tc[b.name];
        if (!c) return w.getMode(a, "text/plain");
        var d = c(a, b);
        if (Vc.hasOwnProperty(b.name)) {
            var e = Vc[b.name];
            for (var f in e) e.hasOwnProperty(f) && (d.hasOwnProperty(f) && (d["_" + f] = d[f]), d[f] = e[f])
        }
        return d.name = b.name, d
    }, w.defineMode("null", function () {
        return {
            token: function (a) {
                a.skipToEnd()
            }
        }
    }), w.defineMIME("text/plain", "null");
    var Vc = w.modeExtensions = {};
    w.extendMode = function (a, b) {
        var c = Vc.hasOwnProperty(a) ? Vc[a] : Vc[a] = {};
        Pe(b, c)
    }, w.defineExtension = function (a, b) {
        w.prototype[a] = b
    }, w.defineOption = Rc;
    var Wc = [];
    w.defineInitHook = function (a) {
        Wc.push(a)
    }, w.copyState = Xc, w.startState = Yc, w.innerMode = function (a, b) {
        for (; a.innerMode;) {
            var c = a.innerMode(b);
            b = c.state, a = c.mode
        }
        return c || {
            mode: a,
            state: b
        }
    };
    var Zc = w.commands = {
        selectAll: function (a) {
            a.setSelection(qc(a.firstLine(), 0), qc(a.lastLine()))
        },
        killLine: function (a) {
            var b = a.getCursor(!0),
                c = a.getCursor(!1),
                d = !rc(b, c);
            d || a.getLine(b.line).length != b.ch ? a.replaceRange("", b, d ? c : qc(b.line), "+delete") : a.replaceRange("", b, qc(b.line + 1, 0), "+delete")
        },
        deleteLine: function (a) {
            var b = a.getCursor().line;
            a.replaceRange("", qc(b, 0), qc(b), "+delete")
        },
        undo: function (a) {
            a.undo()
        },
        redo: function (a) {
            a.redo()
        },
        goDocStart: function (a) {
            a.extendSelection(qc(a.firstLine(), 0))
        },
        goDocEnd: function (a) {
            a.extendSelection(qc(a.lastLine()))
        },
        goLineStart: function (a) {
            a.extendSelection(pf(a, a.getCursor().line))
        },
        goLineStartSmart: function (a) {
            var b = a.getCursor(),
                c = pf(a, b.line),
                d = a.getLineHandle(c.line),
                e = fe(d);
            if (e && 0 != e[0].level) a.extendSelection(c);
            else {
                var f = Math.max(0, d.text.search(/\S/)),
                    g = b.line == c.line && f >= b.ch && b.ch;
                a.extendSelection(qc(c.line, g ? 0 : f))
            }
        },
        goLineEnd: function (a) {
            a.extendSelection(qf(a, a.getCursor().line))
        },
        goLineUp: function (a) {
            a.moveV(-1, "line")
        },
        goLineDown: function (a) {
            a.moveV(1, "line")
        },
        goPageUp: function (a) {
            a.moveV(-1, "page")
        },
        goPageDown: function (a) {
            a.moveV(1, "page")
        },
        goCharLeft: function (a) {
            a.moveH(-1, "char")
        },
        goCharRight: function (a) {
            a.moveH(1, "char")
        },
        goColumnLeft: function (a) {
            a.moveH(-1, "column")
        },
        goColumnRight: function (a) {
            a.moveH(1, "column")
        },
        goWordLeft: function (a) {
            a.moveH(-1, "word")
        },
        goWordRight: function (a) {
            a.moveH(1, "word")
        },
        goWordBoundaryLeft: function (a) {
            a.moveH(-1, "wordBoundary")
        },
        goWordBoundaryRight: function (a) {
            a.moveH(1, "wordBoundary")
        },
        delCharBefore: function (a) {
            a.deleteH(-1, "char")
        },
        delCharAfter: function (a) {
            a.deleteH(1, "char")
        },
        delWordBefore: function (a) {
            a.deleteH(-1, "word")
        },
        delWordAfter: function (a) {
            a.deleteH(1, "word")
        },
        indentAuto: function (a) {
            a.indentSelection("smart")
        },
        indentMore: function (a) {
            a.indentSelection("add")
        },
        indentLess: function (a) {
            a.indentSelection("subtract")
        },
        insertTab: function (a) {
            a.replaceSelection("	", "end", "+input")
        },
        defaultTab: function (a) {
            a.somethingSelected() ? a.indentSelection("add") : a.replaceSelection("	", "end", "+input")
        },
        transposeChars: function (a) {
            var b = a.getCursor(),
                c = a.getLine(b.line);
            b.ch > 0 && b.ch < c.length - 1 && a.replaceRange(c.charAt(b.ch) + c.charAt(b.ch - 1), qc(b.line, b.ch - 1), qc(b.line, b.ch + 1))
        },
        newlineAndIndent: function (a) {
            xb(a, function () {
                a.replaceSelection("\n", "end", "+input"), a.indentLine(a.getCursor().line, null, !0)
            })()
        },
        toggleOverwrite: function (a) {
            a.toggleOverwrite()
        }
    }, $c = w.keyMap = {};
    $c.basic = {
        Left: "goCharLeft",
        Right: "goCharRight",
        Up: "goLineUp",
        Down: "goLineDown",
        End: "goLineEnd",
        Home: "goLineStartSmart",
        PageUp: "goPageUp",
        PageDown: "goPageDown",
        Delete: "delCharAfter",
        Backspace: "delCharBefore",
        Tab: "defaultTab",
        "Shift-Tab": "indentAuto",
        Enter: "newlineAndIndent",
        Insert: "toggleOverwrite"
    }, $c.pcDefault = {
        "Ctrl-A": "selectAll",
        "Ctrl-D": "deleteLine",
        "Ctrl-Z": "undo",
        "Shift-Ctrl-Z": "redo",
        "Ctrl-Y": "redo",
        "Ctrl-Home": "goDocStart",
        "Alt-Up": "goDocStart",
        "Ctrl-End": "goDocEnd",
        "Ctrl-Down": "goDocEnd",
        "Ctrl-Left": "goWordBoundaryLeft",
        "Ctrl-Right": "goWordBoundaryRight",
        "Alt-Left": "goLineStart",
        "Alt-Right": "goLineEnd",
        "Ctrl-Backspace": "delWordBefore",
        "Ctrl-Delete": "delWordAfter",
        "Ctrl-S": "save",
        "Ctrl-F": "find",
        "Ctrl-G": "findNext",
        "Shift-Ctrl-G": "findPrev",
        "Shift-Ctrl-F": "replace",
        "Shift-Ctrl-R": "replaceAll",
        "Ctrl-[": "indentLess",
        "Ctrl-]": "indentMore",
        fallthrough: "basic"
    }, $c.macDefault = {
        "Cmd-A": "selectAll",
        "Cmd-D": "deleteLine",
        "Cmd-Z": "undo",
        "Shift-Cmd-Z": "redo",
        "Cmd-Y": "redo",
        "Cmd-Up": "goDocStart",
        "Cmd-End": "goDocEnd",
        "Cmd-Down": "goDocEnd",
        "Alt-Left": "goWordBoundaryLeft",
        "Alt-Right": "goWordBoundaryRight",
        "Cmd-Left": "goLineStart",
        "Cmd-Right": "goLineEnd",
        "Alt-Backspace": "delWordBefore",
        "Ctrl-Alt-Backspace": "delWordAfter",
        "Alt-Delete": "delWordAfter",
        "Cmd-S": "save",
        "Cmd-F": "find",
        "Cmd-G": "findNext",
        "Shift-Cmd-G": "findPrev",
        "Cmd-Alt-F": "replace",
        "Shift-Cmd-Alt-F": "replaceAll",
        "Cmd-[": "indentLess",
        "Cmd-]": "indentMore",
        fallthrough: ["basic", "emacsy"]
    }, $c["default"] = p ? $c.macDefault : $c.pcDefault, $c.emacsy = {
        "Ctrl-F": "goCharRight",
        "Ctrl-B": "goCharLeft",
        "Ctrl-P": "goLineUp",
        "Ctrl-N": "goLineDown",
        "Alt-F": "goWordRight",
        "Alt-B": "goWordLeft",
        "Ctrl-A": "goLineStart",
        "Ctrl-E": "goLineEnd",
        "Ctrl-V": "goPageDown",
        "Shift-Ctrl-V": "goPageUp",
        "Ctrl-D": "delCharAfter",
        "Ctrl-H": "delCharBefore",
        "Alt-D": "delWordAfter",
        "Alt-Backspace": "delWordBefore",
        "Ctrl-K": "killLine",
        "Ctrl-T": "transposeChars"
    }, w.lookupKey = ad, w.isModifierKey = bd, w.keyName = cd, w.fromTextArea = function (a, b) {
        function e() {
            a.value = i.getValue()
        }
        if (b || (b = {}), b.value = a.value, !b.tabindex && a.tabindex && (b.tabindex = a.tabindex), null == b.autofocus) {
            var c = document.body;
            try {
                c = document.activeElement
            } catch (d) {}
            b.autofocus = c == a || null != a.getAttribute("autofocus") && c == document.body
        }
        if (a.form && (xe(a.form, "submit", e), !b.leaveSubmitMethodAlone)) {
            var f = a.form,
                g = f.submit;
            try {
                var h = f.submit = function () {
                    e(), f.submit = g, f.submit(), f.submit = h
                }
            } catch (d) {}
        }
        a.style.display = "none";
        var i = w(function (b) {
            a.parentNode.insertBefore(b, a.nextSibling)
        }, b);
        return i.save = e, i.getTextArea = function () {
            return a
        }, i.toTextArea = function () {
            e(), a.parentNode.removeChild(i.getWrapperElement()), a.style.display = "", a.form && (ye(a.form, "submit", e), "function" == typeof a.form.submit && (a.form.submit = g))
        }, i
    }, dd.prototype = {
        eol: function () {
            return this.pos >= this.string.length
        },
        sol: function () {
            return 0 == this.pos
        },
        peek: function () {
            return this.string.charAt(this.pos) || void 0
        },
        next: function () {
            return this.pos < this.string.length ? this.string.charAt(this.pos++) : void 0
        },
        eat: function (a) {
            var b = this.string.charAt(this.pos);
            if ("string" == typeof a) var c = b == a;
            else var c = b && (a.test ? a.test(b) : a(b));
            return c ? (++this.pos, b) : void 0
        },
        eatWhile: function (a) {
            for (var b = this.pos; this.eat(a););
            return this.pos > b
        },
        eatSpace: function () {
            for (var a = this.pos;
                /[\s\u00a0]/.test(this.string.charAt(this.pos));)++this.pos;
            return this.pos > a
        },
        skipToEnd: function () {
            this.pos = this.string.length
        },
        skipTo: function (a) {
            var b = this.string.indexOf(a, this.pos);
            return b > -1 ? (this.pos = b, !0) : void 0
        },
        backUp: function (a) {
            this.pos -= a
        },
        column: function () {
            return this.lastColumnPos < this.start && (this.lastColumnValue = Ie(this.string, this.start, this.tabSize, this.lastColumnPos, this.lastColumnValue), this.lastColumnPos = this.start), this.lastColumnValue
        },
        indentation: function () {
            return Ie(this.string, null, this.tabSize)
        },
        match: function (a, b, c) {
            if ("string" != typeof a) {
                var f = this.string.slice(this.pos).match(a);
                return f && f.index > 0 ? null : (f && b !== !1 && (this.pos += f[0].length), f)
            }
            var d = function (a) {
                return c ? a.toLowerCase() : a
            }, e = this.string.substr(this.pos, a.length);
            return d(e) == d(a) ? (b !== !1 && (this.pos += a.length), !0) : void 0
        },
        current: function () {
            return this.string.slice(this.start, this.pos)
        }
    }, w.StringStream = dd, w.TextMarker = ed, ed.prototype.clear = function () {
        if (!this.explicitlyCleared) {
            var a = this.doc.cm,
                b = a && !a.curOp;
            b && vb(a);
            for (var c = null, d = null, e = 0; this.lines.length > e; ++e) {
                var f = this.lines[e],
                    g = id(f.markedSpans, this);
                null != g.to && (d = ce(f)), f.markedSpans = jd(f.markedSpans, g), null != g.from ? c = ce(f) : this.collapsed && !ud(this.doc, f) && a && be(f, sb(a.display))
            }
            if (a && this.collapsed && !a.options.lineWrapping)
                for (var e = 0; this.lines.length > e; ++e) {
                    var h = td(a.doc, this.lines[e]),
                        i = G(a.doc, h);
                    i > a.display.maxLineLength && (a.display.maxLine = h, a.display.maxLineLength = i, a.display.maxLineChanged = !0)
                }
            null != c && a && Ab(a, c, d + 1), this.lines.length = 0, this.explicitlyCleared = !0, this.collapsed && this.doc.cantEdit && (this.doc.cantEdit = !1, a && Bc(a)), b && wb(a), Ce(this, "clear")
        }
    }, ed.prototype.find = function () {
        for (var a, b, c = 0; this.lines.length > c; ++c) {
            var d = this.lines[c],
                e = id(d.markedSpans, this);
            if (null != e.from || null != e.to) {
                var f = ce(d);
                null != e.from && (a = qc(f, e.from)), null != e.to && (b = qc(f, e.to))
            }
        }
        return "bookmark" == this.type ? a : a && {
            from: a,
            to: b
        }
    }, ed.prototype.getOptions = function (a) {
        var b = this.replacedWith;
        return {
            className: this.className,
            inclusiveLeft: this.inclusiveLeft,
            inclusiveRight: this.inclusiveRight,
            atomic: this.atomic,
            collapsed: this.collapsed,
            clearOnEnter: this.clearOnEnter,
            replacedWith: a ? b && b.cloneNode(!0) : b,
            readOnly: this.readOnly,
            startStyle: this.startStyle,
            endStyle: this.endStyle
        }
    }, ed.prototype.attachLine = function (a) {
        if (!this.lines.length && this.doc.cm) {
            var b = this.doc.cm.curOp;
            b.maybeHiddenMarkers && -1 != Ne(b.maybeHiddenMarkers, this) || (b.maybeUnhiddenMarkers || (b.maybeUnhiddenMarkers = [])).push(this)
        }
        this.lines.push(a)
    }, ed.prototype.detachLine = function (a) {
        if (this.lines.splice(Ne(this.lines, a), 1), !this.lines.length && this.doc.cm) {
            var b = this.doc.cm.curOp;
            (b.maybeHiddenMarkers || (b.maybeHiddenMarkers = [])).push(this)
        }
    }, w.SharedTextMarker = gd, gd.prototype.clear = function () {
        if (!this.explicitlyCleared) {
            this.explicitlyCleared = !0;
            for (var a = 0; this.markers.length > a; ++a) this.markers[a].clear();
            Ce(this, "clear")
        }
    }, gd.prototype.find = function () {
        return this.primary.find()
    }, gd.prototype.getOptions = function (a) {
        var b = this.primary.getOptions(a);
        return b.shared = !0, b
    };
    var yd = w.LineWidget = function (a, b, c) {
        for (var d in c) c.hasOwnProperty(d) && (this[d] = c[d]);
        this.cm = a, this.node = b
    };
    yd.prototype.clear = zd(function () {
        var a = this.line.widgets,
            b = ce(this.line);
        if (null != b && a) {
            for (var c = 0; a.length > c; ++c) a[c] == this && a.splice(c--, 1);
            a.length || (this.line.widgets = null), be(this.line, Math.max(0, this.line.height - Ad(this))), Ab(this.cm, b, b + 1)
        }
    }), yd.prototype.changed = zd(function () {
        var a = this.height;
        this.height = null;
        var b = Ad(this) - a;
        if (b) {
            be(this.line, this.line.height + b);
            var c = ce(this.line);
            Ab(this.cm, c, c + 1)
        }
    });
    var Jd = {}, Md = /[\t\u0000-\u0019\u00ad\u200b\u2028\u2029\uFEFF]/g;
    Sd.prototype = {
        chunkSize: function () {
            return this.lines.length
        },
        removeInner: function (a, b) {
            for (var c = a, d = a + b; d > c; ++c) {
                var e = this.lines[c];
                this.height -= e.height, Ed(e), Ce(e, "delete")
            }
            this.lines.splice(a, b)
        },
        collapse: function (a) {
            a.splice.apply(a, [a.length, 0].concat(this.lines))
        },
        insertInner: function (a, b, c) {
            this.height += c, this.lines = this.lines.slice(0, a).concat(b).concat(this.lines.slice(a));
            for (var d = 0, e = b.length; e > d; ++d) b[d].parent = this
        },
        iterN: function (a, b, c) {
            for (var d = a + b; d > a; ++a)
                if (c(this.lines[a])) return !0
        }
    }, Td.prototype = {
        chunkSize: function () {
            return this.size
        },
        removeInner: function (a, b) {
            this.size -= b;
            for (var c = 0; this.children.length > c; ++c) {
                var d = this.children[c],
                    e = d.chunkSize();
                if (e > a) {
                    var f = Math.min(b, e - a),
                        g = d.height;
                    if (d.removeInner(a, f), this.height -= g - d.height, e == f && (this.children.splice(c--, 1), d.parent = null), 0 == (b -= f)) break;
                    a = 0
                } else a -= e
            }
            if (25 > this.size - b) {
                var h = [];
                this.collapse(h), this.children = [new Sd(h)], this.children[0].parent = this
            }
        },
        collapse: function (a) {
            for (var b = 0, c = this.children.length; c > b; ++b) this.children[b].collapse(a)
        },
        insertInner: function (a, b, c) {
            this.size += b.length, this.height += c;
            for (var d = 0, e = this.children.length; e > d; ++d) {
                var f = this.children[d],
                    g = f.chunkSize();
                if (g >= a) {
                    if (f.insertInner(a, b, c), f.lines && f.lines.length > 50) {
                        for (; f.lines.length > 50;) {
                            var h = f.lines.splice(f.lines.length - 25, 25),
                                i = new Sd(h);
                            f.height -= i.height, this.children.splice(d + 1, 0, i), i.parent = this
                        }
                        this.maybeSpill()
                    }
                    break
                }
                a -= g
            }
        },
        maybeSpill: function () {
            if (!(10 >= this.children.length)) {
                var a = this;
                do {
                    var b = a.children.splice(a.children.length - 5, 5),
                        c = new Td(b);
                    if (a.parent) {
                        a.size -= c.size, a.height -= c.height;
                        var e = Ne(a.parent.children, a);
                        a.parent.children.splice(e + 1, 0, c)
                    } else {
                        var d = new Td(a.children);
                        d.parent = a, a.children = [d, c], a = d
                    }
                    c.parent = a.parent
                } while (a.children.length > 10);
                a.parent.maybeSpill()
            }
        },
        iterN: function (a, b, c) {
            for (var d = 0, e = this.children.length; e > d; ++d) {
                var f = this.children[d],
                    g = f.chunkSize();
                if (g > a) {
                    var h = Math.min(b, g - a);
                    if (f.iterN(a, h, c)) return !0;
                    if (0 == (b -= h)) break;
                    a = 0
                } else a -= g
            }
        }
    };
    var Ud = 0,
        Vd = w.Doc = function (a, b, c) {
            if (!(this instanceof Vd)) return new Vd(a, b, c);
            null == c && (c = 0), Td.call(this, [new Sd([Cd("", null)])]), this.first = c, this.scrollTop = this.scrollLeft = 0, this.cantEdit = !1, this.history = ge(), this.frontier = c;
            var d = qc(c, 0);
            this.sel = {
                from: d,
                to: d,
                head: d,
                anchor: d,
                shift: !1,
                extend: !1,
                goalColumn: null
            }, this.id = ++Ud, this.modeOption = b, "string" == typeof a && (a = ff(a)), Rd(this, {
                from: d,
                to: d,
                text: a
            }, null, {
                head: d,
                anchor: d
            })
        };
    Vd.prototype = Oe(Td.prototype, {
        iter: function (a, b, c) {
            c ? this.iterN(a - this.first, b - a, c) : this.iterN(this.first, this.first + this.size, a)
        },
        insert: function (a, b) {
            for (var c = 0, d = 0, e = b.length; e > d; ++d) c += b[d].height;
            this.insertInner(a - this.first, b, c)
        },
        remove: function (a, b) {
            this.removeInner(a - this.first, b)
        },
        getValue: function (a) {
            var b = ae(this, this.first, this.first + this.size);
            return a === !1 ? b : b.join(a || "\n")
        },
        setValue: function (a) {
            var b = qc(this.first, 0),
                c = this.first + this.size - 1;
            jc(this, {
                from: b,
                to: qc(c, $d(this, c).text.length),
                text: ff(a),
                origin: "setValue"
            }, {
                head: b,
                anchor: b
            }, !0)
        },
        replaceRange: function (a, b, c, d) {
            b = vc(this, b), c = c ? vc(this, c) : b, pc(this, a, b, c, d)
        },
        getRange: function (a, b, c) {
            var d = _d(this, vc(this, a), vc(this, b));
            return c === !1 ? d : d.join(c || "\n")
        },
        getLine: function (a) {
            var b = this.getLineHandle(a);
            return b && b.text
        },
        setLine: function (a, b) {
            xc(this, a) && pc(this, b, qc(a, 0), vc(this, qc(a)))
        },
        removeLine: function (a) {
            xc(this, a) && pc(this, "", qc(a, 0), vc(this, qc(a + 1, 0)))
        },
        getLineHandle: function (a) {
            return xc(this, a) ? $d(this, a) : void 0
        },
        getLineNumber: function (a) {
            return ce(a)
        },
        lineCount: function () {
            return this.size
        },
        firstLine: function () {
            return this.first
        },
        lastLine: function () {
            return this.first + this.size - 1
        },
        clipPos: function (a) {
            return vc(this, a)
        },
        getCursor: function (a) {
            var c, b = this.sel;
            return c = null == a || "head" == a ? b.head : "anchor" == a ? b.anchor : "end" == a || a === !1 ? b.to : b.from, tc(c)
        },
        somethingSelected: function () {
            return !rc(this.sel.head, this.sel.anchor)
        },
        setCursor: yb(function (a, b, c) {
            var d = vc(this, "number" == typeof a ? qc(a, b || 0) : a);
            c ? yc(this, d) : Ac(this, d, d)
        }),
        setSelection: yb(function (a, b) {
            Ac(this, vc(this, a), vc(this, b || a))
        }),
        extendSelection: yb(function (a, b) {
            yc(this, vc(this, a), b && vc(this, b))
        }),
        getSelection: function (a) {
            return this.getRange(this.sel.from, this.sel.to, a)
        },
        replaceSelection: function (a, b, c) {
            jc(this, {
                from: this.sel.from,
                to: this.sel.to,
                text: ff(a),
                origin: c
            }, b || "around")
        },
        undo: yb(function () {
            lc(this, "undo")
        }),
        redo: yb(function () {
            lc(this, "redo")
        }),
        setExtending: function (a) {
            this.sel.extend = a
        },
        historySize: function () {
            var a = this.history;
            return {
                undo: a.done.length,
                redo: a.undone.length
            }
        },
        clearHistory: function () {
            this.history = ge()
        },
        markClean: function () {
            this.history.dirtyCounter = 0, this.history.lastOp = this.history.lastOrigin = null
        },
        isClean: function () {
            return 0 == this.history.dirtyCounter
        },
        getHistory: function () {
            return {
                done: me(this.history.done),
                undone: me(this.history.undone)
            }
        },
        setHistory: function (a) {
            var b = this.history = ge();
            b.done = a.done.slice(0), b.undone = a.undone.slice(0)
        },
        markText: function (a, b, c) {
            return fd(this, vc(this, a), vc(this, b), c, "range")
        },
        setBookmark: function (a, b) {
            var c = {
                replacedWith: b && (null == b.nodeType ? b.widget : b),
                insertLeft: b && b.insertLeft
            };
            return a = vc(this, a), fd(this, a, a, c, "bookmark")
        },
        findMarksAt: function (a) {
            a = vc(this, a);
            var b = [],
                c = $d(this, a.line).markedSpans;
            if (c)
                for (var d = 0; c.length > d; ++d) {
                    var e = c[d];
                    (null == e.from || e.from <= a.ch) && (null == e.to || e.to >= a.ch) && b.push(e.marker.parent || e.marker)
                }
            return b
        },
        getAllMarks: function () {
            var a = [];
            return this.iter(function (b) {
                var c = b.markedSpans;
                if (c)
                    for (var d = 0; c.length > d; ++d) null != c[d].from && a.push(c[d].marker)
            }), a
        },
        posFromIndex: function (a) {
            var b, c = this.first;
            return this.iter(function (d) {
                var e = d.text.length + 1;
                return e > a ? (b = a, !0) : (a -= e, ++c, void 0)
            }), vc(this, qc(c, b))
        },
        indexFromPos: function (a) {
            a = vc(this, a);
            var b = a.ch;
            return a.line < this.first || 0 > a.ch ? 0 : (this.iter(this.first, a.line, function (a) {
                b += a.text.length + 1
            }), b)
        },
        copy: function (a) {
            var b = new Vd(ae(this, this.first, this.first + this.size), this.modeOption, this.first);
            return b.scrollTop = this.scrollTop, b.scrollLeft = this.scrollLeft, b.sel = {
                from: this.sel.from,
                to: this.sel.to,
                head: this.sel.head,
                anchor: this.sel.anchor,
                shift: this.sel.shift,
                extend: !1,
                goalColumn: this.sel.goalColumn
            }, a && (b.history.undoDepth = this.history.undoDepth, b.setHistory(this.getHistory())), b
        },
        linkedDoc: function (a) {
            a || (a = {});
            var b = this.first,
                c = this.first + this.size;
            null != a.from && a.from > b && (b = a.from), null != a.to && c > a.to && (c = a.to);
            var d = new Vd(ae(this, b, c), a.mode || this.modeOption, b);
            return a.sharedHist && (d.history = this.history), (this.linked || (this.linked = [])).push({
                doc: d,
                sharedHist: a.sharedHist
            }), d.linked = [{
                doc: this,
                isParent: !0,
                sharedHist: a.sharedHist
            }], d
        },
        unlinkDoc: function (a) {
            if (a instanceof w && (a = a.doc), this.linked)
                for (var b = 0; this.linked.length > b; ++b) {
                    var c = this.linked[b];
                    if (c.doc == a) {
                        this.linked.splice(b, 1), a.unlinkDoc(this);
                        break
                    }
                }
            if (a.history == this.history) {
                var d = [a.id];
                Yd(a, function (a) {
                    d.push(a.id)
                }, !0), a.history = ge(), a.history.done = me(this.history.done, d), a.history.undone = me(this.history.undone, d)
            }
        },
        iterLinkedDocs: function (a) {
            Yd(this, a)
        },
        getMode: function () {
            return this.mode
        },
        getEditor: function () {
            return this.cm
        }
    }), Vd.prototype.eachLine = Vd.prototype.iter;
    var Wd = "iter insert remove copy getEditor".split(" ");
    for (var Xd in Vd.prototype) Vd.prototype.hasOwnProperty(Xd) && 0 > Ne(Wd, Xd) && (w.prototype[Xd] = function (a) {
        return function () {
            return a.apply(this.doc, arguments)
        }
    }(Vd.prototype[Xd]));
    w.e_stop = ue, w.e_preventDefault = se, w.e_stopPropagation = te;
    var Ae, Be = 0;
    w.on = xe, w.off = ye, w.signal = ze;
    var Fe = 30,
        Ge = w.Pass = {
            toString: function () {
                return "CodeMirror.Pass"
            }
        };
    He.prototype = {
        set: function (a, b) {
            clearTimeout(this.id), this.id = setTimeout(b, a)
        }
    }, w.countColumn = Ie;
    var Je = [""],
        Se = /[\u3040-\u309f\u30a0-\u30ff\u3400-\u4db5\u4e00-\u9fcc]/,
        Ve = /[\u0300-\u036F\u0483-\u0487\u0488-\u0489\u0591-\u05BD\u05BF\u05C1-\u05C2\u05C4-\u05C5\u05C7\u0610-\u061A\u064B-\u065F\u0670\u06D6-\u06DC\u06DF-\u06E4\u06E7-\u06E8\u06EA-\u06ED\uA66F\uA670-\uA672\uA674-\uA67D\uA69F\udc00-\udfff]/;
    w.replaceGetRect = function (a) {
        $e = a
    };
    var _e = function () {
        if (d) return !1;
        var a = We("div");
        return "draggable" in a || "dragDrop" in a
    }(),
        af = /^$/;
    a ? af = /$'/ : i && !/Version\/([6-9]|\d\d)\b/.test(navigator.userAgent) ? af = /\-[^ \-?]|\?[^ !'\"\),.\-\/:;\?\]\}]/ : e && (af = /[~!#%&*)=+}\]|\"\.>,:;][({[<]|-[^\-?\.]|\?[\w~`@#$%\^&*(_=+{[|><]/);
    var bf, df, ff = 3 != "\n\nb".split(/\n/).length ? function (a) {
            for (var b = 0, c = [], d = a.length; d >= b;) {
                var e = a.indexOf("\n", b); - 1 == e && (e = a.length);
                var f = a.slice(b, "\r" == a.charAt(e - 1) ? e - 1 : e),
                    g = f.indexOf("\r"); - 1 != g ? (c.push(f.slice(0, g)), b += g + 1) : (c.push(f), b = e + 1)
            }
            return c
        } : function (a) {
            return a.split(/\r\n?|\n/)
        };
    w.splitLines = ff;
    var gf = window.getSelection ? function (a) {
            try {
                return a.selectionStart != a.selectionEnd
            } catch (b) {
                return !1
            }
        } : function (a) {
            try {
                var b = a.ownerDocument.selection.createRange()
            } catch (c) {}
            return b && b.parentElement() == a ? 0 != b.compareEndPoints("StartToEnd", b) : !1
        }, hf = function () {
            var a = We("div");
            return "oncopy" in a ? !0 : (a.setAttribute("oncopy", "return;"), "function" == typeof a.oncopy)
        }(),
        jf = {
            3: "Enter",
            8: "Backspace",
            9: "Tab",
            13: "Enter",
            16: "Shift",
            17: "Ctrl",
            18: "Alt",
            19: "Pause",
            20: "CapsLock",
            27: "Esc",
            32: "Space",
            33: "PageUp",
            34: "PageDown",
            35: "End",
            36: "Home",
            37: "Left",
            38: "Up",
            39: "Right",
            40: "Down",
            44: "PrintScrn",
            45: "Insert",
            46: "Delete",
            59: ";",
            91: "Mod",
            92: "Mod",
            93: "Mod",
            109: "-",
            107: "=",
            127: "Delete",
            186: ";",
            187: "=",
            188: ",",
            189: "-",
            190: ".",
            191: "/",
            192: "`",
            219: "[",
            220: "\\",
            221: "]",
            222: "'",
            63276: "PageUp",
            63277: "PageDown",
            63275: "End",
            63273: "Home",
            63234: "Left",
            63232: "Up",
            63235: "Right",
            63233: "Down",
            63302: "Insert",
            63272: "Delete"
        };
    w.keyNames = jf,
    function () {
        for (var a = 0; 10 > a; a++) jf[a + 48] = a + "";
        for (var a = 65; 90 >= a; a++) jf[a] = String.fromCharCode(a);
        for (var a = 1; 12 >= a; a++) jf[a + 111] = jf[a + 63235] = "F" + a
    }();
    var tf = function () {
        function c(c) {
            return 255 >= c ? a.charAt(c) : c >= 1424 && 1524 >= c ? "R" : c >= 1536 && 1791 >= c ? b.charAt(c - 1536) : c >= 1792 && 2220 >= c ? "r" : "L"
        }
        var a = "bbbbbbbbbtstwsbbbbbbbbbbbbbbssstwNN%%%NNNNNN,N,N1111111111NNNNNNNLLLLLLLLLLLLLLLLLLLLLLLLLLNNNNNNLLLLLLLLLLLLLLLLLLLLLLLLLLNNNNbbbbbbsbbbbbbbbbbbbbbbbbbbbbbbbbb,N%%%%NNNNLNNNNN%%11NLNNN1LNNNNNLLLLLLLLLLLLLLLLLLLLLLLNLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLNLLLLLLLL",
            b = "rrrrrrrrrrrr,rNNmmmmmmrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrmmmmmmmmmmmmmmrrrrrrrnnnnnnnnnn%nnrrrmrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrmmmmmmmmmmmmmmmmmmmNmmmmrrrrrrrrrrrrrrrrrr",
            d = /[\u0590-\u05f4\u0600-\u06ff\u0700-\u08ac]/,
            e = /[stwN]/,
            f = /[LRr]/,
            g = /[Lb1n]/,
            h = /[1n]/,
            i = "L";
        return function (a) {
            if (!d.test(a)) return !1;
            for (var l, b = a.length, j = [], k = 0; b > k; ++k) j.push(l = c(a.charCodeAt(k)));
            for (var k = 0, m = i; b > k; ++k) {
                var l = j[k];
                "m" == l ? j[k] = m : m = l
            }
            for (var k = 0, n = i; b > k; ++k) {
                var l = j[k];
                "1" == l && "r" == n ? j[k] = "n" : f.test(l) && (n = l, "r" == l && (j[k] = "R"))
            }
            for (var k = 1, m = j[0]; b - 1 > k; ++k) {
                var l = j[k];
                "+" == l && "1" == m && "1" == j[k + 1] ? j[k] = "1" : "," != l || m != j[k + 1] || "1" != m && "n" != m || (j[k] = m), m = l
            }
            for (var k = 0; b > k; ++k) {
                var l = j[k];
                if ("," == l) j[k] = "N";
                else if ("%" == l) {
                    for (var o = k + 1; b > o && "%" == j[o]; ++o);
                    for (var p = k && "!" == j[k - 1] || b - 1 > o && "1" == j[o] ? "1" : "N", q = k; o > q; ++q) j[q] = p;
                    k = o - 1
                }
            }
            for (var k = 0, n = i; b > k; ++k) {
                var l = j[k];
                "L" == n && "1" == l ? j[k] = "L" : f.test(l) && (n = l)
            }
            for (var k = 0; b > k; ++k)
                if (e.test(j[k])) {
                    for (var o = k + 1; b > o && e.test(j[o]); ++o);
                    for (var r = "L" == (k ? j[k - 1] : i), s = "L" == (b - 1 > o ? j[o] : i), p = r || s ? "L" : "R", q = k; o > q; ++q) j[q] = p;
                    k = o - 1
                }
            for (var u, t = [], k = 0; b > k;)
                if (g.test(j[k])) {
                    var v = k;
                    for (++k; b > k && g.test(j[k]); ++k);
                    t.push({
                        from: v,
                        to: k,
                        level: 0
                    })
                } else {
                    var w = k,
                        x = t.length;
                    for (++k; b > k && "L" != j[k]; ++k);
                    for (var q = w; k > q;)
                        if (h.test(j[q])) {
                            q > w && t.splice(x, 0, {
                                from: w,
                                to: q,
                                level: 1
                            });
                            var y = q;
                            for (++q; k > q && h.test(j[q]); ++q);
                            t.splice(x, 0, {
                                from: y,
                                to: q,
                                level: 2
                            }), w = q
                        } else ++q;
                    k > w && t.splice(x, 0, {
                        from: w,
                        to: k,
                        level: 1
                    })
                }
            return 1 == t[0].level && (u = a.match(/^\s+/)) && (t[0].from = u[0].length, t.unshift({
                from: 0,
                to: u[0].length,
                level: 0
            })), 1 == Le(t).level && (u = a.match(/\s+$/)) && (Le(t).to -= u[0].length, t.push({
                from: b - u[0].length,
                to: b,
                level: 0
            })), t[0].level != Le(t).level && t.push({
                from: b,
                to: b,
                level: t[0].level
            }), t
        }
    }();
    return w.version = "3.1 +", w
}(), CodeMirror.defineMode("css", function (a) {
    return CodeMirror.getMode(a, "text/css")
}), CodeMirror.defineMode("css-base", function (a, b) {
    "use strict";

    function l(a, b) {
        return k = b, a
    }

    function m(a, b) {
        var c = a.next();
        if (d[c]) {
            var e = d[c](a, b);
            if (e !== !1) return e
        }
        if ("@" == c) return a.eatWhile(/[\w\\\-]/), l("def", a.current());
        if ("=" == c) l(null, "compare");
        else {
            if (("~" == c || "|" == c) && a.eat("=")) return l(null, "compare");
            if ('"' == c || "'" == c) return b.tokenize = n(c), b.tokenize(a, b);
            if ("#" == c) return a.eatWhile(/[\w\\\-]/), l("atom", "hash");
            if ("!" == c) return a.match(/^\s*\w*/), l("keyword", "important");
            if (/\d/.test(c)) return a.eatWhile(/[\w.%]/), l("number", "unit");
            if ("-" !== c) return /[,+>*\/]/.test(c) ? l(null, "select-op") : "." == c && a.match(/^-?[_a-z][_a-z0-9-]*/i) ? l("qualifier", "qualifier") : ":" == c ? l("operator", c) : /[;{}\[\]\(\)]/.test(c) ? l(null, c) : "u" == c && a.match("rl(") ? (a.backUp(1), b.tokenize = o, l("property", "variable")) : (a.eatWhile(/[\w\\\-]/), l("property", "variable"));
            if (/\d/.test(a.peek())) return a.eatWhile(/[\w.%]/), l("number", "unit");
            if (a.match(/^[^-]+-/)) return l("meta", "meta")
        }
    }

    function n(a, b) {
        return function (c, d) {
            for (var f, e = !1; null != (f = c.next()) && (f != a || e);) e = !e && "\\" == f;
            return e || (b && c.backUp(1), d.tokenize = m), l("string", "string")
        }
    }

    function o(a, b) {
        return a.next(), b.tokenize = a.match(/\s*[\"\']/, !1) ? m : n(")", !0), l(null, "(")
    }
    var c = a.indentUnit,
        d = b.hooks || {}, e = b.atMediaTypes || {}, f = b.atMediaFeatures || {}, g = b.propertyKeywords || {}, h = b.colorKeywords || {}, i = b.valueKeywords || {}, j = !! b.allowNested,
        k = null;
    return {
        startState: function (a) {
            return {
                tokenize: m,
                baseIndent: a || 0,
                stack: []
            }
        },
        token: function (a, b) {
            if (b.tokenize = b.tokenize || m, b.tokenize == m && a.eatSpace()) return null;
            var c = b.tokenize(a, b);
            c && "string" != typeof c && (c = l(c[0], c[1]));
            var d = b.stack[b.stack.length - 1];
            if ("variable" == c) return "variable-definition" == k && b.stack.push("propertyValue"), "variable-2";
            if ("property" == c ? "propertyValue" == d ? c = i[a.current()] ? "string-2" : h[a.current()] ? "keyword" : "variable-2" : "rule" == d ? g[a.current()] || (c += " error") : "block" == d ? c = g[a.current()] ? "property" : h[a.current()] ? "keyword" : i[a.current()] ? "string-2" : "tag" : d && "@media{" != d ? "@media" == d ? c = e[a.current()] ? "attribute" : /^(only|not)$/i.test(a.current()) ? "keyword" : "and" == a.current().toLowerCase() ? "error" : f[a.current()] ? "error" : "attribute error" : "@mediaType" == d ? c = e[a.current()] ? "attribute" : "and" == a.current().toLowerCase() ? "operator" : /^(only|not)$/i.test(a.current()) ? "error" : f[a.current()] ? "error" : "error" : "@mediaType(" == d ? g[a.current()] || (e[a.current()] ? c = "error" : "and" == a.current().toLowerCase() ? c = "operator" : /^(only|not)$/i.test(a.current()) ? c = "error" : c += " error") : c = "error" : c = "tag" : "atom" == c ? d && "@media{" != d ? "propertyValue" == d ? /^#([0-9a-fA-f]{3}|[0-9a-fA-f]{6})$/.test(a.current()) || (c += " error") : c = "error" : c = "builtin" : "@media" == d && "{" == k && (c = "error"), "{" == k)
                if ("@media" == d || "@mediaType" == d) b.stack.pop(), b.stack[b.stack.length - 1] = "@media{";
                else {
                    var n = j ? "block" : "rule";
                    b.stack.push(n)
                } else if ("}" == k) {
                var o = b.stack[b.stack.length - 1];
                "interpolation" == o && (c = "operator"), b.stack.pop(), "propertyValue" == d && b.stack.pop()
            } else "interpolation" == k ? b.stack.push("interpolation") : "@media" == k ? b.stack.push("@media") : "@media" == d && /\b(keyword|attribute)\b/.test(c) ? b.stack.push("@mediaType") : "@mediaType" == d && "," == a.current() ? b.stack.pop() : "@mediaType" == d && "(" == k ? b.stack.push("@mediaType(") : "@mediaType(" == d && ")" == k ? b.stack.pop() : "rule" != d && "block" != d || ":" != k ? "propertyValue" == d && ";" == k && b.stack.pop() : b.stack.push("propertyValue");
            return c
        },
        indent: function (a, b) {
            var d = a.stack.length;
            return /^\}/.test(b) && (d -= "propertyValue" == a.stack[a.stack.length - 1] ? 2 : 1), a.baseIndent + d * c
        },
        electricChars: "}"
    }
}),
function () {
    function a(a) {
        for (var b = {}, c = 0; a.length > c; ++c) b[a[c]] = !0;
        return b
    }

    function g(a, b) {
        for (var d, c = !1; null != (d = a.next());) {
            if (c && "/" == d) {
                b.tokenize = null;
                break
            }
            c = "*" == d
        }
        return ["comment", "comment"]
    }
    var b = a(["all", "aural", "braille", "handheld", "print", "projection", "screen", "tty", "tv", "embossed"]),
        c = a(["width", "min-width", "max-width", "height", "min-height", "max-height", "device-width", "min-device-width", "max-device-width", "device-height", "min-device-height", "max-device-height", "aspect-ratio", "min-aspect-ratio", "max-aspect-ratio", "device-aspect-ratio", "min-device-aspect-ratio", "max-device-aspect-ratio", "color", "min-color", "max-color", "color-index", "min-color-index", "max-color-index", "monochrome", "min-monochrome", "max-monochrome", "resolution", "min-resolution", "max-resolution", "scan", "grid"]),
        d = a(["align-content", "align-items", "align-self", "alignment-adjust", "alignment-baseline", "anchor-point", "animation", "animation-delay", "animation-direction", "animation-duration", "animation-iteration-count", "animation-name", "animation-play-state", "animation-timing-function", "appearance", "azimuth", "backface-visibility", "background", "background-attachment", "background-clip", "background-color", "background-image", "background-origin", "background-position", "background-repeat", "background-size", "baseline-shift", "binding", "bleed", "bookmark-label", "bookmark-level", "bookmark-state", "bookmark-target", "border", "border-bottom", "border-bottom-color", "border-bottom-left-radius", "border-bottom-right-radius", "border-bottom-style", "border-bottom-width", "border-collapse", "border-color", "border-image", "border-image-outset", "border-image-repeat", "border-image-slice", "border-image-source", "border-image-width", "border-left", "border-left-color", "border-left-style", "border-left-width", "border-radius", "border-right", "border-right-color", "border-right-style", "border-right-width", "border-spacing", "border-style", "border-top", "border-top-color", "border-top-left-radius", "border-top-right-radius", "border-top-style", "border-top-width", "border-width", "bottom", "box-decoration-break", "box-shadow", "box-sizing", "break-after", "break-before", "break-inside", "caption-side", "clear", "clip", "color", "color-profile", "column-count", "column-fill", "column-gap", "column-rule", "column-rule-color", "column-rule-style", "column-rule-width", "column-span", "column-width", "columns", "content", "counter-increment", "counter-reset", "crop", "cue", "cue-after", "cue-before", "cursor", "direction", "display", "dominant-baseline", "drop-initial-after-adjust", "drop-initial-after-align", "drop-initial-before-adjust", "drop-initial-before-align", "drop-initial-size", "drop-initial-value", "elevation", "empty-cells", "fit", "fit-position", "flex", "flex-basis", "flex-direction", "flex-flow", "flex-grow", "flex-shrink", "flex-wrap", "float", "float-offset", "font", "font-feature-settings", "font-family", "font-kerning", "font-language-override", "font-size", "font-size-adjust", "font-stretch", "font-style", "font-synthesis", "font-variant", "font-variant-alternates", "font-variant-caps", "font-variant-east-asian", "font-variant-ligatures", "font-variant-numeric", "font-variant-position", "font-weight", "grid-cell", "grid-column", "grid-column-align", "grid-column-sizing", "grid-column-span", "grid-columns", "grid-flow", "grid-row", "grid-row-align", "grid-row-sizing", "grid-row-span", "grid-rows", "grid-template", "hanging-punctuation", "height", "hyphens", "icon", "image-orientation", "image-rendering", "image-resolution", "inline-box-align", "justify-content", "left", "letter-spacing", "line-break", "line-height", "line-stacking", "line-stacking-ruby", "line-stacking-shift", "line-stacking-strategy", "list-style", "list-style-image", "list-style-position", "list-style-type", "margin", "margin-bottom", "margin-left", "margin-right", "margin-top", "marker-offset", "marks", "marquee-direction", "marquee-loop", "marquee-play-count", "marquee-speed", "marquee-style", "max-height", "max-width", "min-height", "min-width", "move-to", "nav-down", "nav-index", "nav-left", "nav-right", "nav-up", "opacity", "order", "orphans", "outline", "outline-color", "outline-offset", "outline-style", "outline-width", "overflow", "overflow-style", "overflow-wrap", "overflow-x", "overflow-y", "padding", "padding-bottom", "padding-left", "padding-right", "padding-top", "page", "page-break-after", "page-break-before", "page-break-inside", "page-policy", "pause", "pause-after", "pause-before", "perspective", "perspective-origin", "pitch", "pitch-range", "play-during", "position", "presentation-level", "punctuation-trim", "quotes", "rendering-intent", "resize", "rest", "rest-after", "rest-before", "richness", "right", "rotation", "rotation-point", "ruby-align", "ruby-overhang", "ruby-position", "ruby-span", "size", "speak", "speak-as", "speak-header", "speak-numeral", "speak-punctuation", "speech-rate", "stress", "string-set", "tab-size", "table-layout", "target", "target-name", "target-new", "target-position", "text-align", "text-align-last", "text-decoration", "text-decoration-color", "text-decoration-line", "text-decoration-skip", "text-decoration-style", "text-emphasis", "text-emphasis-color", "text-emphasis-position", "text-emphasis-style", "text-height", "text-indent", "text-justify", "text-outline", "text-shadow", "text-space-collapse", "text-transform", "text-underline-position", "text-wrap", "top", "transform", "transform-origin", "transform-style", "transition", "transition-delay", "transition-duration", "transition-property", "transition-timing-function", "unicode-bidi", "vertical-align", "visibility", "voice-balance", "voice-duration", "voice-family", "voice-pitch", "voice-range", "voice-rate", "voice-stress", "voice-volume", "volume", "white-space", "widows", "width", "word-break", "word-spacing", "word-wrap", "z-index"]),
        e = a(["black", "silver", "gray", "white", "maroon", "red", "purple", "fuchsia", "green", "lime", "olive", "yellow", "navy", "blue", "teal", "aqua"]),
        f = a(["above", "absolute", "activeborder", "activecaption", "afar", "after-white-space", "ahead", "alias", "all", "all-scroll", "alternate", "always", "amharic", "amharic-abegede", "antialiased", "appworkspace", "arabic-indic", "armenian", "asterisks", "auto", "avoid", "background", "backwards", "baseline", "below", "bidi-override", "binary", "bengali", "blink", "block", "block-axis", "bold", "bolder", "border", "border-box", "both", "bottom", "break-all", "break-word", "button", "button-bevel", "buttonface", "buttonhighlight", "buttonshadow", "buttontext", "cambodian", "capitalize", "caps-lock-indicator", "caption", "captiontext", "caret", "cell", "center", "checkbox", "circle", "cjk-earthly-branch", "cjk-heavenly-stem", "cjk-ideographic", "clear", "clip", "close-quote", "col-resize", "collapse", "compact", "condensed", "contain", "content", "content-box", "context-menu", "continuous", "copy", "cover", "crop", "cross", "crosshair", "currentcolor", "cursive", "dashed", "decimal", "decimal-leading-zero", "default", "default-button", "destination-atop", "destination-in", "destination-out", "destination-over", "devanagari", "disc", "discard", "document", "dot-dash", "dot-dot-dash", "dotted", "double", "down", "e-resize", "ease", "ease-in", "ease-in-out", "ease-out", "element", "ellipsis", "embed", "end", "ethiopic", "ethiopic-abegede", "ethiopic-abegede-am-et", "ethiopic-abegede-gez", "ethiopic-abegede-ti-er", "ethiopic-abegede-ti-et", "ethiopic-halehame-aa-er", "ethiopic-halehame-aa-et", "ethiopic-halehame-am-et", "ethiopic-halehame-gez", "ethiopic-halehame-om-et", "ethiopic-halehame-sid-et", "ethiopic-halehame-so-et", "ethiopic-halehame-ti-er", "ethiopic-halehame-ti-et", "ethiopic-halehame-tig", "ew-resize", "expanded", "extra-condensed", "extra-expanded", "fantasy", "fast", "fill", "fixed", "flat", "footnotes", "forwards", "from", "geometricPrecision", "georgian", "graytext", "groove", "gujarati", "gurmukhi", "hand", "hangul", "hangul-consonant", "hebrew", "help", "hidden", "hide", "higher", "highlight", "highlighttext", "hiragana", "hiragana-iroha", "horizontal", "hsl", "hsla", "icon", "ignore", "inactiveborder", "inactivecaption", "inactivecaptiontext", "infinite", "infobackground", "infotext", "inherit", "initial", "inline", "inline-axis", "inline-block", "inline-table", "inset", "inside", "intrinsic", "invert", "italic", "justify", "kannada", "katakana", "katakana-iroha", "khmer", "landscape", "lao", "large", "larger", "left", "level", "lighter", "line-through", "linear", "lines", "list-item", "listbox", "listitem", "local", "logical", "loud", "lower", "lower-alpha", "lower-armenian", "lower-greek", "lower-hexadecimal", "lower-latin", "lower-norwegian", "lower-roman", "lowercase", "ltr", "malayalam", "match", "media-controls-background", "media-current-time-display", "media-fullscreen-button", "media-mute-button", "media-play-button", "media-return-to-realtime-button", "media-rewind-button", "media-seek-back-button", "media-seek-forward-button", "media-slider", "media-sliderthumb", "media-time-remaining-display", "media-volume-slider", "media-volume-slider-container", "media-volume-sliderthumb", "medium", "menu", "menulist", "menulist-button", "menulist-text", "menulist-textfield", "menutext", "message-box", "middle", "min-intrinsic", "mix", "mongolian", "monospace", "move", "multiple", "myanmar", "n-resize", "narrower", "navy", "ne-resize", "nesw-resize", "no-close-quote", "no-drop", "no-open-quote", "no-repeat", "none", "normal", "not-allowed", "nowrap", "ns-resize", "nw-resize", "nwse-resize", "oblique", "octal", "open-quote", "optimizeLegibility", "optimizeSpeed", "oriya", "oromo", "outset", "outside", "overlay", "overline", "padding", "padding-box", "painted", "paused", "persian", "plus-darker", "plus-lighter", "pointer", "portrait", "pre", "pre-line", "pre-wrap", "preserve-3d", "progress", "push-button", "radio", "read-only", "read-write", "read-write-plaintext-only", "relative", "repeat", "repeat-x", "repeat-y", "reset", "reverse", "rgb", "rgba", "ridge", "right", "round", "row-resize", "rtl", "run-in", "running", "s-resize", "sans-serif", "scroll", "scrollbar", "se-resize", "searchfield", "searchfield-cancel-button", "searchfield-decoration", "searchfield-results-button", "searchfield-results-decoration", "semi-condensed", "semi-expanded", "separate", "serif", "show", "sidama", "single", "skip-white-space", "slide", "slider-horizontal", "slider-vertical", "sliderthumb-horizontal", "sliderthumb-vertical", "slow", "small", "small-caps", "small-caption", "smaller", "solid", "somali", "source-atop", "source-in", "source-out", "source-over", "space", "square", "square-button", "start", "static", "status-bar", "stretch", "stroke", "sub", "subpixel-antialiased", "super", "sw-resize", "table", "table-caption", "table-cell", "table-column", "table-column-group", "table-footer-group", "table-header-group", "table-row", "table-row-group", "telugu", "text", "text-bottom", "text-top", "textarea", "textfield", "thai", "thick", "thin", "threeddarkshadow", "threedface", "threedhighlight", "threedlightshadow", "threedshadow", "tibetan", "tigre", "tigrinya-er", "tigrinya-er-abegede", "tigrinya-et", "tigrinya-et-abegede", "to", "top", "transparent", "ultra-condensed", "ultra-expanded", "underline", "up", "upper-alpha", "upper-armenian", "upper-greek", "upper-hexadecimal", "upper-latin", "upper-norwegian", "upper-roman", "uppercase", "urdu", "url", "vertical", "vertical-text", "visible", "visibleFill", "visiblePainted", "visibleStroke", "visual", "w-resize", "wait", "wave", "white", "wider", "window", "windowframe", "windowtext", "x-large", "x-small", "xor", "xx-large", "xx-small", "yellow"]);
    CodeMirror.defineMIME("text/css", {
        atMediaTypes: b,
        atMediaFeatures: c,
        propertyKeywords: d,
        colorKeywords: e,
        valueKeywords: f,
        hooks: {
            "<": function (a, b) {
                function c(a, b) {
                    for (var d, c = 0; null != (d = a.next());) {
                        if (c >= 2 && ">" == d) {
                            b.tokenize = null;
                            break
                        }
                        c = "-" == d ? c + 1 : 0
                    }
                    return ["comment", "comment"]
                }
                return a.eat("!") ? (b.tokenize = c, c(a, b)) : void 0
            },
            "/": function (a, b) {
                return a.eat("*") ? (b.tokenize = g, g(a, b)) : !1
            }
        },
        name: "css-base"
    }), CodeMirror.defineMIME("text/x-scss", {
        atMediaTypes: b,
        atMediaFeatures: c,
        propertyKeywords: d,
        colorKeywords: e,
        valueKeywords: f,
        allowNested: !0,
        hooks: {
            $: function (a) {
                return a.match(/^[\w-]+/), ":" == a.peek() ? ["variable", "variable-definition"] : ["variable", "variable"]
            },
            "/": function (a, b) {
                return a.eat("/") ? (a.skipToEnd(), ["comment", "comment"]) : a.eat("*") ? (b.tokenize = g, g(a, b)) : ["operator", "operator"]
            },
            "#": function (a) {
                return a.eat("{") ? ["operator", "interpolation"] : (a.eatWhile(/[\w\\\-]/), ["atom", "hash"])
            }
        },
        name: "css-base"
    })
}(), CodeMirror.defineMode("htmlmixed", function (a, b) {
    function i(a, b) {
        var f = b.htmlState.tagName,
            g = c.token(a, b.htmlState);
        if ("script" == f && /\btag\b/.test(g) && ">" == a.current()) {
            var h = a.string.slice(Math.max(0, a.pos - 100), a.pos).match(/\btype\s*=\s*("[^"]+"|'[^']+'|\S+)[^<]*$/i);
            h = h ? h[1] : "", h && /[\"\']/.test(h.charAt(0)) && (h = h.slice(1, h.length - 1));
            for (var i = 0; e.length > i; ++i) {
                var j = e[i];
                if ("string" == typeof j.matches ? h == j.matches : j.matches.test(h)) {
                    j.mode && (b.token = k, b.localMode = j.mode, b.localState = j.mode.startState && j.mode.startState(c.indent(b.htmlState, "")));
                    break
                }
            }
        } else "style" == f && /\btag\b/.test(g) && ">" == a.current() && (b.token = l, b.localMode = d, b.localState = d.startState(c.indent(b.htmlState, "")));
        return g
    }

    function j(a, b, c) {
        var f, d = a.current(),
            e = d.search(b);
        return e > -1 ? a.backUp(d.length - e) : (f = d.match(/<\/?$/)) && (a.backUp(d.length), a.match(b, !1) || a.match(d[0])), c
    }

    function k(a, b) {
        return a.match(/^<\/\s*script\s*>/i, !1) ? (b.token = i, b.localState = b.localMode = null, i(a, b)) : j(a, /<\/\s*script\s*>/, b.localMode.token(a, b.localState))
    }

    function l(a, b) {
        return a.match(/^<\/\s*style\s*>/i, !1) ? (b.token = i, b.localState = b.localMode = null, i(a, b)) : j(a, /<\/\s*style\s*>/, d.token(a, b.localState))
    }
    var c = CodeMirror.getMode(a, {
        name: "xml",
        htmlMode: !0
    }),
        d = CodeMirror.getMode(a, "css"),
        e = [],
        f = b && b.scriptTypes;
    if (e.push({
        matches: /^(?:text|application)\/(?:x-)?(?:java|ecma)script$|^$/i,
        mode: CodeMirror.getMode(a, "javascript")
    }), f)
        for (var g = 0; f.length > g; ++g) {
            var h = f[g];
            e.push({
                matches: h.matches,
                mode: h.mode && CodeMirror.getMode(a, h.mode)
            })
        }
    return e.push({
        matches: /./,
        mode: CodeMirror.getMode(a, "text/plain")
    }), {
        startState: function () {
            var a = c.startState();
            return {
                token: i,
                localMode: null,
                localState: null,
                htmlState: a
            }
        },
        copyState: function (a) {
            if (a.localState) var b = CodeMirror.copyState(a.localMode, a.localState);
            return {
                token: a.token,
                localMode: a.localMode,
                localState: b,
                htmlState: CodeMirror.copyState(c, a.htmlState)
            }
        },
        token: function (a, b) {
            return b.token(a, b)
        },
        indent: function (a, b) {
            return !a.localMode || /^\s*<\//.test(b) ? c.indent(a.htmlState, b) : a.localMode.indent ? a.localMode.indent(a.localState, b) : CodeMirror.Pass
        },
        electricChars: "/{}:",
        innerMode: function (a) {
            return {
                state: a.localState || a.htmlState,
                mode: a.localMode || c
            }
        }
    }
}, "xml", "javascript", "css"), CodeMirror.defineMIME("text/html", "htmlmixed"), CodeMirror.defineMode("javascript", function (a, b) {
    function h(a, b, c) {
        return b.tokenize = c, c(a, b)
    }

    function i(a, b) {
        for (var d, c = !1; null != (d = a.next());) {
            if (d == b && !c) return !1;
            c = !c && "\\" == d
        }
        return c
    }

    function l(a, b, c) {
        return j = a, k = c, b
    }

    function m(a, b) {
        var c = a.next();
        if ('"' == c || "'" == c) return h(a, b, n(c));
        if (/[\[\]{}\(\),;\:\.]/.test(c)) return l(c);
        if ("0" == c && a.eat(/x/i)) return a.eatWhile(/[\da-f]/i), l("number", "number");
        if (/\d/.test(c) || "-" == c && a.eat(/\d/)) return a.match(/^\d*(?:\.\d*)?(?:[eE][+\-]?\d+)?/), l("number", "number");
        if ("/" == c) return a.eat("*") ? h(a, b, o) : a.eat("/") ? (a.skipToEnd(), l("comment", "comment")) : "operator" == b.lastType || "keyword c" == b.lastType || /^[\[{}\(,;:]$/.test(b.lastType) ? (i(a, "/"), a.eatWhile(/[gimy]/), l("regexp", "string-2")) : (a.eatWhile(g), l("operator", null, a.current()));
        if ("#" == c) return a.skipToEnd(), l("error", "error");
        if (g.test(c)) return a.eatWhile(g), l("operator", null, a.current());
        a.eatWhile(/[\w\$_]/);
        var d = a.current(),
            e = f.propertyIsEnumerable(d) && f[d];
        return e && "." != b.lastType ? l(e.type, e.style, d) : l("variable", "variable", d)
    }

    function n(a) {
        return function (b, c) {
            return i(b, a) || (c.tokenize = m), l("string", "string")
        }
    }

    function o(a, b) {
        for (var d, c = !1; d = a.next();) {
            if ("/" == d && c) {
                b.tokenize = m;
                break
            }
            c = "*" == d
        }
        return l("comment", "comment")
    }

    function q(a, b, c, d, e, f) {
        this.indented = a, this.column = b, this.type = c, this.prev = e, this.info = f, null != d && (this.align = d)
    }

    function r(a, b) {
        for (var c = a.localVars; c; c = c.next)
            if (c.name == b) return !0
    }

    function s(a, b, c, e, f) {
        var g = a.cc;
        for (t.state = a, t.stream = f, t.marked = null, t.cc = g, a.lexical.hasOwnProperty("align") || (a.lexical.align = !0);;) {
            var h = g.length ? g.pop() : d ? E : D;
            if (h(c, e)) {
                for (; g.length && g[g.length - 1].lex;) g.pop()();
                return t.marked ? t.marked : "variable" == c && r(a, e) ? "variable-2" : b
            }
        }
    }

    function u() {
        for (var a = arguments.length - 1; a >= 0; a--) t.cc.push(arguments[a])
    }

    function v() {
        return u.apply(null, arguments), !0
    }

    function w(a) {
        function b(b) {
            for (var c = b; c; c = c.next)
                if (c.name == a) return !0;
            return !1
        }
        var c = t.state;
        if (c.context) {
            if (t.marked = "def", b(c.localVars)) return;
            c.localVars = {
                name: a,
                next: c.localVars
            }
        } else {
            if (b(c.globalVars)) return;
            c.globalVars = {
                name: a,
                next: c.globalVars
            }
        }
    }

    function y() {
        t.state.context = {
            prev: t.state.context,
            vars: t.state.localVars
        }, t.state.localVars = x
    }

    function z() {
        t.state.localVars = t.state.context.vars, t.state.context = t.state.context.prev
    }

    function A(a, b) {
        var c = function () {
            var c = t.state;
            c.lexical = new q(c.indented, t.stream.column(), a, null, c.lexical, b)
        };
        return c.lex = !0, c
    }

    function B() {
        var a = t.state;
        a.lexical.prev && (")" == a.lexical.type && (a.indented = a.lexical.indented), a.lexical = a.lexical.prev)
    }

    function C(a) {
        return function (b) {
            return b == a ? v() : ";" == a ? u() : v(arguments.callee)
        }
    }

    function D(a) {
        return "var" == a ? v(A("vardef"), O, C(";"), B) : "keyword a" == a ? v(A("form"), E, D, B) : "keyword b" == a ? v(A("form"), D, B) : "{" == a ? v(A("}"), L, B) : ";" == a ? v() : "function" == a ? v(U) : "for" == a ? v(A("form"), C("("), A(")"), Q, C(")"), B, D, B) : "variable" == a ? v(A("stat"), H) : "switch" == a ? v(A("form"), E, A("}", "switch"), C("{"), L, B, B) : "case" == a ? v(E, C(":")) : "default" == a ? v(C(":")) : "catch" == a ? v(A("form"), y, C("("), V, C(")"), D, B, z) : u(A("stat"), E, C(";"), B)
    }

    function E(a) {
        return p.hasOwnProperty(a) ? v(G) : "function" == a ? v(U) : "keyword c" == a ? v(F) : "(" == a ? v(A(")"), F, C(")"), B, G) : "operator" == a ? v(E) : "[" == a ? v(A("]"), K(E, "]"), B, G) : "{" == a ? v(A("}"), K(J, "}"), B, G) : v()
    }

    function F(a) {
        return a.match(/[;\}\)\],]/) ? u() : u(E)
    }

    function G(a, b) {
        if ("operator" == a) return /\+\+|--/.test(b) ? v(G) : "?" == b ? v(E, C(":"), E) : v(E);
        if (";" != a) return "(" == a ? v(A(")"), K(E, ")"), B, G) : "." == a ? v(I, G) : "[" == a ? v(A("]"), E, C("]"), B, G) : void 0
    }

    function H(a) {
        return ":" == a ? v(B, D) : u(G, C(";"), B)
    }

    function I(a) {
        return "variable" == a ? (t.marked = "property", v()) : void 0
    }

    function J(a) {
        return "variable" == a ? t.marked = "property" : ("number" == a || "string" == a) && (t.marked = a + " property"), p.hasOwnProperty(a) ? v(C(":"), E) : void 0
    }

    function K(a, b) {
        function c(d) {
            return "," == d ? v(a, c) : d == b ? v() : v(C(b))
        }
        return function (d) {
            return d == b ? v() : u(a, c)
        }
    }

    function L(a) {
        return "}" == a ? v() : u(D, L)
    }

    function M(a) {
        return ":" == a ? v(N) : u()
    }

    function N(a) {
        return "variable" == a ? (t.marked = "variable-3", v()) : u()
    }

    function O(a, b) {
        return "variable" == a ? (w(b), e ? v(M, P) : v(P)) : u()
    }

    function P(a, b) {
        return "=" == b ? v(E, P) : "," == a ? v(O) : void 0
    }

    function Q(a) {
        return "var" == a ? v(O, C(";"), S) : ";" == a ? v(S) : "variable" == a ? v(R) : v(S)
    }

    function R(a, b) {
        return "in" == b ? v(E) : v(G, S)
    }

    function S(a, b) {
        return ";" == a ? v(T) : "in" == b ? v(E) : v(E, C(";"), T)
    }

    function T(a) {
        ")" != a && v(E)
    }

    function U(a, b) {
        return "variable" == a ? (w(b), v(U)) : "(" == a ? v(A(")"), y, K(V, ")"), B, D, z) : void 0
    }

    function V(a, b) {
        return "variable" == a ? (w(b), e ? v(M) : v()) : void 0
    }
    var j, k, c = a.indentUnit,
        d = b.json,
        e = b.typescript,
        f = function () {
            function a(a) {
                return {
                    type: a,
                    style: "keyword"
                }
            }
            var b = a("keyword a"),
                c = a("keyword b"),
                d = a("keyword c"),
                f = a("operator"),
                g = {
                    type: "atom",
                    style: "atom"
                }, h = {
                    "if": b,
                    "while": b,
                    "with": b,
                    "else": c,
                    "do": c,
                    "try": c,
                    "finally": c,
                    "return": d,
                    "break": d,
                    "continue": d,
                    "new": d,
                    "delete": d,
                    "throw": d,
                    "var": a("var"),
                    "const": a("var"),
                    let: a("var"),
                    "function": a("function"),
                    "catch": a("catch"),
                    "for": a("for"),
                    "switch": a("switch"),
                    "case": a("case"),
                    "default": a("default"),
                    "in": f,
                    "typeof": f,
                    "instanceof": f,
                    "true": g,
                    "false": g,
                    "null": g,
                    undefined: g,
                    NaN: g,
                    Infinity: g,
                    "this": a("this")
                };
            if (e) {
                var i = {
                    type: "variable",
                    style: "variable-3"
                }, j = {
                        "interface": a("interface"),
                        "class": a("class"),
                        "extends": a("extends"),
                        constructor: a("constructor"),
                        "public": a("public"),
                        "private": a("private"),
                        "protected": a("protected"),
                        "static": a("static"),
                        "super": a("super"),
                        string: i,
                        number: i,
                        bool: i,
                        any: i
                    };
                for (var k in j) h[k] = j[k]
            }
            return h
        }(),
        g = /[+\-*&%=<>!?|~^]/,
        p = {
            atom: !0,
            number: !0,
            variable: !0,
            string: !0,
            regexp: !0,
            "this": !0
        }, t = {
            state: null,
            column: null,
            marked: null,
            cc: null
        }, x = {
            name: "this",
            next: {
                name: "arguments"
            }
        };
    return B.lex = !0, {
        startState: function (a) {
            return {
                tokenize: m,
                lastType: null,
                cc: [],
                lexical: new q((a || 0) - c, 0, "block", !1),
                localVars: b.localVars,
                globalVars: b.globalVars,
                context: b.localVars && {
                    vars: b.localVars
                },
                indented: 0
            }
        },
        token: function (a, b) {
            if (a.sol() && (b.lexical.hasOwnProperty("align") || (b.lexical.align = !1), b.indented = a.indentation()), a.eatSpace()) return null;
            var c = b.tokenize(a, b);
            return "comment" == j ? c : (b.lastType = j, s(b, c, j, k, a))
        },
        indent: function (a, b) {
            if (a.tokenize == o) return CodeMirror.Pass;
            if (a.tokenize != m) return 0;
            var d = b && b.charAt(0),
                e = a.lexical;
            "stat" == e.type && "}" == d && (e = e.prev);
            var f = e.type,
                g = d == f;
            return "vardef" == f ? e.indented + ("operator" == a.lastType || "," == a.lastType ? 4 : 0) : "form" == f && "{" == d ? e.indented : "form" == f ? e.indented + c : "stat" == f ? e.indented + ("operator" == a.lastType || "," == a.lastType ? c : 0) : "switch" != e.info || g ? e.align ? e.column + (g ? 0 : 1) : e.indented + (g ? 0 : c) : e.indented + (/^(?:case|default)\b/.test(b) ? c : 2 * c)
        },
        electricChars: ":{}",
        jsonMode: d
    }
}), CodeMirror.defineMIME("text/javascript", "javascript"), CodeMirror.defineMIME("text/ecmascript", "javascript"), CodeMirror.defineMIME("application/javascript", "javascript"), CodeMirror.defineMIME("application/ecmascript", "javascript"), CodeMirror.defineMIME("application/json", {
    name: "javascript",
    json: !0
}), CodeMirror.defineMIME("text/typescript", {
    name: "javascript",
    typescript: !0
}), CodeMirror.defineMIME("application/typescript", {
    name: "javascript",
    typescript: !0
}), CodeMirror.defineMode("properties", function () {
    return {
        token: function (a, b) {
            var c = a.sol() || b.afterSection,
                d = a.eol();
            if (b.afterSection = !1, c && (b.nextMultiline ? (b.inMultiline = !0, b.nextMultiline = !1) : b.position = "def"), d && !b.nextMultiline && (b.inMultiline = !1, b.position = "def"), c)
                for (; a.eatSpace(););
            var e = a.next();
            return !c || "#" !== e && "!" !== e && ";" !== e ? c && "[" === e ? (b.afterSection = !0, a.skipTo("]"), a.eat("]"), "header") : "=" === e || ":" === e ? (b.position = "quote", null) : ("\\" === e && "quote" === b.position && "u" !== a.next() && (b.nextMultiline = !0), b.position) : (b.position = "comment", a.skipToEnd(), "comment")
        },
        startState: function () {
            return {
                position: "def",
                nextMultiline: !1,
                inMultiline: !1,
                afterSection: !1
            }
        }
    }
}), CodeMirror.defineMIME("text/x-properties", "properties"), CodeMirror.defineMIME("text/x-ini", "properties"), CodeMirror.defineMode("xml", function (a, b) {
    function h(a, b) {
        function c(c) {
            return b.tokenize = c, c(a, b)
        }
        var d = a.next();
        if ("<" == d) {
            if (a.eat("!")) return a.eat("[") ? a.match("CDATA[") ? c(k("atom", "]]>")) : null : a.match("--") ? c(k("comment", "-->")) : a.match("DOCTYPE", !0, !0) ? (a.eatWhile(/[\w\._\-]/), c(l(1))) : null;
            if (a.eat("?")) return a.eatWhile(/[\w\._\-]/), b.tokenize = k("meta", "?>"), "meta";
            var e = a.eat("/");
            f = "";
            for (var h; h = a.eat(/[^\s\u00a0=<>\"\'\/?]/);) f += h;
            return f ? (g = e ? "closeTag" : "openTag", b.tokenize = i, "tag") : "error"
        }
        if ("&" == d) {
            var j;
            return j = a.eat("#") ? a.eat("x") ? a.eatWhile(/[a-fA-F\d]/) && a.eat(";") : a.eatWhile(/[\d]/) && a.eat(";") : a.eatWhile(/[\w\.\-:]/) && a.eat(";"), j ? "atom" : "error"
        }
        return a.eatWhile(/[^&<]/), null
    }

    function i(a, b) {
        var c = a.next();
        return ">" == c || "/" == c && a.eat(">") ? (b.tokenize = h, g = ">" == c ? "endTag" : "selfcloseTag", "tag") : "=" == c ? (g = "equals", null) : /[\'\"]/.test(c) ? (b.tokenize = j(c), b.tokenize(a, b)) : (a.eatWhile(/[^\s\u00a0=<>\"\']/), "word")
    }

    function j(a) {
        return function (b, c) {
            for (; !b.eol();)
                if (b.next() == a) {
                    c.tokenize = i;
                    break
                }
            return "string"
        }
    }

    function k(a, b) {
        return function (c, d) {
            for (; !c.eol();) {
                if (c.match(b)) {
                    d.tokenize = h;
                    break
                }
                c.next()
            }
            return a
        }
    }

    function l(a) {
        return function (b, c) {
            for (var d; null != (d = b.next());) {
                if ("<" == d) return c.tokenize = l(a + 1), c.tokenize(b, c);
                if (">" == d) {
                    if (1 == a) {
                        c.tokenize = h;
                        break
                    }
                    return c.tokenize = l(a - 1), c.tokenize(b, c)
                }
            }
            return "meta"
        }
    }

    function o() {
        for (var a = arguments.length - 1; a >= 0; a--) m.cc.push(arguments[a])
    }

    function p() {
        return o.apply(null, arguments), !0
    }

    function q(a, b) {
        var c = d.doNotIndent.hasOwnProperty(a) || m.context && m.context.noIndent;
        m.context = {
            prev: m.context,
            tagName: a,
            indent: m.indented,
            startOfLine: b,
            noIndent: c
        }
    }

    function r() {
        m.context && (m.context = m.context.prev)
    }

    function s(a) {
        if ("openTag" == a) return m.tagName = f, p(w, t(m.startOfLine));
        if ("closeTag" == a) {
            var b = !1;
            return m.context ? m.context.tagName != f && (d.implicitlyClosed.hasOwnProperty(m.context.tagName.toLowerCase()) && r(), b = !m.context || m.context.tagName != f) : b = !0, b && (n = "error"), p(u(b))
        }
        return p()
    }

    function t(a) {
        return function (b) {
            var c = m.tagName;
            return m.tagName = null, "selfcloseTag" == b || "endTag" == b && d.autoSelfClosers.hasOwnProperty(c.toLowerCase()) ? (v(c.toLowerCase()), p()) : "endTag" == b ? (v(c.toLowerCase()), q(c, a), p()) : p()
        }
    }

    function u(a) {
        return function (b) {
            return a && (n = "error"), "endTag" == b ? (r(), p()) : (n = "error", p(arguments.callee))
        }
    }

    function v(a) {
        for (var b;;) {
            if (!m.context) return;
            if (b = m.context.tagName.toLowerCase(), !d.contextGrabbers.hasOwnProperty(b) || !d.contextGrabbers[b].hasOwnProperty(a)) return;
            r()
        }
    }

    function w(a) {
        return "word" == a ? (n = "attribute", p(x, w)) : "endTag" == a || "selfcloseTag" == a ? o() : (n = "error", p(w))
    }

    function x(a) {
        return "equals" == a ? p(y, w) : (d.allowMissing ? "word" == a && (n = "attribute") : n = "error", "endTag" == a || "selfcloseTag" == a ? o() : p())
    }

    function y(a) {
        return "string" == a ? p(z) : "word" == a && d.allowUnquoted ? (n = "string", p()) : (n = "error", "endTag" == a || "selfCloseTag" == a ? o() : p())
    }

    function z(a) {
        return "string" == a ? p(z) : o()
    }
    var f, g, m, n, c = a.indentUnit,
        d = b.htmlMode ? {
            autoSelfClosers: {
                area: !0,
                base: !0,
                br: !0,
                col: !0,
                command: !0,
                embed: !0,
                frame: !0,
                hr: !0,
                img: !0,
                input: !0,
                keygen: !0,
                link: !0,
                meta: !0,
                param: !0,
                source: !0,
                track: !0,
                wbr: !0
            },
            implicitlyClosed: {
                dd: !0,
                li: !0,
                optgroup: !0,
                option: !0,
                p: !0,
                rp: !0,
                rt: !0,
                tbody: !0,
                td: !0,
                tfoot: !0,
                th: !0,
                tr: !0
            },
            contextGrabbers: {
                dd: {
                    dd: !0,
                    dt: !0
                },
                dt: {
                    dd: !0,
                    dt: !0
                },
                li: {
                    li: !0
                },
                option: {
                    option: !0,
                    optgroup: !0
                },
                optgroup: {
                    optgroup: !0
                },
                p: {
                    address: !0,
                    article: !0,
                    aside: !0,
                    blockquote: !0,
                    dir: !0,
                    div: !0,
                    dl: !0,
                    fieldset: !0,
                    footer: !0,
                    form: !0,
                    h1: !0,
                    h2: !0,
                    h3: !0,
                    h4: !0,
                    h5: !0,
                    h6: !0,
                    header: !0,
                    hgroup: !0,
                    hr: !0,
                    menu: !0,
                    nav: !0,
                    ol: !0,
                    p: !0,
                    pre: !0,
                    section: !0,
                    table: !0,
                    ul: !0
                },
                rp: {
                    rp: !0,
                    rt: !0
                },
                rt: {
                    rp: !0,
                    rt: !0
                },
                tbody: {
                    tbody: !0,
                    tfoot: !0
                },
                td: {
                    td: !0,
                    th: !0
                },
                tfoot: {
                    tbody: !0
                },
                th: {
                    td: !0,
                    th: !0
                },
                thead: {
                    tbody: !0,
                    tfoot: !0
                },
                tr: {
                    tr: !0
                }
            },
            doNotIndent: {
                pre: !0
            },
            allowUnquoted: !0,
            allowMissing: !0
        } : {
            autoSelfClosers: {},
            implicitlyClosed: {},
            contextGrabbers: {},
            doNotIndent: {},
            allowUnquoted: !1,
            allowMissing: !1
        }, e = b.alignCDATA;
    return {
        startState: function () {
            return {
                tokenize: h,
                cc: [],
                indented: 0,
                startOfLine: !0,
                tagName: null,
                context: null
            }
        },
        token: function (a, b) {
            if (a.sol() && (b.startOfLine = !0, b.indented = a.indentation()), a.eatSpace()) return null;
            n = g = f = null;
            var c = b.tokenize(a, b);
            if (b.type = g, (c || g) && "comment" != c)
                for (m = b;;) {
                    var d = b.cc.pop() || s;
                    if (d(g || c)) break
                }
            return b.startOfLine = !1, n || c
        },
        indent: function (a, b, d) {
            var f = a.context;
            if (a.tokenize != i && a.tokenize != h || f && f.noIndent) return d ? d.match(/^(\s*)/)[0].length : 0;
            if (e && /<!\[CDATA\[/.test(b)) return 0;
            for (f && /^<\//.test(b) && (f = f.prev); f && !f.startOfLine;) f = f.prev;
            return f ? f.indent + c : 0
        },
        electricChars: "/",
        configuration: b.htmlMode ? "html" : "xml"
    }
}), CodeMirror.defineMIME("text/xml", "xml"), CodeMirror.defineMIME("application/xml", "xml"), CodeMirror.mimeModes.hasOwnProperty("text/html") || CodeMirror.defineMIME("text/html", {
    name: "xml",
    htmlMode: !0
}),
function () {
    "use strict";

    function c(c) {
        "_activeLine" in c && (c.removeLineClass(c._activeLine, "wrap", a), c.removeLineClass(c._activeLine, "background", b))
    }

    function d(d) {
        var e = d.getLineHandle(d.getCursor().line);
        d._activeLine != e && (c(d), d.addLineClass(e, "wrap", a), d.addLineClass(e, "background", b), d._activeLine = e)
    }
    var a = "CodeMirror-activeline",
        b = "CodeMirror-activeline-background";
    CodeMirror.defineOption("styleActiveLine", !1, function (a, b, e) {
        var f = e && e != CodeMirror.Init;
        b && !f ? (d(a), a.on("cursorActivity", d)) : !b && f && (a.off("cursorActivity", d), c(a), delete a._activeLine)
    })
}();