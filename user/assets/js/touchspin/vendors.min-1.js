/*! jQuery v3.2.1 | (c) JS Foundation and other contributors | jquery.org/license */
!(function (a, b) {
  "use strict";
  "object" == typeof module && "object" == typeof module.exports
    ? (module.exports = a.document
        ? b(a, !0)
        : function (a) {
            if (!a.document)
              throw new Error("jQuery requires a window with a document");
            return b(a);
          })
    : b(a);
})("undefined" != typeof window ? window : this, function (a, b) {
  "use strict";
  var c = [],
    d = a.document,
    e = Object.getPrototypeOf,
    f = c.slice,
    g = c.concat,
    h = c.push,
    i = c.indexOf,
    j = {},
    k = j.toString,
    l = j.hasOwnProperty,
    m = l.toString,
    n = m.call(Object),
    o = {};
  function p(a, b) {
    b = b || d;
    var c = b.createElement("script");
    (c.text = a), b.head.appendChild(c).parentNode.removeChild(c);
  }
  var q = "3.2.1",
    r = function (a, b) {
      return new r.fn.init(a, b);
    },
    s = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g,
    t = /^-ms-/,
    u = /-([a-z])/g,
    v = function (a, b) {
      return b.toUpperCase();
    };
  (r.fn = r.prototype =
    {
      jquery: q,
      constructor: r,
      length: 0,
      toArray: function () {
        return f.call(this);
      },
      get: function (a) {
        return null == a
          ? f.call(this)
          : a < 0
          ? this[a + this.length]
          : this[a];
      },
      pushStack: function (a) {
        var b = r.merge(this.constructor(), a);
        return (b.prevObject = this), b;
      },
      each: function (a) {
        return r.each(this, a);
      },
      map: function (a) {
        return this.pushStack(
          r.map(this, function (b, c) {
            return a.call(b, c, b);
          })
        );
      },
      slice: function () {
        return this.pushStack(f.apply(this, arguments));
      },
      first: function () {
        return this.eq(0);
      },
      last: function () {
        return this.eq(-1);
      },
      eq: function (a) {
        var b = this.length,
          c = +a + (a < 0 ? b : 0);
        return this.pushStack(c >= 0 && c < b ? [this[c]] : []);
      },
      end: function () {
        return this.prevObject || this.constructor();
      },
      push: h,
      sort: c.sort,
      splice: c.splice,
    }),
    (r.extend = r.fn.extend =
      function () {
        var a,
          b,
          c,
          d,
          e,
          f,
          g = arguments[0] || {},
          h = 1,
          i = arguments.length,
          j = !1;
        for (
          "boolean" == typeof g && ((j = g), (g = arguments[h] || {}), h++),
            "object" == typeof g || r.isFunction(g) || (g = {}),
            h === i && ((g = this), h--);
          h < i;
          h++
        )
          if (null != (a = arguments[h]))
            for (b in a)
              (c = g[b]),
                (d = a[b]),
                g !== d &&
                  (j && d && (r.isPlainObject(d) || (e = Array.isArray(d)))
                    ? (e
                        ? ((e = !1), (f = c && Array.isArray(c) ? c : []))
                        : (f = c && r.isPlainObject(c) ? c : {}),
                      (g[b] = r.extend(j, f, d)))
                    : void 0 !== d && (g[b] = d));
        return g;
      }),
    r.extend({
      expando: "jQuery" + (q + Math.random()).replace(/\D/g, ""),
      isReady: !0,
      error: function (a) {
        throw new Error(a);
      },
      noop: function () {},
      isFunction: function (a) {
        return "function" === r.type(a);
      },
      isWindow: function (a) {
        return null != a && a === a.window;
      },
      isNumeric: function (a) {
        var b = r.type(a);
        return ("number" === b || "string" === b) && !isNaN(a - parseFloat(a));
      },
      isPlainObject: function (a) {
        var b, c;
        return (
          !(!a || "[object Object]" !== k.call(a)) &&
          (!(b = e(a)) ||
            ((c = l.call(b, "constructor") && b.constructor),
            "function" == typeof c && m.call(c) === n))
        );
      },
      isEmptyObject: function (a) {
        var b;
        for (b in a) return !1;
        return !0;
      },
      type: function (a) {
        return null == a
          ? a + ""
          : "object" == typeof a || "function" == typeof a
          ? j[k.call(a)] || "object"
          : typeof a;
      },
      globalEval: function (a) {
        p(a);
      },
      camelCase: function (a) {
        return a.replace(t, "ms-").replace(u, v);
      },
      each: function (a, b) {
        var c,
          d = 0;
        if (w(a)) {
          for (c = a.length; d < c; d++)
            if (b.call(a[d], d, a[d]) === !1) break;
        } else for (d in a) if (b.call(a[d], d, a[d]) === !1) break;
        return a;
      },
      trim: function (a) {
        return null == a ? "" : (a + "").replace(s, "");
      },
      makeArray: function (a, b) {
        var c = b || [];
        return (
          null != a &&
            (w(Object(a))
              ? r.merge(c, "string" == typeof a ? [a] : a)
              : h.call(c, a)),
          c
        );
      },
      inArray: function (a, b, c) {
        return null == b ? -1 : i.call(b, a, c);
      },
      merge: function (a, b) {
        for (var c = +b.length, d = 0, e = a.length; d < c; d++) a[e++] = b[d];
        return (a.length = e), a;
      },
      grep: function (a, b, c) {
        for (var d, e = [], f = 0, g = a.length, h = !c; f < g; f++)
          (d = !b(a[f], f)), d !== h && e.push(a[f]);
        return e;
      },
      map: function (a, b, c) {
        var d,
          e,
          f = 0,
          h = [];
        if (w(a))
          for (d = a.length; f < d; f++)
            (e = b(a[f], f, c)), null != e && h.push(e);
        else for (f in a) (e = b(a[f], f, c)), null != e && h.push(e);
        return g.apply([], h);
      },
      guid: 1,
      proxy: function (a, b) {
        var c, d, e;
        if (
          ("string" == typeof b && ((c = a[b]), (b = a), (a = c)),
          r.isFunction(a))
        )
          return (
            (d = f.call(arguments, 2)),
            (e = function () {
              return a.apply(b || this, d.concat(f.call(arguments)));
            }),
            (e.guid = a.guid = a.guid || r.guid++),
            e
          );
      },
      now: Date.now,
      support: o,
    }),
    "function" == typeof Symbol && (r.fn[Symbol.iterator] = c[Symbol.iterator]),
    r.each(
      "Boolean Number String Function Array Date RegExp Object Error Symbol".split(
        " "
      ),
      function (a, b) {
        j["[object " + b + "]"] = b.toLowerCase();
      }
    );
  function w(a) {
    var b = !!a && "length" in a && a.length,
      c = r.type(a);
    return (
      "function" !== c &&
      !r.isWindow(a) &&
      ("array" === c ||
        0 === b ||
        ("number" == typeof b && b > 0 && b - 1 in a))
    );
  }
  var x = (function (a) {
    var b,
      c,
      d,
      e,
      f,
      g,
      h,
      i,
      j,
      k,
      l,
      m,
      n,
      o,
      p,
      q,
      r,
      s,
      t,
      u = "sizzle" + 1 * new Date(),
      v = a.document,
      w = 0,
      x = 0,
      y = ha(),
      z = ha(),
      A = ha(),
      B = function (a, b) {
        return a === b && (l = !0), 0;
      },
      C = {}.hasOwnProperty,
      D = [],
      E = D.pop,
      F = D.push,
      G = D.push,
      H = D.slice,
      I = function (a, b) {
        for (var c = 0, d = a.length; c < d; c++) if (a[c] === b) return c;
        return -1;
      },
      J =
        "checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped",
      K = "[\\x20\\t\\r\\n\\f]",
      L = "(?:\\\\.|[\\w-]|[^\0-\\xa0])+",
      M =
        "\\[" +
        K +
        "*(" +
        L +
        ")(?:" +
        K +
        "*([*^$|!~]?=)" +
        K +
        "*(?:'((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\"|(" +
        L +
        "))|)" +
        K +
        "*\\]",
      N =
        ":(" +
        L +
        ")(?:\\((('((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\")|((?:\\\\.|[^\\\\()[\\]]|" +
        M +
        ")*)|.*)\\)|)",
      O = new RegExp(K + "+", "g"),
      P = new RegExp("^" + K + "+|((?:^|[^\\\\])(?:\\\\.)*)" + K + "+$", "g"),
      Q = new RegExp("^" + K + "*," + K + "*"),
      R = new RegExp("^" + K + "*([>+~]|" + K + ")" + K + "*"),
      S = new RegExp("=" + K + "*([^\\]'\"]*?)" + K + "*\\]", "g"),
      T = new RegExp(N),
      U = new RegExp("^" + L + "$"),
      V = {
        ID: new RegExp("^#(" + L + ")"),
        CLASS: new RegExp("^\\.(" + L + ")"),
        TAG: new RegExp("^(" + L + "|[*])"),
        ATTR: new RegExp("^" + M),
        PSEUDO: new RegExp("^" + N),
        CHILD: new RegExp(
          "^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\(" +
            K +
            "*(even|odd|(([+-]|)(\\d*)n|)" +
            K +
            "*(?:([+-]|)" +
            K +
            "*(\\d+)|))" +
            K +
            "*\\)|)",
          "i"
        ),
        bool: new RegExp("^(?:" + J + ")$", "i"),
        needsContext: new RegExp(
          "^" +
            K +
            "*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\(" +
            K +
            "*((?:-\\d)?\\d*)" +
            K +
            "*\\)|)(?=[^-]|$)",
          "i"
        ),
      },
      W = /^(?:input|select|textarea|button)$/i,
      X = /^h\d$/i,
      Y = /^[^{]+\{\s*\[native \w/,
      Z = /^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/,
      $ = /[+~]/,
      _ = new RegExp("\\\\([\\da-f]{1,6}" + K + "?|(" + K + ")|.)", "ig"),
      aa = function (a, b, c) {
        var d = "0x" + b - 65536;
        return d !== d || c
          ? b
          : d < 0
          ? String.fromCharCode(d + 65536)
          : String.fromCharCode((d >> 10) | 55296, (1023 & d) | 56320);
      },
      ba = /([\0-\x1f\x7f]|^-?\d)|^-$|[^\0-\x1f\x7f-\uFFFF\w-]/g,
      ca = function (a, b) {
        return b
          ? "\0" === a
            ? "\ufffd"
            : a.slice(0, -1) +
              "\\" +
              a.charCodeAt(a.length - 1).toString(16) +
              " "
          : "\\" + a;
      },
      da = function () {
        m();
      },
      ea = ta(
        function (a) {
          return a.disabled === !0 && ("form" in a || "label" in a);
        },
        { dir: "parentNode", next: "legend" }
      );
    try {
      G.apply((D = H.call(v.childNodes)), v.childNodes),
        D[v.childNodes.length].nodeType;
    } catch (fa) {
      G = {
        apply: D.length
          ? function (a, b) {
              F.apply(a, H.call(b));
            }
          : function (a, b) {
              var c = a.length,
                d = 0;
              while ((a[c++] = b[d++]));
              a.length = c - 1;
            },
      };
    }
    function ga(a, b, d, e) {
      var f,
        h,
        j,
        k,
        l,
        o,
        r,
        s = b && b.ownerDocument,
        w = b ? b.nodeType : 9;
      if (
        ((d = d || []),
        "string" != typeof a || !a || (1 !== w && 9 !== w && 11 !== w))
      )
        return d;
      if (
        !e &&
        ((b ? b.ownerDocument || b : v) !== n && m(b), (b = b || n), p)
      ) {
        if (11 !== w && (l = Z.exec(a)))
          if ((f = l[1])) {
            if (9 === w) {
              if (!(j = b.getElementById(f))) return d;
              if (j.id === f) return d.push(j), d;
            } else if (s && (j = s.getElementById(f)) && t(b, j) && j.id === f)
              return d.push(j), d;
          } else {
            if (l[2]) return G.apply(d, b.getElementsByTagName(a)), d;
            if (
              (f = l[3]) &&
              c.getElementsByClassName &&
              b.getElementsByClassName
            )
              return G.apply(d, b.getElementsByClassName(f)), d;
          }
        if (c.qsa && !A[a + " "] && (!q || !q.test(a))) {
          if (1 !== w) (s = b), (r = a);
          else if ("object" !== b.nodeName.toLowerCase()) {
            (k = b.getAttribute("id"))
              ? (k = k.replace(ba, ca))
              : b.setAttribute("id", (k = u)),
              (o = g(a)),
              (h = o.length);
            while (h--) o[h] = "#" + k + " " + sa(o[h]);
            (r = o.join(",")), (s = ($.test(a) && qa(b.parentNode)) || b);
          }
          if (r)
            try {
              return G.apply(d, s.querySelectorAll(r)), d;
            } catch (x) {
            } finally {
              k === u && b.removeAttribute("id");
            }
        }
      }
      return i(a.replace(P, "$1"), b, d, e);
    }
    function ha() {
      var a = [];
      function b(c, e) {
        return (
          a.push(c + " ") > d.cacheLength && delete b[a.shift()],
          (b[c + " "] = e)
        );
      }
      return b;
    }
    function ia(a) {
      return (a[u] = !0), a;
    }
    function ja(a) {
      var b = n.createElement("fieldset");
      try {
        return !!a(b);
      } catch (c) {
        return !1;
      } finally {
        b.parentNode && b.parentNode.removeChild(b), (b = null);
      }
    }
    function ka(a, b) {
      var c = a.split("|"),
        e = c.length;
      while (e--) d.attrHandle[c[e]] = b;
    }
    function la(a, b) {
      var c = b && a,
        d =
          c &&
          1 === a.nodeType &&
          1 === b.nodeType &&
          a.sourceIndex - b.sourceIndex;
      if (d) return d;
      if (c) while ((c = c.nextSibling)) if (c === b) return -1;
      return a ? 1 : -1;
    }
    function ma(a) {
      return function (b) {
        var c = b.nodeName.toLowerCase();
        return "input" === c && b.type === a;
      };
    }
    function na(a) {
      return function (b) {
        var c = b.nodeName.toLowerCase();
        return ("input" === c || "button" === c) && b.type === a;
      };
    }
    function oa(a) {
      return function (b) {
        return "form" in b
          ? b.parentNode && b.disabled === !1
            ? "label" in b
              ? "label" in b.parentNode
                ? b.parentNode.disabled === a
                : b.disabled === a
              : b.isDisabled === a || (b.isDisabled !== !a && ea(b) === a)
            : b.disabled === a
          : "label" in b && b.disabled === a;
      };
    }
    function pa(a) {
      return ia(function (b) {
        return (
          (b = +b),
          ia(function (c, d) {
            var e,
              f = a([], c.length, b),
              g = f.length;
            while (g--) c[(e = f[g])] && (c[e] = !(d[e] = c[e]));
          })
        );
      });
    }
    function qa(a) {
      return a && "undefined" != typeof a.getElementsByTagName && a;
    }
    (c = ga.support = {}),
      (f = ga.isXML =
        function (a) {
          var b = a && (a.ownerDocument || a).documentElement;
          return !!b && "HTML" !== b.nodeName;
        }),
      (m = ga.setDocument =
        function (a) {
          var b,
            e,
            g = a ? a.ownerDocument || a : v;
          return g !== n && 9 === g.nodeType && g.documentElement
            ? ((n = g),
              (o = n.documentElement),
              (p = !f(n)),
              v !== n &&
                (e = n.defaultView) &&
                e.top !== e &&
                (e.addEventListener
                  ? e.addEventListener("unload", da, !1)
                  : e.attachEvent && e.attachEvent("onunload", da)),
              (c.attributes = ja(function (a) {
                return (a.className = "i"), !a.getAttribute("className");
              })),
              (c.getElementsByTagName = ja(function (a) {
                return (
                  a.appendChild(n.createComment("")),
                  !a.getElementsByTagName("*").length
                );
              })),
              (c.getElementsByClassName = Y.test(n.getElementsByClassName)),
              (c.getById = ja(function (a) {
                return (
                  (o.appendChild(a).id = u),
                  !n.getElementsByName || !n.getElementsByName(u).length
                );
              })),
              c.getById
                ? ((d.filter.ID = function (a) {
                    var b = a.replace(_, aa);
                    return function (a) {
                      return a.getAttribute("id") === b;
                    };
                  }),
                  (d.find.ID = function (a, b) {
                    if ("undefined" != typeof b.getElementById && p) {
                      var c = b.getElementById(a);
                      return c ? [c] : [];
                    }
                  }))
                : ((d.filter.ID = function (a) {
                    var b = a.replace(_, aa);
                    return function (a) {
                      var c =
                        "undefined" != typeof a.getAttributeNode &&
                        a.getAttributeNode("id");
                      return c && c.value === b;
                    };
                  }),
                  (d.find.ID = function (a, b) {
                    if ("undefined" != typeof b.getElementById && p) {
                      var c,
                        d,
                        e,
                        f = b.getElementById(a);
                      if (f) {
                        if (
                          ((c = f.getAttributeNode("id")), c && c.value === a)
                        )
                          return [f];
                        (e = b.getElementsByName(a)), (d = 0);
                        while ((f = e[d++]))
                          if (
                            ((c = f.getAttributeNode("id")), c && c.value === a)
                          )
                            return [f];
                      }
                      return [];
                    }
                  })),
              (d.find.TAG = c.getElementsByTagName
                ? function (a, b) {
                    return "undefined" != typeof b.getElementsByTagName
                      ? b.getElementsByTagName(a)
                      : c.qsa
                      ? b.querySelectorAll(a)
                      : void 0;
                  }
                : function (a, b) {
                    var c,
                      d = [],
                      e = 0,
                      f = b.getElementsByTagName(a);
                    if ("*" === a) {
                      while ((c = f[e++])) 1 === c.nodeType && d.push(c);
                      return d;
                    }
                    return f;
                  }),
              (d.find.CLASS =
                c.getElementsByClassName &&
                function (a, b) {
                  if ("undefined" != typeof b.getElementsByClassName && p)
                    return b.getElementsByClassName(a);
                }),
              (r = []),
              (q = []),
              (c.qsa = Y.test(n.querySelectorAll)) &&
                (ja(function (a) {
                  (o.appendChild(a).innerHTML =
                    "<a id='" +
                    u +
                    "'></a><select id='" +
                    u +
                    "-\r\\' msallowcapture=''><option selected=''></option></select>"),
                    a.querySelectorAll("[msallowcapture^='']").length &&
                      q.push("[*^$]=" + K + "*(?:''|\"\")"),
                    a.querySelectorAll("[selected]").length ||
                      q.push("\\[" + K + "*(?:value|" + J + ")"),
                    a.querySelectorAll("[id~=" + u + "-]").length ||
                      q.push("~="),
                    a.querySelectorAll(":checked").length || q.push(":checked"),
                    a.querySelectorAll("a#" + u + "+*").length ||
                      q.push(".#.+[+~]");
                }),
                ja(function (a) {
                  a.innerHTML =
                    "<a href='' disabled='disabled'></a><select disabled='disabled'><option/></select>";
                  var b = n.createElement("input");
                  b.setAttribute("type", "hidden"),
                    a.appendChild(b).setAttribute("name", "D"),
                    a.querySelectorAll("[name=d]").length &&
                      q.push("name" + K + "*[*^$|!~]?="),
                    2 !== a.querySelectorAll(":enabled").length &&
                      q.push(":enabled", ":disabled"),
                    (o.appendChild(a).disabled = !0),
                    2 !== a.querySelectorAll(":disabled").length &&
                      q.push(":enabled", ":disabled"),
                    a.querySelectorAll("*,:x"),
                    q.push(",.*:");
                })),
              (c.matchesSelector = Y.test(
                (s =
                  o.matches ||
                  o.webkitMatchesSelector ||
                  o.mozMatchesSelector ||
                  o.oMatchesSelector ||
                  o.msMatchesSelector)
              )) &&
                ja(function (a) {
                  (c.disconnectedMatch = s.call(a, "*")),
                    s.call(a, "[s!='']:x"),
                    r.push("!=", N);
                }),
              (q = q.length && new RegExp(q.join("|"))),
              (r = r.length && new RegExp(r.join("|"))),
              (b = Y.test(o.compareDocumentPosition)),
              (t =
                b || Y.test(o.contains)
                  ? function (a, b) {
                      var c = 9 === a.nodeType ? a.documentElement : a,
                        d = b && b.parentNode;
                      return (
                        a === d ||
                        !(
                          !d ||
                          1 !== d.nodeType ||
                          !(c.contains
                            ? c.contains(d)
                            : a.compareDocumentPosition &&
                              16 & a.compareDocumentPosition(d))
                        )
                      );
                    }
                  : function (a, b) {
                      if (b) while ((b = b.parentNode)) if (b === a) return !0;
                      return !1;
                    }),
              (B = b
                ? function (a, b) {
                    if (a === b) return (l = !0), 0;
                    var d =
                      !a.compareDocumentPosition - !b.compareDocumentPosition;
                    return d
                      ? d
                      : ((d =
                          (a.ownerDocument || a) === (b.ownerDocument || b)
                            ? a.compareDocumentPosition(b)
                            : 1),
                        1 & d ||
                        (!c.sortDetached && b.compareDocumentPosition(a) === d)
                          ? a === n || (a.ownerDocument === v && t(v, a))
                            ? -1
                            : b === n || (b.ownerDocument === v && t(v, b))
                            ? 1
                            : k
                            ? I(k, a) - I(k, b)
                            : 0
                          : 4 & d
                          ? -1
                          : 1);
                  }
                : function (a, b) {
                    if (a === b) return (l = !0), 0;
                    var c,
                      d = 0,
                      e = a.parentNode,
                      f = b.parentNode,
                      g = [a],
                      h = [b];
                    if (!e || !f)
                      return a === n
                        ? -1
                        : b === n
                        ? 1
                        : e
                        ? -1
                        : f
                        ? 1
                        : k
                        ? I(k, a) - I(k, b)
                        : 0;
                    if (e === f) return la(a, b);
                    c = a;
                    while ((c = c.parentNode)) g.unshift(c);
                    c = b;
                    while ((c = c.parentNode)) h.unshift(c);
                    while (g[d] === h[d]) d++;
                    return d
                      ? la(g[d], h[d])
                      : g[d] === v
                      ? -1
                      : h[d] === v
                      ? 1
                      : 0;
                  }),
              n)
            : n;
        }),
      (ga.matches = function (a, b) {
        return ga(a, null, null, b);
      }),
      (ga.matchesSelector = function (a, b) {
        if (
          ((a.ownerDocument || a) !== n && m(a),
          (b = b.replace(S, "='$1']")),
          c.matchesSelector &&
            p &&
            !A[b + " "] &&
            (!r || !r.test(b)) &&
            (!q || !q.test(b)))
        )
          try {
            var d = s.call(a, b);
            if (
              d ||
              c.disconnectedMatch ||
              (a.document && 11 !== a.document.nodeType)
            )
              return d;
          } catch (e) {}
        return ga(b, n, null, [a]).length > 0;
      }),
      (ga.contains = function (a, b) {
        return (a.ownerDocument || a) !== n && m(a), t(a, b);
      }),
      (ga.attr = function (a, b) {
        (a.ownerDocument || a) !== n && m(a);
        var e = d.attrHandle[b.toLowerCase()],
          f = e && C.call(d.attrHandle, b.toLowerCase()) ? e(a, b, !p) : void 0;
        return void 0 !== f
          ? f
          : c.attributes || !p
          ? a.getAttribute(b)
          : (f = a.getAttributeNode(b)) && f.specified
          ? f.value
          : null;
      }),
      (ga.escape = function (a) {
        return (a + "").replace(ba, ca);
      }),
      (ga.error = function (a) {
        throw new Error("Syntax error, unrecognized expression: " + a);
      }),
      (ga.uniqueSort = function (a) {
        var b,
          d = [],
          e = 0,
          f = 0;
        if (
          ((l = !c.detectDuplicates),
          (k = !c.sortStable && a.slice(0)),
          a.sort(B),
          l)
        ) {
          while ((b = a[f++])) b === a[f] && (e = d.push(f));
          while (e--) a.splice(d[e], 1);
        }
        return (k = null), a;
      }),
      (e = ga.getText =
        function (a) {
          var b,
            c = "",
            d = 0,
            f = a.nodeType;
          if (f) {
            if (1 === f || 9 === f || 11 === f) {
              if ("string" == typeof a.textContent) return a.textContent;
              for (a = a.firstChild; a; a = a.nextSibling) c += e(a);
            } else if (3 === f || 4 === f) return a.nodeValue;
          } else while ((b = a[d++])) c += e(b);
          return c;
        }),
      (d = ga.selectors =
        {
          cacheLength: 50,
          createPseudo: ia,
          match: V,
          attrHandle: {},
          find: {},
          relative: {
            ">": { dir: "parentNode", first: !0 },
            " ": { dir: "parentNode" },
            "+": { dir: "previousSibling", first: !0 },
            "~": { dir: "previousSibling" },
          },
          preFilter: {
            ATTR: function (a) {
              return (
                (a[1] = a[1].replace(_, aa)),
                (a[3] = (a[3] || a[4] || a[5] || "").replace(_, aa)),
                "~=" === a[2] && (a[3] = " " + a[3] + " "),
                a.slice(0, 4)
              );
            },
            CHILD: function (a) {
              return (
                (a[1] = a[1].toLowerCase()),
                "nth" === a[1].slice(0, 3)
                  ? (a[3] || ga.error(a[0]),
                    (a[4] = +(a[4]
                      ? a[5] + (a[6] || 1)
                      : 2 * ("even" === a[3] || "odd" === a[3]))),
                    (a[5] = +(a[7] + a[8] || "odd" === a[3])))
                  : a[3] && ga.error(a[0]),
                a
              );
            },
            PSEUDO: function (a) {
              var b,
                c = !a[6] && a[2];
              return V.CHILD.test(a[0])
                ? null
                : (a[3]
                    ? (a[2] = a[4] || a[5] || "")
                    : c &&
                      T.test(c) &&
                      (b = g(c, !0)) &&
                      (b = c.indexOf(")", c.length - b) - c.length) &&
                      ((a[0] = a[0].slice(0, b)), (a[2] = c.slice(0, b))),
                  a.slice(0, 3));
            },
          },
          filter: {
            TAG: function (a) {
              var b = a.replace(_, aa).toLowerCase();
              return "*" === a
                ? function () {
                    return !0;
                  }
                : function (a) {
                    return a.nodeName && a.nodeName.toLowerCase() === b;
                  };
            },
            CLASS: function (a) {
              var b = y[a + " "];
              return (
                b ||
                ((b = new RegExp("(^|" + K + ")" + a + "(" + K + "|$)")) &&
                  y(a, function (a) {
                    return b.test(
                      ("string" == typeof a.className && a.className) ||
                        ("undefined" != typeof a.getAttribute &&
                          a.getAttribute("class")) ||
                        ""
                    );
                  }))
              );
            },
            ATTR: function (a, b, c) {
              return function (d) {
                var e = ga.attr(d, a);
                return null == e
                  ? "!=" === b
                  : !b ||
                      ((e += ""),
                      "=" === b
                        ? e === c
                        : "!=" === b
                        ? e !== c
                        : "^=" === b
                        ? c && 0 === e.indexOf(c)
                        : "*=" === b
                        ? c && e.indexOf(c) > -1
                        : "$=" === b
                        ? c && e.slice(-c.length) === c
                        : "~=" === b
                        ? (" " + e.replace(O, " ") + " ").indexOf(c) > -1
                        : "|=" === b &&
                          (e === c || e.slice(0, c.length + 1) === c + "-"));
              };
            },
            CHILD: function (a, b, c, d, e) {
              var f = "nth" !== a.slice(0, 3),
                g = "last" !== a.slice(-4),
                h = "of-type" === b;
              return 1 === d && 0 === e
                ? function (a) {
                    return !!a.parentNode;
                  }
                : function (b, c, i) {
                    var j,
                      k,
                      l,
                      m,
                      n,
                      o,
                      p = f !== g ? "nextSibling" : "previousSibling",
                      q = b.parentNode,
                      r = h && b.nodeName.toLowerCase(),
                      s = !i && !h,
                      t = !1;
                    if (q) {
                      if (f) {
                        while (p) {
                          m = b;
                          while ((m = m[p]))
                            if (
                              h
                                ? m.nodeName.toLowerCase() === r
                                : 1 === m.nodeType
                            )
                              return !1;
                          o = p = "only" === a && !o && "nextSibling";
                        }
                        return !0;
                      }
                      if (((o = [g ? q.firstChild : q.lastChild]), g && s)) {
                        (m = q),
                          (l = m[u] || (m[u] = {})),
                          (k = l[m.uniqueID] || (l[m.uniqueID] = {})),
                          (j = k[a] || []),
                          (n = j[0] === w && j[1]),
                          (t = n && j[2]),
                          (m = n && q.childNodes[n]);
                        while (
                          (m = (++n && m && m[p]) || (t = n = 0) || o.pop())
                        )
                          if (1 === m.nodeType && ++t && m === b) {
                            k[a] = [w, n, t];
                            break;
                          }
                      } else if (
                        (s &&
                          ((m = b),
                          (l = m[u] || (m[u] = {})),
                          (k = l[m.uniqueID] || (l[m.uniqueID] = {})),
                          (j = k[a] || []),
                          (n = j[0] === w && j[1]),
                          (t = n)),
                        t === !1)
                      )
                        while (
                          (m = (++n && m && m[p]) || (t = n = 0) || o.pop())
                        )
                          if (
                            (h
                              ? m.nodeName.toLowerCase() === r
                              : 1 === m.nodeType) &&
                            ++t &&
                            (s &&
                              ((l = m[u] || (m[u] = {})),
                              (k = l[m.uniqueID] || (l[m.uniqueID] = {})),
                              (k[a] = [w, t])),
                            m === b)
                          )
                            break;
                      return (t -= e), t === d || (t % d === 0 && t / d >= 0);
                    }
                  };
            },
            PSEUDO: function (a, b) {
              var c,
                e =
                  d.pseudos[a] ||
                  d.setFilters[a.toLowerCase()] ||
                  ga.error("unsupported pseudo: " + a);
              return e[u]
                ? e(b)
                : e.length > 1
                ? ((c = [a, a, "", b]),
                  d.setFilters.hasOwnProperty(a.toLowerCase())
                    ? ia(function (a, c) {
                        var d,
                          f = e(a, b),
                          g = f.length;
                        while (g--) (d = I(a, f[g])), (a[d] = !(c[d] = f[g]));
                      })
                    : function (a) {
                        return e(a, 0, c);
                      })
                : e;
            },
          },
          pseudos: {
            not: ia(function (a) {
              var b = [],
                c = [],
                d = h(a.replace(P, "$1"));
              return d[u]
                ? ia(function (a, b, c, e) {
                    var f,
                      g = d(a, null, e, []),
                      h = a.length;
                    while (h--) (f = g[h]) && (a[h] = !(b[h] = f));
                  })
                : function (a, e, f) {
                    return (
                      (b[0] = a), d(b, null, f, c), (b[0] = null), !c.pop()
                    );
                  };
            }),
            has: ia(function (a) {
              return function (b) {
                return ga(a, b).length > 0;
              };
            }),
            contains: ia(function (a) {
              return (
                (a = a.replace(_, aa)),
                function (b) {
                  return (b.textContent || b.innerText || e(b)).indexOf(a) > -1;
                }
              );
            }),
            lang: ia(function (a) {
              return (
                U.test(a || "") || ga.error("unsupported lang: " + a),
                (a = a.replace(_, aa).toLowerCase()),
                function (b) {
                  var c;
                  do
                    if (
                      (c = p
                        ? b.lang
                        : b.getAttribute("xml:lang") || b.getAttribute("lang"))
                    )
                      return (
                        (c = c.toLowerCase()),
                        c === a || 0 === c.indexOf(a + "-")
                      );
                  while ((b = b.parentNode) && 1 === b.nodeType);
                  return !1;
                }
              );
            }),
            target: function (b) {
              var c = a.location && a.location.hash;
              return c && c.slice(1) === b.id;
            },
            root: function (a) {
              return a === o;
            },
            focus: function (a) {
              return (
                a === n.activeElement &&
                (!n.hasFocus || n.hasFocus()) &&
                !!(a.type || a.href || ~a.tabIndex)
              );
            },
            enabled: oa(!1),
            disabled: oa(!0),
            checked: function (a) {
              var b = a.nodeName.toLowerCase();
              return (
                ("input" === b && !!a.checked) ||
                ("option" === b && !!a.selected)
              );
            },
            selected: function (a) {
              return (
                a.parentNode && a.parentNode.selectedIndex, a.selected === !0
              );
            },
            empty: function (a) {
              for (a = a.firstChild; a; a = a.nextSibling)
                if (a.nodeType < 6) return !1;
              return !0;
            },
            parent: function (a) {
              return !d.pseudos.empty(a);
            },
            header: function (a) {
              return X.test(a.nodeName);
            },
            input: function (a) {
              return W.test(a.nodeName);
            },
            button: function (a) {
              var b = a.nodeName.toLowerCase();
              return ("input" === b && "button" === a.type) || "button" === b;
            },
            text: function (a) {
              var b;
              return (
                "input" === a.nodeName.toLowerCase() &&
                "text" === a.type &&
                (null == (b = a.getAttribute("type")) ||
                  "text" === b.toLowerCase())
              );
            },
            first: pa(function () {
              return [0];
            }),
            last: pa(function (a, b) {
              return [b - 1];
            }),
            eq: pa(function (a, b, c) {
              return [c < 0 ? c + b : c];
            }),
            even: pa(function (a, b) {
              for (var c = 0; c < b; c += 2) a.push(c);
              return a;
            }),
            odd: pa(function (a, b) {
              for (var c = 1; c < b; c += 2) a.push(c);
              return a;
            }),
            lt: pa(function (a, b, c) {
              for (var d = c < 0 ? c + b : c; --d >= 0; ) a.push(d);
              return a;
            }),
            gt: pa(function (a, b, c) {
              for (var d = c < 0 ? c + b : c; ++d < b; ) a.push(d);
              return a;
            }),
          },
        }),
      (d.pseudos.nth = d.pseudos.eq);
    for (b in { radio: !0, checkbox: !0, file: !0, password: !0, image: !0 })
      d.pseudos[b] = ma(b);
    for (b in { submit: !0, reset: !0 }) d.pseudos[b] = na(b);
    function ra() {}
    (ra.prototype = d.filters = d.pseudos),
      (d.setFilters = new ra()),
      (g = ga.tokenize =
        function (a, b) {
          var c,
            e,
            f,
            g,
            h,
            i,
            j,
            k = z[a + " "];
          if (k) return b ? 0 : k.slice(0);
          (h = a), (i = []), (j = d.preFilter);
          while (h) {
            (c && !(e = Q.exec(h))) ||
              (e && (h = h.slice(e[0].length) || h), i.push((f = []))),
              (c = !1),
              (e = R.exec(h)) &&
                ((c = e.shift()),
                f.push({ value: c, type: e[0].replace(P, " ") }),
                (h = h.slice(c.length)));
            for (g in d.filter)
              !(e = V[g].exec(h)) ||
                (j[g] && !(e = j[g](e))) ||
                ((c = e.shift()),
                f.push({ value: c, type: g, matches: e }),
                (h = h.slice(c.length)));
            if (!c) break;
          }
          return b ? h.length : h ? ga.error(a) : z(a, i).slice(0);
        });
    function sa(a) {
      for (var b = 0, c = a.length, d = ""; b < c; b++) d += a[b].value;
      return d;
    }
    function ta(a, b, c) {
      var d = b.dir,
        e = b.next,
        f = e || d,
        g = c && "parentNode" === f,
        h = x++;
      return b.first
        ? function (b, c, e) {
            while ((b = b[d])) if (1 === b.nodeType || g) return a(b, c, e);
            return !1;
          }
        : function (b, c, i) {
            var j,
              k,
              l,
              m = [w, h];
            if (i) {
              while ((b = b[d]))
                if ((1 === b.nodeType || g) && a(b, c, i)) return !0;
            } else
              while ((b = b[d]))
                if (1 === b.nodeType || g)
                  if (
                    ((l = b[u] || (b[u] = {})),
                    (k = l[b.uniqueID] || (l[b.uniqueID] = {})),
                    e && e === b.nodeName.toLowerCase())
                  )
                    b = b[d] || b;
                  else {
                    if ((j = k[f]) && j[0] === w && j[1] === h)
                      return (m[2] = j[2]);
                    if (((k[f] = m), (m[2] = a(b, c, i)))) return !0;
                  }
            return !1;
          };
    }
    function ua(a) {
      return a.length > 1
        ? function (b, c, d) {
            var e = a.length;
            while (e--) if (!a[e](b, c, d)) return !1;
            return !0;
          }
        : a[0];
    }
    function va(a, b, c) {
      for (var d = 0, e = b.length; d < e; d++) ga(a, b[d], c);
      return c;
    }
    function wa(a, b, c, d, e) {
      for (var f, g = [], h = 0, i = a.length, j = null != b; h < i; h++)
        (f = a[h]) && ((c && !c(f, d, e)) || (g.push(f), j && b.push(h)));
      return g;
    }
    function xa(a, b, c, d, e, f) {
      return (
        d && !d[u] && (d = xa(d)),
        e && !e[u] && (e = xa(e, f)),
        ia(function (f, g, h, i) {
          var j,
            k,
            l,
            m = [],
            n = [],
            o = g.length,
            p = f || va(b || "*", h.nodeType ? [h] : h, []),
            q = !a || (!f && b) ? p : wa(p, m, a, h, i),
            r = c ? (e || (f ? a : o || d) ? [] : g) : q;
          if ((c && c(q, r, h, i), d)) {
            (j = wa(r, n)), d(j, [], h, i), (k = j.length);
            while (k--) (l = j[k]) && (r[n[k]] = !(q[n[k]] = l));
          }
          if (f) {
            if (e || a) {
              if (e) {
                (j = []), (k = r.length);
                while (k--) (l = r[k]) && j.push((q[k] = l));
                e(null, (r = []), j, i);
              }
              k = r.length;
              while (k--)
                (l = r[k]) &&
                  (j = e ? I(f, l) : m[k]) > -1 &&
                  (f[j] = !(g[j] = l));
            }
          } else (r = wa(r === g ? r.splice(o, r.length) : r)), e ? e(null, g, r, i) : G.apply(g, r);
        })
      );
    }
    function ya(a) {
      for (
        var b,
          c,
          e,
          f = a.length,
          g = d.relative[a[0].type],
          h = g || d.relative[" "],
          i = g ? 1 : 0,
          k = ta(
            function (a) {
              return a === b;
            },
            h,
            !0
          ),
          l = ta(
            function (a) {
              return I(b, a) > -1;
            },
            h,
            !0
          ),
          m = [
            function (a, c, d) {
              var e =
                (!g && (d || c !== j)) ||
                ((b = c).nodeType ? k(a, c, d) : l(a, c, d));
              return (b = null), e;
            },
          ];
        i < f;
        i++
      )
        if ((c = d.relative[a[i].type])) m = [ta(ua(m), c)];
        else {
          if (((c = d.filter[a[i].type].apply(null, a[i].matches)), c[u])) {
            for (e = ++i; e < f; e++) if (d.relative[a[e].type]) break;
            return xa(
              i > 1 && ua(m),
              i > 1 &&
                sa(
                  a
                    .slice(0, i - 1)
                    .concat({ value: " " === a[i - 2].type ? "*" : "" })
                ).replace(P, "$1"),
              c,
              i < e && ya(a.slice(i, e)),
              e < f && ya((a = a.slice(e))),
              e < f && sa(a)
            );
          }
          m.push(c);
        }
      return ua(m);
    }
    function za(a, b) {
      var c = b.length > 0,
        e = a.length > 0,
        f = function (f, g, h, i, k) {
          var l,
            o,
            q,
            r = 0,
            s = "0",
            t = f && [],
            u = [],
            v = j,
            x = f || (e && d.find.TAG("*", k)),
            y = (w += null == v ? 1 : Math.random() || 0.1),
            z = x.length;
          for (
            k && (j = g === n || g || k);
            s !== z && null != (l = x[s]);
            s++
          ) {
            if (e && l) {
              (o = 0), g || l.ownerDocument === n || (m(l), (h = !p));
              while ((q = a[o++]))
                if (q(l, g || n, h)) {
                  i.push(l);
                  break;
                }
              k && (w = y);
            }
            c && ((l = !q && l) && r--, f && t.push(l));
          }
          if (((r += s), c && s !== r)) {
            o = 0;
            while ((q = b[o++])) q(t, u, g, h);
            if (f) {
              if (r > 0) while (s--) t[s] || u[s] || (u[s] = E.call(i));
              u = wa(u);
            }
            G.apply(i, u),
              k && !f && u.length > 0 && r + b.length > 1 && ga.uniqueSort(i);
          }
          return k && ((w = y), (j = v)), t;
        };
      return c ? ia(f) : f;
    }
    return (
      (h = ga.compile =
        function (a, b) {
          var c,
            d = [],
            e = [],
            f = A[a + " "];
          if (!f) {
            b || (b = g(a)), (c = b.length);
            while (c--) (f = ya(b[c])), f[u] ? d.push(f) : e.push(f);
            (f = A(a, za(e, d))), (f.selector = a);
          }
          return f;
        }),
      (i = ga.select =
        function (a, b, c, e) {
          var f,
            i,
            j,
            k,
            l,
            m = "function" == typeof a && a,
            n = !e && g((a = m.selector || a));
          if (((c = c || []), 1 === n.length)) {
            if (
              ((i = n[0] = n[0].slice(0)),
              i.length > 2 &&
                "ID" === (j = i[0]).type &&
                9 === b.nodeType &&
                p &&
                d.relative[i[1].type])
            ) {
              if (
                ((b = (d.find.ID(j.matches[0].replace(_, aa), b) || [])[0]), !b)
              )
                return c;
              m && (b = b.parentNode), (a = a.slice(i.shift().value.length));
            }
            f = V.needsContext.test(a) ? 0 : i.length;
            while (f--) {
              if (((j = i[f]), d.relative[(k = j.type)])) break;
              if (
                (l = d.find[k]) &&
                (e = l(
                  j.matches[0].replace(_, aa),
                  ($.test(i[0].type) && qa(b.parentNode)) || b
                ))
              ) {
                if ((i.splice(f, 1), (a = e.length && sa(i)), !a))
                  return G.apply(c, e), c;
                break;
              }
            }
          }
          return (
            (m || h(a, n))(
              e,
              b,
              !p,
              c,
              !b || ($.test(a) && qa(b.parentNode)) || b
            ),
            c
          );
        }),
      (c.sortStable = u.split("").sort(B).join("") === u),
      (c.detectDuplicates = !!l),
      m(),
      (c.sortDetached = ja(function (a) {
        return 1 & a.compareDocumentPosition(n.createElement("fieldset"));
      })),
      ja(function (a) {
        return (
          (a.innerHTML = "<a href='#'></a>"),
          "#" === a.firstChild.getAttribute("href")
        );
      }) ||
        ka("type|href|height|width", function (a, b, c) {
          if (!c) return a.getAttribute(b, "type" === b.toLowerCase() ? 1 : 2);
        }),
      (c.attributes &&
        ja(function (a) {
          return (
            (a.innerHTML = "<input/>"),
            a.firstChild.setAttribute("value", ""),
            "" === a.firstChild.getAttribute("value")
          );
        })) ||
        ka("value", function (a, b, c) {
          if (!c && "input" === a.nodeName.toLowerCase()) return a.defaultValue;
        }),
      ja(function (a) {
        return null == a.getAttribute("disabled");
      }) ||
        ka(J, function (a, b, c) {
          var d;
          if (!c)
            return a[b] === !0
              ? b.toLowerCase()
              : (d = a.getAttributeNode(b)) && d.specified
              ? d.value
              : null;
        }),
      ga
    );
  })(a);
  (r.find = x),
    (r.expr = x.selectors),
    (r.expr[":"] = r.expr.pseudos),
    (r.uniqueSort = r.unique = x.uniqueSort),
    (r.text = x.getText),
    (r.isXMLDoc = x.isXML),
    (r.contains = x.contains),
    (r.escapeSelector = x.escape);
  var y = function (a, b, c) {
      var d = [],
        e = void 0 !== c;
      while ((a = a[b]) && 9 !== a.nodeType)
        if (1 === a.nodeType) {
          if (e && r(a).is(c)) break;
          d.push(a);
        }
      return d;
    },
    z = function (a, b) {
      for (var c = []; a; a = a.nextSibling)
        1 === a.nodeType && a !== b && c.push(a);
      return c;
    },
    A = r.expr.match.needsContext;
  function B(a, b) {
    return a.nodeName && a.nodeName.toLowerCase() === b.toLowerCase();
  }
  var C = /^<([a-z][^\/\0>:\x20\t\r\n\f]*)[\x20\t\r\n\f]*\/?>(?:<\/\1>|)$/i,
    D = /^.[^:#\[\.,]*$/;
  function E(a, b, c) {
    return r.isFunction(b)
      ? r.grep(a, function (a, d) {
          return !!b.call(a, d, a) !== c;
        })
      : b.nodeType
      ? r.grep(a, function (a) {
          return (a === b) !== c;
        })
      : "string" != typeof b
      ? r.grep(a, function (a) {
          return i.call(b, a) > -1 !== c;
        })
      : D.test(b)
      ? r.filter(b, a, c)
      : ((b = r.filter(b, a)),
        r.grep(a, function (a) {
          return i.call(b, a) > -1 !== c && 1 === a.nodeType;
        }));
  }
  (r.filter = function (a, b, c) {
    var d = b[0];
    return (
      c && (a = ":not(" + a + ")"),
      1 === b.length && 1 === d.nodeType
        ? r.find.matchesSelector(d, a)
          ? [d]
          : []
        : r.find.matches(
            a,
            r.grep(b, function (a) {
              return 1 === a.nodeType;
            })
          )
    );
  }),
    r.fn.extend({
      find: function (a) {
        var b,
          c,
          d = this.length,
          e = this;
        if ("string" != typeof a)
          return this.pushStack(
            r(a).filter(function () {
              for (b = 0; b < d; b++) if (r.contains(e[b], this)) return !0;
            })
          );
        for (c = this.pushStack([]), b = 0; b < d; b++) r.find(a, e[b], c);
        return d > 1 ? r.uniqueSort(c) : c;
      },
      filter: function (a) {
        return this.pushStack(E(this, a || [], !1));
      },
      not: function (a) {
        return this.pushStack(E(this, a || [], !0));
      },
      is: function (a) {
        return !!E(this, "string" == typeof a && A.test(a) ? r(a) : a || [], !1)
          .length;
      },
    });
  var F,
    G = /^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]+))$/,
    H = (r.fn.init = function (a, b, c) {
      var e, f;
      if (!a) return this;
      if (((c = c || F), "string" == typeof a)) {
        if (
          ((e =
            "<" === a[0] && ">" === a[a.length - 1] && a.length >= 3
              ? [null, a, null]
              : G.exec(a)),
          !e || (!e[1] && b))
        )
          return !b || b.jquery
            ? (b || c).find(a)
            : this.constructor(b).find(a);
        if (e[1]) {
          if (
            ((b = b instanceof r ? b[0] : b),
            r.merge(
              this,
              r.parseHTML(e[1], b && b.nodeType ? b.ownerDocument || b : d, !0)
            ),
            C.test(e[1]) && r.isPlainObject(b))
          )
            for (e in b)
              r.isFunction(this[e]) ? this[e](b[e]) : this.attr(e, b[e]);
          return this;
        }
        return (
          (f = d.getElementById(e[2])),
          f && ((this[0] = f), (this.length = 1)),
          this
        );
      }
      return a.nodeType
        ? ((this[0] = a), (this.length = 1), this)
        : r.isFunction(a)
        ? void 0 !== c.ready
          ? c.ready(a)
          : a(r)
        : r.makeArray(a, this);
    });
  (H.prototype = r.fn), (F = r(d));
  var I = /^(?:parents|prev(?:Until|All))/,
    J = { children: !0, contents: !0, next: !0, prev: !0 };
  r.fn.extend({
    has: function (a) {
      var b = r(a, this),
        c = b.length;
      return this.filter(function () {
        for (var a = 0; a < c; a++) if (r.contains(this, b[a])) return !0;
      });
    },
    closest: function (a, b) {
      var c,
        d = 0,
        e = this.length,
        f = [],
        g = "string" != typeof a && r(a);
      if (!A.test(a))
        for (; d < e; d++)
          for (c = this[d]; c && c !== b; c = c.parentNode)
            if (
              c.nodeType < 11 &&
              (g
                ? g.index(c) > -1
                : 1 === c.nodeType && r.find.matchesSelector(c, a))
            ) {
              f.push(c);
              break;
            }
      return this.pushStack(f.length > 1 ? r.uniqueSort(f) : f);
    },
    index: function (a) {
      return a
        ? "string" == typeof a
          ? i.call(r(a), this[0])
          : i.call(this, a.jquery ? a[0] : a)
        : this[0] && this[0].parentNode
        ? this.first().prevAll().length
        : -1;
    },
    add: function (a, b) {
      return this.pushStack(r.uniqueSort(r.merge(this.get(), r(a, b))));
    },
    addBack: function (a) {
      return this.add(null == a ? this.prevObject : this.prevObject.filter(a));
    },
  });
  function K(a, b) {
    while ((a = a[b]) && 1 !== a.nodeType);
    return a;
  }
  r.each(
    {
      parent: function (a) {
        var b = a.parentNode;
        return b && 11 !== b.nodeType ? b : null;
      },
      parents: function (a) {
        return y(a, "parentNode");
      },
      parentsUntil: function (a, b, c) {
        return y(a, "parentNode", c);
      },
      next: function (a) {
        return K(a, "nextSibling");
      },
      prev: function (a) {
        return K(a, "previousSibling");
      },
      nextAll: function (a) {
        return y(a, "nextSibling");
      },
      prevAll: function (a) {
        return y(a, "previousSibling");
      },
      nextUntil: function (a, b, c) {
        return y(a, "nextSibling", c);
      },
      prevUntil: function (a, b, c) {
        return y(a, "previousSibling", c);
      },
      siblings: function (a) {
        return z((a.parentNode || {}).firstChild, a);
      },
      children: function (a) {
        return z(a.firstChild);
      },
      contents: function (a) {
        return B(a, "iframe")
          ? a.contentDocument
          : (B(a, "template") && (a = a.content || a),
            r.merge([], a.childNodes));
      },
    },
    function (a, b) {
      r.fn[a] = function (c, d) {
        var e = r.map(this, b, c);
        return (
          "Until" !== a.slice(-5) && (d = c),
          d && "string" == typeof d && (e = r.filter(d, e)),
          this.length > 1 &&
            (J[a] || r.uniqueSort(e), I.test(a) && e.reverse()),
          this.pushStack(e)
        );
      };
    }
  );
  var L = /[^\x20\t\r\n\f]+/g;
  function M(a) {
    var b = {};
    return (
      r.each(a.match(L) || [], function (a, c) {
        b[c] = !0;
      }),
      b
    );
  }
  r.Callbacks = function (a) {
    a = "string" == typeof a ? M(a) : r.extend({}, a);
    var b,
      c,
      d,
      e,
      f = [],
      g = [],
      h = -1,
      i = function () {
        for (e = e || a.once, d = b = !0; g.length; h = -1) {
          c = g.shift();
          while (++h < f.length)
            f[h].apply(c[0], c[1]) === !1 &&
              a.stopOnFalse &&
              ((h = f.length), (c = !1));
        }
        a.memory || (c = !1), (b = !1), e && (f = c ? [] : "");
      },
      j = {
        add: function () {
          return (
            f &&
              (c && !b && ((h = f.length - 1), g.push(c)),
              (function d(b) {
                r.each(b, function (b, c) {
                  r.isFunction(c)
                    ? (a.unique && j.has(c)) || f.push(c)
                    : c && c.length && "string" !== r.type(c) && d(c);
                });
              })(arguments),
              c && !b && i()),
            this
          );
        },
        remove: function () {
          return (
            r.each(arguments, function (a, b) {
              var c;
              while ((c = r.inArray(b, f, c)) > -1)
                f.splice(c, 1), c <= h && h--;
            }),
            this
          );
        },
        has: function (a) {
          return a ? r.inArray(a, f) > -1 : f.length > 0;
        },
        empty: function () {
          return f && (f = []), this;
        },
        disable: function () {
          return (e = g = []), (f = c = ""), this;
        },
        disabled: function () {
          return !f;
        },
        lock: function () {
          return (e = g = []), c || b || (f = c = ""), this;
        },
        locked: function () {
          return !!e;
        },
        fireWith: function (a, c) {
          return (
            e ||
              ((c = c || []),
              (c = [a, c.slice ? c.slice() : c]),
              g.push(c),
              b || i()),
            this
          );
        },
        fire: function () {
          return j.fireWith(this, arguments), this;
        },
        fired: function () {
          return !!d;
        },
      };
    return j;
  };
  function N(a) {
    return a;
  }
  function O(a) {
    throw a;
  }
  function P(a, b, c, d) {
    var e;
    try {
      a && r.isFunction((e = a.promise))
        ? e.call(a).done(b).fail(c)
        : a && r.isFunction((e = a.then))
        ? e.call(a, b, c)
        : b.apply(void 0, [a].slice(d));
    } catch (a) {
      c.apply(void 0, [a]);
    }
  }
  r.extend({
    Deferred: function (b) {
      var c = [
          [
            "notify",
            "progress",
            r.Callbacks("memory"),
            r.Callbacks("memory"),
            2,
          ],
          [
            "resolve",
            "done",
            r.Callbacks("once memory"),
            r.Callbacks("once memory"),
            0,
            "resolved",
          ],
          [
            "reject",
            "fail",
            r.Callbacks("once memory"),
            r.Callbacks("once memory"),
            1,
            "rejected",
          ],
        ],
        d = "pending",
        e = {
          state: function () {
            return d;
          },
          always: function () {
            return f.done(arguments).fail(arguments), this;
          },
          catch: function (a) {
            return e.then(null, a);
          },
          pipe: function () {
            var a = arguments;
            return r
              .Deferred(function (b) {
                r.each(c, function (c, d) {
                  var e = r.isFunction(a[d[4]]) && a[d[4]];
                  f[d[1]](function () {
                    var a = e && e.apply(this, arguments);
                    a && r.isFunction(a.promise)
                      ? a
                          .promise()
                          .progress(b.notify)
                          .done(b.resolve)
                          .fail(b.reject)
                      : b[d[0] + "With"](this, e ? [a] : arguments);
                  });
                }),
                  (a = null);
              })
              .promise();
          },
          then: function (b, d, e) {
            var f = 0;
            function g(b, c, d, e) {
              return function () {
                var h = this,
                  i = arguments,
                  j = function () {
                    var a, j;
                    if (!(b < f)) {
                      if (((a = d.apply(h, i)), a === c.promise()))
                        throw new TypeError("Thenable self-resolution");
                      (j =
                        a &&
                        ("object" == typeof a || "function" == typeof a) &&
                        a.then),
                        r.isFunction(j)
                          ? e
                            ? j.call(a, g(f, c, N, e), g(f, c, O, e))
                            : (f++,
                              j.call(
                                a,
                                g(f, c, N, e),
                                g(f, c, O, e),
                                g(f, c, N, c.notifyWith)
                              ))
                          : (d !== N && ((h = void 0), (i = [a])),
                            (e || c.resolveWith)(h, i));
                    }
                  },
                  k = e
                    ? j
                    : function () {
                        try {
                          j();
                        } catch (a) {
                          r.Deferred.exceptionHook &&
                            r.Deferred.exceptionHook(a, k.stackTrace),
                            b + 1 >= f &&
                              (d !== O && ((h = void 0), (i = [a])),
                              c.rejectWith(h, i));
                        }
                      };
                b
                  ? k()
                  : (r.Deferred.getStackHook &&
                      (k.stackTrace = r.Deferred.getStackHook()),
                    a.setTimeout(k));
              };
            }
            return r
              .Deferred(function (a) {
                c[0][3].add(g(0, a, r.isFunction(e) ? e : N, a.notifyWith)),
                  c[1][3].add(g(0, a, r.isFunction(b) ? b : N)),
                  c[2][3].add(g(0, a, r.isFunction(d) ? d : O));
              })
              .promise();
          },
          promise: function (a) {
            return null != a ? r.extend(a, e) : e;
          },
        },
        f = {};
      return (
        r.each(c, function (a, b) {
          var g = b[2],
            h = b[5];
          (e[b[1]] = g.add),
            h &&
              g.add(
                function () {
                  d = h;
                },
                c[3 - a][2].disable,
                c[0][2].lock
              ),
            g.add(b[3].fire),
            (f[b[0]] = function () {
              return (
                f[b[0] + "With"](this === f ? void 0 : this, arguments), this
              );
            }),
            (f[b[0] + "With"] = g.fireWith);
        }),
        e.promise(f),
        b && b.call(f, f),
        f
      );
    },
    when: function (a) {
      var b = arguments.length,
        c = b,
        d = Array(c),
        e = f.call(arguments),
        g = r.Deferred(),
        h = function (a) {
          return function (c) {
            (d[a] = this),
              (e[a] = arguments.length > 1 ? f.call(arguments) : c),
              --b || g.resolveWith(d, e);
          };
        };
      if (
        b <= 1 &&
        (P(a, g.done(h(c)).resolve, g.reject, !b),
        "pending" === g.state() || r.isFunction(e[c] && e[c].then))
      )
        return g.then();
      while (c--) P(e[c], h(c), g.reject);
      return g.promise();
    },
  });
  var Q = /^(Eval|Internal|Range|Reference|Syntax|Type|URI)Error$/;
  (r.Deferred.exceptionHook = function (b, c) {
    a.console &&
      a.console.warn &&
      b &&
      Q.test(b.name) &&
      a.console.warn("jQuery.Deferred exception: " + b.message, b.stack, c);
  }),
    (r.readyException = function (b) {
      a.setTimeout(function () {
        throw b;
      });
    });
  var R = r.Deferred();
  (r.fn.ready = function (a) {
    return (
      R.then(a)["catch"](function (a) {
        r.readyException(a);
      }),
      this
    );
  }),
    r.extend({
      isReady: !1,
      readyWait: 1,
      ready: function (a) {
        (a === !0 ? --r.readyWait : r.isReady) ||
          ((r.isReady = !0),
          (a !== !0 && --r.readyWait > 0) || R.resolveWith(d, [r]));
      },
    }),
    (r.ready.then = R.then);
  function S() {
    d.removeEventListener("DOMContentLoaded", S),
      a.removeEventListener("load", S),
      r.ready();
  }
  "complete" === d.readyState ||
  ("loading" !== d.readyState && !d.documentElement.doScroll)
    ? a.setTimeout(r.ready)
    : (d.addEventListener("DOMContentLoaded", S),
      a.addEventListener("load", S));
  var T = function (a, b, c, d, e, f, g) {
      var h = 0,
        i = a.length,
        j = null == c;
      if ("object" === r.type(c)) {
        e = !0;
        for (h in c) T(a, b, h, c[h], !0, f, g);
      } else if (
        void 0 !== d &&
        ((e = !0),
        r.isFunction(d) || (g = !0),
        j &&
          (g
            ? (b.call(a, d), (b = null))
            : ((j = b),
              (b = function (a, b, c) {
                return j.call(r(a), c);
              }))),
        b)
      )
        for (; h < i; h++) b(a[h], c, g ? d : d.call(a[h], h, b(a[h], c)));
      return e ? a : j ? b.call(a) : i ? b(a[0], c) : f;
    },
    U = function (a) {
      return 1 === a.nodeType || 9 === a.nodeType || !+a.nodeType;
    };
  function V() {
    this.expando = r.expando + V.uid++;
  }
  (V.uid = 1),
    (V.prototype = {
      cache: function (a) {
        var b = a[this.expando];
        return (
          b ||
            ((b = {}),
            U(a) &&
              (a.nodeType
                ? (a[this.expando] = b)
                : Object.defineProperty(a, this.expando, {
                    value: b,
                    configurable: !0,
                  }))),
          b
        );
      },
      set: function (a, b, c) {
        var d,
          e = this.cache(a);
        if ("string" == typeof b) e[r.camelCase(b)] = c;
        else for (d in b) e[r.camelCase(d)] = b[d];
        return e;
      },
      get: function (a, b) {
        return void 0 === b
          ? this.cache(a)
          : a[this.expando] && a[this.expando][r.camelCase(b)];
      },
      access: function (a, b, c) {
        return void 0 === b || (b && "string" == typeof b && void 0 === c)
          ? this.get(a, b)
          : (this.set(a, b, c), void 0 !== c ? c : b);
      },
      remove: function (a, b) {
        var c,
          d = a[this.expando];
        if (void 0 !== d) {
          if (void 0 !== b) {
            Array.isArray(b)
              ? (b = b.map(r.camelCase))
              : ((b = r.camelCase(b)), (b = b in d ? [b] : b.match(L) || [])),
              (c = b.length);
            while (c--) delete d[b[c]];
          }
          (void 0 === b || r.isEmptyObject(d)) &&
            (a.nodeType ? (a[this.expando] = void 0) : delete a[this.expando]);
        }
      },
      hasData: function (a) {
        var b = a[this.expando];
        return void 0 !== b && !r.isEmptyObject(b);
      },
    });
  var W = new V(),
    X = new V(),
    Y = /^(?:\{[\w\W]*\}|\[[\w\W]*\])$/,
    Z = /[A-Z]/g;
  function $(a) {
    return (
      "true" === a ||
      ("false" !== a &&
        ("null" === a
          ? null
          : a === +a + ""
          ? +a
          : Y.test(a)
          ? JSON.parse(a)
          : a))
    );
  }
  function _(a, b, c) {
    var d;
    if (void 0 === c && 1 === a.nodeType)
      if (
        ((d = "data-" + b.replace(Z, "-$&").toLowerCase()),
        (c = a.getAttribute(d)),
        "string" == typeof c)
      ) {
        try {
          c = $(c);
        } catch (e) {}
        X.set(a, b, c);
      } else c = void 0;
    return c;
  }
  r.extend({
    hasData: function (a) {
      return X.hasData(a) || W.hasData(a);
    },
    data: function (a, b, c) {
      return X.access(a, b, c);
    },
    removeData: function (a, b) {
      X.remove(a, b);
    },
    _data: function (a, b, c) {
      return W.access(a, b, c);
    },
    _removeData: function (a, b) {
      W.remove(a, b);
    },
  }),
    r.fn.extend({
      data: function (a, b) {
        var c,
          d,
          e,
          f = this[0],
          g = f && f.attributes;
        if (void 0 === a) {
          if (
            this.length &&
            ((e = X.get(f)), 1 === f.nodeType && !W.get(f, "hasDataAttrs"))
          ) {
            c = g.length;
            while (c--)
              g[c] &&
                ((d = g[c].name),
                0 === d.indexOf("data-") &&
                  ((d = r.camelCase(d.slice(5))), _(f, d, e[d])));
            W.set(f, "hasDataAttrs", !0);
          }
          return e;
        }
        return "object" == typeof a
          ? this.each(function () {
              X.set(this, a);
            })
          : T(
              this,
              function (b) {
                var c;
                if (f && void 0 === b) {
                  if (((c = X.get(f, a)), void 0 !== c)) return c;
                  if (((c = _(f, a)), void 0 !== c)) return c;
                } else
                  this.each(function () {
                    X.set(this, a, b);
                  });
              },
              null,
              b,
              arguments.length > 1,
              null,
              !0
            );
      },
      removeData: function (a) {
        return this.each(function () {
          X.remove(this, a);
        });
      },
    }),
    r.extend({
      queue: function (a, b, c) {
        var d;
        if (a)
          return (
            (b = (b || "fx") + "queue"),
            (d = W.get(a, b)),
            c &&
              (!d || Array.isArray(c)
                ? (d = W.access(a, b, r.makeArray(c)))
                : d.push(c)),
            d || []
          );
      },
      dequeue: function (a, b) {
        b = b || "fx";
        var c = r.queue(a, b),
          d = c.length,
          e = c.shift(),
          f = r._queueHooks(a, b),
          g = function () {
            r.dequeue(a, b);
          };
        "inprogress" === e && ((e = c.shift()), d--),
          e &&
            ("fx" === b && c.unshift("inprogress"),
            delete f.stop,
            e.call(a, g, f)),
          !d && f && f.empty.fire();
      },
      _queueHooks: function (a, b) {
        var c = b + "queueHooks";
        return (
          W.get(a, c) ||
          W.access(a, c, {
            empty: r.Callbacks("once memory").add(function () {
              W.remove(a, [b + "queue", c]);
            }),
          })
        );
      },
    }),
    r.fn.extend({
      queue: function (a, b) {
        var c = 2;
        return (
          "string" != typeof a && ((b = a), (a = "fx"), c--),
          arguments.length < c
            ? r.queue(this[0], a)
            : void 0 === b
            ? this
            : this.each(function () {
                var c = r.queue(this, a, b);
                r._queueHooks(this, a),
                  "fx" === a && "inprogress" !== c[0] && r.dequeue(this, a);
              })
        );
      },
      dequeue: function (a) {
        return this.each(function () {
          r.dequeue(this, a);
        });
      },
      clearQueue: function (a) {
        return this.queue(a || "fx", []);
      },
      promise: function (a, b) {
        var c,
          d = 1,
          e = r.Deferred(),
          f = this,
          g = this.length,
          h = function () {
            --d || e.resolveWith(f, [f]);
          };
        "string" != typeof a && ((b = a), (a = void 0)), (a = a || "fx");
        while (g--)
          (c = W.get(f[g], a + "queueHooks")),
            c && c.empty && (d++, c.empty.add(h));
        return h(), e.promise(b);
      },
    });
  var aa = /[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source,
    ba = new RegExp("^(?:([+-])=|)(" + aa + ")([a-z%]*)$", "i"),
    ca = ["Top", "Right", "Bottom", "Left"],
    da = function (a, b) {
      return (
        (a = b || a),
        "none" === a.style.display ||
          ("" === a.style.display &&
            r.contains(a.ownerDocument, a) &&
            "none" === r.css(a, "display"))
      );
    },
    ea = function (a, b, c, d) {
      var e,
        f,
        g = {};
      for (f in b) (g[f] = a.style[f]), (a.style[f] = b[f]);
      e = c.apply(a, d || []);
      for (f in b) a.style[f] = g[f];
      return e;
    };
  function fa(a, b, c, d) {
    var e,
      f = 1,
      g = 20,
      h = d
        ? function () {
            return d.cur();
          }
        : function () {
            return r.css(a, b, "");
          },
      i = h(),
      j = (c && c[3]) || (r.cssNumber[b] ? "" : "px"),
      k = (r.cssNumber[b] || ("px" !== j && +i)) && ba.exec(r.css(a, b));
    if (k && k[3] !== j) {
      (j = j || k[3]), (c = c || []), (k = +i || 1);
      do (f = f || ".5"), (k /= f), r.style(a, b, k + j);
      while (f !== (f = h() / i) && 1 !== f && --g);
    }
    return (
      c &&
        ((k = +k || +i || 0),
        (e = c[1] ? k + (c[1] + 1) * c[2] : +c[2]),
        d && ((d.unit = j), (d.start = k), (d.end = e))),
      e
    );
  }
  var ga = {};
  function ha(a) {
    var b,
      c = a.ownerDocument,
      d = a.nodeName,
      e = ga[d];
    return e
      ? e
      : ((b = c.body.appendChild(c.createElement(d))),
        (e = r.css(b, "display")),
        b.parentNode.removeChild(b),
        "none" === e && (e = "block"),
        (ga[d] = e),
        e);
  }
  function ia(a, b) {
    for (var c, d, e = [], f = 0, g = a.length; f < g; f++)
      (d = a[f]),
        d.style &&
          ((c = d.style.display),
          b
            ? ("none" === c &&
                ((e[f] = W.get(d, "display") || null),
                e[f] || (d.style.display = "")),
              "" === d.style.display && da(d) && (e[f] = ha(d)))
            : "none" !== c && ((e[f] = "none"), W.set(d, "display", c)));
    for (f = 0; f < g; f++) null != e[f] && (a[f].style.display = e[f]);
    return a;
  }
  r.fn.extend({
    show: function () {
      return ia(this, !0);
    },
    hide: function () {
      return ia(this);
    },
    toggle: function (a) {
      return "boolean" == typeof a
        ? a
          ? this.show()
          : this.hide()
        : this.each(function () {
            da(this) ? r(this).show() : r(this).hide();
          });
    },
  });
  var ja = /^(?:checkbox|radio)$/i,
    ka = /<([a-z][^\/\0>\x20\t\r\n\f]+)/i,
    la = /^$|\/(?:java|ecma)script/i,
    ma = {
      option: [1, "<select multiple='multiple'>", "</select>"],
      thead: [1, "<table>", "</table>"],
      col: [2, "<table><colgroup>", "</colgroup></table>"],
      tr: [2, "<table><tbody>", "</tbody></table>"],
      td: [3, "<table><tbody><tr>", "</tr></tbody></table>"],
      _default: [0, "", ""],
    };
  (ma.optgroup = ma.option),
    (ma.tbody = ma.tfoot = ma.colgroup = ma.caption = ma.thead),
    (ma.th = ma.td);
  function na(a, b) {
    var c;
    return (
      (c =
        "undefined" != typeof a.getElementsByTagName
          ? a.getElementsByTagName(b || "*")
          : "undefined" != typeof a.querySelectorAll
          ? a.querySelectorAll(b || "*")
          : []),
      void 0 === b || (b && B(a, b)) ? r.merge([a], c) : c
    );
  }
  function oa(a, b) {
    for (var c = 0, d = a.length; c < d; c++)
      W.set(a[c], "globalEval", !b || W.get(b[c], "globalEval"));
  }
  var pa = /<|&#?\w+;/;
  function qa(a, b, c, d, e) {
    for (
      var f,
        g,
        h,
        i,
        j,
        k,
        l = b.createDocumentFragment(),
        m = [],
        n = 0,
        o = a.length;
      n < o;
      n++
    )
      if (((f = a[n]), f || 0 === f))
        if ("object" === r.type(f)) r.merge(m, f.nodeType ? [f] : f);
        else if (pa.test(f)) {
          (g = g || l.appendChild(b.createElement("div"))),
            (h = (ka.exec(f) || ["", ""])[1].toLowerCase()),
            (i = ma[h] || ma._default),
            (g.innerHTML = i[1] + r.htmlPrefilter(f) + i[2]),
            (k = i[0]);
          while (k--) g = g.lastChild;
          r.merge(m, g.childNodes), (g = l.firstChild), (g.textContent = "");
        } else m.push(b.createTextNode(f));
    (l.textContent = ""), (n = 0);
    while ((f = m[n++]))
      if (d && r.inArray(f, d) > -1) e && e.push(f);
      else if (
        ((j = r.contains(f.ownerDocument, f)),
        (g = na(l.appendChild(f), "script")),
        j && oa(g),
        c)
      ) {
        k = 0;
        while ((f = g[k++])) la.test(f.type || "") && c.push(f);
      }
    return l;
  }
  !(function () {
    var a = d.createDocumentFragment(),
      b = a.appendChild(d.createElement("div")),
      c = d.createElement("input");
    c.setAttribute("type", "radio"),
      c.setAttribute("checked", "checked"),
      c.setAttribute("name", "t"),
      b.appendChild(c),
      (o.checkClone = b.cloneNode(!0).cloneNode(!0).lastChild.checked),
      (b.innerHTML = "<textarea>x</textarea>"),
      (o.noCloneChecked = !!b.cloneNode(!0).lastChild.defaultValue);
  })();
  var ra = d.documentElement,
    sa = /^key/,
    ta = /^(?:mouse|pointer|contextmenu|drag|drop)|click/,
    ua = /^([^.]*)(?:\.(.+)|)/;
  function va() {
    return !0;
  }
  function wa() {
    return !1;
  }
  function xa() {
    try {
      return d.activeElement;
    } catch (a) {}
  }
  function ya(a, b, c, d, e, f) {
    var g, h;
    if ("object" == typeof b) {
      "string" != typeof c && ((d = d || c), (c = void 0));
      for (h in b) ya(a, h, c, d, b[h], f);
      return a;
    }
    if (
      (null == d && null == e
        ? ((e = c), (d = c = void 0))
        : null == e &&
          ("string" == typeof c
            ? ((e = d), (d = void 0))
            : ((e = d), (d = c), (c = void 0))),
      e === !1)
    )
      e = wa;
    else if (!e) return a;
    return (
      1 === f &&
        ((g = e),
        (e = function (a) {
          return r().off(a), g.apply(this, arguments);
        }),
        (e.guid = g.guid || (g.guid = r.guid++))),
      a.each(function () {
        r.event.add(this, b, e, d, c);
      })
    );
  }
  (r.event = {
    global: {},
    add: function (a, b, c, d, e) {
      var f,
        g,
        h,
        i,
        j,
        k,
        l,
        m,
        n,
        o,
        p,
        q = W.get(a);
      if (q) {
        c.handler && ((f = c), (c = f.handler), (e = f.selector)),
          e && r.find.matchesSelector(ra, e),
          c.guid || (c.guid = r.guid++),
          (i = q.events) || (i = q.events = {}),
          (g = q.handle) ||
            (g = q.handle =
              function (b) {
                return "undefined" != typeof r && r.event.triggered !== b.type
                  ? r.event.dispatch.apply(a, arguments)
                  : void 0;
              }),
          (b = (b || "").match(L) || [""]),
          (j = b.length);
        while (j--)
          (h = ua.exec(b[j]) || []),
            (n = p = h[1]),
            (o = (h[2] || "").split(".").sort()),
            n &&
              ((l = r.event.special[n] || {}),
              (n = (e ? l.delegateType : l.bindType) || n),
              (l = r.event.special[n] || {}),
              (k = r.extend(
                {
                  type: n,
                  origType: p,
                  data: d,
                  handler: c,
                  guid: c.guid,
                  selector: e,
                  needsContext: e && r.expr.match.needsContext.test(e),
                  namespace: o.join("."),
                },
                f
              )),
              (m = i[n]) ||
                ((m = i[n] = []),
                (m.delegateCount = 0),
                (l.setup && l.setup.call(a, d, o, g) !== !1) ||
                  (a.addEventListener && a.addEventListener(n, g))),
              l.add &&
                (l.add.call(a, k), k.handler.guid || (k.handler.guid = c.guid)),
              e ? m.splice(m.delegateCount++, 0, k) : m.push(k),
              (r.event.global[n] = !0));
      }
    },
    remove: function (a, b, c, d, e) {
      var f,
        g,
        h,
        i,
        j,
        k,
        l,
        m,
        n,
        o,
        p,
        q = W.hasData(a) && W.get(a);
      if (q && (i = q.events)) {
        (b = (b || "").match(L) || [""]), (j = b.length);
        while (j--)
          if (
            ((h = ua.exec(b[j]) || []),
            (n = p = h[1]),
            (o = (h[2] || "").split(".").sort()),
            n)
          ) {
            (l = r.event.special[n] || {}),
              (n = (d ? l.delegateType : l.bindType) || n),
              (m = i[n] || []),
              (h =
                h[2] &&
                new RegExp("(^|\\.)" + o.join("\\.(?:.*\\.|)") + "(\\.|$)")),
              (g = f = m.length);
            while (f--)
              (k = m[f]),
                (!e && p !== k.origType) ||
                  (c && c.guid !== k.guid) ||
                  (h && !h.test(k.namespace)) ||
                  (d && d !== k.selector && ("**" !== d || !k.selector)) ||
                  (m.splice(f, 1),
                  k.selector && m.delegateCount--,
                  l.remove && l.remove.call(a, k));
            g &&
              !m.length &&
              ((l.teardown && l.teardown.call(a, o, q.handle) !== !1) ||
                r.removeEvent(a, n, q.handle),
              delete i[n]);
          } else for (n in i) r.event.remove(a, n + b[j], c, d, !0);
        r.isEmptyObject(i) && W.remove(a, "handle events");
      }
    },
    dispatch: function (a) {
      var b = r.event.fix(a),
        c,
        d,
        e,
        f,
        g,
        h,
        i = new Array(arguments.length),
        j = (W.get(this, "events") || {})[b.type] || [],
        k = r.event.special[b.type] || {};
      for (i[0] = b, c = 1; c < arguments.length; c++) i[c] = arguments[c];
      if (
        ((b.delegateTarget = this),
        !k.preDispatch || k.preDispatch.call(this, b) !== !1)
      ) {
        (h = r.event.handlers.call(this, b, j)), (c = 0);
        while ((f = h[c++]) && !b.isPropagationStopped()) {
          (b.currentTarget = f.elem), (d = 0);
          while ((g = f.handlers[d++]) && !b.isImmediatePropagationStopped())
            (b.rnamespace && !b.rnamespace.test(g.namespace)) ||
              ((b.handleObj = g),
              (b.data = g.data),
              (e = (
                (r.event.special[g.origType] || {}).handle || g.handler
              ).apply(f.elem, i)),
              void 0 !== e &&
                (b.result = e) === !1 &&
                (b.preventDefault(), b.stopPropagation()));
        }
        return k.postDispatch && k.postDispatch.call(this, b), b.result;
      }
    },
    handlers: function (a, b) {
      var c,
        d,
        e,
        f,
        g,
        h = [],
        i = b.delegateCount,
        j = a.target;
      if (i && j.nodeType && !("click" === a.type && a.button >= 1))
        for (; j !== this; j = j.parentNode || this)
          if (1 === j.nodeType && ("click" !== a.type || j.disabled !== !0)) {
            for (f = [], g = {}, c = 0; c < i; c++)
              (d = b[c]),
                (e = d.selector + " "),
                void 0 === g[e] &&
                  (g[e] = d.needsContext
                    ? r(e, this).index(j) > -1
                    : r.find(e, this, null, [j]).length),
                g[e] && f.push(d);
            f.length && h.push({ elem: j, handlers: f });
          }
      return (
        (j = this), i < b.length && h.push({ elem: j, handlers: b.slice(i) }), h
      );
    },
    addProp: function (a, b) {
      Object.defineProperty(r.Event.prototype, a, {
        enumerable: !0,
        configurable: !0,
        get: r.isFunction(b)
          ? function () {
              if (this.originalEvent) return b(this.originalEvent);
            }
          : function () {
              if (this.originalEvent) return this.originalEvent[a];
            },
        set: function (b) {
          Object.defineProperty(this, a, {
            enumerable: !0,
            configurable: !0,
            writable: !0,
            value: b,
          });
        },
      });
    },
    fix: function (a) {
      return a[r.expando] ? a : new r.Event(a);
    },
    special: {
      load: { noBubble: !0 },
      focus: {
        trigger: function () {
          if (this !== xa() && this.focus) return this.focus(), !1;
        },
        delegateType: "focusin",
      },
      blur: {
        trigger: function () {
          if (this === xa() && this.blur) return this.blur(), !1;
        },
        delegateType: "focusout",
      },
      click: {
        trigger: function () {
          if ("checkbox" === this.type && this.click && B(this, "input"))
            return this.click(), !1;
        },
        _default: function (a) {
          return B(a.target, "a");
        },
      },
      beforeunload: {
        postDispatch: function (a) {
          void 0 !== a.result &&
            a.originalEvent &&
            (a.originalEvent.returnValue = a.result);
        },
      },
    },
  }),
    (r.removeEvent = function (a, b, c) {
      a.removeEventListener && a.removeEventListener(b, c);
    }),
    (r.Event = function (a, b) {
      return this instanceof r.Event
        ? (a && a.type
            ? ((this.originalEvent = a),
              (this.type = a.type),
              (this.isDefaultPrevented =
                a.defaultPrevented ||
                (void 0 === a.defaultPrevented && a.returnValue === !1)
                  ? va
                  : wa),
              (this.target =
                a.target && 3 === a.target.nodeType
                  ? a.target.parentNode
                  : a.target),
              (this.currentTarget = a.currentTarget),
              (this.relatedTarget = a.relatedTarget))
            : (this.type = a),
          b && r.extend(this, b),
          (this.timeStamp = (a && a.timeStamp) || r.now()),
          void (this[r.expando] = !0))
        : new r.Event(a, b);
    }),
    (r.Event.prototype = {
      constructor: r.Event,
      isDefaultPrevented: wa,
      isPropagationStopped: wa,
      isImmediatePropagationStopped: wa,
      isSimulated: !1,
      preventDefault: function () {
        var a = this.originalEvent;
        (this.isDefaultPrevented = va),
          a && !this.isSimulated && a.preventDefault();
      },
      stopPropagation: function () {
        var a = this.originalEvent;
        (this.isPropagationStopped = va),
          a && !this.isSimulated && a.stopPropagation();
      },
      stopImmediatePropagation: function () {
        var a = this.originalEvent;
        (this.isImmediatePropagationStopped = va),
          a && !this.isSimulated && a.stopImmediatePropagation(),
          this.stopPropagation();
      },
    }),
    r.each(
      {
        altKey: !0,
        bubbles: !0,
        cancelable: !0,
        changedTouches: !0,
        ctrlKey: !0,
        detail: !0,
        eventPhase: !0,
        metaKey: !0,
        pageX: !0,
        pageY: !0,
        shiftKey: !0,
        view: !0,
        char: !0,
        charCode: !0,
        key: !0,
        keyCode: !0,
        button: !0,
        buttons: !0,
        clientX: !0,
        clientY: !0,
        offsetX: !0,
        offsetY: !0,
        pointerId: !0,
        pointerType: !0,
        screenX: !0,
        screenY: !0,
        targetTouches: !0,
        toElement: !0,
        touches: !0,
        which: function (a) {
          var b = a.button;
          return null == a.which && sa.test(a.type)
            ? null != a.charCode
              ? a.charCode
              : a.keyCode
            : !a.which && void 0 !== b && ta.test(a.type)
            ? 1 & b
              ? 1
              : 2 & b
              ? 3
              : 4 & b
              ? 2
              : 0
            : a.which;
        },
      },
      r.event.addProp
    ),
    r.each(
      {
        mouseenter: "mouseover",
        mouseleave: "mouseout",
        pointerenter: "pointerover",
        pointerleave: "pointerout",
      },
      function (a, b) {
        r.event.special[a] = {
          delegateType: b,
          bindType: b,
          handle: function (a) {
            var c,
              d = this,
              e = a.relatedTarget,
              f = a.handleObj;
            return (
              (e && (e === d || r.contains(d, e))) ||
                ((a.type = f.origType),
                (c = f.handler.apply(this, arguments)),
                (a.type = b)),
              c
            );
          },
        };
      }
    ),
    r.fn.extend({
      on: function (a, b, c, d) {
        return ya(this, a, b, c, d);
      },
      one: function (a, b, c, d) {
        return ya(this, a, b, c, d, 1);
      },
      off: function (a, b, c) {
        var d, e;
        if (a && a.preventDefault && a.handleObj)
          return (
            (d = a.handleObj),
            r(a.delegateTarget).off(
              d.namespace ? d.origType + "." + d.namespace : d.origType,
              d.selector,
              d.handler
            ),
            this
          );
        if ("object" == typeof a) {
          for (e in a) this.off(e, b, a[e]);
          return this;
        }
        return (
          (b !== !1 && "function" != typeof b) || ((c = b), (b = void 0)),
          c === !1 && (c = wa),
          this.each(function () {
            r.event.remove(this, a, c, b);
          })
        );
      },
    });
  var za =
      /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([a-z][^\/\0>\x20\t\r\n\f]*)[^>]*)\/>/gi,
    Aa = /<script|<style|<link/i,
    Ba = /checked\s*(?:[^=]|=\s*.checked.)/i,
    Ca = /^true\/(.*)/,
    Da = /^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g;
  function Ea(a, b) {
    return B(a, "table") && B(11 !== b.nodeType ? b : b.firstChild, "tr")
      ? r(">tbody", a)[0] || a
      : a;
  }
  function Fa(a) {
    return (a.type = (null !== a.getAttribute("type")) + "/" + a.type), a;
  }
  function Ga(a) {
    var b = Ca.exec(a.type);
    return b ? (a.type = b[1]) : a.removeAttribute("type"), a;
  }
  function Ha(a, b) {
    var c, d, e, f, g, h, i, j;
    if (1 === b.nodeType) {
      if (
        W.hasData(a) &&
        ((f = W.access(a)), (g = W.set(b, f)), (j = f.events))
      ) {
        delete g.handle, (g.events = {});
        for (e in j)
          for (c = 0, d = j[e].length; c < d; c++) r.event.add(b, e, j[e][c]);
      }
      X.hasData(a) && ((h = X.access(a)), (i = r.extend({}, h)), X.set(b, i));
    }
  }
  function Ia(a, b) {
    var c = b.nodeName.toLowerCase();
    "input" === c && ja.test(a.type)
      ? (b.checked = a.checked)
      : ("input" !== c && "textarea" !== c) ||
        (b.defaultValue = a.defaultValue);
  }
  function Ja(a, b, c, d) {
    b = g.apply([], b);
    var e,
      f,
      h,
      i,
      j,
      k,
      l = 0,
      m = a.length,
      n = m - 1,
      q = b[0],
      s = r.isFunction(q);
    if (s || (m > 1 && "string" == typeof q && !o.checkClone && Ba.test(q)))
      return a.each(function (e) {
        var f = a.eq(e);
        s && (b[0] = q.call(this, e, f.html())), Ja(f, b, c, d);
      });
    if (
      m &&
      ((e = qa(b, a[0].ownerDocument, !1, a, d)),
      (f = e.firstChild),
      1 === e.childNodes.length && (e = f),
      f || d)
    ) {
      for (h = r.map(na(e, "script"), Fa), i = h.length; l < m; l++)
        (j = e),
          l !== n &&
            ((j = r.clone(j, !0, !0)), i && r.merge(h, na(j, "script"))),
          c.call(a[l], j, l);
      if (i)
        for (k = h[h.length - 1].ownerDocument, r.map(h, Ga), l = 0; l < i; l++)
          (j = h[l]),
            la.test(j.type || "") &&
              !W.access(j, "globalEval") &&
              r.contains(k, j) &&
              (j.src
                ? r._evalUrl && r._evalUrl(j.src)
                : p(j.textContent.replace(Da, ""), k));
    }
    return a;
  }
  function Ka(a, b, c) {
    for (var d, e = b ? r.filter(b, a) : a, f = 0; null != (d = e[f]); f++)
      c || 1 !== d.nodeType || r.cleanData(na(d)),
        d.parentNode &&
          (c && r.contains(d.ownerDocument, d) && oa(na(d, "script")),
          d.parentNode.removeChild(d));
    return a;
  }
  r.extend({
    htmlPrefilter: function (a) {
      return a.replace(za, "<$1></$2>");
    },
    clone: function (a, b, c) {
      var d,
        e,
        f,
        g,
        h = a.cloneNode(!0),
        i = r.contains(a.ownerDocument, a);
      if (
        !(
          o.noCloneChecked ||
          (1 !== a.nodeType && 11 !== a.nodeType) ||
          r.isXMLDoc(a)
        )
      )
        for (g = na(h), f = na(a), d = 0, e = f.length; d < e; d++)
          Ia(f[d], g[d]);
      if (b)
        if (c)
          for (f = f || na(a), g = g || na(h), d = 0, e = f.length; d < e; d++)
            Ha(f[d], g[d]);
        else Ha(a, h);
      return (
        (g = na(h, "script")), g.length > 0 && oa(g, !i && na(a, "script")), h
      );
    },
    cleanData: function (a) {
      for (var b, c, d, e = r.event.special, f = 0; void 0 !== (c = a[f]); f++)
        if (U(c)) {
          if ((b = c[W.expando])) {
            if (b.events)
              for (d in b.events)
                e[d] ? r.event.remove(c, d) : r.removeEvent(c, d, b.handle);
            c[W.expando] = void 0;
          }
          c[X.expando] && (c[X.expando] = void 0);
        }
    },
  }),
    r.fn.extend({
      detach: function (a) {
        return Ka(this, a, !0);
      },
      remove: function (a) {
        return Ka(this, a);
      },
      text: function (a) {
        return T(
          this,
          function (a) {
            return void 0 === a
              ? r.text(this)
              : this.empty().each(function () {
                  (1 !== this.nodeType &&
                    11 !== this.nodeType &&
                    9 !== this.nodeType) ||
                    (this.textContent = a);
                });
          },
          null,
          a,
          arguments.length
        );
      },
      append: function () {
        return Ja(this, arguments, function (a) {
          if (
            1 === this.nodeType ||
            11 === this.nodeType ||
            9 === this.nodeType
          ) {
            var b = Ea(this, a);
            b.appendChild(a);
          }
        });
      },
      prepend: function () {
        return Ja(this, arguments, function (a) {
          if (
            1 === this.nodeType ||
            11 === this.nodeType ||
            9 === this.nodeType
          ) {
            var b = Ea(this, a);
            b.insertBefore(a, b.firstChild);
          }
        });
      },
      before: function () {
        return Ja(this, arguments, function (a) {
          this.parentNode && this.parentNode.insertBefore(a, this);
        });
      },
      after: function () {
        return Ja(this, arguments, function (a) {
          this.parentNode && this.parentNode.insertBefore(a, this.nextSibling);
        });
      },
      empty: function () {
        for (var a, b = 0; null != (a = this[b]); b++)
          1 === a.nodeType && (r.cleanData(na(a, !1)), (a.textContent = ""));
        return this;
      },
      clone: function (a, b) {
        return (
          (a = null != a && a),
          (b = null == b ? a : b),
          this.map(function () {
            return r.clone(this, a, b);
          })
        );
      },
      html: function (a) {
        return T(
          this,
          function (a) {
            var b = this[0] || {},
              c = 0,
              d = this.length;
            if (void 0 === a && 1 === b.nodeType) return b.innerHTML;
            if (
              "string" == typeof a &&
              !Aa.test(a) &&
              !ma[(ka.exec(a) || ["", ""])[1].toLowerCase()]
            ) {
              a = r.htmlPrefilter(a);
              try {
                for (; c < d; c++)
                  (b = this[c] || {}),
                    1 === b.nodeType &&
                      (r.cleanData(na(b, !1)), (b.innerHTML = a));
                b = 0;
              } catch (e) {}
            }
            b && this.empty().append(a);
          },
          null,
          a,
          arguments.length
        );
      },
      replaceWith: function () {
        var a = [];
        return Ja(
          this,
          arguments,
          function (b) {
            var c = this.parentNode;
            r.inArray(this, a) < 0 &&
              (r.cleanData(na(this)), c && c.replaceChild(b, this));
          },
          a
        );
      },
    }),
    r.each(
      {
        appendTo: "append",
        prependTo: "prepend",
        insertBefore: "before",
        insertAfter: "after",
        replaceAll: "replaceWith",
      },
      function (a, b) {
        r.fn[a] = function (a) {
          for (var c, d = [], e = r(a), f = e.length - 1, g = 0; g <= f; g++)
            (c = g === f ? this : this.clone(!0)),
              r(e[g])[b](c),
              h.apply(d, c.get());
          return this.pushStack(d);
        };
      }
    );
  var La = /^margin/,
    Ma = new RegExp("^(" + aa + ")(?!px)[a-z%]+$", "i"),
    Na = function (b) {
      var c = b.ownerDocument.defaultView;
      return (c && c.opener) || (c = a), c.getComputedStyle(b);
    };
  !(function () {
    function b() {
      if (i) {
        (i.style.cssText =
          "box-sizing:border-box;position:relative;display:block;margin:auto;border:1px;padding:1px;top:1%;width:50%"),
          (i.innerHTML = ""),
          ra.appendChild(h);
        var b = a.getComputedStyle(i);
        (c = "1%" !== b.top),
          (g = "2px" === b.marginLeft),
          (e = "4px" === b.width),
          (i.style.marginRight = "50%"),
          (f = "4px" === b.marginRight),
          ra.removeChild(h),
          (i = null);
      }
    }
    var c,
      e,
      f,
      g,
      h = d.createElement("div"),
      i = d.createElement("div");
    i.style &&
      ((i.style.backgroundClip = "content-box"),
      (i.cloneNode(!0).style.backgroundClip = ""),
      (o.clearCloneStyle = "content-box" === i.style.backgroundClip),
      (h.style.cssText =
        "border:0;width:8px;height:0;top:0;left:-9999px;padding:0;margin-top:1px;position:absolute"),
      h.appendChild(i),
      r.extend(o, {
        pixelPosition: function () {
          return b(), c;
        },
        boxSizingReliable: function () {
          return b(), e;
        },
        pixelMarginRight: function () {
          return b(), f;
        },
        reliableMarginLeft: function () {
          return b(), g;
        },
      }));
  })();
  function Oa(a, b, c) {
    var d,
      e,
      f,
      g,
      h = a.style;
    return (
      (c = c || Na(a)),
      c &&
        ((g = c.getPropertyValue(b) || c[b]),
        "" !== g || r.contains(a.ownerDocument, a) || (g = r.style(a, b)),
        !o.pixelMarginRight() &&
          Ma.test(g) &&
          La.test(b) &&
          ((d = h.width),
          (e = h.minWidth),
          (f = h.maxWidth),
          (h.minWidth = h.maxWidth = h.width = g),
          (g = c.width),
          (h.width = d),
          (h.minWidth = e),
          (h.maxWidth = f))),
      void 0 !== g ? g + "" : g
    );
  }
  function Pa(a, b) {
    return {
      get: function () {
        return a()
          ? void delete this.get
          : (this.get = b).apply(this, arguments);
      },
    };
  }
  var Qa = /^(none|table(?!-c[ea]).+)/,
    Ra = /^--/,
    Sa = { position: "absolute", visibility: "hidden", display: "block" },
    Ta = { letterSpacing: "0", fontWeight: "400" },
    Ua = ["Webkit", "Moz", "ms"],
    Va = d.createElement("div").style;
  function Wa(a) {
    if (a in Va) return a;
    var b = a[0].toUpperCase() + a.slice(1),
      c = Ua.length;
    while (c--) if (((a = Ua[c] + b), a in Va)) return a;
  }
  function Xa(a) {
    var b = r.cssProps[a];
    return b || (b = r.cssProps[a] = Wa(a) || a), b;
  }
  function Ya(a, b, c) {
    var d = ba.exec(b);
    return d ? Math.max(0, d[2] - (c || 0)) + (d[3] || "px") : b;
  }
  function Za(a, b, c, d, e) {
    var f,
      g = 0;
    for (
      f = c === (d ? "border" : "content") ? 4 : "width" === b ? 1 : 0;
      f < 4;
      f += 2
    )
      "margin" === c && (g += r.css(a, c + ca[f], !0, e)),
        d
          ? ("content" === c && (g -= r.css(a, "padding" + ca[f], !0, e)),
            "margin" !== c &&
              (g -= r.css(a, "border" + ca[f] + "Width", !0, e)))
          : ((g += r.css(a, "padding" + ca[f], !0, e)),
            "padding" !== c &&
              (g += r.css(a, "border" + ca[f] + "Width", !0, e)));
    return g;
  }
  function $a(a, b, c) {
    var d,
      e = Na(a),
      f = Oa(a, b, e),
      g = "border-box" === r.css(a, "boxSizing", !1, e);
    return Ma.test(f)
      ? f
      : ((d = g && (o.boxSizingReliable() || f === a.style[b])),
        "auto" === f && (f = a["offset" + b[0].toUpperCase() + b.slice(1)]),
        (f = parseFloat(f) || 0),
        f + Za(a, b, c || (g ? "border" : "content"), d, e) + "px");
  }
  r.extend({
    cssHooks: {
      opacity: {
        get: function (a, b) {
          if (b) {
            var c = Oa(a, "opacity");
            return "" === c ? "1" : c;
          }
        },
      },
    },
    cssNumber: {
      animationIterationCount: !0,
      columnCount: !0,
      fillOpacity: !0,
      flexGrow: !0,
      flexShrink: !0,
      fontWeight: !0,
      lineHeight: !0,
      opacity: !0,
      order: !0,
      orphans: !0,
      widows: !0,
      zIndex: !0,
      zoom: !0,
    },
    cssProps: { float: "cssFloat" },
    style: function (a, b, c, d) {
      if (a && 3 !== a.nodeType && 8 !== a.nodeType && a.style) {
        var e,
          f,
          g,
          h = r.camelCase(b),
          i = Ra.test(b),
          j = a.style;
        return (
          i || (b = Xa(h)),
          (g = r.cssHooks[b] || r.cssHooks[h]),
          void 0 === c
            ? g && "get" in g && void 0 !== (e = g.get(a, !1, d))
              ? e
              : j[b]
            : ((f = typeof c),
              "string" === f &&
                (e = ba.exec(c)) &&
                e[1] &&
                ((c = fa(a, b, e)), (f = "number")),
              null != c &&
                c === c &&
                ("number" === f &&
                  (c += (e && e[3]) || (r.cssNumber[h] ? "" : "px")),
                o.clearCloneStyle ||
                  "" !== c ||
                  0 !== b.indexOf("background") ||
                  (j[b] = "inherit"),
                (g && "set" in g && void 0 === (c = g.set(a, c, d))) ||
                  (i ? j.setProperty(b, c) : (j[b] = c))),
              void 0)
        );
      }
    },
    css: function (a, b, c, d) {
      var e,
        f,
        g,
        h = r.camelCase(b),
        i = Ra.test(b);
      return (
        i || (b = Xa(h)),
        (g = r.cssHooks[b] || r.cssHooks[h]),
        g && "get" in g && (e = g.get(a, !0, c)),
        void 0 === e && (e = Oa(a, b, d)),
        "normal" === e && b in Ta && (e = Ta[b]),
        "" === c || c
          ? ((f = parseFloat(e)), c === !0 || isFinite(f) ? f || 0 : e)
          : e
      );
    },
  }),
    r.each(["height", "width"], function (a, b) {
      r.cssHooks[b] = {
        get: function (a, c, d) {
          if (c)
            return !Qa.test(r.css(a, "display")) ||
              (a.getClientRects().length && a.getBoundingClientRect().width)
              ? $a(a, b, d)
              : ea(a, Sa, function () {
                  return $a(a, b, d);
                });
        },
        set: function (a, c, d) {
          var e,
            f = d && Na(a),
            g =
              d &&
              Za(a, b, d, "border-box" === r.css(a, "boxSizing", !1, f), f);
          return (
            g &&
              (e = ba.exec(c)) &&
              "px" !== (e[3] || "px") &&
              ((a.style[b] = c), (c = r.css(a, b))),
            Ya(a, c, g)
          );
        },
      };
    }),
    (r.cssHooks.marginLeft = Pa(o.reliableMarginLeft, function (a, b) {
      if (b)
        return (
          (parseFloat(Oa(a, "marginLeft")) ||
            a.getBoundingClientRect().left -
              ea(a, { marginLeft: 0 }, function () {
                return a.getBoundingClientRect().left;
              })) + "px"
        );
    })),
    r.each({ margin: "", padding: "", border: "Width" }, function (a, b) {
      (r.cssHooks[a + b] = {
        expand: function (c) {
          for (
            var d = 0, e = {}, f = "string" == typeof c ? c.split(" ") : [c];
            d < 4;
            d++
          )
            e[a + ca[d] + b] = f[d] || f[d - 2] || f[0];
          return e;
        },
      }),
        La.test(a) || (r.cssHooks[a + b].set = Ya);
    }),
    r.fn.extend({
      css: function (a, b) {
        return T(
          this,
          function (a, b, c) {
            var d,
              e,
              f = {},
              g = 0;
            if (Array.isArray(b)) {
              for (d = Na(a), e = b.length; g < e; g++)
                f[b[g]] = r.css(a, b[g], !1, d);
              return f;
            }
            return void 0 !== c ? r.style(a, b, c) : r.css(a, b);
          },
          a,
          b,
          arguments.length > 1
        );
      },
    });
  function _a(a, b, c, d, e) {
    return new _a.prototype.init(a, b, c, d, e);
  }
  (r.Tween = _a),
    (_a.prototype = {
      constructor: _a,
      init: function (a, b, c, d, e, f) {
        (this.elem = a),
          (this.prop = c),
          (this.easing = e || r.easing._default),
          (this.options = b),
          (this.start = this.now = this.cur()),
          (this.end = d),
          (this.unit = f || (r.cssNumber[c] ? "" : "px"));
      },
      cur: function () {
        var a = _a.propHooks[this.prop];
        return a && a.get ? a.get(this) : _a.propHooks._default.get(this);
      },
      run: function (a) {
        var b,
          c = _a.propHooks[this.prop];
        return (
          this.options.duration
            ? (this.pos = b =
                r.easing[this.easing](
                  a,
                  this.options.duration * a,
                  0,
                  1,
                  this.options.duration
                ))
            : (this.pos = b = a),
          (this.now = (this.end - this.start) * b + this.start),
          this.options.step &&
            this.options.step.call(this.elem, this.now, this),
          c && c.set ? c.set(this) : _a.propHooks._default.set(this),
          this
        );
      },
    }),
    (_a.prototype.init.prototype = _a.prototype),
    (_a.propHooks = {
      _default: {
        get: function (a) {
          var b;
          return 1 !== a.elem.nodeType ||
            (null != a.elem[a.prop] && null == a.elem.style[a.prop])
            ? a.elem[a.prop]
            : ((b = r.css(a.elem, a.prop, "")), b && "auto" !== b ? b : 0);
        },
        set: function (a) {
          r.fx.step[a.prop]
            ? r.fx.step[a.prop](a)
            : 1 !== a.elem.nodeType ||
              (null == a.elem.style[r.cssProps[a.prop]] && !r.cssHooks[a.prop])
            ? (a.elem[a.prop] = a.now)
            : r.style(a.elem, a.prop, a.now + a.unit);
        },
      },
    }),
    (_a.propHooks.scrollTop = _a.propHooks.scrollLeft =
      {
        set: function (a) {
          a.elem.nodeType && a.elem.parentNode && (a.elem[a.prop] = a.now);
        },
      }),
    (r.easing = {
      linear: function (a) {
        return a;
      },
      swing: function (a) {
        return 0.5 - Math.cos(a * Math.PI) / 2;
      },
      _default: "swing",
    }),
    (r.fx = _a.prototype.init),
    (r.fx.step = {});
  var ab,
    bb,
    cb = /^(?:toggle|show|hide)$/,
    db = /queueHooks$/;
  function eb() {
    bb &&
      (d.hidden === !1 && a.requestAnimationFrame
        ? a.requestAnimationFrame(eb)
        : a.setTimeout(eb, r.fx.interval),
      r.fx.tick());
  }
  function fb() {
    return (
      a.setTimeout(function () {
        ab = void 0;
      }),
      (ab = r.now())
    );
  }
  function gb(a, b) {
    var c,
      d = 0,
      e = { height: a };
    for (b = b ? 1 : 0; d < 4; d += 2 - b)
      (c = ca[d]), (e["margin" + c] = e["padding" + c] = a);
    return b && (e.opacity = e.width = a), e;
  }
  function hb(a, b, c) {
    for (
      var d,
        e = (kb.tweeners[b] || []).concat(kb.tweeners["*"]),
        f = 0,
        g = e.length;
      f < g;
      f++
    )
      if ((d = e[f].call(c, b, a))) return d;
  }
  function ib(a, b, c) {
    var d,
      e,
      f,
      g,
      h,
      i,
      j,
      k,
      l = "width" in b || "height" in b,
      m = this,
      n = {},
      o = a.style,
      p = a.nodeType && da(a),
      q = W.get(a, "fxshow");
    c.queue ||
      ((g = r._queueHooks(a, "fx")),
      null == g.unqueued &&
        ((g.unqueued = 0),
        (h = g.empty.fire),
        (g.empty.fire = function () {
          g.unqueued || h();
        })),
      g.unqueued++,
      m.always(function () {
        m.always(function () {
          g.unqueued--, r.queue(a, "fx").length || g.empty.fire();
        });
      }));
    for (d in b)
      if (((e = b[d]), cb.test(e))) {
        if (
          (delete b[d], (f = f || "toggle" === e), e === (p ? "hide" : "show"))
        ) {
          if ("show" !== e || !q || void 0 === q[d]) continue;
          p = !0;
        }
        n[d] = (q && q[d]) || r.style(a, d);
      }
    if (((i = !r.isEmptyObject(b)), i || !r.isEmptyObject(n))) {
      l &&
        1 === a.nodeType &&
        ((c.overflow = [o.overflow, o.overflowX, o.overflowY]),
        (j = q && q.display),
        null == j && (j = W.get(a, "display")),
        (k = r.css(a, "display")),
        "none" === k &&
          (j
            ? (k = j)
            : (ia([a], !0),
              (j = a.style.display || j),
              (k = r.css(a, "display")),
              ia([a]))),
        ("inline" === k || ("inline-block" === k && null != j)) &&
          "none" === r.css(a, "float") &&
          (i ||
            (m.done(function () {
              o.display = j;
            }),
            null == j && ((k = o.display), (j = "none" === k ? "" : k))),
          (o.display = "inline-block"))),
        c.overflow &&
          ((o.overflow = "hidden"),
          m.always(function () {
            (o.overflow = c.overflow[0]),
              (o.overflowX = c.overflow[1]),
              (o.overflowY = c.overflow[2]);
          })),
        (i = !1);
      for (d in n)
        i ||
          (q
            ? "hidden" in q && (p = q.hidden)
            : (q = W.access(a, "fxshow", { display: j })),
          f && (q.hidden = !p),
          p && ia([a], !0),
          m.done(function () {
            p || ia([a]), W.remove(a, "fxshow");
            for (d in n) r.style(a, d, n[d]);
          })),
          (i = hb(p ? q[d] : 0, d, m)),
          d in q || ((q[d] = i.start), p && ((i.end = i.start), (i.start = 0)));
    }
  }
  function jb(a, b) {
    var c, d, e, f, g;
    for (c in a)
      if (
        ((d = r.camelCase(c)),
        (e = b[d]),
        (f = a[c]),
        Array.isArray(f) && ((e = f[1]), (f = a[c] = f[0])),
        c !== d && ((a[d] = f), delete a[c]),
        (g = r.cssHooks[d]),
        g && "expand" in g)
      ) {
        (f = g.expand(f)), delete a[d];
        for (c in f) c in a || ((a[c] = f[c]), (b[c] = e));
      } else b[d] = e;
  }
  function kb(a, b, c) {
    var d,
      e,
      f = 0,
      g = kb.prefilters.length,
      h = r.Deferred().always(function () {
        delete i.elem;
      }),
      i = function () {
        if (e) return !1;
        for (
          var b = ab || fb(),
            c = Math.max(0, j.startTime + j.duration - b),
            d = c / j.duration || 0,
            f = 1 - d,
            g = 0,
            i = j.tweens.length;
          g < i;
          g++
        )
          j.tweens[g].run(f);
        return (
          h.notifyWith(a, [j, f, c]),
          f < 1 && i
            ? c
            : (i || h.notifyWith(a, [j, 1, 0]), h.resolveWith(a, [j]), !1)
        );
      },
      j = h.promise({
        elem: a,
        props: r.extend({}, b),
        opts: r.extend(!0, { specialEasing: {}, easing: r.easing._default }, c),
        originalProperties: b,
        originalOptions: c,
        startTime: ab || fb(),
        duration: c.duration,
        tweens: [],
        createTween: function (b, c) {
          var d = r.Tween(
            a,
            j.opts,
            b,
            c,
            j.opts.specialEasing[b] || j.opts.easing
          );
          return j.tweens.push(d), d;
        },
        stop: function (b) {
          var c = 0,
            d = b ? j.tweens.length : 0;
          if (e) return this;
          for (e = !0; c < d; c++) j.tweens[c].run(1);
          return (
            b
              ? (h.notifyWith(a, [j, 1, 0]), h.resolveWith(a, [j, b]))
              : h.rejectWith(a, [j, b]),
            this
          );
        },
      }),
      k = j.props;
    for (jb(k, j.opts.specialEasing); f < g; f++)
      if ((d = kb.prefilters[f].call(j, a, k, j.opts)))
        return (
          r.isFunction(d.stop) &&
            (r._queueHooks(j.elem, j.opts.queue).stop = r.proxy(d.stop, d)),
          d
        );
    return (
      r.map(k, hb, j),
      r.isFunction(j.opts.start) && j.opts.start.call(a, j),
      j
        .progress(j.opts.progress)
        .done(j.opts.done, j.opts.complete)
        .fail(j.opts.fail)
        .always(j.opts.always),
      r.fx.timer(r.extend(i, { elem: a, anim: j, queue: j.opts.queue })),
      j
    );
  }
  (r.Animation = r.extend(kb, {
    tweeners: {
      "*": [
        function (a, b) {
          var c = this.createTween(a, b);
          return fa(c.elem, a, ba.exec(b), c), c;
        },
      ],
    },
    tweener: function (a, b) {
      r.isFunction(a) ? ((b = a), (a = ["*"])) : (a = a.match(L));
      for (var c, d = 0, e = a.length; d < e; d++)
        (c = a[d]),
          (kb.tweeners[c] = kb.tweeners[c] || []),
          kb.tweeners[c].unshift(b);
    },
    prefilters: [ib],
    prefilter: function (a, b) {
      b ? kb.prefilters.unshift(a) : kb.prefilters.push(a);
    },
  })),
    (r.speed = function (a, b, c) {
      var d =
        a && "object" == typeof a
          ? r.extend({}, a)
          : {
              complete: c || (!c && b) || (r.isFunction(a) && a),
              duration: a,
              easing: (c && b) || (b && !r.isFunction(b) && b),
            };
      return (
        r.fx.off
          ? (d.duration = 0)
          : "number" != typeof d.duration &&
            (d.duration in r.fx.speeds
              ? (d.duration = r.fx.speeds[d.duration])
              : (d.duration = r.fx.speeds._default)),
        (null != d.queue && d.queue !== !0) || (d.queue = "fx"),
        (d.old = d.complete),
        (d.complete = function () {
          r.isFunction(d.old) && d.old.call(this),
            d.queue && r.dequeue(this, d.queue);
        }),
        d
      );
    }),
    r.fn.extend({
      fadeTo: function (a, b, c, d) {
        return this.filter(da)
          .css("opacity", 0)
          .show()
          .end()
          .animate({ opacity: b }, a, c, d);
      },
      animate: function (a, b, c, d) {
        var e = r.isEmptyObject(a),
          f = r.speed(b, c, d),
          g = function () {
            var b = kb(this, r.extend({}, a), f);
            (e || W.get(this, "finish")) && b.stop(!0);
          };
        return (
          (g.finish = g),
          e || f.queue === !1 ? this.each(g) : this.queue(f.queue, g)
        );
      },
      stop: function (a, b, c) {
        var d = function (a) {
          var b = a.stop;
          delete a.stop, b(c);
        };
        return (
          "string" != typeof a && ((c = b), (b = a), (a = void 0)),
          b && a !== !1 && this.queue(a || "fx", []),
          this.each(function () {
            var b = !0,
              e = null != a && a + "queueHooks",
              f = r.timers,
              g = W.get(this);
            if (e) g[e] && g[e].stop && d(g[e]);
            else for (e in g) g[e] && g[e].stop && db.test(e) && d(g[e]);
            for (e = f.length; e--; )
              f[e].elem !== this ||
                (null != a && f[e].queue !== a) ||
                (f[e].anim.stop(c), (b = !1), f.splice(e, 1));
            (!b && c) || r.dequeue(this, a);
          })
        );
      },
      finish: function (a) {
        return (
          a !== !1 && (a = a || "fx"),
          this.each(function () {
            var b,
              c = W.get(this),
              d = c[a + "queue"],
              e = c[a + "queueHooks"],
              f = r.timers,
              g = d ? d.length : 0;
            for (
              c.finish = !0,
                r.queue(this, a, []),
                e && e.stop && e.stop.call(this, !0),
                b = f.length;
              b--;

            )
              f[b].elem === this &&
                f[b].queue === a &&
                (f[b].anim.stop(!0), f.splice(b, 1));
            for (b = 0; b < g; b++)
              d[b] && d[b].finish && d[b].finish.call(this);
            delete c.finish;
          })
        );
      },
    }),
    r.each(["toggle", "show", "hide"], function (a, b) {
      var c = r.fn[b];
      r.fn[b] = function (a, d, e) {
        return null == a || "boolean" == typeof a
          ? c.apply(this, arguments)
          : this.animate(gb(b, !0), a, d, e);
      };
    }),
    r.each(
      {
        slideDown: gb("show"),
        slideUp: gb("hide"),
        slideToggle: gb("toggle"),
        fadeIn: { opacity: "show" },
        fadeOut: { opacity: "hide" },
        fadeToggle: { opacity: "toggle" },
      },
      function (a, b) {
        r.fn[a] = function (a, c, d) {
          return this.animate(b, a, c, d);
        };
      }
    ),
    (r.timers = []),
    (r.fx.tick = function () {
      var a,
        b = 0,
        c = r.timers;
      for (ab = r.now(); b < c.length; b++)
        (a = c[b]), a() || c[b] !== a || c.splice(b--, 1);
      c.length || r.fx.stop(), (ab = void 0);
    }),
    (r.fx.timer = function (a) {
      r.timers.push(a), r.fx.start();
    }),
    (r.fx.interval = 13),
    (r.fx.start = function () {
      bb || ((bb = !0), eb());
    }),
    (r.fx.stop = function () {
      bb = null;
    }),
    (r.fx.speeds = { slow: 600, fast: 200, _default: 400 }),
    (r.fn.delay = function (b, c) {
      return (
        (b = r.fx ? r.fx.speeds[b] || b : b),
        (c = c || "fx"),
        this.queue(c, function (c, d) {
          var e = a.setTimeout(c, b);
          d.stop = function () {
            a.clearTimeout(e);
          };
        })
      );
    }),
    (function () {
      var a = d.createElement("input"),
        b = d.createElement("select"),
        c = b.appendChild(d.createElement("option"));
      (a.type = "checkbox"),
        (o.checkOn = "" !== a.value),
        (o.optSelected = c.selected),
        (a = d.createElement("input")),
        (a.value = "t"),
        (a.type = "radio"),
        (o.radioValue = "t" === a.value);
    })();
  var lb,
    mb = r.expr.attrHandle;
  r.fn.extend({
    attr: function (a, b) {
      return T(this, r.attr, a, b, arguments.length > 1);
    },
    removeAttr: function (a) {
      return this.each(function () {
        r.removeAttr(this, a);
      });
    },
  }),
    r.extend({
      attr: function (a, b, c) {
        var d,
          e,
          f = a.nodeType;
        if (3 !== f && 8 !== f && 2 !== f)
          return "undefined" == typeof a.getAttribute
            ? r.prop(a, b, c)
            : ((1 === f && r.isXMLDoc(a)) ||
                (e =
                  r.attrHooks[b.toLowerCase()] ||
                  (r.expr.match.bool.test(b) ? lb : void 0)),
              void 0 !== c
                ? null === c
                  ? void r.removeAttr(a, b)
                  : e && "set" in e && void 0 !== (d = e.set(a, c, b))
                  ? d
                  : (a.setAttribute(b, c + ""), c)
                : e && "get" in e && null !== (d = e.get(a, b))
                ? d
                : ((d = r.find.attr(a, b)), null == d ? void 0 : d));
      },
      attrHooks: {
        type: {
          set: function (a, b) {
            if (!o.radioValue && "radio" === b && B(a, "input")) {
              var c = a.value;
              return a.setAttribute("type", b), c && (a.value = c), b;
            }
          },
        },
      },
      removeAttr: function (a, b) {
        var c,
          d = 0,
          e = b && b.match(L);
        if (e && 1 === a.nodeType) while ((c = e[d++])) a.removeAttribute(c);
      },
    }),
    (lb = {
      set: function (a, b, c) {
        return b === !1 ? r.removeAttr(a, c) : a.setAttribute(c, c), c;
      },
    }),
    r.each(r.expr.match.bool.source.match(/\w+/g), function (a, b) {
      var c = mb[b] || r.find.attr;
      mb[b] = function (a, b, d) {
        var e,
          f,
          g = b.toLowerCase();
        return (
          d ||
            ((f = mb[g]),
            (mb[g] = e),
            (e = null != c(a, b, d) ? g : null),
            (mb[g] = f)),
          e
        );
      };
    });
  var nb = /^(?:input|select|textarea|button)$/i,
    ob = /^(?:a|area)$/i;
  r.fn.extend({
    prop: function (a, b) {
      return T(this, r.prop, a, b, arguments.length > 1);
    },
    removeProp: function (a) {
      return this.each(function () {
        delete this[r.propFix[a] || a];
      });
    },
  }),
    r.extend({
      prop: function (a, b, c) {
        var d,
          e,
          f = a.nodeType;
        if (3 !== f && 8 !== f && 2 !== f)
          return (
            (1 === f && r.isXMLDoc(a)) ||
              ((b = r.propFix[b] || b), (e = r.propHooks[b])),
            void 0 !== c
              ? e && "set" in e && void 0 !== (d = e.set(a, c, b))
                ? d
                : (a[b] = c)
              : e && "get" in e && null !== (d = e.get(a, b))
              ? d
              : a[b]
          );
      },
      propHooks: {
        tabIndex: {
          get: function (a) {
            var b = r.find.attr(a, "tabindex");
            return b
              ? parseInt(b, 10)
              : nb.test(a.nodeName) || (ob.test(a.nodeName) && a.href)
              ? 0
              : -1;
          },
        },
      },
      propFix: { for: "htmlFor", class: "className" },
    }),
    o.optSelected ||
      (r.propHooks.selected = {
        get: function (a) {
          var b = a.parentNode;
          return b && b.parentNode && b.parentNode.selectedIndex, null;
        },
        set: function (a) {
          var b = a.parentNode;
          b && (b.selectedIndex, b.parentNode && b.parentNode.selectedIndex);
        },
      }),
    r.each(
      [
        "tabIndex",
        "readOnly",
        "maxLength",
        "cellSpacing",
        "cellPadding",
        "rowSpan",
        "colSpan",
        "useMap",
        "frameBorder",
        "contentEditable",
      ],
      function () {
        r.propFix[this.toLowerCase()] = this;
      }
    );
  function pb(a) {
    var b = a.match(L) || [];
    return b.join(" ");
  }
  function qb(a) {
    return (a.getAttribute && a.getAttribute("class")) || "";
  }
  r.fn.extend({
    addClass: function (a) {
      var b,
        c,
        d,
        e,
        f,
        g,
        h,
        i = 0;
      if (r.isFunction(a))
        return this.each(function (b) {
          r(this).addClass(a.call(this, b, qb(this)));
        });
      if ("string" == typeof a && a) {
        b = a.match(L) || [];
        while ((c = this[i++]))
          if (((e = qb(c)), (d = 1 === c.nodeType && " " + pb(e) + " "))) {
            g = 0;
            while ((f = b[g++])) d.indexOf(" " + f + " ") < 0 && (d += f + " ");
            (h = pb(d)), e !== h && c.setAttribute("class", h);
          }
      }
      return this;
    },
    removeClass: function (a) {
      var b,
        c,
        d,
        e,
        f,
        g,
        h,
        i = 0;
      if (r.isFunction(a))
        return this.each(function (b) {
          r(this).removeClass(a.call(this, b, qb(this)));
        });
      if (!arguments.length) return this.attr("class", "");
      if ("string" == typeof a && a) {
        b = a.match(L) || [];
        while ((c = this[i++]))
          if (((e = qb(c)), (d = 1 === c.nodeType && " " + pb(e) + " "))) {
            g = 0;
            while ((f = b[g++]))
              while (d.indexOf(" " + f + " ") > -1)
                d = d.replace(" " + f + " ", " ");
            (h = pb(d)), e !== h && c.setAttribute("class", h);
          }
      }
      return this;
    },
    toggleClass: function (a, b) {
      var c = typeof a;
      return "boolean" == typeof b && "string" === c
        ? b
          ? this.addClass(a)
          : this.removeClass(a)
        : r.isFunction(a)
        ? this.each(function (c) {
            r(this).toggleClass(a.call(this, c, qb(this), b), b);
          })
        : this.each(function () {
            var b, d, e, f;
            if ("string" === c) {
              (d = 0), (e = r(this)), (f = a.match(L) || []);
              while ((b = f[d++]))
                e.hasClass(b) ? e.removeClass(b) : e.addClass(b);
            } else (void 0 !== a && "boolean" !== c) || ((b = qb(this)), b && W.set(this, "__className__", b), this.setAttribute && this.setAttribute("class", b || a === !1 ? "" : W.get(this, "__className__") || ""));
          });
    },
    hasClass: function (a) {
      var b,
        c,
        d = 0;
      b = " " + a + " ";
      while ((c = this[d++]))
        if (1 === c.nodeType && (" " + pb(qb(c)) + " ").indexOf(b) > -1)
          return !0;
      return !1;
    },
  });
  var rb = /\r/g;
  r.fn.extend({
    val: function (a) {
      var b,
        c,
        d,
        e = this[0];
      {
        if (arguments.length)
          return (
            (d = r.isFunction(a)),
            this.each(function (c) {
              var e;
              1 === this.nodeType &&
                ((e = d ? a.call(this, c, r(this).val()) : a),
                null == e
                  ? (e = "")
                  : "number" == typeof e
                  ? (e += "")
                  : Array.isArray(e) &&
                    (e = r.map(e, function (a) {
                      return null == a ? "" : a + "";
                    })),
                (b =
                  r.valHooks[this.type] ||
                  r.valHooks[this.nodeName.toLowerCase()]),
                (b && "set" in b && void 0 !== b.set(this, e, "value")) ||
                  (this.value = e));
            })
          );
        if (e)
          return (
            (b = r.valHooks[e.type] || r.valHooks[e.nodeName.toLowerCase()]),
            b && "get" in b && void 0 !== (c = b.get(e, "value"))
              ? c
              : ((c = e.value),
                "string" == typeof c ? c.replace(rb, "") : null == c ? "" : c)
          );
      }
    },
  }),
    r.extend({
      valHooks: {
        option: {
          get: function (a) {
            var b = r.find.attr(a, "value");
            return null != b ? b : pb(r.text(a));
          },
        },
        select: {
          get: function (a) {
            var b,
              c,
              d,
              e = a.options,
              f = a.selectedIndex,
              g = "select-one" === a.type,
              h = g ? null : [],
              i = g ? f + 1 : e.length;
            for (d = f < 0 ? i : g ? f : 0; d < i; d++)
              if (
                ((c = e[d]),
                (c.selected || d === f) &&
                  !c.disabled &&
                  (!c.parentNode.disabled || !B(c.parentNode, "optgroup")))
              ) {
                if (((b = r(c).val()), g)) return b;
                h.push(b);
              }
            return h;
          },
          set: function (a, b) {
            var c,
              d,
              e = a.options,
              f = r.makeArray(b),
              g = e.length;
            while (g--)
              (d = e[g]),
                (d.selected = r.inArray(r.valHooks.option.get(d), f) > -1) &&
                  (c = !0);
            return c || (a.selectedIndex = -1), f;
          },
        },
      },
    }),
    r.each(["radio", "checkbox"], function () {
      (r.valHooks[this] = {
        set: function (a, b) {
          if (Array.isArray(b))
            return (a.checked = r.inArray(r(a).val(), b) > -1);
        },
      }),
        o.checkOn ||
          (r.valHooks[this].get = function (a) {
            return null === a.getAttribute("value") ? "on" : a.value;
          });
    });
  var sb = /^(?:focusinfocus|focusoutblur)$/;
  r.extend(r.event, {
    trigger: function (b, c, e, f) {
      var g,
        h,
        i,
        j,
        k,
        m,
        n,
        o = [e || d],
        p = l.call(b, "type") ? b.type : b,
        q = l.call(b, "namespace") ? b.namespace.split(".") : [];
      if (
        ((h = i = e = e || d),
        3 !== e.nodeType &&
          8 !== e.nodeType &&
          !sb.test(p + r.event.triggered) &&
          (p.indexOf(".") > -1 &&
            ((q = p.split(".")), (p = q.shift()), q.sort()),
          (k = p.indexOf(":") < 0 && "on" + p),
          (b = b[r.expando] ? b : new r.Event(p, "object" == typeof b && b)),
          (b.isTrigger = f ? 2 : 3),
          (b.namespace = q.join(".")),
          (b.rnamespace = b.namespace
            ? new RegExp("(^|\\.)" + q.join("\\.(?:.*\\.|)") + "(\\.|$)")
            : null),
          (b.result = void 0),
          b.target || (b.target = e),
          (c = null == c ? [b] : r.makeArray(c, [b])),
          (n = r.event.special[p] || {}),
          f || !n.trigger || n.trigger.apply(e, c) !== !1))
      ) {
        if (!f && !n.noBubble && !r.isWindow(e)) {
          for (
            j = n.delegateType || p, sb.test(j + p) || (h = h.parentNode);
            h;
            h = h.parentNode
          )
            o.push(h), (i = h);
          i === (e.ownerDocument || d) &&
            o.push(i.defaultView || i.parentWindow || a);
        }
        g = 0;
        while ((h = o[g++]) && !b.isPropagationStopped())
          (b.type = g > 1 ? j : n.bindType || p),
            (m = (W.get(h, "events") || {})[b.type] && W.get(h, "handle")),
            m && m.apply(h, c),
            (m = k && h[k]),
            m &&
              m.apply &&
              U(h) &&
              ((b.result = m.apply(h, c)),
              b.result === !1 && b.preventDefault());
        return (
          (b.type = p),
          f ||
            b.isDefaultPrevented() ||
            (n._default && n._default.apply(o.pop(), c) !== !1) ||
            !U(e) ||
            (k &&
              r.isFunction(e[p]) &&
              !r.isWindow(e) &&
              ((i = e[k]),
              i && (e[k] = null),
              (r.event.triggered = p),
              e[p](),
              (r.event.triggered = void 0),
              i && (e[k] = i))),
          b.result
        );
      }
    },
    simulate: function (a, b, c) {
      var d = r.extend(new r.Event(), c, { type: a, isSimulated: !0 });
      r.event.trigger(d, null, b);
    },
  }),
    r.fn.extend({
      trigger: function (a, b) {
        return this.each(function () {
          r.event.trigger(a, b, this);
        });
      },
      triggerHandler: function (a, b) {
        var c = this[0];
        if (c) return r.event.trigger(a, b, c, !0);
      },
    }),
    r.each(
      "blur focus focusin focusout resize scroll click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup contextmenu".split(
        " "
      ),
      function (a, b) {
        r.fn[b] = function (a, c) {
          return arguments.length > 0
            ? this.on(b, null, a, c)
            : this.trigger(b);
        };
      }
    ),
    r.fn.extend({
      hover: function (a, b) {
        return this.mouseenter(a).mouseleave(b || a);
      },
    }),
    (o.focusin = "onfocusin" in a),
    o.focusin ||
      r.each({ focus: "focusin", blur: "focusout" }, function (a, b) {
        var c = function (a) {
          r.event.simulate(b, a.target, r.event.fix(a));
        };
        r.event.special[b] = {
          setup: function () {
            var d = this.ownerDocument || this,
              e = W.access(d, b);
            e || d.addEventListener(a, c, !0), W.access(d, b, (e || 0) + 1);
          },
          teardown: function () {
            var d = this.ownerDocument || this,
              e = W.access(d, b) - 1;
            e
              ? W.access(d, b, e)
              : (d.removeEventListener(a, c, !0), W.remove(d, b));
          },
        };
      });
  var tb = a.location,
    ub = r.now(),
    vb = /\?/;
  r.parseXML = function (b) {
    var c;
    if (!b || "string" != typeof b) return null;
    try {
      c = new a.DOMParser().parseFromString(b, "text/xml");
    } catch (d) {
      c = void 0;
    }
    return (
      (c && !c.getElementsByTagName("parsererror").length) ||
        r.error("Invalid XML: " + b),
      c
    );
  };
  var wb = /\[\]$/,
    xb = /\r?\n/g,
    yb = /^(?:submit|button|image|reset|file)$/i,
    zb = /^(?:input|select|textarea|keygen)/i;
  function Ab(a, b, c, d) {
    var e;
    if (Array.isArray(b))
      r.each(b, function (b, e) {
        c || wb.test(a)
          ? d(a, e)
          : Ab(
              a + "[" + ("object" == typeof e && null != e ? b : "") + "]",
              e,
              c,
              d
            );
      });
    else if (c || "object" !== r.type(b)) d(a, b);
    else for (e in b) Ab(a + "[" + e + "]", b[e], c, d);
  }
  (r.param = function (a, b) {
    var c,
      d = [],
      e = function (a, b) {
        var c = r.isFunction(b) ? b() : b;
        d[d.length] =
          encodeURIComponent(a) + "=" + encodeURIComponent(null == c ? "" : c);
      };
    if (Array.isArray(a) || (a.jquery && !r.isPlainObject(a)))
      r.each(a, function () {
        e(this.name, this.value);
      });
    else for (c in a) Ab(c, a[c], b, e);
    return d.join("&");
  }),
    r.fn.extend({
      serialize: function () {
        return r.param(this.serializeArray());
      },
      serializeArray: function () {
        return this.map(function () {
          var a = r.prop(this, "elements");
          return a ? r.makeArray(a) : this;
        })
          .filter(function () {
            var a = this.type;
            return (
              this.name &&
              !r(this).is(":disabled") &&
              zb.test(this.nodeName) &&
              !yb.test(a) &&
              (this.checked || !ja.test(a))
            );
          })
          .map(function (a, b) {
            var c = r(this).val();
            return null == c
              ? null
              : Array.isArray(c)
              ? r.map(c, function (a) {
                  return { name: b.name, value: a.replace(xb, "\r\n") };
                })
              : { name: b.name, value: c.replace(xb, "\r\n") };
          })
          .get();
      },
    });
  var Bb = /%20/g,
    Cb = /#.*$/,
    Db = /([?&])_=[^&]*/,
    Eb = /^(.*?):[ \t]*([^\r\n]*)$/gm,
    Fb = /^(?:about|app|app-storage|.+-extension|file|res|widget):$/,
    Gb = /^(?:GET|HEAD)$/,
    Hb = /^\/\//,
    Ib = {},
    Jb = {},
    Kb = "*/".concat("*"),
    Lb = d.createElement("a");
  Lb.href = tb.href;
  function Mb(a) {
    return function (b, c) {
      "string" != typeof b && ((c = b), (b = "*"));
      var d,
        e = 0,
        f = b.toLowerCase().match(L) || [];
      if (r.isFunction(c))
        while ((d = f[e++]))
          "+" === d[0]
            ? ((d = d.slice(1) || "*"), (a[d] = a[d] || []).unshift(c))
            : (a[d] = a[d] || []).push(c);
    };
  }
  function Nb(a, b, c, d) {
    var e = {},
      f = a === Jb;
    function g(h) {
      var i;
      return (
        (e[h] = !0),
        r.each(a[h] || [], function (a, h) {
          var j = h(b, c, d);
          return "string" != typeof j || f || e[j]
            ? f
              ? !(i = j)
              : void 0
            : (b.dataTypes.unshift(j), g(j), !1);
        }),
        i
      );
    }
    return g(b.dataTypes[0]) || (!e["*"] && g("*"));
  }
  function Ob(a, b) {
    var c,
      d,
      e = r.ajaxSettings.flatOptions || {};
    for (c in b) void 0 !== b[c] && ((e[c] ? a : d || (d = {}))[c] = b[c]);
    return d && r.extend(!0, a, d), a;
  }
  function Pb(a, b, c) {
    var d,
      e,
      f,
      g,
      h = a.contents,
      i = a.dataTypes;
    while ("*" === i[0])
      i.shift(),
        void 0 === d && (d = a.mimeType || b.getResponseHeader("Content-Type"));
    if (d)
      for (e in h)
        if (h[e] && h[e].test(d)) {
          i.unshift(e);
          break;
        }
    if (i[0] in c) f = i[0];
    else {
      for (e in c) {
        if (!i[0] || a.converters[e + " " + i[0]]) {
          f = e;
          break;
        }
        g || (g = e);
      }
      f = f || g;
    }
    if (f) return f !== i[0] && i.unshift(f), c[f];
  }
  function Qb(a, b, c, d) {
    var e,
      f,
      g,
      h,
      i,
      j = {},
      k = a.dataTypes.slice();
    if (k[1]) for (g in a.converters) j[g.toLowerCase()] = a.converters[g];
    f = k.shift();
    while (f)
      if (
        (a.responseFields[f] && (c[a.responseFields[f]] = b),
        !i && d && a.dataFilter && (b = a.dataFilter(b, a.dataType)),
        (i = f),
        (f = k.shift()))
      )
        if ("*" === f) f = i;
        else if ("*" !== i && i !== f) {
          if (((g = j[i + " " + f] || j["* " + f]), !g))
            for (e in j)
              if (
                ((h = e.split(" ")),
                h[1] === f && (g = j[i + " " + h[0]] || j["* " + h[0]]))
              ) {
                g === !0
                  ? (g = j[e])
                  : j[e] !== !0 && ((f = h[0]), k.unshift(h[1]));
                break;
              }
          if (g !== !0)
            if (g && a["throws"]) b = g(b);
            else
              try {
                b = g(b);
              } catch (l) {
                return {
                  state: "parsererror",
                  error: g ? l : "No conversion from " + i + " to " + f,
                };
              }
        }
    return { state: "success", data: b };
  }
  r.extend({
    active: 0,
    lastModified: {},
    etag: {},
    ajaxSettings: {
      url: tb.href,
      type: "GET",
      isLocal: Fb.test(tb.protocol),
      global: !0,
      processData: !0,
      async: !0,
      contentType: "application/x-www-form-urlencoded; charset=UTF-8",
      accepts: {
        "*": Kb,
        text: "text/plain",
        html: "text/html",
        xml: "application/xml, text/xml",
        json: "application/json, text/javascript",
      },
      contents: { xml: /\bxml\b/, html: /\bhtml/, json: /\bjson\b/ },
      responseFields: {
        xml: "responseXML",
        text: "responseText",
        json: "responseJSON",
      },
      converters: {
        "* text": String,
        "text html": !0,
        "text json": JSON.parse,
        "text xml": r.parseXML,
      },
      flatOptions: { url: !0, context: !0 },
    },
    ajaxSetup: function (a, b) {
      return b ? Ob(Ob(a, r.ajaxSettings), b) : Ob(r.ajaxSettings, a);
    },
    ajaxPrefilter: Mb(Ib),
    ajaxTransport: Mb(Jb),
    ajax: function (b, c) {
      "object" == typeof b && ((c = b), (b = void 0)), (c = c || {});
      var e,
        f,
        g,
        h,
        i,
        j,
        k,
        l,
        m,
        n,
        o = r.ajaxSetup({}, c),
        p = o.context || o,
        q = o.context && (p.nodeType || p.jquery) ? r(p) : r.event,
        s = r.Deferred(),
        t = r.Callbacks("once memory"),
        u = o.statusCode || {},
        v = {},
        w = {},
        x = "canceled",
        y = {
          readyState: 0,
          getResponseHeader: function (a) {
            var b;
            if (k) {
              if (!h) {
                h = {};
                while ((b = Eb.exec(g))) h[b[1].toLowerCase()] = b[2];
              }
              b = h[a.toLowerCase()];
            }
            return null == b ? null : b;
          },
          getAllResponseHeaders: function () {
            return k ? g : null;
          },
          setRequestHeader: function (a, b) {
            return (
              null == k &&
                ((a = w[a.toLowerCase()] = w[a.toLowerCase()] || a),
                (v[a] = b)),
              this
            );
          },
          overrideMimeType: function (a) {
            return null == k && (o.mimeType = a), this;
          },
          statusCode: function (a) {
            var b;
            if (a)
              if (k) y.always(a[y.status]);
              else for (b in a) u[b] = [u[b], a[b]];
            return this;
          },
          abort: function (a) {
            var b = a || x;
            return e && e.abort(b), A(0, b), this;
          },
        };
      if (
        (s.promise(y),
        (o.url = ((b || o.url || tb.href) + "").replace(
          Hb,
          tb.protocol + "//"
        )),
        (o.type = c.method || c.type || o.method || o.type),
        (o.dataTypes = (o.dataType || "*").toLowerCase().match(L) || [""]),
        null == o.crossDomain)
      ) {
        j = d.createElement("a");
        try {
          (j.href = o.url),
            (j.href = j.href),
            (o.crossDomain =
              Lb.protocol + "//" + Lb.host != j.protocol + "//" + j.host);
        } catch (z) {
          o.crossDomain = !0;
        }
      }
      if (
        (o.data &&
          o.processData &&
          "string" != typeof o.data &&
          (o.data = r.param(o.data, o.traditional)),
        Nb(Ib, o, c, y),
        k)
      )
        return y;
      (l = r.event && o.global),
        l && 0 === r.active++ && r.event.trigger("ajaxStart"),
        (o.type = o.type.toUpperCase()),
        (o.hasContent = !Gb.test(o.type)),
        (f = o.url.replace(Cb, "")),
        o.hasContent
          ? o.data &&
            o.processData &&
            0 ===
              (o.contentType || "").indexOf(
                "application/x-www-form-urlencoded"
              ) &&
            (o.data = o.data.replace(Bb, "+"))
          : ((n = o.url.slice(f.length)),
            o.data && ((f += (vb.test(f) ? "&" : "?") + o.data), delete o.data),
            o.cache === !1 &&
              ((f = f.replace(Db, "$1")),
              (n = (vb.test(f) ? "&" : "?") + "_=" + ub++ + n)),
            (o.url = f + n)),
        o.ifModified &&
          (r.lastModified[f] &&
            y.setRequestHeader("If-Modified-Since", r.lastModified[f]),
          r.etag[f] && y.setRequestHeader("If-None-Match", r.etag[f])),
        ((o.data && o.hasContent && o.contentType !== !1) || c.contentType) &&
          y.setRequestHeader("Content-Type", o.contentType),
        y.setRequestHeader(
          "Accept",
          o.dataTypes[0] && o.accepts[o.dataTypes[0]]
            ? o.accepts[o.dataTypes[0]] +
                ("*" !== o.dataTypes[0] ? ", " + Kb + "; q=0.01" : "")
            : o.accepts["*"]
        );
      for (m in o.headers) y.setRequestHeader(m, o.headers[m]);
      if (o.beforeSend && (o.beforeSend.call(p, y, o) === !1 || k))
        return y.abort();
      if (
        ((x = "abort"),
        t.add(o.complete),
        y.done(o.success),
        y.fail(o.error),
        (e = Nb(Jb, o, c, y)))
      ) {
        if (((y.readyState = 1), l && q.trigger("ajaxSend", [y, o]), k))
          return y;
        o.async &&
          o.timeout > 0 &&
          (i = a.setTimeout(function () {
            y.abort("timeout");
          }, o.timeout));
        try {
          (k = !1), e.send(v, A);
        } catch (z) {
          if (k) throw z;
          A(-1, z);
        }
      } else A(-1, "No Transport");
      function A(b, c, d, h) {
        var j,
          m,
          n,
          v,
          w,
          x = c;
        k ||
          ((k = !0),
          i && a.clearTimeout(i),
          (e = void 0),
          (g = h || ""),
          (y.readyState = b > 0 ? 4 : 0),
          (j = (b >= 200 && b < 300) || 304 === b),
          d && (v = Pb(o, y, d)),
          (v = Qb(o, v, y, j)),
          j
            ? (o.ifModified &&
                ((w = y.getResponseHeader("Last-Modified")),
                w && (r.lastModified[f] = w),
                (w = y.getResponseHeader("etag")),
                w && (r.etag[f] = w)),
              204 === b || "HEAD" === o.type
                ? (x = "nocontent")
                : 304 === b
                ? (x = "notmodified")
                : ((x = v.state), (m = v.data), (n = v.error), (j = !n)))
            : ((n = x), (!b && x) || ((x = "error"), b < 0 && (b = 0))),
          (y.status = b),
          (y.statusText = (c || x) + ""),
          j ? s.resolveWith(p, [m, x, y]) : s.rejectWith(p, [y, x, n]),
          y.statusCode(u),
          (u = void 0),
          l && q.trigger(j ? "ajaxSuccess" : "ajaxError", [y, o, j ? m : n]),
          t.fireWith(p, [y, x]),
          l &&
            (q.trigger("ajaxComplete", [y, o]),
            --r.active || r.event.trigger("ajaxStop")));
      }
      return y;
    },
    getJSON: function (a, b, c) {
      return r.get(a, b, c, "json");
    },
    getScript: function (a, b) {
      return r.get(a, void 0, b, "script");
    },
  }),
    r.each(["get", "post"], function (a, b) {
      r[b] = function (a, c, d, e) {
        return (
          r.isFunction(c) && ((e = e || d), (d = c), (c = void 0)),
          r.ajax(
            r.extend(
              { url: a, type: b, dataType: e, data: c, success: d },
              r.isPlainObject(a) && a
            )
          )
        );
      };
    }),
    (r._evalUrl = function (a) {
      return r.ajax({
        url: a,
        type: "GET",
        dataType: "script",
        cache: !0,
        async: !1,
        global: !1,
        throws: !0,
      });
    }),
    r.fn.extend({
      wrapAll: function (a) {
        var b;
        return (
          this[0] &&
            (r.isFunction(a) && (a = a.call(this[0])),
            (b = r(a, this[0].ownerDocument).eq(0).clone(!0)),
            this[0].parentNode && b.insertBefore(this[0]),
            b
              .map(function () {
                var a = this;
                while (a.firstElementChild) a = a.firstElementChild;
                return a;
              })
              .append(this)),
          this
        );
      },
      wrapInner: function (a) {
        return r.isFunction(a)
          ? this.each(function (b) {
              r(this).wrapInner(a.call(this, b));
            })
          : this.each(function () {
              var b = r(this),
                c = b.contents();
              c.length ? c.wrapAll(a) : b.append(a);
            });
      },
      wrap: function (a) {
        var b = r.isFunction(a);
        return this.each(function (c) {
          r(this).wrapAll(b ? a.call(this, c) : a);
        });
      },
      unwrap: function (a) {
        return (
          this.parent(a)
            .not("body")
            .each(function () {
              r(this).replaceWith(this.childNodes);
            }),
          this
        );
      },
    }),
    (r.expr.pseudos.hidden = function (a) {
      return !r.expr.pseudos.visible(a);
    }),
    (r.expr.pseudos.visible = function (a) {
      return !!(a.offsetWidth || a.offsetHeight || a.getClientRects().length);
    }),
    (r.ajaxSettings.xhr = function () {
      try {
        return new a.XMLHttpRequest();
      } catch (b) {}
    });
  var Rb = { 0: 200, 1223: 204 },
    Sb = r.ajaxSettings.xhr();
  (o.cors = !!Sb && "withCredentials" in Sb),
    (o.ajax = Sb = !!Sb),
    r.ajaxTransport(function (b) {
      var c, d;
      if (o.cors || (Sb && !b.crossDomain))
        return {
          send: function (e, f) {
            var g,
              h = b.xhr();
            if (
              (h.open(b.type, b.url, b.async, b.username, b.password),
              b.xhrFields)
            )
              for (g in b.xhrFields) h[g] = b.xhrFields[g];
            b.mimeType && h.overrideMimeType && h.overrideMimeType(b.mimeType),
              b.crossDomain ||
                e["X-Requested-With"] ||
                (e["X-Requested-With"] = "XMLHttpRequest");
            for (g in e) h.setRequestHeader(g, e[g]);
            (c = function (a) {
              return function () {
                c &&
                  ((c =
                    d =
                    h.onload =
                    h.onerror =
                    h.onabort =
                    h.onreadystatechange =
                      null),
                  "abort" === a
                    ? h.abort()
                    : "error" === a
                    ? "number" != typeof h.status
                      ? f(0, "error")
                      : f(h.status, h.statusText)
                    : f(
                        Rb[h.status] || h.status,
                        h.statusText,
                        "text" !== (h.responseType || "text") ||
                          "string" != typeof h.responseText
                          ? { binary: h.response }
                          : { text: h.responseText },
                        h.getAllResponseHeaders()
                      ));
              };
            }),
              (h.onload = c()),
              (d = h.onerror = c("error")),
              void 0 !== h.onabort
                ? (h.onabort = d)
                : (h.onreadystatechange = function () {
                    4 === h.readyState &&
                      a.setTimeout(function () {
                        c && d();
                      });
                  }),
              (c = c("abort"));
            try {
              h.send((b.hasContent && b.data) || null);
            } catch (i) {
              if (c) throw i;
            }
          },
          abort: function () {
            c && c();
          },
        };
    }),
    r.ajaxPrefilter(function (a) {
      a.crossDomain && (a.contents.script = !1);
    }),
    r.ajaxSetup({
      accepts: {
        script:
          "text/javascript, application/javascript, application/ecmascript, application/x-ecmascript",
      },
      contents: { script: /\b(?:java|ecma)script\b/ },
      converters: {
        "text script": function (a) {
          return r.globalEval(a), a;
        },
      },
    }),
    r.ajaxPrefilter("script", function (a) {
      void 0 === a.cache && (a.cache = !1), a.crossDomain && (a.type = "GET");
    }),
    r.ajaxTransport("script", function (a) {
      if (a.crossDomain) {
        var b, c;
        return {
          send: function (e, f) {
            (b = r("<script>")
              .prop({ charset: a.scriptCharset, src: a.url })
              .on(
                "load error",
                (c = function (a) {
                  b.remove(),
                    (c = null),
                    a && f("error" === a.type ? 404 : 200, a.type);
                })
              )),
              d.head.appendChild(b[0]);
          },
          abort: function () {
            c && c();
          },
        };
      }
    });
  var Tb = [],
    Ub = /(=)\?(?=&|$)|\?\?/;
  r.ajaxSetup({
    jsonp: "callback",
    jsonpCallback: function () {
      var a = Tb.pop() || r.expando + "_" + ub++;
      return (this[a] = !0), a;
    },
  }),
    r.ajaxPrefilter("json jsonp", function (b, c, d) {
      var e,
        f,
        g,
        h =
          b.jsonp !== !1 &&
          (Ub.test(b.url)
            ? "url"
            : "string" == typeof b.data &&
              0 ===
                (b.contentType || "").indexOf(
                  "application/x-www-form-urlencoded"
                ) &&
              Ub.test(b.data) &&
              "data");
      if (h || "jsonp" === b.dataTypes[0])
        return (
          (e = b.jsonpCallback =
            r.isFunction(b.jsonpCallback)
              ? b.jsonpCallback()
              : b.jsonpCallback),
          h
            ? (b[h] = b[h].replace(Ub, "$1" + e))
            : b.jsonp !== !1 &&
              (b.url += (vb.test(b.url) ? "&" : "?") + b.jsonp + "=" + e),
          (b.converters["script json"] = function () {
            return g || r.error(e + " was not called"), g[0];
          }),
          (b.dataTypes[0] = "json"),
          (f = a[e]),
          (a[e] = function () {
            g = arguments;
          }),
          d.always(function () {
            void 0 === f ? r(a).removeProp(e) : (a[e] = f),
              b[e] && ((b.jsonpCallback = c.jsonpCallback), Tb.push(e)),
              g && r.isFunction(f) && f(g[0]),
              (g = f = void 0);
          }),
          "script"
        );
    }),
    (o.createHTMLDocument = (function () {
      var a = d.implementation.createHTMLDocument("").body;
      return (
        (a.innerHTML = "<form></form><form></form>"), 2 === a.childNodes.length
      );
    })()),
    (r.parseHTML = function (a, b, c) {
      if ("string" != typeof a) return [];
      "boolean" == typeof b && ((c = b), (b = !1));
      var e, f, g;
      return (
        b ||
          (o.createHTMLDocument
            ? ((b = d.implementation.createHTMLDocument("")),
              (e = b.createElement("base")),
              (e.href = d.location.href),
              b.head.appendChild(e))
            : (b = d)),
        (f = C.exec(a)),
        (g = !c && []),
        f
          ? [b.createElement(f[1])]
          : ((f = qa([a], b, g)),
            g && g.length && r(g).remove(),
            r.merge([], f.childNodes))
      );
    }),
    (r.fn.load = function (a, b, c) {
      var d,
        e,
        f,
        g = this,
        h = a.indexOf(" ");
      return (
        h > -1 && ((d = pb(a.slice(h))), (a = a.slice(0, h))),
        r.isFunction(b)
          ? ((c = b), (b = void 0))
          : b && "object" == typeof b && (e = "POST"),
        g.length > 0 &&
          r
            .ajax({ url: a, type: e || "GET", dataType: "html", data: b })
            .done(function (a) {
              (f = arguments),
                g.html(d ? r("<div>").append(r.parseHTML(a)).find(d) : a);
            })
            .always(
              c &&
                function (a, b) {
                  g.each(function () {
                    c.apply(this, f || [a.responseText, b, a]);
                  });
                }
            ),
        this
      );
    }),
    r.each(
      [
        "ajaxStart",
        "ajaxStop",
        "ajaxComplete",
        "ajaxError",
        "ajaxSuccess",
        "ajaxSend",
      ],
      function (a, b) {
        r.fn[b] = function (a) {
          return this.on(b, a);
        };
      }
    ),
    (r.expr.pseudos.animated = function (a) {
      return r.grep(r.timers, function (b) {
        return a === b.elem;
      }).length;
    }),
    (r.offset = {
      setOffset: function (a, b, c) {
        var d,
          e,
          f,
          g,
          h,
          i,
          j,
          k = r.css(a, "position"),
          l = r(a),
          m = {};
        "static" === k && (a.style.position = "relative"),
          (h = l.offset()),
          (f = r.css(a, "top")),
          (i = r.css(a, "left")),
          (j =
            ("absolute" === k || "fixed" === k) &&
            (f + i).indexOf("auto") > -1),
          j
            ? ((d = l.position()), (g = d.top), (e = d.left))
            : ((g = parseFloat(f) || 0), (e = parseFloat(i) || 0)),
          r.isFunction(b) && (b = b.call(a, c, r.extend({}, h))),
          null != b.top && (m.top = b.top - h.top + g),
          null != b.left && (m.left = b.left - h.left + e),
          "using" in b ? b.using.call(a, m) : l.css(m);
      },
    }),
    r.fn.extend({
      offset: function (a) {
        if (arguments.length)
          return void 0 === a
            ? this
            : this.each(function (b) {
                r.offset.setOffset(this, a, b);
              });
        var b,
          c,
          d,
          e,
          f = this[0];
        if (f)
          return f.getClientRects().length
            ? ((d = f.getBoundingClientRect()),
              (b = f.ownerDocument),
              (c = b.documentElement),
              (e = b.defaultView),
              {
                top: d.top + e.pageYOffset - c.clientTop,
                left: d.left + e.pageXOffset - c.clientLeft,
              })
            : { top: 0, left: 0 };
      },
      position: function () {
        if (this[0]) {
          var a,
            b,
            c = this[0],
            d = { top: 0, left: 0 };
          return (
            "fixed" === r.css(c, "position")
              ? (b = c.getBoundingClientRect())
              : ((a = this.offsetParent()),
                (b = this.offset()),
                B(a[0], "html") || (d = a.offset()),
                (d = {
                  top: d.top + r.css(a[0], "borderTopWidth", !0),
                  left: d.left + r.css(a[0], "borderLeftWidth", !0),
                })),
            {
              top: b.top - d.top - r.css(c, "marginTop", !0),
              left: b.left - d.left - r.css(c, "marginLeft", !0),
            }
          );
        }
      },
      offsetParent: function () {
        return this.map(function () {
          var a = this.offsetParent;
          while (a && "static" === r.css(a, "position")) a = a.offsetParent;
          return a || ra;
        });
      },
    }),
    r.each(
      { scrollLeft: "pageXOffset", scrollTop: "pageYOffset" },
      function (a, b) {
        var c = "pageYOffset" === b;
        r.fn[a] = function (d) {
          return T(
            this,
            function (a, d, e) {
              var f;
              return (
                r.isWindow(a)
                  ? (f = a)
                  : 9 === a.nodeType && (f = a.defaultView),
                void 0 === e
                  ? f
                    ? f[b]
                    : a[d]
                  : void (f
                      ? f.scrollTo(c ? f.pageXOffset : e, c ? e : f.pageYOffset)
                      : (a[d] = e))
              );
            },
            a,
            d,
            arguments.length
          );
        };
      }
    ),
    r.each(["top", "left"], function (a, b) {
      r.cssHooks[b] = Pa(o.pixelPosition, function (a, c) {
        if (c)
          return (c = Oa(a, b)), Ma.test(c) ? r(a).position()[b] + "px" : c;
      });
    }),
    r.each({ Height: "height", Width: "width" }, function (a, b) {
      r.each(
        { padding: "inner" + a, content: b, "": "outer" + a },
        function (c, d) {
          r.fn[d] = function (e, f) {
            var g = arguments.length && (c || "boolean" != typeof e),
              h = c || (e === !0 || f === !0 ? "margin" : "border");
            return T(
              this,
              function (b, c, e) {
                var f;
                return r.isWindow(b)
                  ? 0 === d.indexOf("outer")
                    ? b["inner" + a]
                    : b.document.documentElement["client" + a]
                  : 9 === b.nodeType
                  ? ((f = b.documentElement),
                    Math.max(
                      b.body["scroll" + a],
                      f["scroll" + a],
                      b.body["offset" + a],
                      f["offset" + a],
                      f["client" + a]
                    ))
                  : void 0 === e
                  ? r.css(b, c, h)
                  : r.style(b, c, e, h);
              },
              b,
              g ? e : void 0,
              g
            );
          };
        }
      );
    }),
    r.fn.extend({
      bind: function (a, b, c) {
        return this.on(a, null, b, c);
      },
      unbind: function (a, b) {
        return this.off(a, null, b);
      },
      delegate: function (a, b, c, d) {
        return this.on(b, a, c, d);
      },
      undelegate: function (a, b, c) {
        return 1 === arguments.length
          ? this.off(a, "**")
          : this.off(b, a || "**", c);
      },
    }),
    (r.holdReady = function (a) {
      a ? r.readyWait++ : r.ready(!0);
    }),
    (r.isArray = Array.isArray),
    (r.parseJSON = JSON.parse),
    (r.nodeName = B),
    "function" == typeof define &&
      define.amd &&
      define("jquery", [], function () {
        return r;
      });
  var Vb = a.jQuery,
    Wb = a.$;
  return (
    (r.noConflict = function (b) {
      return a.$ === r && (a.$ = Wb), b && a.jQuery === r && (a.jQuery = Vb), r;
    }),
    b || (a.jQuery = a.$ = r),
    r
  );
});

/* popper JS */
/*
 Copyright (C) Federico Zivolo 2017
 Distributed under the MIT License (license terms are at http://opensource.org/licenses/MIT).
 */
(function (e, t) {
  "object" == typeof exports && "undefined" != typeof module
    ? (module.exports = t())
    : "function" == typeof define && define.amd
    ? define(t)
    : (e.Popper = t());
})(this, function () {
  "use strict";
  function e(e) {
    return e && "[object Function]" === {}.toString.call(e);
  }
  function t(e, t) {
    if (1 !== e.nodeType) return [];
    var o = window.getComputedStyle(e, null);
    return t ? o[t] : o;
  }
  function o(e) {
    return "HTML" === e.nodeName ? e : e.parentNode || e.host;
  }
  function n(e) {
    if (!e || -1 !== ["HTML", "BODY", "#document"].indexOf(e.nodeName))
      return window.document.body;
    var i = t(e),
      r = i.overflow,
      p = i.overflowX,
      s = i.overflowY;
    return /(auto|scroll)/.test(r + s + p) ? e : n(o(e));
  }
  function r(e) {
    var o = e && e.offsetParent,
      i = o && o.nodeName;
    return i && "BODY" !== i && "HTML" !== i
      ? -1 !== ["TD", "TABLE"].indexOf(o.nodeName) &&
        "static" === t(o, "position")
        ? r(o)
        : o
      : window.document.documentElement;
  }
  function p(e) {
    var t = e.nodeName;
    return "BODY" !== t && ("HTML" === t || r(e.firstElementChild) === e);
  }
  function s(e) {
    return null === e.parentNode ? e : s(e.parentNode);
  }
  function d(e, t) {
    if (!e || !e.nodeType || !t || !t.nodeType)
      return window.document.documentElement;
    var o = e.compareDocumentPosition(t) & Node.DOCUMENT_POSITION_FOLLOWING,
      i = o ? e : t,
      n = o ? t : e,
      a = document.createRange();
    a.setStart(i, 0), a.setEnd(n, 0);
    var l = a.commonAncestorContainer;
    if ((e !== l && t !== l) || i.contains(n)) return p(l) ? l : r(l);
    var f = s(e);
    return f.host ? d(f.host, t) : d(e, s(t).host);
  }
  function a(e) {
    var t =
        1 < arguments.length && void 0 !== arguments[1] ? arguments[1] : "top",
      o = "top" === t ? "scrollTop" : "scrollLeft",
      i = e.nodeName;
    if ("BODY" === i || "HTML" === i) {
      var n = window.document.documentElement,
        r = window.document.scrollingElement || n;
      return r[o];
    }
    return e[o];
  }
  function l(e, t) {
    var o = 2 < arguments.length && void 0 !== arguments[2] && arguments[2],
      i = a(t, "top"),
      n = a(t, "left"),
      r = o ? -1 : 1;
    return (
      (e.top += i * r),
      (e.bottom += i * r),
      (e.left += n * r),
      (e.right += n * r),
      e
    );
  }
  function f(e, t) {
    var o = "x" === t ? "Left" : "Top",
      i = "Left" == o ? "Right" : "Bottom";
    return (
      +e["border" + o + "Width"].split("px")[0] +
      +e["border" + i + "Width"].split("px")[0]
    );
  }
  function m(e, t, o, i) {
    return X(
      t["offset" + e],
      t["scroll" + e],
      o["client" + e],
      o["offset" + e],
      o["scroll" + e],
      ne()
        ? o["offset" + e] +
            i["margin" + ("Height" === e ? "Top" : "Left")] +
            i["margin" + ("Height" === e ? "Bottom" : "Right")]
        : 0
    );
  }
  function c() {
    var e = window.document.body,
      t = window.document.documentElement,
      o = ne() && window.getComputedStyle(t);
    return { height: m("Height", e, t, o), width: m("Width", e, t, o) };
  }
  function h(e) {
    return de({}, e, { right: e.left + e.width, bottom: e.top + e.height });
  }
  function g(e) {
    var o = {};
    if (ne())
      try {
        o = e.getBoundingClientRect();
        var i = a(e, "top"),
          n = a(e, "left");
        (o.top += i), (o.left += n), (o.bottom += i), (o.right += n);
      } catch (e) {}
    else o = e.getBoundingClientRect();
    var r = {
        left: o.left,
        top: o.top,
        width: o.right - o.left,
        height: o.bottom - o.top,
      },
      p = "HTML" === e.nodeName ? c() : {},
      s = p.width || e.clientWidth || r.right - r.left,
      d = p.height || e.clientHeight || r.bottom - r.top,
      l = e.offsetWidth - s,
      m = e.offsetHeight - d;
    if (l || m) {
      var g = t(e);
      (l -= f(g, "x")), (m -= f(g, "y")), (r.width -= l), (r.height -= m);
    }
    return h(r);
  }
  function u(e, o) {
    var i = ne(),
      r = "HTML" === o.nodeName,
      p = g(e),
      s = g(o),
      d = n(e),
      a = t(o),
      f = +a.borderTopWidth.split("px")[0],
      m = +a.borderLeftWidth.split("px")[0],
      c = h({
        top: p.top - s.top - f,
        left: p.left - s.left - m,
        width: p.width,
        height: p.height,
      });
    if (((c.marginTop = 0), (c.marginLeft = 0), !i && r)) {
      var u = +a.marginTop.split("px")[0],
        b = +a.marginLeft.split("px")[0];
      (c.top -= f - u),
        (c.bottom -= f - u),
        (c.left -= m - b),
        (c.right -= m - b),
        (c.marginTop = u),
        (c.marginLeft = b);
    }
    return (
      (i ? o.contains(d) : o === d && "BODY" !== d.nodeName) && (c = l(c, o)), c
    );
  }
  function b(e) {
    var t = window.document.documentElement,
      o = u(e, t),
      i = X(t.clientWidth, window.innerWidth || 0),
      n = X(t.clientHeight, window.innerHeight || 0),
      r = a(t),
      p = a(t, "left"),
      s = {
        top: r - o.top + o.marginTop,
        left: p - o.left + o.marginLeft,
        width: i,
        height: n,
      };
    return h(s);
  }
  function y(e) {
    var i = e.nodeName;
    return "BODY" === i || "HTML" === i
      ? !1
      : "fixed" === t(e, "position") || y(o(e));
  }
  function w(e, t, i, r) {
    var p = { top: 0, left: 0 },
      s = d(e, t);
    if ("viewport" === r) p = b(s);
    else {
      var a;
      "scrollParent" === r
        ? ((a = n(o(e))),
          "BODY" === a.nodeName && (a = window.document.documentElement))
        : "window" === r
        ? (a = window.document.documentElement)
        : (a = r);
      var l = u(a, s);
      if ("HTML" === a.nodeName && !y(s)) {
        var f = c(),
          m = f.height,
          h = f.width;
        (p.top += l.top - l.marginTop),
          (p.bottom = m + l.top),
          (p.left += l.left - l.marginLeft),
          (p.right = h + l.left);
      } else p = l;
    }
    return (p.left += i), (p.top += i), (p.right -= i), (p.bottom -= i), p;
  }
  function E(e) {
    var t = e.width,
      o = e.height;
    return t * o;
  }
  function v(e, t, o, i, n) {
    var r = 5 < arguments.length && void 0 !== arguments[5] ? arguments[5] : 0;
    if (-1 === e.indexOf("auto")) return e;
    var p = w(o, i, r, n),
      s = {
        top: { width: p.width, height: t.top - p.top },
        right: { width: p.right - t.right, height: p.height },
        bottom: { width: p.width, height: p.bottom - t.bottom },
        left: { width: t.left - p.left, height: p.height },
      },
      d = Object.keys(s)
        .map(function (e) {
          return de({ key: e }, s[e], { area: E(s[e]) });
        })
        .sort(function (e, t) {
          return t.area - e.area;
        }),
      a = d.filter(function (e) {
        var t = e.width,
          i = e.height;
        return t >= o.clientWidth && i >= o.clientHeight;
      }),
      l = 0 < a.length ? a[0].key : d[0].key,
      f = e.split("-")[1];
    return l + (f ? "-" + f : "");
  }
  function x(e, t, o) {
    var i = d(t, o);
    return u(o, i);
  }
  function O(e) {
    var t = window.getComputedStyle(e),
      o = parseFloat(t.marginTop) + parseFloat(t.marginBottom),
      i = parseFloat(t.marginLeft) + parseFloat(t.marginRight),
      n = { width: e.offsetWidth + i, height: e.offsetHeight + o };
    return n;
  }
  function L(e) {
    var t = { left: "right", right: "left", bottom: "top", top: "bottom" };
    return e.replace(/left|right|bottom|top/g, function (e) {
      return t[e];
    });
  }
  function S(e, t, o) {
    o = o.split("-")[0];
    var i = O(e),
      n = { width: i.width, height: i.height },
      r = -1 !== ["right", "left"].indexOf(o),
      p = r ? "top" : "left",
      s = r ? "left" : "top",
      d = r ? "height" : "width",
      a = r ? "width" : "height";
    return (
      (n[p] = t[p] + t[d] / 2 - i[d] / 2),
      (n[s] = o === s ? t[s] - i[a] : t[L(s)]),
      n
    );
  }
  function T(e, t) {
    return Array.prototype.find ? e.find(t) : e.filter(t)[0];
  }
  function C(e, t, o) {
    if (Array.prototype.findIndex)
      return e.findIndex(function (e) {
        return e[t] === o;
      });
    var i = T(e, function (e) {
      return e[t] === o;
    });
    return e.indexOf(i);
  }
  function N(t, o, i) {
    var n = void 0 === i ? t : t.slice(0, C(t, "name", i));
    return (
      n.forEach(function (t) {
        t.function &&
          console.warn("`modifier.function` is deprecated, use `modifier.fn`!");
        var i = t.function || t.fn;
        t.enabled &&
          e(i) &&
          ((o.offsets.popper = h(o.offsets.popper)),
          (o.offsets.reference = h(o.offsets.reference)),
          (o = i(o, t)));
      }),
      o
    );
  }
  function k() {
    if (!this.state.isDestroyed) {
      var e = {
        instance: this,
        styles: {},
        arrowStyles: {},
        attributes: {},
        flipped: !1,
        offsets: {},
      };
      (e.offsets.reference = x(this.state, this.popper, this.reference)),
        (e.placement = v(
          this.options.placement,
          e.offsets.reference,
          this.popper,
          this.reference,
          this.options.modifiers.flip.boundariesElement,
          this.options.modifiers.flip.padding
        )),
        (e.originalPlacement = e.placement),
        (e.offsets.popper = S(this.popper, e.offsets.reference, e.placement)),
        (e.offsets.popper.position = "absolute"),
        (e = N(this.modifiers, e)),
        this.state.isCreated
          ? this.options.onUpdate(e)
          : ((this.state.isCreated = !0), this.options.onCreate(e));
    }
  }
  function W(e, t) {
    return e.some(function (e) {
      var o = e.name,
        i = e.enabled;
      return i && o === t;
    });
  }
  function B(e) {
    for (
      var t = [!1, "ms", "Webkit", "Moz", "O"],
        o = e.charAt(0).toUpperCase() + e.slice(1),
        n = 0;
      n < t.length - 1;
      n++
    ) {
      var i = t[n],
        r = i ? "" + i + o : e;
      if ("undefined" != typeof window.document.body.style[r]) return r;
    }
    return null;
  }
  function P() {
    return (
      (this.state.isDestroyed = !0),
      W(this.modifiers, "applyStyle") &&
        (this.popper.removeAttribute("x-placement"),
        (this.popper.style.left = ""),
        (this.popper.style.position = ""),
        (this.popper.style.top = ""),
        (this.popper.style[B("transform")] = "")),
      this.disableEventListeners(),
      this.options.removeOnDestroy &&
        this.popper.parentNode.removeChild(this.popper),
      this
    );
  }
  function D(e, t, o, i) {
    var r = "BODY" === e.nodeName,
      p = r ? window : e;
    p.addEventListener(t, o, { passive: !0 }),
      r || D(n(p.parentNode), t, o, i),
      i.push(p);
  }
  function H(e, t, o, i) {
    (o.updateBound = i),
      window.addEventListener("resize", o.updateBound, { passive: !0 });
    var r = n(e);
    return (
      D(r, "scroll", o.updateBound, o.scrollParents),
      (o.scrollElement = r),
      (o.eventsEnabled = !0),
      o
    );
  }
  function A() {
    this.state.eventsEnabled ||
      (this.state = H(
        this.reference,
        this.options,
        this.state,
        this.scheduleUpdate
      ));
  }
  function M(e, t) {
    return (
      window.removeEventListener("resize", t.updateBound),
      t.scrollParents.forEach(function (e) {
        e.removeEventListener("scroll", t.updateBound);
      }),
      (t.updateBound = null),
      (t.scrollParents = []),
      (t.scrollElement = null),
      (t.eventsEnabled = !1),
      t
    );
  }
  function I() {
    this.state.eventsEnabled &&
      (window.cancelAnimationFrame(this.scheduleUpdate),
      (this.state = M(this.reference, this.state)));
  }
  function R(e) {
    return "" !== e && !isNaN(parseFloat(e)) && isFinite(e);
  }
  function U(e, t) {
    Object.keys(t).forEach(function (o) {
      var i = "";
      -1 !== ["width", "height", "top", "right", "bottom", "left"].indexOf(o) &&
        R(t[o]) &&
        (i = "px"),
        (e.style[o] = t[o] + i);
    });
  }
  function Y(e, t) {
    Object.keys(t).forEach(function (o) {
      var i = t[o];
      !1 === i ? e.removeAttribute(o) : e.setAttribute(o, t[o]);
    });
  }
  function F(e, t, o) {
    var i = T(e, function (e) {
        var o = e.name;
        return o === t;
      }),
      n =
        !!i &&
        e.some(function (e) {
          return e.name === o && e.enabled && e.order < i.order;
        });
    if (!n) {
      var r = "`" + t + "`";
      console.warn(
        "`" +
          o +
          "`" +
          " modifier is required by " +
          r +
          " modifier in order to work, be sure to include it before " +
          r +
          "!"
      );
    }
    return n;
  }
  function j(e) {
    return "end" === e ? "start" : "start" === e ? "end" : e;
  }
  function K(e) {
    var t = 1 < arguments.length && void 0 !== arguments[1] && arguments[1],
      o = le.indexOf(e),
      i = le.slice(o + 1).concat(le.slice(0, o));
    return t ? i.reverse() : i;
  }
  function q(e, t, o, i) {
    var n = e.match(/((?:\-|\+)?\d*\.?\d*)(.*)/),
      r = +n[1],
      p = n[2];
    if (!r) return e;
    if (0 === p.indexOf("%")) {
      var s;
      switch (p) {
        case "%p":
          s = o;
          break;
        case "%":
        case "%r":
        default:
          s = i;
      }
      var d = h(s);
      return (d[t] / 100) * r;
    }
    if ("vh" === p || "vw" === p) {
      var a;
      return (
        (a =
          "vh" === p
            ? X(document.documentElement.clientHeight, window.innerHeight || 0)
            : X(document.documentElement.clientWidth, window.innerWidth || 0)),
        (a / 100) * r
      );
    }
    return r;
  }
  function G(e, t, o, i) {
    var n = [0, 0],
      r = -1 !== ["right", "left"].indexOf(i),
      p = e.split(/(\+|\-)/).map(function (e) {
        return e.trim();
      }),
      s = p.indexOf(
        T(p, function (e) {
          return -1 !== e.search(/,|\s/);
        })
      );
    p[s] &&
      -1 === p[s].indexOf(",") &&
      console.warn(
        "Offsets separated by white space(s) are deprecated, use a comma (,) instead."
      );
    var d = /\s*,\s*|\s+/,
      a =
        -1 === s
          ? [p]
          : [
              p.slice(0, s).concat([p[s].split(d)[0]]),
              [p[s].split(d)[1]].concat(p.slice(s + 1)),
            ];
    return (
      (a = a.map(function (e, i) {
        var n = (1 === i ? !r : r) ? "height" : "width",
          p = !1;
        return e
          .reduce(function (e, t) {
            return "" === e[e.length - 1] && -1 !== ["+", "-"].indexOf(t)
              ? ((e[e.length - 1] = t), (p = !0), e)
              : p
              ? ((e[e.length - 1] += t), (p = !1), e)
              : e.concat(t);
          }, [])
          .map(function (e) {
            return q(e, n, t, o);
          });
      })),
      a.forEach(function (e, t) {
        e.forEach(function (o, i) {
          R(o) && (n[t] += o * ("-" === e[i - 1] ? -1 : 1));
        });
      }),
      n
    );
  }
  function z(e, t) {
    var o,
      i = t.offset,
      n = e.placement,
      r = e.offsets,
      p = r.popper,
      s = r.reference,
      d = n.split("-")[0];
    return (
      (o = R(+i) ? [+i, 0] : G(i, p, s, d)),
      "left" === d
        ? ((p.top += o[0]), (p.left -= o[1]))
        : "right" === d
        ? ((p.top += o[0]), (p.left += o[1]))
        : "top" === d
        ? ((p.left += o[0]), (p.top -= o[1]))
        : "bottom" === d && ((p.left += o[0]), (p.top += o[1])),
      (e.popper = p),
      e
    );
  }
  for (
    var V = Math.min,
      _ = Math.floor,
      X = Math.max,
      Q = ["native code", "[object MutationObserverConstructor]"],
      J = function (e) {
        return Q.some(function (t) {
          return -1 < (e || "").toString().indexOf(t);
        });
      },
      Z = "undefined" != typeof window,
      $ = ["Edge", "Trident", "Firefox"],
      ee = 0,
      te = 0;
    te < $.length;
    te += 1
  )
    if (Z && 0 <= navigator.userAgent.indexOf($[te])) {
      ee = 1;
      break;
    }
  var i,
    oe = Z && J(window.MutationObserver),
    ie = oe
      ? function (e) {
          var t = !1,
            o = 0,
            i = document.createElement("span"),
            n = new MutationObserver(function () {
              e(), (t = !1);
            });
          return (
            n.observe(i, { attributes: !0 }),
            function () {
              t || ((t = !0), i.setAttribute("x-index", o), ++o);
            }
          );
        }
      : function (e) {
          var t = !1;
          return function () {
            t ||
              ((t = !0),
              setTimeout(function () {
                (t = !1), e();
              }, ee));
          };
        },
    ne = function () {
      return (
        void 0 == i && (i = -1 !== navigator.appVersion.indexOf("MSIE 10")), i
      );
    },
    re = function (e, t) {
      if (!(e instanceof t))
        throw new TypeError("Cannot call a class as a function");
    },
    pe = (function () {
      function e(e, t) {
        for (var o, n = 0; n < t.length; n++)
          (o = t[n]),
            (o.enumerable = o.enumerable || !1),
            (o.configurable = !0),
            "value" in o && (o.writable = !0),
            Object.defineProperty(e, o.key, o);
      }
      return function (t, o, i) {
        return o && e(t.prototype, o), i && e(t, i), t;
      };
    })(),
    se = function (e, t, o) {
      return (
        t in e
          ? Object.defineProperty(e, t, {
              value: o,
              enumerable: !0,
              configurable: !0,
              writable: !0,
            })
          : (e[t] = o),
        e
      );
    },
    de =
      Object.assign ||
      function (e) {
        for (var t, o = 1; o < arguments.length; o++)
          for (var i in ((t = arguments[o]), t))
            Object.prototype.hasOwnProperty.call(t, i) && (e[i] = t[i]);
        return e;
      },
    ae = [
      "auto-start",
      "auto",
      "auto-end",
      "top-start",
      "top",
      "top-end",
      "right-start",
      "right",
      "right-end",
      "bottom-end",
      "bottom",
      "bottom-start",
      "left-end",
      "left",
      "left-start",
    ],
    le = ae.slice(3),
    fe = {
      FLIP: "flip",
      CLOCKWISE: "clockwise",
      COUNTERCLOCKWISE: "counterclockwise",
    },
    me = (function () {
      function t(o, i) {
        var n = this,
          r =
            2 < arguments.length && void 0 !== arguments[2] ? arguments[2] : {};
        re(this, t),
          (this.scheduleUpdate = function () {
            return requestAnimationFrame(n.update);
          }),
          (this.update = ie(this.update.bind(this))),
          (this.options = de({}, t.Defaults, r)),
          (this.state = { isDestroyed: !1, isCreated: !1, scrollParents: [] }),
          (this.reference = o.jquery ? o[0] : o),
          (this.popper = i.jquery ? i[0] : i),
          (this.options.modifiers = {}),
          Object.keys(de({}, t.Defaults.modifiers, r.modifiers)).forEach(
            function (e) {
              n.options.modifiers[e] = de(
                {},
                t.Defaults.modifiers[e] || {},
                r.modifiers ? r.modifiers[e] : {}
              );
            }
          ),
          (this.modifiers = Object.keys(this.options.modifiers)
            .map(function (e) {
              return de({ name: e }, n.options.modifiers[e]);
            })
            .sort(function (e, t) {
              return e.order - t.order;
            })),
          this.modifiers.forEach(function (t) {
            t.enabled &&
              e(t.onLoad) &&
              t.onLoad(n.reference, n.popper, n.options, t, n.state);
          }),
          this.update();
        var p = this.options.eventsEnabled;
        p && this.enableEventListeners(), (this.state.eventsEnabled = p);
      }
      return (
        pe(t, [
          {
            key: "update",
            value: function () {
              return k.call(this);
            },
          },
          {
            key: "destroy",
            value: function () {
              return P.call(this);
            },
          },
          {
            key: "enableEventListeners",
            value: function () {
              return A.call(this);
            },
          },
          {
            key: "disableEventListeners",
            value: function () {
              return I.call(this);
            },
          },
        ]),
        t
      );
    })();
  return (
    (me.Utils = ("undefined" == typeof window ? global : window).PopperUtils),
    (me.placements = ae),
    (me.Defaults = {
      placement: "bottom",
      eventsEnabled: !0,
      removeOnDestroy: !1,
      onCreate: function () {},
      onUpdate: function () {},
      modifiers: {
        shift: {
          order: 100,
          enabled: !0,
          fn: function (e) {
            var t = e.placement,
              o = t.split("-")[0],
              i = t.split("-")[1];
            if (i) {
              var n = e.offsets,
                r = n.reference,
                p = n.popper,
                s = -1 !== ["bottom", "top"].indexOf(o),
                d = s ? "left" : "top",
                a = s ? "width" : "height",
                l = {
                  start: se({}, d, r[d]),
                  end: se({}, d, r[d] + r[a] - p[a]),
                };
              e.offsets.popper = de({}, p, l[i]);
            }
            return e;
          },
        },
        offset: { order: 200, enabled: !0, fn: z, offset: 0 },
        preventOverflow: {
          order: 300,
          enabled: !0,
          fn: function (e, t) {
            var o = t.boundariesElement || r(e.instance.popper);
            e.instance.reference === o && (o = r(o));
            var i = w(e.instance.popper, e.instance.reference, t.padding, o);
            t.boundaries = i;
            var n = t.priority,
              p = e.offsets.popper,
              s = {
                primary: function (e) {
                  var o = p[e];
                  return (
                    p[e] < i[e] &&
                      !t.escapeWithReference &&
                      (o = X(p[e], i[e])),
                    se({}, e, o)
                  );
                },
                secondary: function (e) {
                  var o = "right" === e ? "left" : "top",
                    n = p[o];
                  return (
                    p[e] > i[e] &&
                      !t.escapeWithReference &&
                      (n = V(
                        p[o],
                        i[e] - ("right" === e ? p.width : p.height)
                      )),
                    se({}, o, n)
                  );
                },
              };
            return (
              n.forEach(function (e) {
                var t =
                  -1 === ["left", "top"].indexOf(e) ? "secondary" : "primary";
                p = de({}, p, s[t](e));
              }),
              (e.offsets.popper = p),
              e
            );
          },
          priority: ["left", "right", "top", "bottom"],
          padding: 5,
          boundariesElement: "scrollParent",
        },
        keepTogether: {
          order: 400,
          enabled: !0,
          fn: function (e) {
            var t = e.offsets,
              o = t.popper,
              i = t.reference,
              n = e.placement.split("-")[0],
              r = _,
              p = -1 !== ["top", "bottom"].indexOf(n),
              s = p ? "right" : "bottom",
              d = p ? "left" : "top",
              a = p ? "width" : "height";
            return (
              o[s] < r(i[d]) && (e.offsets.popper[d] = r(i[d]) - o[a]),
              o[d] > r(i[s]) && (e.offsets.popper[d] = r(i[s])),
              e
            );
          },
        },
        arrow: {
          order: 500,
          enabled: !0,
          fn: function (e, o) {
            if (!F(e.instance.modifiers, "arrow", "keepTogether")) return e;
            var i = o.element;
            if ("string" == typeof i) {
              if (((i = e.instance.popper.querySelector(i)), !i)) return e;
            } else if (!e.instance.popper.contains(i))
              return (
                console.warn(
                  "WARNING: `arrow.element` must be child of its popper element!"
                ),
                e
              );
            var n = e.placement.split("-")[0],
              r = e.offsets,
              p = r.popper,
              s = r.reference,
              d = -1 !== ["left", "right"].indexOf(n),
              a = d ? "height" : "width",
              l = d ? "Top" : "Left",
              f = l.toLowerCase(),
              m = d ? "left" : "top",
              c = d ? "bottom" : "right",
              g = O(i)[a];
            s[c] - g < p[f] && (e.offsets.popper[f] -= p[f] - (s[c] - g)),
              s[f] + g > p[c] && (e.offsets.popper[f] += s[f] + g - p[c]);
            var u = s[f] + s[a] / 2 - g / 2,
              b = t(e.instance.popper, "margin" + l).replace("px", ""),
              y = u - h(e.offsets.popper)[f] - b;
            return (
              (y = X(V(p[a] - g, y), 0)),
              (e.arrowElement = i),
              (e.offsets.arrow = {}),
              (e.offsets.arrow[f] = Math.round(y)),
              (e.offsets.arrow[m] = ""),
              e
            );
          },
          element: "[x-arrow]",
        },
        flip: {
          order: 600,
          enabled: !0,
          fn: function (e, t) {
            if (W(e.instance.modifiers, "inner")) return e;
            if (e.flipped && e.placement === e.originalPlacement) return e;
            var o = w(
                e.instance.popper,
                e.instance.reference,
                t.padding,
                t.boundariesElement
              ),
              i = e.placement.split("-")[0],
              n = L(i),
              r = e.placement.split("-")[1] || "",
              p = [];
            switch (t.behavior) {
              case fe.FLIP:
                p = [i, n];
                break;
              case fe.CLOCKWISE:
                p = K(i);
                break;
              case fe.COUNTERCLOCKWISE:
                p = K(i, !0);
                break;
              default:
                p = t.behavior;
            }
            return (
              p.forEach(function (s, d) {
                if (i !== s || p.length === d + 1) return e;
                (i = e.placement.split("-")[0]), (n = L(i));
                var a = e.offsets.popper,
                  l = e.offsets.reference,
                  f = _,
                  m =
                    ("left" === i && f(a.right) > f(l.left)) ||
                    ("right" === i && f(a.left) < f(l.right)) ||
                    ("top" === i && f(a.bottom) > f(l.top)) ||
                    ("bottom" === i && f(a.top) < f(l.bottom)),
                  c = f(a.left) < f(o.left),
                  h = f(a.right) > f(o.right),
                  g = f(a.top) < f(o.top),
                  u = f(a.bottom) > f(o.bottom),
                  b =
                    ("left" === i && c) ||
                    ("right" === i && h) ||
                    ("top" === i && g) ||
                    ("bottom" === i && u),
                  y = -1 !== ["top", "bottom"].indexOf(i),
                  w =
                    !!t.flipVariations &&
                    ((y && "start" === r && c) ||
                      (y && "end" === r && h) ||
                      (!y && "start" === r && g) ||
                      (!y && "end" === r && u));
                (m || b || w) &&
                  ((e.flipped = !0),
                  (m || b) && (i = p[d + 1]),
                  w && (r = j(r)),
                  (e.placement = i + (r ? "-" + r : "")),
                  (e.offsets.popper = de(
                    {},
                    e.offsets.popper,
                    S(e.instance.popper, e.offsets.reference, e.placement)
                  )),
                  (e = N(e.instance.modifiers, e, "flip")));
              }),
              e
            );
          },
          behavior: "flip",
          padding: 5,
          boundariesElement: "viewport",
        },
        inner: {
          order: 700,
          enabled: !1,
          fn: function (e) {
            var t = e.placement,
              o = t.split("-")[0],
              i = e.offsets,
              n = i.popper,
              r = i.reference,
              p = -1 !== ["left", "right"].indexOf(o),
              s = -1 === ["top", "left"].indexOf(o);
            return (
              (n[p ? "left" : "top"] =
                r[o] - (s ? n[p ? "width" : "height"] : 0)),
              (e.placement = L(t)),
              (e.offsets.popper = h(n)),
              e
            );
          },
        },
        hide: {
          order: 800,
          enabled: !0,
          fn: function (e) {
            if (!F(e.instance.modifiers, "hide", "preventOverflow")) return e;
            var t = e.offsets.reference,
              o = T(e.instance.modifiers, function (e) {
                return "preventOverflow" === e.name;
              }).boundaries;
            if (
              t.bottom < o.top ||
              t.left > o.right ||
              t.top > o.bottom ||
              t.right < o.left
            ) {
              if (!0 === e.hide) return e;
              (e.hide = !0), (e.attributes["x-out-of-boundaries"] = "");
            } else {
              if (!1 === e.hide) return e;
              (e.hide = !1), (e.attributes["x-out-of-boundaries"] = !1);
            }
            return e;
          },
        },
        computeStyle: {
          order: 850,
          enabled: !0,
          fn: function (e, t) {
            var o = t.x,
              i = t.y,
              n = e.offsets.popper,
              p = T(e.instance.modifiers, function (e) {
                return "applyStyle" === e.name;
              }).gpuAcceleration;
            void 0 !== p &&
              console.warn(
                "WARNING: `gpuAcceleration` option moved to `computeStyle` modifier and will not be supported in future versions of Popper.js!"
              );
            var s,
              d,
              a = void 0 === p ? t.gpuAcceleration : p,
              l = r(e.instance.popper),
              f = g(l),
              m = { position: n.position },
              c = {
                left: _(n.left),
                top: _(n.top),
                bottom: _(n.bottom),
                right: _(n.right),
              },
              h = "bottom" === o ? "top" : "bottom",
              u = "right" === i ? "left" : "right",
              b = B("transform");
            if (
              ((d = "bottom" == h ? -f.height + c.bottom : c.top),
              (s = "right" == u ? -f.width + c.right : c.left),
              a && b)
            )
              (m[b] = "translate3d(" + s + "px, " + d + "px, 0)"),
                (m[h] = 0),
                (m[u] = 0),
                (m.willChange = "transform");
            else {
              var y = "bottom" == h ? -1 : 1,
                w = "right" == u ? -1 : 1;
              (m[h] = d * y), (m[u] = s * w), (m.willChange = h + ", " + u);
            }
            var E = { "x-placement": e.placement };
            return (
              (e.attributes = de({}, E, e.attributes)),
              (e.styles = de({}, m, e.styles)),
              (e.arrowStyles = de({}, e.offsets.arrow, e.arrowStyles)),
              e
            );
          },
          gpuAcceleration: !0,
          x: "bottom",
          y: "right",
        },
        applyStyle: {
          order: 900,
          enabled: !0,
          fn: function (e) {
            return (
              U(e.instance.popper, e.styles),
              Y(e.instance.popper, e.attributes),
              e.arrowElement &&
                Object.keys(e.arrowStyles).length &&
                U(e.arrowElement, e.arrowStyles),
              e
            );
          },
          onLoad: function (e, t, o, i, n) {
            var r = x(n, t, e),
              p = v(
                o.placement,
                r,
                t,
                e,
                o.modifiers.flip.boundariesElement,
                o.modifiers.flip.padding
              );
            return (
              t.setAttribute("x-placement", p),
              U(t, { position: "absolute" }),
              o
            );
          },
          gpuAcceleration: void 0,
        },
      },
    }),
    me
  );
});

/*!
 * Bootstrap v4.0.0-beta.2 (https://getbootstrap.com)
 * Copyright 2011-2017 The Bootstrap Authors (https://github.com/twbs/bootstrap/graphs/contributors)
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 */
var bootstrap = (function (t, e, n) {
  "use strict";
  function i(t, e) {
    for (var n = 0; n < e.length; n++) {
      var i = e[n];
      (i.enumerable = i.enumerable || !1),
        (i.configurable = !0),
        "value" in i && (i.writable = !0),
        Object.defineProperty(t, i.key, i);
    }
  }
  (e = e && e.hasOwnProperty("default") ? e.default : e),
    (n = n && n.hasOwnProperty("default") ? n.default : n);
  var s = (function () {
      function t(t) {
        return {}.toString
          .call(t)
          .match(/\s([a-zA-Z]+)/)[1]
          .toLowerCase();
      }
      function n() {
        return {
          bindType: r.end,
          delegateType: r.end,
          handle: function (t) {
            if (e(t.target).is(this))
              return t.handleObj.handler.apply(this, arguments);
          },
        };
      }
      function i() {
        if (window.QUnit) return !1;
        var t = document.createElement("bootstrap");
        for (var e in o)
          if ("undefined" != typeof t.style[e]) return { end: o[e] };
        return !1;
      }
      function s(t) {
        var n = this,
          i = !1;
        return (
          e(this).one(a.TRANSITION_END, function () {
            i = !0;
          }),
          setTimeout(function () {
            i || a.triggerTransitionEnd(n);
          }, t),
          this
        );
      }
      var r = !1,
        o = {
          WebkitTransition: "webkitTransitionEnd",
          MozTransition: "transitionend",
          OTransition: "oTransitionEnd otransitionend",
          transition: "transitionend",
        },
        a = {
          TRANSITION_END: "bsTransitionEnd",
          getUID: function (t) {
            do {
              t += ~~(1e6 * Math.random());
            } while (document.getElementById(t));
            return t;
          },
          getSelectorFromElement: function (t) {
            var n = t.getAttribute("data-target");
            (n && "#" !== n) || (n = t.getAttribute("href") || "");
            try {
              return e(document).find(n).length > 0 ? n : null;
            } catch (t) {
              return null;
            }
          },
          reflow: function (t) {
            return t.offsetHeight;
          },
          triggerTransitionEnd: function (t) {
            e(t).trigger(r.end);
          },
          supportsTransitionEnd: function () {
            return Boolean(r);
          },
          isElement: function (t) {
            return (t[0] || t).nodeType;
          },
          typeCheckConfig: function (e, n, i) {
            for (var s in i)
              if (Object.prototype.hasOwnProperty.call(i, s)) {
                var r = i[s],
                  o = n[s],
                  l = o && a.isElement(o) ? "element" : t(o);
                if (!new RegExp(r).test(l))
                  throw new Error(
                    e.toUpperCase() +
                      ': Option "' +
                      s +
                      '" provided type "' +
                      l +
                      '" but expected type "' +
                      r +
                      '".'
                  );
              }
          },
        };
      return (
        (r = i()),
        (e.fn.emulateTransitionEnd = s),
        a.supportsTransitionEnd() && (e.event.special[a.TRANSITION_END] = n()),
        a
      );
    })(),
    r = function (t, e, n) {
      return e && i(t.prototype, e), n && i(t, n), t;
    },
    o = function (t, e) {
      (t.prototype = Object.create(e.prototype)),
        (t.prototype.constructor = t),
        (t.__proto__ = e);
    },
    a = (function () {
      var t = "alert",
        n = e.fn[t],
        i = {
          CLOSE: "close.bs.alert",
          CLOSED: "closed.bs.alert",
          CLICK_DATA_API: "click.bs.alert.data-api",
        },
        o = { ALERT: "alert", FADE: "fade", SHOW: "show" },
        a = (function () {
          function t(t) {
            this._element = t;
          }
          var n = t.prototype;
          return (
            (n.close = function (t) {
              t = t || this._element;
              var e = this._getRootElement(t);
              this._triggerCloseEvent(e).isDefaultPrevented() ||
                this._removeElement(e);
            }),
            (n.dispose = function () {
              e.removeData(this._element, "bs.alert"), (this._element = null);
            }),
            (n._getRootElement = function (t) {
              var n = s.getSelectorFromElement(t),
                i = !1;
              return (
                n && (i = e(n)[0]), i || (i = e(t).closest("." + o.ALERT)[0]), i
              );
            }),
            (n._triggerCloseEvent = function (t) {
              var n = e.Event(i.CLOSE);
              return e(t).trigger(n), n;
            }),
            (n._removeElement = function (t) {
              var n = this;
              e(t).removeClass(o.SHOW),
                s.supportsTransitionEnd() && e(t).hasClass(o.FADE)
                  ? e(t)
                      .one(s.TRANSITION_END, function (e) {
                        return n._destroyElement(t, e);
                      })
                      .emulateTransitionEnd(150)
                  : this._destroyElement(t);
            }),
            (n._destroyElement = function (t) {
              e(t).detach().trigger(i.CLOSED).remove();
            }),
            (t._jQueryInterface = function (n) {
              return this.each(function () {
                var i = e(this),
                  s = i.data("bs.alert");
                s || ((s = new t(this)), i.data("bs.alert", s)),
                  "close" === n && s[n](this);
              });
            }),
            (t._handleDismiss = function (t) {
              return function (e) {
                e && e.preventDefault(), t.close(this);
              };
            }),
            r(t, null, [
              {
                key: "VERSION",
                get: function () {
                  return "4.0.0-beta.2";
                },
              },
            ]),
            t
          );
        })();
      return (
        e(document).on(
          i.CLICK_DATA_API,
          { DISMISS: '[data-bs-dismiss="alert"]' }.DISMISS,
          a._handleDismiss(new a())
        ),
        (e.fn[t] = a._jQueryInterface),
        (e.fn[t].Constructor = a),
        (e.fn[t].noConflict = function () {
          return (e.fn[t] = n), a._jQueryInterface;
        }),
        a
      );
    })(),
    l = (function () {
      var t = "button",
        n = e.fn[t],
        i = { ACTIVE: "active", BUTTON: "btn", FOCUS: "focus" },
        s = {
          DATA_TOGGLE_CARROT: '[data-toggle^="button"]',
          DATA_TOGGLE: '[data-toggle="buttons"]',
          INPUT: "input",
          ACTIVE: ".active",
          BUTTON: ".btn",
        },
        o = {
          CLICK_DATA_API: "click.bs.button.data-api",
          FOCUS_BLUR_DATA_API:
            "focus.bs.button.data-api blur.bs.button.data-api",
        },
        a = (function () {
          function t(t) {
            this._element = t;
          }
          var n = t.prototype;
          return (
            (n.toggle = function () {
              var t = !0,
                n = !0,
                r = e(this._element).closest(s.DATA_TOGGLE)[0];
              if (r) {
                var o = e(this._element).find(s.INPUT)[0];
                if (o) {
                  if ("radio" === o.type)
                    if (o.checked && e(this._element).hasClass(i.ACTIVE))
                      t = !1;
                    else {
                      var a = e(r).find(s.ACTIVE)[0];
                      a && e(a).removeClass(i.ACTIVE);
                    }
                  if (t) {
                    if (
                      o.hasAttribute("disabled") ||
                      r.hasAttribute("disabled") ||
                      o.classList.contains("disabled") ||
                      r.classList.contains("disabled")
                    )
                      return;
                    (o.checked = !e(this._element).hasClass(i.ACTIVE)),
                      e(o).trigger("change");
                  }
                  o.focus(), (n = !1);
                }
              }
              n &&
                this._element.setAttribute(
                  "aria-pressed",
                  !e(this._element).hasClass(i.ACTIVE)
                ),
                t && e(this._element).toggleClass(i.ACTIVE);
            }),
            (n.dispose = function () {
              e.removeData(this._element, "bs.button"), (this._element = null);
            }),
            (t._jQueryInterface = function (n) {
              return this.each(function () {
                var i = e(this).data("bs.button");
                i || ((i = new t(this)), e(this).data("bs.button", i)),
                  "toggle" === n && i[n]();
              });
            }),
            r(t, null, [
              {
                key: "VERSION",
                get: function () {
                  return "4.0.0-beta.2";
                },
              },
            ]),
            t
          );
        })();
      return (
        e(document)
          .on(o.CLICK_DATA_API, s.DATA_TOGGLE_CARROT, function (t) {
            t.preventDefault();
            var n = t.target;
            e(n).hasClass(i.BUTTON) || (n = e(n).closest(s.BUTTON)),
              a._jQueryInterface.call(e(n), "toggle");
          })
          .on(o.FOCUS_BLUR_DATA_API, s.DATA_TOGGLE_CARROT, function (t) {
            var n = e(t.target).closest(s.BUTTON)[0];
            e(n).toggleClass(i.FOCUS, /^focus(in)?$/.test(t.type));
          }),
        (e.fn[t] = a._jQueryInterface),
        (e.fn[t].Constructor = a),
        (e.fn[t].noConflict = function () {
          return (e.fn[t] = n), a._jQueryInterface;
        }),
        a
      );
    })(),
    h = (function () {
      var t = "carousel",
        n = "bs.carousel",
        i = "." + n,
        o = e.fn[t],
        a = {
          interval: 5e3,
          keyboard: !0,
          slide: !1,
          pause: "hover",
          wrap: !0,
        },
        l = {
          interval: "(number|boolean)",
          keyboard: "boolean",
          slide: "(boolean|string)",
          pause: "(string|boolean)",
          wrap: "boolean",
        },
        h = { NEXT: "next", PREV: "prev", LEFT: "left", RIGHT: "right" },
        c = {
          SLIDE: "slide" + i,
          SLID: "slid" + i,
          KEYDOWN: "keydown" + i,
          MOUSEENTER: "mouseenter" + i,
          MOUSELEAVE: "mouseleave" + i,
          TOUCHEND: "touchend" + i,
          LOAD_DATA_API: "load.bs.carousel.data-api",
          CLICK_DATA_API: "click.bs.carousel.data-api",
        },
        u = {
          CAROUSEL: "carousel",
          ACTIVE: "active",
          SLIDE: "slide",
          RIGHT: "carousel-item-right",
          LEFT: "carousel-item-left",
          NEXT: "carousel-item-next",
          PREV: "carousel-item-prev",
          ITEM: "carousel-item",
        },
        d = {
          ACTIVE: ".active",
          ACTIVE_ITEM: ".active.carousel-item",
          ITEM: ".carousel-item",
          NEXT_PREV: ".carousel-item-next, .carousel-item-prev",
          INDICATORS: ".carousel-indicators",
          DATA_SLIDE: "[data-slide], [data-slide-to]",
          DATA_RIDE: '[data-ride="carousel"]',
        },
        f = (function () {
          function o(t, n) {
            (this._items = null),
              (this._interval = null),
              (this._activeElement = null),
              (this._isPaused = !1),
              (this._isSliding = !1),
              (this.touchTimeout = null),
              (this._config = this._getConfig(n)),
              (this._element = e(t)[0]),
              (this._indicatorsElement = e(this._element).find(
                d.INDICATORS
              )[0]),
              this._addEventListeners();
          }
          var f = o.prototype;
          return (
            (f.next = function () {
              this._isSliding || this._slide(h.NEXT);
            }),
            (f.nextWhenVisible = function () {
              !document.hidden &&
                e(this._element).is(":visible") &&
                "hidden" !== e(this._element).css("visibility") &&
                this.next();
            }),
            (f.prev = function () {
              this._isSliding || this._slide(h.PREV);
            }),
            (f.pause = function (t) {
              t || (this._isPaused = !0),
                e(this._element).find(d.NEXT_PREV)[0] &&
                  s.supportsTransitionEnd() &&
                  (s.triggerTransitionEnd(this._element), this.cycle(!0)),
                clearInterval(this._interval),
                (this._interval = null);
            }),
            (f.cycle = function (t) {
              t || (this._isPaused = !1),
                this._interval &&
                  (clearInterval(this._interval), (this._interval = null)),
                this._config.interval &&
                  !this._isPaused &&
                  (this._interval = setInterval(
                    (document.visibilityState
                      ? this.nextWhenVisible
                      : this.next
                    ).bind(this),
                    this._config.interval
                  ));
            }),
            (f.to = function (t) {
              var n = this;
              this._activeElement = e(this._element).find(d.ACTIVE_ITEM)[0];
              var i = this._getItemIndex(this._activeElement);
              if (!(t > this._items.length - 1 || t < 0))
                if (this._isSliding)
                  e(this._element).one(c.SLID, function () {
                    return n.to(t);
                  });
                else {
                  if (i === t) return this.pause(), void this.cycle();
                  var s = t > i ? h.NEXT : h.PREV;
                  this._slide(s, this._items[t]);
                }
            }),
            (f.dispose = function () {
              e(this._element).off(i),
                e.removeData(this._element, n),
                (this._items = null),
                (this._config = null),
                (this._element = null),
                (this._interval = null),
                (this._isPaused = null),
                (this._isSliding = null),
                (this._activeElement = null),
                (this._indicatorsElement = null);
            }),
            (f._getConfig = function (n) {
              return (n = e.extend({}, a, n)), s.typeCheckConfig(t, n, l), n;
            }),
            (f._addEventListeners = function () {
              var t = this;
              this._config.keyboard &&
                e(this._element).on(c.KEYDOWN, function (e) {
                  return t._keydown(e);
                }),
                "hover" === this._config.pause &&
                  (e(this._element)
                    .on(c.MOUSEENTER, function (e) {
                      return t.pause(e);
                    })
                    .on(c.MOUSELEAVE, function (e) {
                      return t.cycle(e);
                    }),
                  "ontouchstart" in document.documentElement &&
                    e(this._element).on(c.TOUCHEND, function () {
                      t.pause(),
                        t.touchTimeout && clearTimeout(t.touchTimeout),
                        (t.touchTimeout = setTimeout(function (e) {
                          return t.cycle(e);
                        }, 500 + t._config.interval));
                    }));
            }),
            (f._keydown = function (t) {
              if (!/input|textarea/i.test(t.target.tagName))
                switch (t.which) {
                  case 37:
                    t.preventDefault(), this.prev();
                    break;
                  case 39:
                    t.preventDefault(), this.next();
                    break;
                  default:
                    return;
                }
            }),
            (f._getItemIndex = function (t) {
              return (
                (this._items = e.makeArray(e(t).parent().find(d.ITEM))),
                this._items.indexOf(t)
              );
            }),
            (f._getItemByDirection = function (t, e) {
              var n = t === h.NEXT,
                i = t === h.PREV,
                s = this._getItemIndex(e),
                r = this._items.length - 1;
              if (((i && 0 === s) || (n && s === r)) && !this._config.wrap)
                return e;
              var o = (s + (t === h.PREV ? -1 : 1)) % this._items.length;
              return -1 === o
                ? this._items[this._items.length - 1]
                : this._items[o];
            }),
            (f._triggerSlideEvent = function (t, n) {
              var i = this._getItemIndex(t),
                s = this._getItemIndex(e(this._element).find(d.ACTIVE_ITEM)[0]),
                r = e.Event(c.SLIDE, {
                  relatedTarget: t,
                  direction: n,
                  from: s,
                  to: i,
                });
              return e(this._element).trigger(r), r;
            }),
            (f._setActiveIndicatorElement = function (t) {
              if (this._indicatorsElement) {
                e(this._indicatorsElement).find(d.ACTIVE).removeClass(u.ACTIVE);
                var n = this._indicatorsElement.children[this._getItemIndex(t)];
                n && e(n).addClass(u.ACTIVE);
              }
            }),
            (f._slide = function (t, n) {
              var i,
                r,
                o,
                a = this,
                l = e(this._element).find(d.ACTIVE_ITEM)[0],
                f = this._getItemIndex(l),
                _ = n || (l && this._getItemByDirection(t, l)),
                g = this._getItemIndex(_),
                m = Boolean(this._interval);
              if (
                (t === h.NEXT
                  ? ((i = u.LEFT), (r = u.NEXT), (o = h.LEFT))
                  : ((i = u.RIGHT), (r = u.PREV), (o = h.RIGHT)),
                _ && e(_).hasClass(u.ACTIVE))
              )
                this._isSliding = !1;
              else if (
                !this._triggerSlideEvent(_, o).isDefaultPrevented() &&
                l &&
                _
              ) {
                (this._isSliding = !0),
                  m && this.pause(),
                  this._setActiveIndicatorElement(_);
                var p = e.Event(c.SLID, {
                  relatedTarget: _,
                  direction: o,
                  from: f,
                  to: g,
                });
                s.supportsTransitionEnd() && e(this._element).hasClass(u.SLIDE)
                  ? (e(_).addClass(r),
                    s.reflow(_),
                    e(l).addClass(i),
                    e(_).addClass(i),
                    e(l)
                      .one(s.TRANSITION_END, function () {
                        e(_)
                          .removeClass(i + " " + r)
                          .addClass(u.ACTIVE),
                          e(l).removeClass(u.ACTIVE + " " + r + " " + i),
                          (a._isSliding = !1),
                          setTimeout(function () {
                            return e(a._element).trigger(p);
                          }, 0);
                      })
                      .emulateTransitionEnd(600))
                  : (e(l).removeClass(u.ACTIVE),
                    e(_).addClass(u.ACTIVE),
                    (this._isSliding = !1),
                    e(this._element).trigger(p)),
                  m && this.cycle();
              }
            }),
            (o._jQueryInterface = function (t) {
              return this.each(function () {
                var i = e(this).data(n),
                  s = e.extend({}, a, e(this).data());
                "object" == typeof t && e.extend(s, t);
                var r = "string" == typeof t ? t : s.slide;
                if (
                  (i || ((i = new o(this, s)), e(this).data(n, i)),
                  "number" == typeof t)
                )
                  i.to(t);
                else if ("string" == typeof r) {
                  if ("undefined" == typeof i[r])
                    throw new Error('No method named "' + r + '"');
                  i[r]();
                } else s.interval && (i.pause(), i.cycle());
              });
            }),
            (o._dataApiClickHandler = function (t) {
              var i = s.getSelectorFromElement(this);
              if (i) {
                var r = e(i)[0];
                if (r && e(r).hasClass(u.CAROUSEL)) {
                  var a = e.extend({}, e(r).data(), e(this).data()),
                    l = this.getAttribute("data-slide-to");
                  l && (a.interval = !1),
                    o._jQueryInterface.call(e(r), a),
                    l && e(r).data(n).to(l),
                    t.preventDefault();
                }
              }
            }),
            r(o, null, [
              {
                key: "VERSION",
                get: function () {
                  return "4.0.0-beta.2";
                },
              },
              {
                key: "Default",
                get: function () {
                  return a;
                },
              },
            ]),
            o
          );
        })();
      return (
        e(document).on(c.CLICK_DATA_API, d.DATA_SLIDE, f._dataApiClickHandler),
        e(window).on(c.LOAD_DATA_API, function () {
          e(d.DATA_RIDE).each(function () {
            var t = e(this);
            f._jQueryInterface.call(t, t.data());
          });
        }),
        (e.fn[t] = f._jQueryInterface),
        (e.fn[t].Constructor = f),
        (e.fn[t].noConflict = function () {
          return (e.fn[t] = o), f._jQueryInterface;
        }),
        f
      );
    })(),
    c = (function () {
      var t = "collapse",
        n = "bs.collapse",
        i = e.fn[t],
        o = { toggle: !0, parent: "" },
        a = { toggle: "boolean", parent: "(string|element)" },
        l = {
          SHOW: "show.bs.collapse",
          SHOWN: "shown.bs.collapse",
          HIDE: "hide.bs.collapse",
          HIDDEN: "hidden.bs.collapse",
          CLICK_DATA_API: "click.bs.collapse.data-api",
        },
        h = {
          SHOW: "show",
          COLLAPSE: "collapse",
          COLLAPSING: "collapsing",
          COLLAPSED: "collapsed",
        },
        c = { WIDTH: "width", HEIGHT: "height" },
        u = {
          ACTIVES: ".show, .collapsing",
          DATA_TOGGLE: '[data-toggle="collapse"]',
        },
        d = (function () {
          function i(t, n) {
            (this._isTransitioning = !1),
              (this._element = t),
              (this._config = this._getConfig(n)),
              (this._triggerArray = e.makeArray(
                e(
                  '[data-toggle="collapse"][href="#' +
                    t.id +
                    '"],[data-toggle="collapse"][data-target="#' +
                    t.id +
                    '"]'
                )
              ));
            for (var i = e(u.DATA_TOGGLE), r = 0; r < i.length; r++) {
              var o = i[r],
                a = s.getSelectorFromElement(o);
              null !== a &&
                e(a).filter(t).length > 0 &&
                this._triggerArray.push(o);
            }
            (this._parent = this._config.parent ? this._getParent() : null),
              this._config.parent ||
                this._addAriaAndCollapsedClass(
                  this._element,
                  this._triggerArray
                ),
              this._config.toggle && this.toggle();
          }
          var d = i.prototype;
          return (
            (d.toggle = function () {
              e(this._element).hasClass(h.SHOW) ? this.hide() : this.show();
            }),
            (d.show = function () {
              var t = this;
              if (
                !this._isTransitioning &&
                !e(this._element).hasClass(h.SHOW)
              ) {
                var r, o;
                if (
                  (this._parent &&
                    ((r = e.makeArray(
                      e(this._parent).children().children(u.ACTIVES)
                    )).length ||
                      (r = null)),
                  !(r && (o = e(r).data(n)) && o._isTransitioning))
                ) {
                  var a = e.Event(l.SHOW);
                  if ((e(this._element).trigger(a), !a.isDefaultPrevented())) {
                    r &&
                      (i._jQueryInterface.call(e(r), "hide"),
                      o || e(r).data(n, null));
                    var c = this._getDimension();
                    e(this._element)
                      .removeClass(h.COLLAPSE)
                      .addClass(h.COLLAPSING),
                      (this._element.style[c] = 0),
                      this._triggerArray.length &&
                        e(this._triggerArray)
                          .removeClass(h.COLLAPSED)
                          .attr("aria-expanded", !0),
                      this.setTransitioning(!0);
                    var d = function () {
                      e(t._element)
                        .removeClass(h.COLLAPSING)
                        .addClass(h.COLLAPSE)
                        .addClass(h.SHOW),
                        (t._element.style[c] = ""),
                        t.setTransitioning(!1),
                        e(t._element).trigger(l.SHOWN);
                    };
                    if (s.supportsTransitionEnd()) {
                      var f = "scroll" + (c[0].toUpperCase() + c.slice(1));
                      e(this._element)
                        .one(s.TRANSITION_END, d)
                        .emulateTransitionEnd(600),
                        (this._element.style[c] = this._element[f] + "px");
                    } else d();
                  }
                }
              }
            }),
            (d.hide = function () {
              var t = this;
              if (!this._isTransitioning && e(this._element).hasClass(h.SHOW)) {
                var n = e.Event(l.HIDE);
                if ((e(this._element).trigger(n), !n.isDefaultPrevented())) {
                  var i = this._getDimension();
                  if (
                    ((this._element.style[i] =
                      this._element.getBoundingClientRect()[i] + "px"),
                    s.reflow(this._element),
                    e(this._element)
                      .addClass(h.COLLAPSING)
                      .removeClass(h.COLLAPSE)
                      .removeClass(h.SHOW),
                    this._triggerArray.length)
                  )
                    for (var r = 0; r < this._triggerArray.length; r++) {
                      var o = this._triggerArray[r],
                        a = s.getSelectorFromElement(o);
                      null !== a &&
                        (e(a).hasClass(h.SHOW) ||
                          e(o).addClass(h.COLLAPSED).attr("aria-expanded", !1));
                    }
                  this.setTransitioning(!0);
                  var c = function () {
                    t.setTransitioning(!1),
                      e(t._element)
                        .removeClass(h.COLLAPSING)
                        .addClass(h.COLLAPSE)
                        .trigger(l.HIDDEN);
                  };
                  (this._element.style[i] = ""),
                    s.supportsTransitionEnd()
                      ? e(this._element)
                          .one(s.TRANSITION_END, c)
                          .emulateTransitionEnd(600)
                      : c();
                }
              }
            }),
            (d.setTransitioning = function (t) {
              this._isTransitioning = t;
            }),
            (d.dispose = function () {
              e.removeData(this._element, n),
                (this._config = null),
                (this._parent = null),
                (this._element = null),
                (this._triggerArray = null),
                (this._isTransitioning = null);
            }),
            (d._getConfig = function (n) {
              return (
                (n = e.extend({}, o, n)),
                (n.toggle = Boolean(n.toggle)),
                s.typeCheckConfig(t, n, a),
                n
              );
            }),
            (d._getDimension = function () {
              return e(this._element).hasClass(c.WIDTH) ? c.WIDTH : c.HEIGHT;
            }),
            (d._getParent = function () {
              var t = this,
                n = null;
              s.isElement(this._config.parent)
                ? ((n = this._config.parent),
                  "undefined" != typeof this._config.parent.jquery &&
                    (n = this._config.parent[0]))
                : (n = e(this._config.parent)[0]);
              var r =
                '[data-toggle="collapse"][data-parent="' +
                this._config.parent +
                '"]';
              return (
                e(n)
                  .find(r)
                  .each(function (e, n) {
                    t._addAriaAndCollapsedClass(i._getTargetFromElement(n), [
                      n,
                    ]);
                  }),
                n
              );
            }),
            (d._addAriaAndCollapsedClass = function (t, n) {
              if (t) {
                var i = e(t).hasClass(h.SHOW);
                n.length &&
                  e(n).toggleClass(h.COLLAPSED, !i).attr("aria-expanded", i);
              }
            }),
            (i._getTargetFromElement = function (t) {
              var n = s.getSelectorFromElement(t);
              return n ? e(n)[0] : null;
            }),
            (i._jQueryInterface = function (t) {
              return this.each(function () {
                var s = e(this),
                  r = s.data(n),
                  a = e.extend({}, o, s.data(), "object" == typeof t && t);
                if (
                  (!r && a.toggle && /show|hide/.test(t) && (a.toggle = !1),
                  r || ((r = new i(this, a)), s.data(n, r)),
                  "string" == typeof t)
                ) {
                  if ("undefined" == typeof r[t])
                    throw new Error('No method named "' + t + '"');
                  r[t]();
                }
              });
            }),
            r(i, null, [
              {
                key: "VERSION",
                get: function () {
                  return "4.0.0-beta.2";
                },
              },
              {
                key: "Default",
                get: function () {
                  return o;
                },
              },
            ]),
            i
          );
        })();
      return (
        e(document).on(l.CLICK_DATA_API, u.DATA_TOGGLE, function (t) {
          "A" === t.currentTarget.tagName && t.preventDefault();
          var i = e(this),
            r = s.getSelectorFromElement(this);
          e(r).each(function () {
            var t = e(this),
              s = t.data(n) ? "toggle" : i.data();
            d._jQueryInterface.call(t, s);
          });
        }),
        (e.fn[t] = d._jQueryInterface),
        (e.fn[t].Constructor = d),
        (e.fn[t].noConflict = function () {
          return (e.fn[t] = i), d._jQueryInterface;
        }),
        d
      );
    })(),
    u = (function () {
      if ("undefined" == typeof n)
        throw new Error(
          "Bootstrap dropdown require Popper.js (https://popper.js.org)"
        );
      var t = "dropdown",
        i = "bs.dropdown",
        o = "." + i,
        a = e.fn[t],
        l = new RegExp("38|40|27"),
        h = {
          HIDE: "hide" + o,
          HIDDEN: "hidden" + o,
          SHOW: "show" + o,
          SHOWN: "shown" + o,
          CLICK: "click" + o,
          CLICK_DATA_API: "click.bs.dropdown.data-api",
          KEYDOWN_DATA_API: "keydown.bs.dropdown.data-api",
          KEYUP_DATA_API: "keyup.bs.dropdown.data-api",
        },
        c = {
          DISABLED: "disabled",
          SHOW: "show",
          DROPUP: "dropup",
          MENURIGHT: "dropdown-menu-right",
          MENULEFT: "dropdown-menu-left",
        },
        u = {
          DATA_TOGGLE: '[data-toggle="dropdown"]',
          FORM_CHILD: ".dropdown form",
          MENU: ".dropdown-menu",
          NAVBAR_NAV: ".navbar-nav",
          VISIBLE_ITEMS: ".dropdown-menu .dropdown-item:not(.disabled)",
        },
        d = {
          TOP: "top-start",
          TOPEND: "top-end",
          BOTTOM: "bottom-start",
          BOTTOMEND: "bottom-end",
        },
        f = { offset: 0, flip: !0 },
        _ = { offset: "(number|string|function)", flip: "boolean" },
        g = (function () {
          function a(t, e) {
            (this._element = t),
              (this._popper = null),
              (this._config = this._getConfig(e)),
              (this._menu = this._getMenuElement()),
              (this._inNavbar = this._detectNavbar()),
              this._addEventListeners();
          }
          var g = a.prototype;
          return (
            (g.toggle = function () {
              if (
                !this._element.disabled &&
                !e(this._element).hasClass(c.DISABLED)
              ) {
                var t = a._getParentFromElement(this._element),
                  i = e(this._menu).hasClass(c.SHOW);
                if ((a._clearMenus(), !i)) {
                  var s = { relatedTarget: this._element },
                    r = e.Event(h.SHOW, s);
                  if ((e(t).trigger(r), !r.isDefaultPrevented())) {
                    var o = this._element;
                    e(t).hasClass(c.DROPUP) &&
                      (e(this._menu).hasClass(c.MENULEFT) ||
                        e(this._menu).hasClass(c.MENURIGHT)) &&
                      (o = t),
                      (this._popper = new n(
                        o,
                        this._menu,
                        this._getPopperConfig()
                      )),
                      "ontouchstart" in document.documentElement &&
                        !e(t).closest(u.NAVBAR_NAV).length &&
                        e("body").children().on("mouseover", null, e.noop),
                      this._element.focus(),
                      this._element.setAttribute("aria-expanded", !0),
                      e(this._menu).toggleClass(c.SHOW),
                      e(t).toggleClass(c.SHOW).trigger(e.Event(h.SHOWN, s));
                  }
                }
              }
            }),
            (g.dispose = function () {
              e.removeData(this._element, i),
                e(this._element).off(o),
                (this._element = null),
                (this._menu = null),
                null !== this._popper && this._popper.destroy(),
                (this._popper = null);
            }),
            (g.update = function () {
              (this._inNavbar = this._detectNavbar()),
                null !== this._popper && this._popper.scheduleUpdate();
            }),
            (g._addEventListeners = function () {
              var t = this;
              e(this._element).on(h.CLICK, function (e) {
                e.preventDefault(), e.stopPropagation(), t.toggle();
              });
            }),
            (g._getConfig = function (n) {
              return (
                (n = e.extend(
                  {},
                  this.constructor.Default,
                  e(this._element).data(),
                  n
                )),
                s.typeCheckConfig(t, n, this.constructor.DefaultType),
                n
              );
            }),
            (g._getMenuElement = function () {
              if (!this._menu) {
                var t = a._getParentFromElement(this._element);
                this._menu = e(t).find(u.MENU)[0];
              }
              return this._menu;
            }),
            (g._getPlacement = function () {
              var t = e(this._element).parent(),
                n = d.BOTTOM;
              return (
                t.hasClass(c.DROPUP)
                  ? ((n = d.TOP),
                    e(this._menu).hasClass(c.MENURIGHT) && (n = d.TOPEND))
                  : e(this._menu).hasClass(c.MENURIGHT) && (n = d.BOTTOMEND),
                n
              );
            }),
            (g._detectNavbar = function () {
              return e(this._element).closest(".navbar").length > 0;
            }),
            (g._getPopperConfig = function () {
              var t = this,
                n = {};
              "function" == typeof this._config.offset
                ? (n.fn = function (n) {
                    return (
                      (n.offsets = e.extend(
                        {},
                        n.offsets,
                        t._config.offset(n.offsets) || {}
                      )),
                      n
                    );
                  })
                : (n.offset = this._config.offset);
              var i = {
                placement: this._getPlacement(),
                modifiers: { offset: n, flip: { enabled: this._config.flip } },
              };
              return (
                this._inNavbar &&
                  (i.modifiers.applyStyle = { enabled: !this._inNavbar }),
                i
              );
            }),
            (a._jQueryInterface = function (t) {
              return this.each(function () {
                var n = e(this).data(i),
                  s = "object" == typeof t ? t : null;
                if (
                  (n || ((n = new a(this, s)), e(this).data(i, n)),
                  "string" == typeof t)
                ) {
                  if ("undefined" == typeof n[t])
                    throw new Error('No method named "' + t + '"');
                  n[t]();
                }
              });
            }),
            (a._clearMenus = function (t) {
              if (
                !t ||
                (3 !== t.which && ("keyup" !== t.type || 9 === t.which))
              )
                for (
                  var n = e.makeArray(e(u.DATA_TOGGLE)), s = 0;
                  s < n.length;
                  s++
                ) {
                  var r = a._getParentFromElement(n[s]),
                    o = e(n[s]).data(i),
                    l = { relatedTarget: n[s] };
                  if (o) {
                    var d = o._menu;
                    if (
                      e(r).hasClass(c.SHOW) &&
                      !(
                        t &&
                        (("click" === t.type &&
                          /input|textarea/i.test(t.target.tagName)) ||
                          ("keyup" === t.type && 9 === t.which)) &&
                        e.contains(r, t.target)
                      )
                    ) {
                      var f = e.Event(h.HIDE, l);
                      e(r).trigger(f),
                        f.isDefaultPrevented() ||
                          ("ontouchstart" in document.documentElement &&
                            e("body").children().off("mouseover", null, e.noop),
                          n[s].setAttribute("aria-expanded", "false"),
                          e(d).removeClass(c.SHOW),
                          e(r)
                            .removeClass(c.SHOW)
                            .trigger(e.Event(h.HIDDEN, l)));
                    }
                  }
                }
            }),
            (a._getParentFromElement = function (t) {
              var n,
                i = s.getSelectorFromElement(t);
              return i && (n = e(i)[0]), n || t.parentNode;
            }),
            (a._dataApiKeydownHandler = function (t) {
              if (
                !(
                  !l.test(t.which) ||
                  (/button/i.test(t.target.tagName) && 32 === t.which) ||
                  /input|textarea/i.test(t.target.tagName) ||
                  (t.preventDefault(),
                  t.stopPropagation(),
                  this.disabled || e(this).hasClass(c.DISABLED))
                )
              ) {
                var n = a._getParentFromElement(this),
                  i = e(n).hasClass(c.SHOW);
                if (
                  (i || (27 === t.which && 32 === t.which)) &&
                  (!i || (27 !== t.which && 32 !== t.which))
                ) {
                  var s = e(n).find(u.VISIBLE_ITEMS).get();
                  if (s.length) {
                    var r = s.indexOf(t.target);
                    38 === t.which && r > 0 && r--,
                      40 === t.which && r < s.length - 1 && r++,
                      r < 0 && (r = 0),
                      s[r].focus();
                  }
                } else {
                  if (27 === t.which) {
                    var o = e(n).find(u.DATA_TOGGLE)[0];
                    e(o).trigger("focus");
                  }
                  e(this).trigger("click");
                }
              }
            }),
            r(a, null, [
              {
                key: "VERSION",
                get: function () {
                  return "4.0.0-beta.2";
                },
              },
              {
                key: "Default",
                get: function () {
                  return f;
                },
              },
              {
                key: "DefaultType",
                get: function () {
                  return _;
                },
              },
            ]),
            a
          );
        })();
      return (
        e(document)
          .on(h.KEYDOWN_DATA_API, u.DATA_TOGGLE, g._dataApiKeydownHandler)
          .on(h.KEYDOWN_DATA_API, u.MENU, g._dataApiKeydownHandler)
          .on(h.CLICK_DATA_API + " " + h.KEYUP_DATA_API, g._clearMenus)
          .on(h.CLICK_DATA_API, u.DATA_TOGGLE, function (t) {
            t.preventDefault(),
              t.stopPropagation(),
              g._jQueryInterface.call(e(this), "toggle");
          })
          .on(h.CLICK_DATA_API, u.FORM_CHILD, function (t) {
            t.stopPropagation();
          }),
        (e.fn[t] = g._jQueryInterface),
        (e.fn[t].Constructor = g),
        (e.fn[t].noConflict = function () {
          return (e.fn[t] = a), g._jQueryInterface;
        }),
        g
      );
    })(),
    d = (function () {
      var t = "modal",
        n = ".bs.modal",
        i = e.fn[t],
        o = { backdrop: !0, keyboard: !0, focus: !0, show: !0 },
        a = {
          backdrop: "(boolean|string)",
          keyboard: "boolean",
          focus: "boolean",
          show: "boolean",
        },
        l = {
          HIDE: "hide.bs.modal",
          HIDDEN: "hidden.bs.modal",
          SHOW: "show.bs.modal",
          SHOWN: "shown.bs.modal",
          FOCUSIN: "focusin.bs.modal",
          RESIZE: "resize.bs.modal",
          CLICK_DISMISS: "click.dismiss.bs.modal",
          KEYDOWN_DISMISS: "keydown.dismiss.bs.modal",
          MOUSEUP_DISMISS: "mouseup.dismiss.bs.modal",
          MOUSEDOWN_DISMISS: "mousedown.dismiss.bs.modal",
          CLICK_DATA_API: "click.bs.modal.data-api",
        },
        h = {
          SCROLLBAR_MEASURER: "modal-scrollbar-measure",
          BACKDROP: "modal-backdrop",
          OPEN: "modal-open",
          FADE: "fade",
          SHOW: "show",
        },
        c = {
          DIALOG: ".modal-dialog",
          DATA_TOGGLE: '[data-toggle="modal"]',
          DATA_DISMISS: '[data-bs-dismiss="modal"]',
          FIXED_CONTENT: ".fixed-top, .fixed-bottom, .is-fixed, .sticky-top",
          STICKY_CONTENT: ".sticky-top",
          NAVBAR_TOGGLER: ".navbar-toggler",
        },
        u = (function () {
          function i(t, n) {
            (this._config = this._getConfig(n)),
              (this._element = t),
              (this._dialog = e(t).find(c.DIALOG)[0]),
              (this._backdrop = null),
              (this._isShown = !1),
              (this._isBodyOverflowing = !1),
              (this._ignoreBackdropClick = !1),
              (this._originalBodyPadding = 0),
              (this._scrollbarWidth = 0);
          }
          var u = i.prototype;
          return (
            (u.toggle = function (t) {
              return this._isShown ? this.hide() : this.show(t);
            }),
            (u.show = function (t) {
              var n = this;
              if (!this._isTransitioning && !this._isShown) {
                s.supportsTransitionEnd() &&
                  e(this._element).hasClass(h.FADE) &&
                  (this._isTransitioning = !0);
                var i = e.Event(l.SHOW, { relatedTarget: t });
                e(this._element).trigger(i),
                  this._isShown ||
                    i.isDefaultPrevented() ||
                    ((this._isShown = !0),
                    this._checkScrollbar(),
                    this._setScrollbar(),
                    this._adjustDialog(),
                    e(document.body).addClass(h.OPEN),
                    this._setEscapeEvent(),
                    this._setResizeEvent(),
                    e(this._element).on(
                      l.CLICK_DISMISS,
                      c.DATA_DISMISS,
                      function (t) {
                        return n.hide(t);
                      }
                    ),
                    e(this._dialog).on(l.MOUSEDOWN_DISMISS, function () {
                      e(n._element).one(l.MOUSEUP_DISMISS, function (t) {
                        e(t.target).is(n._element) &&
                          (n._ignoreBackdropClick = !0);
                      });
                    }),
                    this._showBackdrop(function () {
                      return n._showElement(t);
                    }));
              }
            }),
            (u.hide = function (t) {
              var n = this;
              if (
                (t && t.preventDefault(),
                !this._isTransitioning && this._isShown)
              ) {
                var i = e.Event(l.HIDE);
                if (
                  (e(this._element).trigger(i),
                  this._isShown && !i.isDefaultPrevented())
                ) {
                  this._isShown = !1;
                  var r =
                    s.supportsTransitionEnd() &&
                    e(this._element).hasClass(h.FADE);
                  r && (this._isTransitioning = !0),
                    this._setEscapeEvent(),
                    this._setResizeEvent(),
                    e(document).off(l.FOCUSIN),
                    e(this._element).removeClass(h.SHOW),
                    e(this._element).off(l.CLICK_DISMISS),
                    e(this._dialog).off(l.MOUSEDOWN_DISMISS),
                    r
                      ? e(this._element)
                          .one(s.TRANSITION_END, function (t) {
                            return n._hideModal(t);
                          })
                          .emulateTransitionEnd(300)
                      : this._hideModal();
                }
              }
            }),
            (u.dispose = function () {
              e.removeData(this._element, "bs.modal"),
                e(window, document, this._element, this._backdrop).off(n),
                (this._config = null),
                (this._element = null),
                (this._dialog = null),
                (this._backdrop = null),
                (this._isShown = null),
                (this._isBodyOverflowing = null),
                (this._ignoreBackdropClick = null),
                (this._scrollbarWidth = null);
            }),
            (u.handleUpdate = function () {
              this._adjustDialog();
            }),
            (u._getConfig = function (n) {
              return (n = e.extend({}, o, n)), s.typeCheckConfig(t, n, a), n;
            }),
            (u._showElement = function (t) {
              var n = this,
                i =
                  s.supportsTransitionEnd() &&
                  e(this._element).hasClass(h.FADE);
              (this._element.parentNode &&
                this._element.parentNode.nodeType === Node.ELEMENT_NODE) ||
                document.body.appendChild(this._element),
                (this._element.style.display = "block"),
                this._element.removeAttribute("aria-hidden"),
                (this._element.scrollTop = 0),
                i && s.reflow(this._element),
                e(this._element).addClass(h.SHOW),
                this._config.focus && this._enforceFocus();
              var r = e.Event(l.SHOWN, { relatedTarget: t }),
                o = function () {
                  n._config.focus && n._element.focus(),
                    (n._isTransitioning = !1),
                    e(n._element).trigger(r);
                };
              i
                ? e(this._dialog)
                    .one(s.TRANSITION_END, o)
                    .emulateTransitionEnd(300)
                : o();
            }),
            (u._enforceFocus = function () {
              var t = this;
              e(document)
                .off(l.FOCUSIN)
                .on(l.FOCUSIN, function (n) {
                  document === n.target ||
                    t._element === n.target ||
                    e(t._element).has(n.target).length ||
                    t._element.focus();
                });
            }),
            (u._setEscapeEvent = function () {
              var t = this;
              this._isShown && this._config.keyboard
                ? e(this._element).on(l.KEYDOWN_DISMISS, function (e) {
                    27 === e.which && (e.preventDefault(), t.hide());
                  })
                : this._isShown || e(this._element).off(l.KEYDOWN_DISMISS);
            }),
            (u._setResizeEvent = function () {
              var t = this;
              this._isShown
                ? e(window).on(l.RESIZE, function (e) {
                    return t.handleUpdate(e);
                  })
                : e(window).off(l.RESIZE);
            }),
            (u._hideModal = function () {
              var t = this;
              (this._element.style.display = "none"),
                this._element.setAttribute("aria-hidden", !0),
                (this._isTransitioning = !1),
                this._showBackdrop(function () {
                  e(document.body).removeClass(h.OPEN),
                    t._resetAdjustments(),
                    t._resetScrollbar(),
                    e(t._element).trigger(l.HIDDEN);
                });
            }),
            (u._removeBackdrop = function () {
              this._backdrop &&
                (e(this._backdrop).remove(), (this._backdrop = null));
            }),
            (u._showBackdrop = function (t) {
              var n = this,
                i = e(this._element).hasClass(h.FADE) ? h.FADE : "";
              if (this._isShown && this._config.backdrop) {
                var r = s.supportsTransitionEnd() && i;
                if (
                  ((this._backdrop = document.createElement("div")),
                  (this._backdrop.className = h.BACKDROP),
                  i && e(this._backdrop).addClass(i),
                  e(this._backdrop).appendTo(document.body),
                  e(this._element).on(l.CLICK_DISMISS, function (t) {
                    n._ignoreBackdropClick
                      ? (n._ignoreBackdropClick = !1)
                      : t.target === t.currentTarget &&
                        ("static" === n._config.backdrop
                          ? n._element.focus()
                          : n.hide());
                  }),
                  r && s.reflow(this._backdrop),
                  e(this._backdrop).addClass(h.SHOW),
                  !t)
                )
                  return;
                if (!r) return void t();
                e(this._backdrop)
                  .one(s.TRANSITION_END, t)
                  .emulateTransitionEnd(150);
              } else if (!this._isShown && this._backdrop) {
                e(this._backdrop).removeClass(h.SHOW);
                var o = function () {
                  n._removeBackdrop(), t && t();
                };
                s.supportsTransitionEnd() && e(this._element).hasClass(h.FADE)
                  ? e(this._backdrop)
                      .one(s.TRANSITION_END, o)
                      .emulateTransitionEnd(150)
                  : o();
              } else t && t();
            }),
            (u._adjustDialog = function () {
              var t =
                this._element.scrollHeight >
                document.documentElement.clientHeight;
              !this._isBodyOverflowing &&
                t &&
                (this._element.style.paddingLeft = this._scrollbarWidth + "px"),
                this._isBodyOverflowing &&
                  !t &&
                  (this._element.style.paddingRight =
                    this._scrollbarWidth + "px");
            }),
            (u._resetAdjustments = function () {
              (this._element.style.paddingLeft = ""),
                (this._element.style.paddingRight = "");
            }),
            (u._checkScrollbar = function () {
              var t = document.body.getBoundingClientRect();
              (this._isBodyOverflowing = t.left + t.right < window.innerWidth),
                (this._scrollbarWidth = this._getScrollbarWidth());
            }),
            (u._setScrollbar = function () {
              var t = this;
              if (this._isBodyOverflowing) {
                e(c.FIXED_CONTENT).each(function (n, i) {
                  var s = e(i)[0].style.paddingRight,
                    r = e(i).css("padding-right");
                  e(i)
                    .data("padding-right", s)
                    .css(
                      "padding-right",
                      parseFloat(r) + t._scrollbarWidth + "px"
                    );
                }),
                  e(c.STICKY_CONTENT).each(function (n, i) {
                    var s = e(i)[0].style.marginRight,
                      r = e(i).css("margin-right");
                    e(i)
                      .data("margin-right", s)
                      .css(
                        "margin-right",
                        parseFloat(r) - t._scrollbarWidth + "px"
                      );
                  }),
                  e(c.NAVBAR_TOGGLER).each(function (n, i) {
                    var s = e(i)[0].style.marginRight,
                      r = e(i).css("margin-right");
                    e(i)
                      .data("margin-right", s)
                      .css(
                        "margin-right",
                        parseFloat(r) + t._scrollbarWidth + "px"
                      );
                  });
                var n = document.body.style.paddingRight,
                  i = e("body").css("padding-right");
                e("body")
                  .data("padding-right", n)
                  .css(
                    "padding-right",
                    parseFloat(i) + this._scrollbarWidth + "px"
                  );
              }
            }),
            (u._resetScrollbar = function () {
              e(c.FIXED_CONTENT).each(function (t, n) {
                var i = e(n).data("padding-right");
                "undefined" != typeof i &&
                  e(n).css("padding-right", i).removeData("padding-right");
              }),
                e(c.STICKY_CONTENT + ", " + c.NAVBAR_TOGGLER).each(function (
                  t,
                  n
                ) {
                  var i = e(n).data("margin-right");
                  "undefined" != typeof i &&
                    e(n).css("margin-right", i).removeData("margin-right");
                });
              var t = e("body").data("padding-right");
              "undefined" != typeof t &&
                e("body").css("padding-right", t).removeData("padding-right");
            }),
            (u._getScrollbarWidth = function () {
              var t = document.createElement("div");
              (t.className = h.SCROLLBAR_MEASURER),
                document.body.appendChild(t);
              var e = t.getBoundingClientRect().width - t.clientWidth;
              return document.body.removeChild(t), e;
            }),
            (i._jQueryInterface = function (t, n) {
              return this.each(function () {
                var s = e(this).data("bs.modal"),
                  r = e.extend(
                    {},
                    i.Default,
                    e(this).data(),
                    "object" == typeof t && t
                  );
                if (
                  (s || ((s = new i(this, r)), e(this).data("bs.modal", s)),
                  "string" == typeof t)
                ) {
                  if ("undefined" == typeof s[t])
                    throw new Error('No method named "' + t + '"');
                  s[t](n);
                } else r.show && s.show(n);
              });
            }),
            r(i, null, [
              {
                key: "VERSION",
                get: function () {
                  return "4.0.0-beta.2";
                },
              },
              {
                key: "Default",
                get: function () {
                  return o;
                },
              },
            ]),
            i
          );
        })();
      return (
        e(document).on(l.CLICK_DATA_API, c.DATA_TOGGLE, function (t) {
          var n,
            i = this,
            r = s.getSelectorFromElement(this);
          r && (n = e(r)[0]);
          var o = e(n).data("bs.modal")
            ? "toggle"
            : e.extend({}, e(n).data(), e(this).data());
          ("A" !== this.tagName && "AREA" !== this.tagName) ||
            t.preventDefault();
          var a = e(n).one(l.SHOW, function (t) {
            t.isDefaultPrevented() ||
              a.one(l.HIDDEN, function () {
                e(i).is(":visible") && i.focus();
              });
          });
          u._jQueryInterface.call(e(n), o, this);
        }),
        (e.fn[t] = u._jQueryInterface),
        (e.fn[t].Constructor = u),
        (e.fn[t].noConflict = function () {
          return (e.fn[t] = i), u._jQueryInterface;
        }),
        u
      );
    })(),
    f = (function () {
      if ("undefined" == typeof n)
        throw new Error(
          "Bootstrap tooltips require Popper.js (https://popper.js.org)"
        );
      var t = "tooltip",
        i = ".bs.tooltip",
        o = e.fn[t],
        a = new RegExp("(^|\\s)bs-tooltip\\S+", "g"),
        l = {
          animation: "boolean",
          template: "string",
          title: "(string|element|function)",
          trigger: "string",
          delay: "(number|object)",
          html: "boolean",
          selector: "(string|boolean)",
          placement: "(string|function)",
          offset: "(number|string)",
          container: "(string|element|boolean)",
          fallbackPlacement: "(string|array)",
        },
        h = {
          AUTO: "auto",
          TOP: "top",
          RIGHT: "right",
          BOTTOM: "bottom",
          LEFT: "left",
        },
        c = {
          animation: !0,
          template:
            '<div class="tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>',
          trigger: "hover focus",
          title: "",
          delay: 0,
          html: !1,
          selector: !1,
          placement: "top",
          offset: 0,
          container: !1,
          fallbackPlacement: "flip",
        },
        u = { SHOW: "show", OUT: "out" },
        d = {
          HIDE: "hide" + i,
          HIDDEN: "hidden" + i,
          SHOW: "show" + i,
          SHOWN: "shown" + i,
          INSERTED: "inserted" + i,
          CLICK: "click" + i,
          FOCUSIN: "focusin" + i,
          FOCUSOUT: "focusout" + i,
          MOUSEENTER: "mouseenter" + i,
          MOUSELEAVE: "mouseleave" + i,
        },
        f = { FADE: "fade", SHOW: "show" },
        _ = {
          TOOLTIP: ".tooltip",
          TOOLTIP_INNER: ".tooltip-inner",
          ARROW: ".arrow",
        },
        g = {
          HOVER: "hover",
          FOCUS: "focus",
          CLICK: "click",
          MANUAL: "manual",
        },
        m = (function () {
          function o(t, e) {
            (this._isEnabled = !0),
              (this._timeout = 0),
              (this._hoverState = ""),
              (this._activeTrigger = {}),
              (this._popper = null),
              (this.element = t),
              (this.config = this._getConfig(e)),
              (this.tip = null),
              this._setListeners();
          }
          var m = o.prototype;
          return (
            (m.enable = function () {
              this._isEnabled = !0;
            }),
            (m.disable = function () {
              this._isEnabled = !1;
            }),
            (m.toggleEnabled = function () {
              this._isEnabled = !this._isEnabled;
            }),
            (m.toggle = function (t) {
              if (this._isEnabled)
                if (t) {
                  var n = this.constructor.DATA_KEY,
                    i = e(t.currentTarget).data(n);
                  i ||
                    ((i = new this.constructor(
                      t.currentTarget,
                      this._getDelegateConfig()
                    )),
                    e(t.currentTarget).data(n, i)),
                    (i._activeTrigger.click = !i._activeTrigger.click),
                    i._isWithActiveTrigger()
                      ? i._enter(null, i)
                      : i._leave(null, i);
                } else {
                  if (e(this.getTipElement()).hasClass(f.SHOW))
                    return void this._leave(null, this);
                  this._enter(null, this);
                }
            }),
            (m.dispose = function () {
              clearTimeout(this._timeout),
                e.removeData(this.element, this.constructor.DATA_KEY),
                e(this.element).off(this.constructor.EVENT_KEY),
                e(this.element).closest(".modal").off("hide.bs.modal"),
                this.tip && e(this.tip).remove(),
                (this._isEnabled = null),
                (this._timeout = null),
                (this._hoverState = null),
                (this._activeTrigger = null),
                null !== this._popper && this._popper.destroy(),
                (this._popper = null),
                (this.element = null),
                (this.config = null),
                (this.tip = null);
            }),
            (m.show = function () {
              var t = this;
              if ("none" === e(this.element).css("display"))
                throw new Error("Please use show on visible elements");
              var i = e.Event(this.constructor.Event.SHOW);
              if (this.isWithContent() && this._isEnabled) {
                e(this.element).trigger(i);
                var r = e.contains(
                  this.element.ownerDocument.documentElement,
                  this.element
                );
                if (i.isDefaultPrevented() || !r) return;
                var a = this.getTipElement(),
                  l = s.getUID(this.constructor.NAME);
                a.setAttribute("id", l),
                  this.element.setAttribute("aria-describedby", l),
                  this.setContent(),
                  this.config.animation && e(a).addClass(f.FADE);
                var h =
                    "function" == typeof this.config.placement
                      ? this.config.placement.call(this, a, this.element)
                      : this.config.placement,
                  c = this._getAttachment(h);
                this.addAttachmentClass(c);
                var d =
                  !1 === this.config.container
                    ? document.body
                    : e(this.config.container);
                e(a).data(this.constructor.DATA_KEY, this),
                  e.contains(
                    this.element.ownerDocument.documentElement,
                    this.tip
                  ) || e(a).appendTo(d),
                  e(this.element).trigger(this.constructor.Event.INSERTED),
                  (this._popper = new n(this.element, a, {
                    placement: c,
                    modifiers: {
                      offset: { offset: this.config.offset },
                      flip: { behavior: this.config.fallbackPlacement },
                      arrow: { element: _.ARROW },
                    },
                    onCreate: function (e) {
                      e.originalPlacement !== e.placement &&
                        t._handlePopperPlacementChange(e);
                    },
                    onUpdate: function (e) {
                      t._handlePopperPlacementChange(e);
                    },
                  })),
                  e(a).addClass(f.SHOW),
                  "ontouchstart" in document.documentElement &&
                    e("body").children().on("mouseover", null, e.noop);
                var g = function () {
                  t.config.animation && t._fixTransition();
                  var n = t._hoverState;
                  (t._hoverState = null),
                    e(t.element).trigger(t.constructor.Event.SHOWN),
                    n === u.OUT && t._leave(null, t);
                };
                s.supportsTransitionEnd() && e(this.tip).hasClass(f.FADE)
                  ? e(this.tip)
                      .one(s.TRANSITION_END, g)
                      .emulateTransitionEnd(o._TRANSITION_DURATION)
                  : g();
              }
            }),
            (m.hide = function (t) {
              var n = this,
                i = this.getTipElement(),
                r = e.Event(this.constructor.Event.HIDE),
                o = function () {
                  n._hoverState !== u.SHOW &&
                    i.parentNode &&
                    i.parentNode.removeChild(i),
                    n._cleanTipClass(),
                    n.element.removeAttribute("aria-describedby"),
                    e(n.element).trigger(n.constructor.Event.HIDDEN),
                    null !== n._popper && n._popper.destroy(),
                    t && t();
                };
              e(this.element).trigger(r),
                r.isDefaultPrevented() ||
                  (e(i).removeClass(f.SHOW),
                  "ontouchstart" in document.documentElement &&
                    e("body").children().off("mouseover", null, e.noop),
                  (this._activeTrigger[g.CLICK] = !1),
                  (this._activeTrigger[g.FOCUS] = !1),
                  (this._activeTrigger[g.HOVER] = !1),
                  s.supportsTransitionEnd() && e(this.tip).hasClass(f.FADE)
                    ? e(i).one(s.TRANSITION_END, o).emulateTransitionEnd(150)
                    : o(),
                  (this._hoverState = ""));
            }),
            (m.update = function () {
              null !== this._popper && this._popper.scheduleUpdate();
            }),
            (m.isWithContent = function () {
              return Boolean(this.getTitle());
            }),
            (m.addAttachmentClass = function (t) {
              e(this.getTipElement()).addClass("bs-tooltip-" + t);
            }),
            (m.getTipElement = function () {
              return (
                (this.tip = this.tip || e(this.config.template)[0]), this.tip
              );
            }),
            (m.setContent = function () {
              var t = e(this.getTipElement());
              this.setElementContent(t.find(_.TOOLTIP_INNER), this.getTitle()),
                t.removeClass(f.FADE + " " + f.SHOW);
            }),
            (m.setElementContent = function (t, n) {
              var i = this.config.html;
              "object" == typeof n && (n.nodeType || n.jquery)
                ? i
                  ? e(n).parent().is(t) || t.empty().append(n)
                  : t.text(e(n).text())
                : t[i ? "html" : "text"](n);
            }),
            (m.getTitle = function () {
              var t = this.element.getAttribute("data-original-title");
              return (
                t ||
                  (t =
                    "function" == typeof this.config.title
                      ? this.config.title.call(this.element)
                      : this.config.title),
                t
              );
            }),
            (m._getAttachment = function (t) {
              return h[t.toUpperCase()];
            }),
            (m._setListeners = function () {
              var t = this;
              this.config.trigger.split(" ").forEach(function (n) {
                if ("click" === n)
                  e(t.element).on(
                    t.constructor.Event.CLICK,
                    t.config.selector,
                    function (e) {
                      return t.toggle(e);
                    }
                  );
                else if (n !== g.MANUAL) {
                  var i =
                      n === g.HOVER
                        ? t.constructor.Event.MOUSEENTER
                        : t.constructor.Event.FOCUSIN,
                    s =
                      n === g.HOVER
                        ? t.constructor.Event.MOUSELEAVE
                        : t.constructor.Event.FOCUSOUT;
                  e(t.element)
                    .on(i, t.config.selector, function (e) {
                      return t._enter(e);
                    })
                    .on(s, t.config.selector, function (e) {
                      return t._leave(e);
                    });
                }
                e(t.element)
                  .closest(".modal")
                  .on("hide.bs.modal", function () {
                    return t.hide();
                  });
              }),
                this.config.selector
                  ? (this.config = e.extend({}, this.config, {
                      trigger: "manual",
                      selector: "",
                    }))
                  : this._fixTitle();
            }),
            (m._fixTitle = function () {
              var t = typeof this.element.getAttribute("data-original-title");
              (this.element.getAttribute("title") || "string" !== t) &&
                (this.element.setAttribute(
                  "data-original-title",
                  this.element.getAttribute("title") || ""
                ),
                this.element.setAttribute("title", ""));
            }),
            (m._enter = function (t, n) {
              var i = this.constructor.DATA_KEY;
              (n = n || e(t.currentTarget).data(i)) ||
                ((n = new this.constructor(
                  t.currentTarget,
                  this._getDelegateConfig()
                )),
                e(t.currentTarget).data(i, n)),
                t &&
                  (n._activeTrigger["focusin" === t.type ? g.FOCUS : g.HOVER] =
                    !0),
                e(n.getTipElement()).hasClass(f.SHOW) ||
                n._hoverState === u.SHOW
                  ? (n._hoverState = u.SHOW)
                  : (clearTimeout(n._timeout),
                    (n._hoverState = u.SHOW),
                    n.config.delay && n.config.delay.show
                      ? (n._timeout = setTimeout(function () {
                          n._hoverState === u.SHOW && n.show();
                        }, n.config.delay.show))
                      : n.show());
            }),
            (m._leave = function (t, n) {
              var i = this.constructor.DATA_KEY;
              (n = n || e(t.currentTarget).data(i)) ||
                ((n = new this.constructor(
                  t.currentTarget,
                  this._getDelegateConfig()
                )),
                e(t.currentTarget).data(i, n)),
                t &&
                  (n._activeTrigger["focusout" === t.type ? g.FOCUS : g.HOVER] =
                    !1),
                n._isWithActiveTrigger() ||
                  (clearTimeout(n._timeout),
                  (n._hoverState = u.OUT),
                  n.config.delay && n.config.delay.hide
                    ? (n._timeout = setTimeout(function () {
                        n._hoverState === u.OUT && n.hide();
                      }, n.config.delay.hide))
                    : n.hide());
            }),
            (m._isWithActiveTrigger = function () {
              for (var t in this._activeTrigger)
                if (this._activeTrigger[t]) return !0;
              return !1;
            }),
            (m._getConfig = function (n) {
              return (
                "number" ==
                  typeof (n = e.extend(
                    {},
                    this.constructor.Default,
                    e(this.element).data(),
                    n
                  )).delay && (n.delay = { show: n.delay, hide: n.delay }),
                "number" == typeof n.title && (n.title = n.title.toString()),
                "number" == typeof n.content &&
                  (n.content = n.content.toString()),
                s.typeCheckConfig(t, n, this.constructor.DefaultType),
                n
              );
            }),
            (m._getDelegateConfig = function () {
              var t = {};
              if (this.config)
                for (var e in this.config)
                  this.constructor.Default[e] !== this.config[e] &&
                    (t[e] = this.config[e]);
              return t;
            }),
            (m._cleanTipClass = function () {
              var t = e(this.getTipElement()),
                n = t.attr("class").match(a);
              null !== n && n.length > 0 && t.removeClass(n.join(""));
            }),
            (m._handlePopperPlacementChange = function (t) {
              this._cleanTipClass(),
                this.addAttachmentClass(this._getAttachment(t.placement));
            }),
            (m._fixTransition = function () {
              var t = this.getTipElement(),
                n = this.config.animation;
              null === t.getAttribute("x-placement") &&
                (e(t).removeClass(f.FADE),
                (this.config.animation = !1),
                this.hide(),
                this.show(),
                (this.config.animation = n));
            }),
            (o._jQueryInterface = function (t) {
              return this.each(function () {
                var n = e(this).data("bs.tooltip"),
                  i = "object" == typeof t && t;
                if (
                  (n || !/dispose|hide/.test(t)) &&
                  (n || ((n = new o(this, i)), e(this).data("bs.tooltip", n)),
                  "string" == typeof t)
                ) {
                  if ("undefined" == typeof n[t])
                    throw new Error('No method named "' + t + '"');
                  n[t]();
                }
              });
            }),
            r(o, null, [
              {
                key: "VERSION",
                get: function () {
                  return "4.0.0-beta.2";
                },
              },
              {
                key: "Default",
                get: function () {
                  return c;
                },
              },
              {
                key: "NAME",
                get: function () {
                  return t;
                },
              },
              {
                key: "DATA_KEY",
                get: function () {
                  return "bs.tooltip";
                },
              },
              {
                key: "Event",
                get: function () {
                  return d;
                },
              },
              {
                key: "EVENT_KEY",
                get: function () {
                  return i;
                },
              },
              {
                key: "DefaultType",
                get: function () {
                  return l;
                },
              },
            ]),
            o
          );
        })();
      return (
        (e.fn[t] = m._jQueryInterface),
        (e.fn[t].Constructor = m),
        (e.fn[t].noConflict = function () {
          return (e.fn[t] = o), m._jQueryInterface;
        }),
        m
      );
    })(),
    _ = (function () {
      var t = "popover",
        n = ".bs.popover",
        i = e.fn[t],
        s = new RegExp("(^|\\s)bs-popover\\S+", "g"),
        a = e.extend({}, f.Default, {
          placement: "right",
          trigger: "click",
          content: "",
          template:
            '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>',
        }),
        l = e.extend({}, f.DefaultType, {
          content: "(string|element|function)",
        }),
        h = { FADE: "fade", SHOW: "show" },
        c = { TITLE: ".popover-header", CONTENT: ".popover-body" },
        u = {
          HIDE: "hide" + n,
          HIDDEN: "hidden" + n,
          SHOW: "show" + n,
          SHOWN: "shown" + n,
          INSERTED: "inserted" + n,
          CLICK: "click" + n,
          FOCUSIN: "focusin" + n,
          FOCUSOUT: "focusout" + n,
          MOUSEENTER: "mouseenter" + n,
          MOUSELEAVE: "mouseleave" + n,
        },
        d = (function (i) {
          function d() {
            return i.apply(this, arguments) || this;
          }
          o(d, i);
          var f = d.prototype;
          return (
            (f.isWithContent = function () {
              return this.getTitle() || this._getContent();
            }),
            (f.addAttachmentClass = function (t) {
              e(this.getTipElement()).addClass("bs-popover-" + t);
            }),
            (f.getTipElement = function () {
              return (
                (this.tip = this.tip || e(this.config.template)[0]), this.tip
              );
            }),
            (f.setContent = function () {
              var t = e(this.getTipElement());
              this.setElementContent(t.find(c.TITLE), this.getTitle()),
                this.setElementContent(t.find(c.CONTENT), this._getContent()),
                t.removeClass(h.FADE + " " + h.SHOW);
            }),
            (f._getContent = function () {
              return (
                this.element.getAttribute("data-content") ||
                ("function" == typeof this.config.content
                  ? this.config.content.call(this.element)
                  : this.config.content)
              );
            }),
            (f._cleanTipClass = function () {
              var t = e(this.getTipElement()),
                n = t.attr("class").match(s);
              null !== n && n.length > 0 && t.removeClass(n.join(""));
            }),
            (d._jQueryInterface = function (t) {
              return this.each(function () {
                var n = e(this).data("bs.popover"),
                  i = "object" == typeof t ? t : null;
                if (
                  (n || !/destroy|hide/.test(t)) &&
                  (n || ((n = new d(this, i)), e(this).data("bs.popover", n)),
                  "string" == typeof t)
                ) {
                  if ("undefined" == typeof n[t])
                    throw new Error('No method named "' + t + '"');
                  n[t]();
                }
              });
            }),
            r(d, null, [
              {
                key: "VERSION",
                get: function () {
                  return "4.0.0-beta.2";
                },
              },
              {
                key: "Default",
                get: function () {
                  return a;
                },
              },
              {
                key: "NAME",
                get: function () {
                  return t;
                },
              },
              {
                key: "DATA_KEY",
                get: function () {
                  return "bs.popover";
                },
              },
              {
                key: "Event",
                get: function () {
                  return u;
                },
              },
              {
                key: "EVENT_KEY",
                get: function () {
                  return n;
                },
              },
              {
                key: "DefaultType",
                get: function () {
                  return l;
                },
              },
            ]),
            d
          );
        })(f);
      return (
        (e.fn[t] = d._jQueryInterface),
        (e.fn[t].Constructor = d),
        (e.fn[t].noConflict = function () {
          return (e.fn[t] = i), d._jQueryInterface;
        }),
        d
      );
    })(),
    g = (function () {
      var t = "scrollspy",
        n = e.fn[t],
        i = { offset: 10, method: "auto", target: "" },
        o = { offset: "number", method: "string", target: "(string|element)" },
        a = {
          ACTIVATE: "activate.bs.scrollspy",
          SCROLL: "scroll.bs.scrollspy",
          LOAD_DATA_API: "load.bs.scrollspy.data-api",
        },
        l = {
          DROPDOWN_ITEM: "dropdown-item",
          DROPDOWN_MENU: "dropdown-menu",
          ACTIVE: "active",
        },
        h = {
          DATA_SPY: '[data-spy="scroll"]',
          ACTIVE: ".active",
          NAV_LIST_GROUP: ".nav, .list-group",
          NAV_LINKS: ".nav-link",
          NAV_ITEMS: ".nav-item",
          LIST_ITEMS: ".list-group-item",
          DROPDOWN: ".dropdown",
          DROPDOWN_ITEMS: ".dropdown-item",
          DROPDOWN_TOGGLE: ".dropdown-toggle",
        },
        c = { OFFSET: "offset", POSITION: "position" },
        u = (function () {
          function n(t, n) {
            var i = this;
            (this._element = t),
              (this._scrollElement = "BODY" === t.tagName ? window : t),
              (this._config = this._getConfig(n)),
              (this._selector =
                this._config.target +
                " " +
                h.NAV_LINKS +
                "," +
                this._config.target +
                " " +
                h.LIST_ITEMS +
                "," +
                this._config.target +
                " " +
                h.DROPDOWN_ITEMS),
              (this._offsets = []),
              (this._targets = []),
              (this._activeTarget = null),
              (this._scrollHeight = 0),
              e(this._scrollElement).on(a.SCROLL, function (t) {
                return i._process(t);
              }),
              this.refresh(),
              this._process();
          }
          var u = n.prototype;
          return (
            (u.refresh = function () {
              var t = this,
                n =
                  this._scrollElement !== this._scrollElement.window
                    ? c.POSITION
                    : c.OFFSET,
                i = "auto" === this._config.method ? n : this._config.method,
                r = i === c.POSITION ? this._getScrollTop() : 0;
              (this._offsets = []),
                (this._targets = []),
                (this._scrollHeight = this._getScrollHeight()),
                e
                  .makeArray(e(this._selector))
                  .map(function (t) {
                    var n,
                      o = s.getSelectorFromElement(t);
                    if ((o && (n = e(o)[0]), n)) {
                      var a = n.getBoundingClientRect();
                      if (a.width || a.height) return [e(n)[i]().top + r, o];
                    }
                    return null;
                  })
                  .filter(function (t) {
                    return t;
                  })
                  .sort(function (t, e) {
                    return t[0] - e[0];
                  })
                  .forEach(function (e) {
                    t._offsets.push(e[0]), t._targets.push(e[1]);
                  });
            }),
            (u.dispose = function () {
              e.removeData(this._element, "bs.scrollspy"),
                e(this._scrollElement).off(".bs.scrollspy"),
                (this._element = null),
                (this._scrollElement = null),
                (this._config = null),
                (this._selector = null),
                (this._offsets = null),
                (this._targets = null),
                (this._activeTarget = null),
                (this._scrollHeight = null);
            }),
            (u._getConfig = function (n) {
              if ("string" != typeof (n = e.extend({}, i, n)).target) {
                var r = e(n.target).attr("id");
                r || ((r = s.getUID(t)), e(n.target).attr("id", r)),
                  (n.target = "#" + r);
              }
              return s.typeCheckConfig(t, n, o), n;
            }),
            (u._getScrollTop = function () {
              return this._scrollElement === window
                ? this._scrollElement.pageYOffset
                : this._scrollElement.scrollTop;
            }),
            (u._getScrollHeight = function () {
              return (
                this._scrollElement.scrollHeight ||
                Math.max(
                  document.body.scrollHeight,
                  document.documentElement.scrollHeight
                )
              );
            }),
            (u._getOffsetHeight = function () {
              return this._scrollElement === window
                ? window.innerHeight
                : this._scrollElement.getBoundingClientRect().height;
            }),
            (u._process = function () {
              var t = this._getScrollTop() + this._config.offset,
                e = this._getScrollHeight(),
                n = this._config.offset + e - this._getOffsetHeight();
              if ((this._scrollHeight !== e && this.refresh(), t >= n)) {
                var i = this._targets[this._targets.length - 1];
                this._activeTarget !== i && this._activate(i);
              } else {
                if (
                  this._activeTarget &&
                  t < this._offsets[0] &&
                  this._offsets[0] > 0
                )
                  return (this._activeTarget = null), void this._clear();
                for (var s = this._offsets.length; s--; )
                  this._activeTarget !== this._targets[s] &&
                    t >= this._offsets[s] &&
                    ("undefined" == typeof this._offsets[s + 1] ||
                      t < this._offsets[s + 1]) &&
                    this._activate(this._targets[s]);
              }
            }),
            (u._activate = function (t) {
              (this._activeTarget = t), this._clear();
              var n = this._selector.split(",");
              n = n.map(function (e) {
                return (
                  e + '[data-target="' + t + '"],' + e + '[href="' + t + '"]'
                );
              });
              var i = e(n.join(","));
              i.hasClass(l.DROPDOWN_ITEM)
                ? (i
                    .closest(h.DROPDOWN)
                    .find(h.DROPDOWN_TOGGLE)
                    .addClass(l.ACTIVE),
                  i.addClass(l.ACTIVE))
                : (i.addClass(l.ACTIVE),
                  i
                    .parents(h.NAV_LIST_GROUP)
                    .prev(h.NAV_LINKS + ", " + h.LIST_ITEMS)
                    .addClass(l.ACTIVE),
                  i
                    .parents(h.NAV_LIST_GROUP)
                    .prev(h.NAV_ITEMS)
                    .children(h.NAV_LINKS)
                    .addClass(l.ACTIVE)),
                e(this._scrollElement).trigger(a.ACTIVATE, {
                  relatedTarget: t,
                });
            }),
            (u._clear = function () {
              e(this._selector).filter(h.ACTIVE).removeClass(l.ACTIVE);
            }),
            (n._jQueryInterface = function (t) {
              return this.each(function () {
                var i = e(this).data("bs.scrollspy"),
                  s = "object" == typeof t && t;
                if (
                  (i || ((i = new n(this, s)), e(this).data("bs.scrollspy", i)),
                  "string" == typeof t)
                ) {
                  if ("undefined" == typeof i[t])
                    throw new Error('No method named "' + t + '"');
                  i[t]();
                }
              });
            }),
            r(n, null, [
              {
                key: "VERSION",
                get: function () {
                  return "4.0.0-beta.2";
                },
              },
              {
                key: "Default",
                get: function () {
                  return i;
                },
              },
            ]),
            n
          );
        })();
      return (
        e(window).on(a.LOAD_DATA_API, function () {
          for (var t = e.makeArray(e(h.DATA_SPY)), n = t.length; n--; ) {
            var i = e(t[n]);
            u._jQueryInterface.call(i, i.data());
          }
        }),
        (e.fn[t] = u._jQueryInterface),
        (e.fn[t].Constructor = u),
        (e.fn[t].noConflict = function () {
          return (e.fn[t] = n), u._jQueryInterface;
        }),
        u
      );
    })(),
    m = (function () {
      var t = e.fn.tab,
        n = {
          HIDE: "hide.bs.tab",
          HIDDEN: "hidden.bs.tab",
          SHOW: "show.bs.tab",
          SHOWN: "shown.bs.tab",
          CLICK_DATA_API: "click.bs.tab.data-api",
        },
        i = {
          DROPDOWN_MENU: "dropdown-menu",
          ACTIVE: "active",
          DISABLED: "disabled",
          FADE: "fade",
          SHOW: "show",
        },
        o = {
          DROPDOWN: ".dropdown",
          NAV_LIST_GROUP: ".nav, .list-group",
          ACTIVE: ".active",
          ACTIVE_UL: "> li > .active",
          DATA_TOGGLE:
            '[data-toggle="tab"], [data-toggle="pill"], [data-toggle="list"]',
          DROPDOWN_TOGGLE: ".dropdown-toggle",
          DROPDOWN_ACTIVE_CHILD: "> .dropdown-menu .active",
        },
        a = (function () {
          function t(t) {
            this._element = t;
          }
          var a = t.prototype;
          return (
            (a.show = function () {
              var t = this;
              if (
                !(
                  (this._element.parentNode &&
                    this._element.parentNode.nodeType === Node.ELEMENT_NODE &&
                    e(this._element).hasClass(i.ACTIVE)) ||
                  e(this._element).hasClass(i.DISABLED)
                )
              ) {
                var r,
                  a,
                  l = e(this._element).closest(o.NAV_LIST_GROUP)[0],
                  h = s.getSelectorFromElement(this._element);
                if (l) {
                  var c = "UL" === l.nodeName ? o.ACTIVE_UL : o.ACTIVE;
                  (a = e.makeArray(e(l).find(c))), (a = a[a.length - 1]);
                }
                var u = e.Event(n.HIDE, { relatedTarget: this._element }),
                  d = e.Event(n.SHOW, { relatedTarget: a });
                if (
                  (a && e(a).trigger(u),
                  e(this._element).trigger(d),
                  !d.isDefaultPrevented() && !u.isDefaultPrevented())
                ) {
                  h && (r = e(h)[0]), this._activate(this._element, l);
                  var f = function () {
                    var i = e.Event(n.HIDDEN, { relatedTarget: t._element }),
                      s = e.Event(n.SHOWN, { relatedTarget: a });
                    e(a).trigger(i), e(t._element).trigger(s);
                  };
                  r ? this._activate(r, r.parentNode, f) : f();
                }
              }
            }),
            (a.dispose = function () {
              e.removeData(this._element, "bs.tab"), (this._element = null);
            }),
            (a._activate = function (t, n, r) {
              var a,
                l = this,
                h = (a =
                  "UL" === n.nodeName
                    ? e(n).find(o.ACTIVE_UL)
                    : e(n).children(o.ACTIVE))[0],
                c =
                  r && s.supportsTransitionEnd() && h && e(h).hasClass(i.FADE),
                u = function () {
                  return l._transitionComplete(t, h, c, r);
                };
              h && c
                ? e(h).one(s.TRANSITION_END, u).emulateTransitionEnd(150)
                : u(),
                h && e(h).removeClass(i.SHOW);
            }),
            (a._transitionComplete = function (t, n, r, a) {
              if (n) {
                e(n).removeClass(i.ACTIVE);
                var l = e(n.parentNode).find(o.DROPDOWN_ACTIVE_CHILD)[0];
                l && e(l).removeClass(i.ACTIVE),
                  "tab" === n.getAttribute("role") &&
                    n.setAttribute("aria-selected", !1);
              }
              if (
                (e(t).addClass(i.ACTIVE),
                "tab" === t.getAttribute("role") &&
                  t.setAttribute("aria-selected", !0),
                r
                  ? (s.reflow(t), e(t).addClass(i.SHOW))
                  : e(t).removeClass(i.FADE),
                t.parentNode && e(t.parentNode).hasClass(i.DROPDOWN_MENU))
              ) {
                var h = e(t).closest(o.DROPDOWN)[0];
                h && e(h).find(o.DROPDOWN_TOGGLE).addClass(i.ACTIVE),
                  t.setAttribute("aria-expanded", !0);
              }
              a && a();
            }),
            (t._jQueryInterface = function (n) {
              return this.each(function () {
                var i = e(this),
                  s = i.data("bs.tab");
                if (
                  (s || ((s = new t(this)), i.data("bs.tab", s)),
                  "string" == typeof n)
                ) {
                  if ("undefined" == typeof s[n])
                    throw new Error('No method named "' + n + '"');
                  s[n]();
                }
              });
            }),
            r(t, null, [
              {
                key: "VERSION",
                get: function () {
                  return "4.0.0-beta.2";
                },
              },
            ]),
            t
          );
        })();
      return (
        e(document).on(n.CLICK_DATA_API, o.DATA_TOGGLE, function (t) {
          t.preventDefault(), a._jQueryInterface.call(e(this), "show");
        }),
        (e.fn.tab = a._jQueryInterface),
        (e.fn.tab.Constructor = a),
        (e.fn.tab.noConflict = function () {
          return (e.fn.tab = t), a._jQueryInterface;
        }),
        a
      );
    })();
  return (
    (function () {
      if ("undefined" == typeof e)
        throw new Error(
          "Bootstrap's JavaScript requires jQuery. jQuery must be included before Bootstrap's JavaScript."
        );
      var t = e.fn.jquery.split(" ")[0].split(".");
      if (
        (t[0] < 2 && t[1] < 9) ||
        (1 === t[0] && 9 === t[1] && t[2] < 1) ||
        t[0] >= 4
      )
        throw new Error(
          "Bootstrap's JavaScript requires at least jQuery v1.9.1 but less than v4.0.0"
        );
    })(),
    (t.Util = s),
    (t.Alert = a),
    (t.Button = l),
    (t.Carousel = h),
    (t.Collapse = c),
    (t.Dropdown = u),
    (t.Modal = d),
    (t.Popover = _),
    (t.Scrollspy = g),
    (t.Tab = m),
    (t.Tooltip = f),
    t
  );
})({}, $, Popper);

/* perfect-scrollbar v0.6.11 */
!(function t(e, n, r) {
  function o(l, a) {
    if (!n[l]) {
      if (!e[l]) {
        var s = "function" == typeof require && require;
        if (!a && s) return s(l, !0);
        if (i) return i(l, !0);
        var c = new Error("Cannot find module '" + l + "'");
        throw ((c.code = "MODULE_NOT_FOUND"), c);
      }
      var u = (n[l] = { exports: {} });
      e[l][0].call(
        u.exports,
        function (t) {
          var n = e[l][1][t];
          return o(n ? n : t);
        },
        u,
        u.exports,
        t,
        e,
        n,
        r
      );
    }
    return n[l].exports;
  }
  for (
    var i = "function" == typeof require && require, l = 0;
    l < r.length;
    l++
  )
    o(r[l]);
  return o;
})(
  {
    1: [
      function (t, e, n) {
        "use strict";
        function r(t) {
          t.fn.perfectScrollbar = function (t) {
            return this.each(function () {
              if ("object" == typeof t || "undefined" == typeof t) {
                var e = t;
                i.get(this) || o.initialize(this, e);
              } else {
                var n = t;
                "update" === n
                  ? o.update(this)
                  : "destroy" === n && o.destroy(this);
              }
            });
          };
        }
        var o = t("../main"),
          i = t("../plugin/instances");
        if ("function" == typeof define && define.amd) define(["jquery"], r);
        else {
          var l = window.jQuery ? window.jQuery : window.$;
          "undefined" != typeof l && r(l);
        }
        e.exports = r;
      },
      { "../main": 7, "../plugin/instances": 18 },
    ],
    2: [
      function (t, e, n) {
        "use strict";
        function r(t, e) {
          var n = t.className.split(" ");
          n.indexOf(e) < 0 && n.push(e), (t.className = n.join(" "));
        }
        function o(t, e) {
          var n = t.className.split(" "),
            r = n.indexOf(e);
          r >= 0 && n.splice(r, 1), (t.className = n.join(" "));
        }
        (n.add = function (t, e) {
          t.classList ? t.classList.add(e) : r(t, e);
        }),
          (n.remove = function (t, e) {
            t.classList ? t.classList.remove(e) : o(t, e);
          }),
          (n.list = function (t) {
            return t.classList
              ? Array.prototype.slice.apply(t.classList)
              : t.className.split(" ");
          });
      },
      {},
    ],
    3: [
      function (t, e, n) {
        "use strict";
        function r(t, e) {
          return window.getComputedStyle(t)[e];
        }
        function o(t, e, n) {
          return (
            "number" == typeof n && (n = n.toString() + "px"),
            (t.style[e] = n),
            t
          );
        }
        function i(t, e) {
          for (var n in e) {
            var r = e[n];
            "number" == typeof r && (r = r.toString() + "px"), (t.style[n] = r);
          }
          return t;
        }
        var l = {};
        (l.e = function (t, e) {
          var n = document.createElement(t);
          return (n.className = e), n;
        }),
          (l.appendTo = function (t, e) {
            return e.appendChild(t), t;
          }),
          (l.css = function (t, e, n) {
            return "object" == typeof e
              ? i(t, e)
              : "undefined" == typeof n
              ? r(t, e)
              : o(t, e, n);
          }),
          (l.matches = function (t, e) {
            return "undefined" != typeof t.matches
              ? t.matches(e)
              : "undefined" != typeof t.matchesSelector
              ? t.matchesSelector(e)
              : "undefined" != typeof t.webkitMatchesSelector
              ? t.webkitMatchesSelector(e)
              : "undefined" != typeof t.mozMatchesSelector
              ? t.mozMatchesSelector(e)
              : "undefined" != typeof t.msMatchesSelector
              ? t.msMatchesSelector(e)
              : void 0;
          }),
          (l.remove = function (t) {
            "undefined" != typeof t.remove
              ? t.remove()
              : t.parentNode && t.parentNode.removeChild(t);
          }),
          (l.queryChildren = function (t, e) {
            return Array.prototype.filter.call(t.childNodes, function (t) {
              return l.matches(t, e);
            });
          }),
          (e.exports = l);
      },
      {},
    ],
    4: [
      function (t, e, n) {
        "use strict";
        var r = function (t) {
          (this.element = t), (this.events = {});
        };
        (r.prototype.bind = function (t, e) {
          "undefined" == typeof this.events[t] && (this.events[t] = []),
            this.events[t].push(e),
            this.element.addEventListener(t, e, !1);
        }),
          (r.prototype.unbind = function (t, e) {
            var n = "undefined" != typeof e;
            this.events[t] = this.events[t].filter(function (r) {
              return n && r !== e
                ? !0
                : (this.element.removeEventListener(t, r, !1), !1);
            }, this);
          }),
          (r.prototype.unbindAll = function () {
            for (var t in this.events) this.unbind(t);
          });
        var o = function () {
          this.eventElements = [];
        };
        (o.prototype.eventElement = function (t) {
          var e = this.eventElements.filter(function (e) {
            return e.element === t;
          })[0];
          return (
            "undefined" == typeof e &&
              ((e = new r(t)), this.eventElements.push(e)),
            e
          );
        }),
          (o.prototype.bind = function (t, e, n) {
            this.eventElement(t).bind(e, n);
          }),
          (o.prototype.unbind = function (t, e, n) {
            this.eventElement(t).unbind(e, n);
          }),
          (o.prototype.unbindAll = function () {
            for (var t = 0; t < this.eventElements.length; t++)
              this.eventElements[t].unbindAll();
          }),
          (o.prototype.once = function (t, e, n) {
            var r = this.eventElement(t),
              o = function (t) {
                r.unbind(e, o), n(t);
              };
            r.bind(e, o);
          }),
          (e.exports = o);
      },
      {},
    ],
    5: [
      function (t, e, n) {
        "use strict";
        e.exports = (function () {
          function t() {
            return Math.floor(65536 * (1 + Math.random()))
              .toString(16)
              .substring(1);
          }
          return function () {
            return (
              t() +
              t() +
              "-" +
              t() +
              "-" +
              t() +
              "-" +
              t() +
              "-" +
              t() +
              t() +
              t()
            );
          };
        })();
      },
      {},
    ],
    6: [
      function (t, e, n) {
        "use strict";
        var r = t("./class"),
          o = t("./dom"),
          i = (n.toInt = function (t) {
            return parseInt(t, 10) || 0;
          }),
          l = (n.clone = function (t) {
            if (null === t) return null;
            if (t.constructor === Array) return t.map(l);
            if ("object" == typeof t) {
              var e = {};
              for (var n in t) e[n] = l(t[n]);
              return e;
            }
            return t;
          });
        (n.extend = function (t, e) {
          var n = l(t);
          for (var r in e) n[r] = l(e[r]);
          return n;
        }),
          (n.isEditable = function (t) {
            return (
              o.matches(t, "input,[contenteditable]") ||
              o.matches(t, "select,[contenteditable]") ||
              o.matches(t, "textarea,[contenteditable]") ||
              o.matches(t, "button,[contenteditable]")
            );
          }),
          (n.removePsClasses = function (t) {
            for (var e = r.list(t), n = 0; n < e.length; n++) {
              var o = e[n];
              0 === o.indexOf("ps-") && r.remove(t, o);
            }
          }),
          (n.outerWidth = function (t) {
            return (
              i(o.css(t, "width")) +
              i(o.css(t, "paddingLeft")) +
              i(o.css(t, "paddingRight")) +
              i(o.css(t, "borderLeftWidth")) +
              i(o.css(t, "borderRightWidth"))
            );
          }),
          (n.startScrolling = function (t, e) {
            r.add(t, "ps-in-scrolling"),
              "undefined" != typeof e
                ? r.add(t, "ps-" + e)
                : (r.add(t, "ps-x"), r.add(t, "ps-y"));
          }),
          (n.stopScrolling = function (t, e) {
            r.remove(t, "ps-in-scrolling"),
              "undefined" != typeof e
                ? r.remove(t, "ps-" + e)
                : (r.remove(t, "ps-x"), r.remove(t, "ps-y"));
          }),
          (n.env = {
            isWebKit: "WebkitAppearance" in document.documentElement.style,
            supportsTouch:
              "ontouchstart" in window ||
              (window.DocumentTouch &&
                document instanceof window.DocumentTouch),
            supportsIePointer: null !== window.navigator.msMaxTouchPoints,
          });
      },
      { "./class": 2, "./dom": 3 },
    ],
    7: [
      function (t, e, n) {
        "use strict";
        var r = t("./plugin/destroy"),
          o = t("./plugin/initialize"),
          i = t("./plugin/update");
        e.exports = { initialize: o, update: i, destroy: r };
      },
      {
        "./plugin/destroy": 9,
        "./plugin/initialize": 17,
        "./plugin/update": 21,
      },
    ],
    8: [
      function (t, e, n) {
        "use strict";
        e.exports = {
          handlers: [
            "click-rail",
            "drag-scrollbar",
            "keyboard",
            "wheel",
            "touch",
          ],
          maxScrollbarLength: null,
          minScrollbarLength: null,
          scrollXMarginOffset: 0,
          scrollYMarginOffset: 0,
          stopPropagationOnClick: !0,
          suppressScrollX: !1,
          suppressScrollY: !1,
          swipePropagation: !0,
          useBothWheelAxes: !1,
          wheelPropagation: !1,
          wheelSpeed: 1,
          theme: "default",
        };
      },
      {},
    ],
    9: [
      function (t, e, n) {
        "use strict";
        var r = t("../lib/helper"),
          o = t("../lib/dom"),
          i = t("./instances");
        e.exports = function (t) {
          var e = i.get(t);
          e &&
            (e.event.unbindAll(),
            o.remove(e.scrollbarX),
            o.remove(e.scrollbarY),
            o.remove(e.scrollbarXRail),
            o.remove(e.scrollbarYRail),
            r.removePsClasses(t),
            i.remove(t));
        };
      },
      { "../lib/dom": 3, "../lib/helper": 6, "./instances": 18 },
    ],
    10: [
      function (t, e, n) {
        "use strict";
        function r(t, e) {
          function n(t) {
            return t.getBoundingClientRect();
          }
          var r = function (t) {
            t.stopPropagation();
          };
          e.settings.stopPropagationOnClick &&
            e.event.bind(e.scrollbarY, "click", r),
            e.event.bind(e.scrollbarYRail, "click", function (r) {
              var i = o.toInt(e.scrollbarYHeight / 2),
                s =
                  e.railYRatio *
                  (r.pageY - window.pageYOffset - n(e.scrollbarYRail).top - i),
                c = e.railYRatio * (e.railYHeight - e.scrollbarYHeight),
                u = s / c;
              0 > u ? (u = 0) : u > 1 && (u = 1),
                a(t, "top", (e.contentHeight - e.containerHeight) * u),
                l(t),
                r.stopPropagation();
            }),
            e.settings.stopPropagationOnClick &&
              e.event.bind(e.scrollbarX, "click", r),
            e.event.bind(e.scrollbarXRail, "click", function (r) {
              var i = o.toInt(e.scrollbarXWidth / 2),
                s =
                  e.railXRatio *
                  (r.pageX - window.pageXOffset - n(e.scrollbarXRail).left - i),
                c = e.railXRatio * (e.railXWidth - e.scrollbarXWidth),
                u = s / c;
              0 > u ? (u = 0) : u > 1 && (u = 1),
                a(
                  t,
                  "left",
                  (e.contentWidth - e.containerWidth) * u -
                    e.negativeScrollAdjustment
                ),
                l(t),
                r.stopPropagation();
            });
        }
        var o = t("../../lib/helper"),
          i = t("../instances"),
          l = t("../update-geometry"),
          a = t("../update-scroll");
        e.exports = function (t) {
          var e = i.get(t);
          r(t, e);
        };
      },
      {
        "../../lib/helper": 6,
        "../instances": 18,
        "../update-geometry": 19,
        "../update-scroll": 20,
      },
    ],
    11: [
      function (t, e, n) {
        "use strict";
        function r(t, e) {
          function n(n) {
            var o = r + n * e.railXRatio,
              l =
                Math.max(0, e.scrollbarXRail.getBoundingClientRect().left) +
                e.railXRatio * (e.railXWidth - e.scrollbarXWidth);
            0 > o
              ? (e.scrollbarXLeft = 0)
              : o > l
              ? (e.scrollbarXLeft = l)
              : (e.scrollbarXLeft = o);
            var a =
              i.toInt(
                (e.scrollbarXLeft * (e.contentWidth - e.containerWidth)) /
                  (e.containerWidth - e.railXRatio * e.scrollbarXWidth)
              ) - e.negativeScrollAdjustment;
            c(t, "left", a);
          }
          var r = null,
            o = null,
            a = function (e) {
              n(e.pageX - o), s(t), e.stopPropagation(), e.preventDefault();
            },
            u = function () {
              i.stopScrolling(t, "x"),
                e.event.unbind(e.ownerDocument, "mousemove", a);
            };
          e.event.bind(e.scrollbarX, "mousedown", function (n) {
            (o = n.pageX),
              (r = i.toInt(l.css(e.scrollbarX, "left")) * e.railXRatio),
              i.startScrolling(t, "x"),
              e.event.bind(e.ownerDocument, "mousemove", a),
              e.event.once(e.ownerDocument, "mouseup", u),
              n.stopPropagation(),
              n.preventDefault();
          });
        }
        function o(t, e) {
          function n(n) {
            var o = r + n * e.railYRatio,
              l =
                Math.max(0, e.scrollbarYRail.getBoundingClientRect().top) +
                e.railYRatio * (e.railYHeight - e.scrollbarYHeight);
            0 > o
              ? (e.scrollbarYTop = 0)
              : o > l
              ? (e.scrollbarYTop = l)
              : (e.scrollbarYTop = o);
            var a = i.toInt(
              (e.scrollbarYTop * (e.contentHeight - e.containerHeight)) /
                (e.containerHeight - e.railYRatio * e.scrollbarYHeight)
            );
            c(t, "top", a);
          }
          var r = null,
            o = null,
            a = function (e) {
              n(e.pageY - o), s(t), e.stopPropagation(), e.preventDefault();
            },
            u = function () {
              i.stopScrolling(t, "y"),
                e.event.unbind(e.ownerDocument, "mousemove", a);
            };
          e.event.bind(e.scrollbarY, "mousedown", function (n) {
            (o = n.pageY),
              (r = i.toInt(l.css(e.scrollbarY, "top")) * e.railYRatio),
              i.startScrolling(t, "y"),
              e.event.bind(e.ownerDocument, "mousemove", a),
              e.event.once(e.ownerDocument, "mouseup", u),
              n.stopPropagation(),
              n.preventDefault();
          });
        }
        var i = t("../../lib/helper"),
          l = t("../../lib/dom"),
          a = t("../instances"),
          s = t("../update-geometry"),
          c = t("../update-scroll");
        e.exports = function (t) {
          var e = a.get(t);
          r(t, e), o(t, e);
        };
      },
      {
        "../../lib/dom": 3,
        "../../lib/helper": 6,
        "../instances": 18,
        "../update-geometry": 19,
        "../update-scroll": 20,
      },
    ],
    12: [
      function (t, e, n) {
        "use strict";
        function r(t, e) {
          function n(n, r) {
            var o = t.scrollTop;
            if (0 === n) {
              if (!e.scrollbarYActive) return !1;
              if (
                (0 === o && r > 0) ||
                (o >= e.contentHeight - e.containerHeight && 0 > r)
              )
                return !e.settings.wheelPropagation;
            }
            var i = t.scrollLeft;
            if (0 === r) {
              if (!e.scrollbarXActive) return !1;
              if (
                (0 === i && 0 > n) ||
                (i >= e.contentWidth - e.containerWidth && n > 0)
              )
                return !e.settings.wheelPropagation;
            }
            return !0;
          }
          var r = !1;
          e.event.bind(t, "mouseenter", function () {
            r = !0;
          }),
            e.event.bind(t, "mouseleave", function () {
              r = !1;
            });
          var l = !1;
          e.event.bind(e.ownerDocument, "keydown", function (c) {
            if (!c.isDefaultPrevented || !c.isDefaultPrevented()) {
              var u =
                i.matches(e.scrollbarX, ":focus") ||
                i.matches(e.scrollbarY, ":focus");
              if (r || u) {
                var d = document.activeElement
                  ? document.activeElement
                  : e.ownerDocument.activeElement;
                if (d) {
                  if ("IFRAME" === d.tagName)
                    d = d.contentDocument.activeElement;
                  else for (; d.shadowRoot; ) d = d.shadowRoot.activeElement;
                  if (o.isEditable(d)) return;
                }
                var p = 0,
                  f = 0;
                switch (c.which) {
                  case 37:
                    p = -30;
                    break;
                  case 38:
                    f = 30;
                    break;
                  case 39:
                    p = 30;
                    break;
                  case 40:
                    f = -30;
                    break;
                  case 33:
                    f = 90;
                    break;
                  case 32:
                    f = c.shiftKey ? 90 : -90;
                    break;
                  case 34:
                    f = -90;
                    break;
                  case 35:
                    f = c.ctrlKey ? -e.contentHeight : -e.containerHeight;
                    break;
                  case 36:
                    f = c.ctrlKey ? t.scrollTop : e.containerHeight;
                    break;
                  default:
                    return;
                }
                s(t, "top", t.scrollTop - f),
                  s(t, "left", t.scrollLeft + p),
                  a(t),
                  (l = n(p, f)),
                  l && c.preventDefault();
              }
            }
          });
        }
        var o = t("../../lib/helper"),
          i = t("../../lib/dom"),
          l = t("../instances"),
          a = t("../update-geometry"),
          s = t("../update-scroll");
        e.exports = function (t) {
          var e = l.get(t);
          r(t, e);
        };
      },
      {
        "../../lib/dom": 3,
        "../../lib/helper": 6,
        "../instances": 18,
        "../update-geometry": 19,
        "../update-scroll": 20,
      },
    ],
    13: [
      function (t, e, n) {
        "use strict";
        function r(t, e) {
          function n(n, r) {
            var o = t.scrollTop;
            if (0 === n) {
              if (!e.scrollbarYActive) return !1;
              if (
                (0 === o && r > 0) ||
                (o >= e.contentHeight - e.containerHeight && 0 > r)
              )
                return !e.settings.wheelPropagation;
            }
            var i = t.scrollLeft;
            if (0 === r) {
              if (!e.scrollbarXActive) return !1;
              if (
                (0 === i && 0 > n) ||
                (i >= e.contentWidth - e.containerWidth && n > 0)
              )
                return !e.settings.wheelPropagation;
            }
            return !0;
          }
          function r(t) {
            var e = t.deltaX,
              n = -1 * t.deltaY;
            return (
              ("undefined" != typeof e && "undefined" != typeof n) ||
                ((e = (-1 * t.wheelDeltaX) / 6), (n = t.wheelDeltaY / 6)),
              t.deltaMode && 1 === t.deltaMode && ((e *= 10), (n *= 10)),
              e !== e && n !== n && ((e = 0), (n = t.wheelDelta)),
              [e, n]
            );
          }
          function o(e, n) {
            var r = t.querySelector("textarea:hover, .ps-child:hover");
            if (r) {
              if (
                "TEXTAREA" !== r.tagName &&
                !window.getComputedStyle(r).overflow.match(/(scroll|auto)/)
              )
                return !1;
              var o = r.scrollHeight - r.clientHeight;
              if (
                o > 0 &&
                !((0 === r.scrollTop && n > 0) || (r.scrollTop === o && 0 > n))
              )
                return !0;
              var i = r.scrollLeft - r.clientWidth;
              if (
                i > 0 &&
                !(
                  (0 === r.scrollLeft && 0 > e) ||
                  (r.scrollLeft === i && e > 0)
                )
              )
                return !0;
            }
            return !1;
          }
          function a(a) {
            var c = r(a),
              u = c[0],
              d = c[1];
            o(u, d) ||
              ((s = !1),
              e.settings.useBothWheelAxes
                ? e.scrollbarYActive && !e.scrollbarXActive
                  ? (d
                      ? l(t, "top", t.scrollTop - d * e.settings.wheelSpeed)
                      : l(t, "top", t.scrollTop + u * e.settings.wheelSpeed),
                    (s = !0))
                  : e.scrollbarXActive &&
                    !e.scrollbarYActive &&
                    (u
                      ? l(t, "left", t.scrollLeft + u * e.settings.wheelSpeed)
                      : l(t, "left", t.scrollLeft - d * e.settings.wheelSpeed),
                    (s = !0))
                : (l(t, "top", t.scrollTop - d * e.settings.wheelSpeed),
                  l(t, "left", t.scrollLeft + u * e.settings.wheelSpeed)),
              i(t),
              (s = s || n(u, d)),
              s && (a.stopPropagation(), a.preventDefault()));
          }
          var s = !1;
          "undefined" != typeof window.onwheel
            ? e.event.bind(t, "wheel", a)
            : "undefined" != typeof window.onmousewheel &&
              e.event.bind(t, "mousewheel", a);
        }
        var o = t("../instances"),
          i = t("../update-geometry"),
          l = t("../update-scroll");
        e.exports = function (t) {
          var e = o.get(t);
          r(t, e);
        };
      },
      { "../instances": 18, "../update-geometry": 19, "../update-scroll": 20 },
    ],
    14: [
      function (t, e, n) {
        "use strict";
        function r(t, e) {
          e.event.bind(t, "scroll", function () {
            i(t);
          });
        }
        var o = t("../instances"),
          i = t("../update-geometry");
        e.exports = function (t) {
          var e = o.get(t);
          r(t, e);
        };
      },
      { "../instances": 18, "../update-geometry": 19 },
    ],
    15: [
      function (t, e, n) {
        "use strict";
        function r(t, e) {
          function n() {
            var t = window.getSelection
              ? window.getSelection()
              : document.getSelection
              ? document.getSelection()
              : "";
            return 0 === t.toString().length
              ? null
              : t.getRangeAt(0).commonAncestorContainer;
          }
          function r() {
            c ||
              (c = setInterval(function () {
                return i.get(t)
                  ? (a(t, "top", t.scrollTop + u.top),
                    a(t, "left", t.scrollLeft + u.left),
                    void l(t))
                  : void clearInterval(c);
              }, 50));
          }
          function s() {
            c && (clearInterval(c), (c = null)), o.stopScrolling(t);
          }
          var c = null,
            u = { top: 0, left: 0 },
            d = !1;
          e.event.bind(e.ownerDocument, "selectionchange", function () {
            t.contains(n()) ? (d = !0) : ((d = !1), s());
          }),
            e.event.bind(window, "mouseup", function () {
              d && ((d = !1), s());
            }),
            e.event.bind(window, "mousemove", function (e) {
              if (d) {
                var n = { x: e.pageX, y: e.pageY },
                  i = {
                    left: t.offsetLeft,
                    right: t.offsetLeft + t.offsetWidth,
                    top: t.offsetTop,
                    bottom: t.offsetTop + t.offsetHeight,
                  };
                n.x < i.left + 3
                  ? ((u.left = -5), o.startScrolling(t, "x"))
                  : n.x > i.right - 3
                  ? ((u.left = 5), o.startScrolling(t, "x"))
                  : (u.left = 0),
                  n.y < i.top + 3
                    ? (i.top + 3 - n.y < 5 ? (u.top = -5) : (u.top = -20),
                      o.startScrolling(t, "y"))
                    : n.y > i.bottom - 3
                    ? (n.y - i.bottom + 3 < 5 ? (u.top = 5) : (u.top = 20),
                      o.startScrolling(t, "y"))
                    : (u.top = 0),
                  0 === u.top && 0 === u.left ? s() : r();
              }
            });
        }
        var o = t("../../lib/helper"),
          i = t("../instances"),
          l = t("../update-geometry"),
          a = t("../update-scroll");
        e.exports = function (t) {
          var e = i.get(t);
          r(t, e);
        };
      },
      {
        "../../lib/helper": 6,
        "../instances": 18,
        "../update-geometry": 19,
        "../update-scroll": 20,
      },
    ],
    16: [
      function (t, e, n) {
        "use strict";
        function r(t, e, n, r) {
          function o(n, r) {
            var o = t.scrollTop,
              i = t.scrollLeft,
              l = Math.abs(n),
              a = Math.abs(r);
            if (a > l) {
              if (
                (0 > r && o === e.contentHeight - e.containerHeight) ||
                (r > 0 && 0 === o)
              )
                return !e.settings.swipePropagation;
            } else if (
              l > a &&
              ((0 > n && i === e.contentWidth - e.containerWidth) ||
                (n > 0 && 0 === i))
            )
              return !e.settings.swipePropagation;
            return !0;
          }
          function s(e, n) {
            a(t, "top", t.scrollTop - n), a(t, "left", t.scrollLeft - e), l(t);
          }
          function c() {
            Y = !0;
          }
          function u() {
            Y = !1;
          }
          function d(t) {
            return t.targetTouches ? t.targetTouches[0] : t;
          }
          function p(t) {
            return t.targetTouches && 1 === t.targetTouches.length
              ? !0
              : !(
                  !t.pointerType ||
                  "mouse" === t.pointerType ||
                  t.pointerType === t.MSPOINTER_TYPE_MOUSE
                );
          }
          function f(t) {
            if (p(t)) {
              w = !0;
              var e = d(t);
              (v.pageX = e.pageX),
                (v.pageY = e.pageY),
                (g = new Date().getTime()),
                null !== y && clearInterval(y),
                t.stopPropagation();
            }
          }
          function h(t) {
            if ((!w && e.settings.swipePropagation && f(t), !Y && w && p(t))) {
              var n = d(t),
                r = { pageX: n.pageX, pageY: n.pageY },
                i = r.pageX - v.pageX,
                l = r.pageY - v.pageY;
              s(i, l), (v = r);
              var a = new Date().getTime(),
                c = a - g;
              c > 0 && ((m.x = i / c), (m.y = l / c), (g = a)),
                o(i, l) && (t.stopPropagation(), t.preventDefault());
            }
          }
          function b() {
            !Y &&
              w &&
              ((w = !1),
              clearInterval(y),
              (y = setInterval(function () {
                return i.get(t)
                  ? Math.abs(m.x) < 0.01 && Math.abs(m.y) < 0.01
                    ? void clearInterval(y)
                    : (s(30 * m.x, 30 * m.y), (m.x *= 0.8), void (m.y *= 0.8))
                  : void clearInterval(y);
              }, 10)));
          }
          var v = {},
            g = 0,
            m = {},
            y = null,
            Y = !1,
            w = !1;
          n &&
            (e.event.bind(window, "touchstart", c),
            e.event.bind(window, "touchend", u),
            e.event.bind(t, "touchstart", f),
            e.event.bind(t, "touchmove", h),
            e.event.bind(t, "touchend", b)),
            r &&
              (window.PointerEvent
                ? (e.event.bind(window, "pointerdown", c),
                  e.event.bind(window, "pointerup", u),
                  e.event.bind(t, "pointerdown", f),
                  e.event.bind(t, "pointermove", h),
                  e.event.bind(t, "pointerup", b))
                : window.MSPointerEvent &&
                  (e.event.bind(window, "MSPointerDown", c),
                  e.event.bind(window, "MSPointerUp", u),
                  e.event.bind(t, "MSPointerDown", f),
                  e.event.bind(t, "MSPointerMove", h),
                  e.event.bind(t, "MSPointerUp", b)));
        }
        var o = t("../../lib/helper"),
          i = t("../instances"),
          l = t("../update-geometry"),
          a = t("../update-scroll");
        e.exports = function (t) {
          if (o.env.supportsTouch || o.env.supportsIePointer) {
            var e = i.get(t);
            r(t, e, o.env.supportsTouch, o.env.supportsIePointer);
          }
        };
      },
      {
        "../../lib/helper": 6,
        "../instances": 18,
        "../update-geometry": 19,
        "../update-scroll": 20,
      },
    ],
    17: [
      function (t, e, n) {
        "use strict";
        var r = t("../lib/helper"),
          o = t("../lib/class"),
          i = t("./instances"),
          l = t("./update-geometry"),
          a = {
            "click-rail": t("./handler/click-rail"),
            "drag-scrollbar": t("./handler/drag-scrollbar"),
            keyboard: t("./handler/keyboard"),
            wheel: t("./handler/mouse-wheel"),
            touch: t("./handler/touch"),
            selection: t("./handler/selection"),
          },
          s = t("./handler/native-scroll");
        e.exports = function (t, e) {
          (e = "object" == typeof e ? e : {}), o.add(t, "ps-container");
          var n = i.add(t);
          (n.settings = r.extend(n.settings, e)),
            o.add(t, "ps-theme-" + n.settings.theme),
            n.settings.handlers.forEach(function (e) {
              a[e](t);
            }),
            s(t),
            l(t);
        };
      },
      {
        "../lib/class": 2,
        "../lib/helper": 6,
        "./handler/click-rail": 10,
        "./handler/drag-scrollbar": 11,
        "./handler/keyboard": 12,
        "./handler/mouse-wheel": 13,
        "./handler/native-scroll": 14,
        "./handler/selection": 15,
        "./handler/touch": 16,
        "./instances": 18,
        "./update-geometry": 19,
      },
    ],
    18: [
      function (t, e, n) {
        "use strict";
        function r(t) {
          function e() {
            s.add(t, "ps-focus");
          }
          function n() {
            s.remove(t, "ps-focus");
          }
          var r = this;
          (r.settings = a.clone(c)),
            (r.containerWidth = null),
            (r.containerHeight = null),
            (r.contentWidth = null),
            (r.contentHeight = null),
            (r.isRtl = "rtl" === u.css(t, "direction")),
            (r.isNegativeScroll = (function () {
              var e = t.scrollLeft,
                n = null;
              return (
                (t.scrollLeft = -1),
                (n = t.scrollLeft < 0),
                (t.scrollLeft = e),
                n
              );
            })()),
            (r.negativeScrollAdjustment = r.isNegativeScroll
              ? t.scrollWidth - t.clientWidth
              : 0),
            (r.event = new d()),
            (r.ownerDocument = t.ownerDocument || document),
            (r.scrollbarXRail = u.appendTo(
              u.e("div", "ps-scrollbar-x-rail"),
              t
            )),
            (r.scrollbarX = u.appendTo(
              u.e("div", "ps-scrollbar-x"),
              r.scrollbarXRail
            )),
            r.scrollbarX.setAttribute("tabindex", 0),
            r.event.bind(r.scrollbarX, "focus", e),
            r.event.bind(r.scrollbarX, "blur", n),
            (r.scrollbarXActive = null),
            (r.scrollbarXWidth = null),
            (r.scrollbarXLeft = null),
            (r.scrollbarXBottom = a.toInt(u.css(r.scrollbarXRail, "bottom"))),
            (r.isScrollbarXUsingBottom =
              r.scrollbarXBottom === r.scrollbarXBottom),
            (r.scrollbarXTop = r.isScrollbarXUsingBottom
              ? null
              : a.toInt(u.css(r.scrollbarXRail, "top"))),
            (r.railBorderXWidth =
              a.toInt(u.css(r.scrollbarXRail, "borderLeftWidth")) +
              a.toInt(u.css(r.scrollbarXRail, "borderRightWidth"))),
            u.css(r.scrollbarXRail, "display", "block"),
            (r.railXMarginWidth =
              a.toInt(u.css(r.scrollbarXRail, "marginLeft")) +
              a.toInt(u.css(r.scrollbarXRail, "marginRight"))),
            u.css(r.scrollbarXRail, "display", ""),
            (r.railXWidth = null),
            (r.railXRatio = null),
            (r.scrollbarYRail = u.appendTo(
              u.e("div", "ps-scrollbar-y-rail"),
              t
            )),
            (r.scrollbarY = u.appendTo(
              u.e("div", "ps-scrollbar-y"),
              r.scrollbarYRail
            )),
            r.scrollbarY.setAttribute("tabindex", 0),
            r.event.bind(r.scrollbarY, "focus", e),
            r.event.bind(r.scrollbarY, "blur", n),
            (r.scrollbarYActive = null),
            (r.scrollbarYHeight = null),
            (r.scrollbarYTop = null),
            (r.scrollbarYRight = a.toInt(u.css(r.scrollbarYRail, "right"))),
            (r.isScrollbarYUsingRight =
              r.scrollbarYRight === r.scrollbarYRight),
            (r.scrollbarYLeft = r.isScrollbarYUsingRight
              ? null
              : a.toInt(u.css(r.scrollbarYRail, "left"))),
            (r.scrollbarYOuterWidth = r.isRtl
              ? a.outerWidth(r.scrollbarY)
              : null),
            (r.railBorderYWidth =
              a.toInt(u.css(r.scrollbarYRail, "borderTopWidth")) +
              a.toInt(u.css(r.scrollbarYRail, "borderBottomWidth"))),
            u.css(r.scrollbarYRail, "display", "block"),
            (r.railYMarginHeight =
              a.toInt(u.css(r.scrollbarYRail, "marginTop")) +
              a.toInt(u.css(r.scrollbarYRail, "marginBottom"))),
            u.css(r.scrollbarYRail, "display", ""),
            (r.railYHeight = null),
            (r.railYRatio = null);
        }
        function o(t) {
          return t.getAttribute("data-ps-id");
        }
        function i(t, e) {
          t.setAttribute("data-ps-id", e);
        }
        function l(t) {
          t.removeAttribute("data-ps-id");
        }
        var a = t("../lib/helper"),
          s = t("../lib/class"),
          c = t("./default-setting"),
          u = t("../lib/dom"),
          d = t("../lib/event-manager"),
          p = t("../lib/guid"),
          f = {};
        (n.add = function (t) {
          var e = p();
          return i(t, e), (f[e] = new r(t)), f[e];
        }),
          (n.remove = function (t) {
            delete f[o(t)], l(t);
          }),
          (n.get = function (t) {
            return f[o(t)];
          });
      },
      {
        "../lib/class": 2,
        "../lib/dom": 3,
        "../lib/event-manager": 4,
        "../lib/guid": 5,
        "../lib/helper": 6,
        "./default-setting": 8,
      },
    ],
    19: [
      function (t, e, n) {
        "use strict";
        function r(t, e) {
          return (
            t.settings.minScrollbarLength &&
              (e = Math.max(e, t.settings.minScrollbarLength)),
            t.settings.maxScrollbarLength &&
              (e = Math.min(e, t.settings.maxScrollbarLength)),
            e
          );
        }
        function o(t, e) {
          var n = { width: e.railXWidth };
          e.isRtl
            ? (n.left =
                e.negativeScrollAdjustment +
                t.scrollLeft +
                e.containerWidth -
                e.contentWidth)
            : (n.left = t.scrollLeft),
            e.isScrollbarXUsingBottom
              ? (n.bottom = e.scrollbarXBottom - t.scrollTop)
              : (n.top = e.scrollbarXTop + t.scrollTop),
            a.css(e.scrollbarXRail, n);
          var r = { top: t.scrollTop, height: e.railYHeight };
          e.isScrollbarYUsingRight
            ? e.isRtl
              ? (r.right =
                  e.contentWidth -
                  (e.negativeScrollAdjustment + t.scrollLeft) -
                  e.scrollbarYRight -
                  e.scrollbarYOuterWidth)
              : (r.right = e.scrollbarYRight - t.scrollLeft)
            : e.isRtl
            ? (r.left =
                e.negativeScrollAdjustment +
                t.scrollLeft +
                2 * e.containerWidth -
                e.contentWidth -
                e.scrollbarYLeft -
                e.scrollbarYOuterWidth)
            : (r.left = e.scrollbarYLeft + t.scrollLeft),
            a.css(e.scrollbarYRail, r),
            a.css(e.scrollbarX, {
              left: e.scrollbarXLeft,
              width: e.scrollbarXWidth - e.railBorderXWidth,
            }),
            a.css(e.scrollbarY, {
              top: e.scrollbarYTop,
              height: e.scrollbarYHeight - e.railBorderYWidth,
            });
        }
        var i = t("../lib/helper"),
          l = t("../lib/class"),
          a = t("../lib/dom"),
          s = t("./instances"),
          c = t("./update-scroll");
        e.exports = function (t) {
          var e = s.get(t);
          (e.containerWidth = t.clientWidth),
            (e.containerHeight = t.clientHeight),
            (e.contentWidth = t.scrollWidth),
            (e.contentHeight = t.scrollHeight);
          var n;
          t.contains(e.scrollbarXRail) ||
            ((n = a.queryChildren(t, ".ps-scrollbar-x-rail")),
            n.length > 0 &&
              n.forEach(function (t) {
                a.remove(t);
              }),
            a.appendTo(e.scrollbarXRail, t)),
            t.contains(e.scrollbarYRail) ||
              ((n = a.queryChildren(t, ".ps-scrollbar-y-rail")),
              n.length > 0 &&
                n.forEach(function (t) {
                  a.remove(t);
                }),
              a.appendTo(e.scrollbarYRail, t)),
            !e.settings.suppressScrollX &&
            e.containerWidth + e.settings.scrollXMarginOffset < e.contentWidth
              ? ((e.scrollbarXActive = !0),
                (e.railXWidth = e.containerWidth - e.railXMarginWidth),
                (e.railXRatio = e.containerWidth / e.railXWidth),
                (e.scrollbarXWidth = r(
                  e,
                  i.toInt((e.railXWidth * e.containerWidth) / e.contentWidth)
                )),
                (e.scrollbarXLeft = i.toInt(
                  ((e.negativeScrollAdjustment + t.scrollLeft) *
                    (e.railXWidth - e.scrollbarXWidth)) /
                    (e.contentWidth - e.containerWidth)
                )))
              : (e.scrollbarXActive = !1),
            !e.settings.suppressScrollY &&
            e.containerHeight + e.settings.scrollYMarginOffset < e.contentHeight
              ? ((e.scrollbarYActive = !0),
                (e.railYHeight = e.containerHeight - e.railYMarginHeight),
                (e.railYRatio = e.containerHeight / e.railYHeight),
                (e.scrollbarYHeight = r(
                  e,
                  i.toInt((e.railYHeight * e.containerHeight) / e.contentHeight)
                )),
                (e.scrollbarYTop = i.toInt(
                  (t.scrollTop * (e.railYHeight - e.scrollbarYHeight)) /
                    (e.contentHeight - e.containerHeight)
                )))
              : (e.scrollbarYActive = !1),
            e.scrollbarXLeft >= e.railXWidth - e.scrollbarXWidth &&
              (e.scrollbarXLeft = e.railXWidth - e.scrollbarXWidth),
            e.scrollbarYTop >= e.railYHeight - e.scrollbarYHeight &&
              (e.scrollbarYTop = e.railYHeight - e.scrollbarYHeight),
            o(t, e),
            e.scrollbarXActive
              ? l.add(t, "ps-active-x")
              : (l.remove(t, "ps-active-x"),
                (e.scrollbarXWidth = 0),
                (e.scrollbarXLeft = 0),
                c(t, "left", 0)),
            e.scrollbarYActive
              ? l.add(t, "ps-active-y")
              : (l.remove(t, "ps-active-y"),
                (e.scrollbarYHeight = 0),
                (e.scrollbarYTop = 0),
                c(t, "top", 0));
        };
      },
      {
        "../lib/class": 2,
        "../lib/dom": 3,
        "../lib/helper": 6,
        "./instances": 18,
        "./update-scroll": 20,
      },
    ],
    20: [
      function (t, e, n) {
        "use strict";
        var r,
          o,
          i = t("./instances"),
          l = document.createEvent("Event"),
          a = document.createEvent("Event"),
          s = document.createEvent("Event"),
          c = document.createEvent("Event"),
          u = document.createEvent("Event"),
          d = document.createEvent("Event"),
          p = document.createEvent("Event"),
          f = document.createEvent("Event"),
          h = document.createEvent("Event"),
          b = document.createEvent("Event");
        l.initEvent("ps-scroll-up", !0, !0),
          a.initEvent("ps-scroll-down", !0, !0),
          s.initEvent("ps-scroll-left", !0, !0),
          c.initEvent("ps-scroll-right", !0, !0),
          u.initEvent("ps-scroll-y", !0, !0),
          d.initEvent("ps-scroll-x", !0, !0),
          p.initEvent("ps-x-reach-start", !0, !0),
          f.initEvent("ps-x-reach-end", !0, !0),
          h.initEvent("ps-y-reach-start", !0, !0),
          b.initEvent("ps-y-reach-end", !0, !0),
          (e.exports = function (t, e, n) {
            if ("undefined" == typeof t)
              throw "You must provide an element to the update-scroll function";
            if ("undefined" == typeof e)
              throw "You must provide an axis to the update-scroll function";
            if ("undefined" == typeof n)
              throw "You must provide a value to the update-scroll function";
            "top" === e &&
              0 >= n &&
              ((t.scrollTop = n = 0), t.dispatchEvent(h)),
              "left" === e &&
                0 >= n &&
                ((t.scrollLeft = n = 0), t.dispatchEvent(p));
            var v = i.get(t);
            "top" === e &&
              n >= v.contentHeight - v.containerHeight &&
              ((n = v.contentHeight - v.containerHeight),
              n - t.scrollTop <= 1 ? (n = t.scrollTop) : (t.scrollTop = n),
              t.dispatchEvent(b)),
              "left" === e &&
                n >= v.contentWidth - v.containerWidth &&
                ((n = v.contentWidth - v.containerWidth),
                n - t.scrollLeft <= 1 ? (n = t.scrollLeft) : (t.scrollLeft = n),
                t.dispatchEvent(f)),
              r || (r = t.scrollTop),
              o || (o = t.scrollLeft),
              "top" === e && r > n && t.dispatchEvent(l),
              "top" === e && n > r && t.dispatchEvent(a),
              "left" === e && o > n && t.dispatchEvent(s),
              "left" === e && n > o && t.dispatchEvent(c),
              "top" === e && ((t.scrollTop = r = n), t.dispatchEvent(u)),
              "left" === e && ((t.scrollLeft = o = n), t.dispatchEvent(d));
          });
      },
      { "./instances": 18 },
    ],
    21: [
      function (t, e, n) {
        "use strict";
        var r = t("../lib/helper"),
          o = t("../lib/dom"),
          i = t("./instances"),
          l = t("./update-geometry"),
          a = t("./update-scroll");
        e.exports = function (t) {
          var e = i.get(t);
          e &&
            ((e.negativeScrollAdjustment = e.isNegativeScroll
              ? t.scrollWidth - t.clientWidth
              : 0),
            o.css(e.scrollbarXRail, "display", "block"),
            o.css(e.scrollbarYRail, "display", "block"),
            (e.railXMarginWidth =
              r.toInt(o.css(e.scrollbarXRail, "marginLeft")) +
              r.toInt(o.css(e.scrollbarXRail, "marginRight"))),
            (e.railYMarginHeight =
              r.toInt(o.css(e.scrollbarYRail, "marginTop")) +
              r.toInt(o.css(e.scrollbarYRail, "marginBottom"))),
            o.css(e.scrollbarXRail, "display", "none"),
            o.css(e.scrollbarYRail, "display", "none"),
            l(t),
            a(t, "top", t.scrollTop),
            a(t, "left", t.scrollLeft),
            o.css(e.scrollbarXRail, "display", ""),
            o.css(e.scrollbarYRail, "display", ""));
        };
      },
      {
        "../lib/dom": 3,
        "../lib/helper": 6,
        "./instances": 18,
        "./update-geometry": 19,
        "./update-scroll": 20,
      },
    ],
  },
  {},
  [1]
);

/* Unison JS */
Unison = (function () {
  "use strict";
  var a,
    b = window,
    c = document,
    d = c.head,
    e = {},
    f = !1,
    g = {
      parseMQ: function (a) {
        var c = b.getComputedStyle(a, null).getPropertyValue("font-family");
        return c.replace(/"/g, "").replace(/'/g, "");
      },
      debounce: function (a, b, c) {
        var d;
        return function () {
          var e = this,
            f = arguments;
          clearTimeout(d),
            (d = setTimeout(function () {
              (d = null), c || a.apply(e, f);
            }, b)),
            c && !d && a.apply(e, f);
        };
      },
      isObject: function (a) {
        return "object" == typeof a;
      },
      isUndefined: function (a) {
        return "undefined" == typeof a;
      },
    },
    h = {
      on: function (a, b) {
        g.isObject(e[a]) || (e[a] = []), e[a].push(b);
      },
      emit: function (a, b) {
        if (g.isObject(e[a]))
          for (var c = e[a].slice(), d = 0; d < c.length; d++)
            c[d].call(this, b);
      },
    },
    i = {
      all: function () {
        for (
          var a = {}, b = g.parseMQ(c.querySelector("title")).split(","), d = 0;
          d < b.length;
          d++
        ) {
          var e = b[d].trim().split(" ");
          a[e[0]] = e[1];
        }
        return f ? a : null;
      },
      now: function (a) {
        var b = g.parseMQ(d).split(" "),
          c = { name: b[0], width: b[1] };
        return f ? (g.isUndefined(a) ? c : a(c)) : null;
      },
      update: function () {
        i.now(function (b) {
          b.name !== a && (h.emit(b.name), h.emit("change", b), (a = b.name));
        });
      },
    };
  return (
    (b.onresize = g.debounce(i.update, 100)),
    c.addEventListener("DOMContentLoaded", function () {
      (f = "none" !== b.getComputedStyle(d, null).getPropertyValue("clear")),
        i.update();
    }),
    {
      fetch: { all: i.all, now: i.now },
      on: h.on,
      emit: h.emit,
      util: { debounce: g.debounce, isObject: g.isObject },
    }
  );
})();

/*!
 * jQuery blockUI plugin
 * Version 2.70.0-2014.11.23
 * Requires jQuery v1.7 or later
 *
 * Examples at: http://malsup.com/jquery/block/
 * Copyright (c) 2007-2013 M. Alsup
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 * Thanks to Amir-Hossein Sobhi for some excellent contributions!
 */

!(function () {
  "use strict";
  function e(e) {
    function t(t, n) {
      var s,
        h,
        k = t == window,
        y = n && void 0 !== n.message ? n.message : void 0;
      if (
        ((n = e.extend({}, e.blockUI.defaults, n || {})),
        !n.ignoreIfBlocked || !e(t).data("blockUI.isBlocked"))
      ) {
        if (
          ((n.overlayCSS = e.extend(
            {},
            e.blockUI.defaults.overlayCSS,
            n.overlayCSS || {}
          )),
          (s = e.extend({}, e.blockUI.defaults.css, n.css || {})),
          n.onOverlayClick && (n.overlayCSS.cursor = "pointer"),
          (h = e.extend({}, e.blockUI.defaults.themedCSS, n.themedCSS || {})),
          (y = void 0 === y ? n.message : y),
          k && p && o(window, { fadeOut: 0 }),
          y && "string" != typeof y && (y.parentNode || y.jquery))
        ) {
          var m = y.jquery ? y[0] : y,
            v = {};
          e(t).data("blockUI.history", v),
            (v.el = m),
            (v.parent = m.parentNode),
            (v.display = m.style.display),
            (v.position = m.style.position),
            v.parent && v.parent.removeChild(m);
        }
        e(t).data("blockUI.onUnblock", n.onUnblock);
        var g,
          I,
          w,
          U,
          x = n.baseZ;
        (g = e(
          r || n.forceIframe
            ? '<iframe class="blockUI" style="z-index:' +
                x++ +
                ';display:none;border:none;margin:0;padding:0;position:absolute;width:100%;height:100%;top:0;left:0" src="' +
                n.iframeSrc +
                '"></iframe>'
            : '<div class="blockUI" style="display:none"></div>'
        )),
          (I = e(
            n.theme
              ? '<div class="blockUI blockOverlay ui-widget-overlay" style="z-index:' +
                  x++ +
                  ';display:none"></div>'
              : '<div class="blockUI blockOverlay" style="z-index:' +
                  x++ +
                  ';display:none;border:none;margin:0;padding:0;width:100%;height:100%;top:0;left:0"></div>'
          )),
          n.theme && k
            ? ((U =
                '<div class="blockUI ' +
                n.blockMsgClass +
                ' blockPage ui-dialog ui-widget ui-corner-all" style="z-index:' +
                (x + 10) +
                ';display:none;position:fixed">'),
              n.title &&
                (U +=
                  '<div class="ui-widget-header ui-dialog-titlebar ui-corner-all blockTitle">' +
                  (n.title || "&nbsp;") +
                  "</div>"),
              (U += '<div class="ui-widget-content ui-dialog-content"></div>'),
              (U += "</div>"))
            : n.theme
            ? ((U =
                '<div class="blockUI ' +
                n.blockMsgClass +
                ' blockElement ui-dialog ui-widget ui-corner-all" style="z-index:' +
                (x + 10) +
                ';display:none;position:absolute">'),
              n.title &&
                (U +=
                  '<div class="ui-widget-header ui-dialog-titlebar ui-corner-all blockTitle">' +
                  (n.title || "&nbsp;") +
                  "</div>"),
              (U += '<div class="ui-widget-content ui-dialog-content"></div>'),
              (U += "</div>"))
            : (U = k
                ? '<div class="blockUI ' +
                  n.blockMsgClass +
                  ' blockPage" style="z-index:' +
                  (x + 10) +
                  ';display:none;position:fixed"></div>'
                : '<div class="blockUI ' +
                  n.blockMsgClass +
                  ' blockElement" style="z-index:' +
                  (x + 10) +
                  ';display:none;position:absolute"></div>'),
          (w = e(U)),
          y &&
            (n.theme ? (w.css(h), w.addClass("ui-widget-content")) : w.css(s)),
          n.theme || I.css(n.overlayCSS),
          I.css("position", k ? "fixed" : "absolute"),
          (r || n.forceIframe) && g.css("opacity", 0);
        var C = [g, I, w],
          S = e(k ? "body" : t);
        e.each(C, function () {
          this.appendTo(S);
        }),
          n.theme &&
            n.draggable &&
            e.fn.draggable &&
            w.draggable({ handle: ".ui-dialog-titlebar", cancel: "li" });
        var O =
          f &&
          (!e.support.boxModel || e("object,embed", k ? null : t).length > 0);
        if (u || O) {
          if (
            (k &&
              n.allowBodyStretch &&
              e.support.boxModel &&
              e("html,body").css("height", "100%"),
            (u || !e.support.boxModel) && !k)
          )
            var E = d(t, "borderTopWidth"),
              T = d(t, "borderLeftWidth"),
              M = E ? "(0 - " + E + ")" : 0,
              B = T ? "(0 - " + T + ")" : 0;
          e.each(C, function (e, t) {
            var o = t[0].style;
            if (((o.position = "absolute"), 2 > e))
              k
                ? o.setExpression(
                    "height",
                    "Math.max(document.body.scrollHeight, document.body.offsetHeight) - (jQuery.support.boxModel?0:" +
                      n.quirksmodeOffsetHack +
                      ') + "px"'
                  )
                : o.setExpression(
                    "height",
                    'this.parentNode.offsetHeight + "px"'
                  ),
                k
                  ? o.setExpression(
                      "width",
                      'jQuery.support.boxModel && document.documentElement.clientWidth || document.body.clientWidth + "px"'
                    )
                  : o.setExpression(
                      "width",
                      'this.parentNode.offsetWidth + "px"'
                    ),
                B && o.setExpression("left", B),
                M && o.setExpression("top", M);
            else if (n.centerY)
              k &&
                o.setExpression(
                  "top",
                  '(document.documentElement.clientHeight || document.body.clientHeight) / 2 - (this.offsetHeight / 2) + (blah = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop) + "px"'
                ),
                (o.marginTop = 0);
            else if (!n.centerY && k) {
              var i = n.css && n.css.top ? parseInt(n.css.top, 10) : 0,
                s =
                  "((document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop) + " +
                  i +
                  ') + "px"';
              o.setExpression("top", s);
            }
          });
        }
        if (
          (y &&
            (n.theme ? w.find(".ui-widget-content").append(y) : w.append(y),
            (y.jquery || y.nodeType) && e(y).show()),
          (r || n.forceIframe) && n.showOverlay && g.show(),
          n.fadeIn)
        ) {
          var j = n.onBlock ? n.onBlock : c,
            H = n.showOverlay && !y ? j : c,
            z = y ? j : c;
          n.showOverlay && I._fadeIn(n.fadeIn, H), y && w._fadeIn(n.fadeIn, z);
        } else
          n.showOverlay && I.show(),
            y && w.show(),
            n.onBlock && n.onBlock.bind(w)();
        if (
          (i(1, t, n),
          k
            ? ((p = w[0]),
              (b = e(n.focusableElements, p)),
              n.focusInput && setTimeout(l, 20))
            : a(w[0], n.centerX, n.centerY),
          n.timeout)
        ) {
          var W = setTimeout(function () {
            k ? e.unblockUI(n) : e(t).unblock(n);
          }, n.timeout);
          e(t).data("blockUI.timeout", W);
        }
      }
    }
    function o(t, o) {
      var s,
        l = t == window,
        a = e(t),
        d = a.data("blockUI.history"),
        c = a.data("blockUI.timeout");
      c && (clearTimeout(c), a.removeData("blockUI.timeout")),
        (o = e.extend({}, e.blockUI.defaults, o || {})),
        i(0, t, o),
        null === o.onUnblock &&
          ((o.onUnblock = a.data("blockUI.onUnblock")),
          a.removeData("blockUI.onUnblock"));
      var r;
      (r = l
        ? e("body").children().filter(".blockUI").add("body > .blockUI")
        : a.find(">.blockUI")),
        o.cursorReset &&
          (r.length > 1 && (r[1].style.cursor = o.cursorReset),
          r.length > 2 && (r[2].style.cursor = o.cursorReset)),
        l && (p = b = null),
        o.fadeOut
          ? ((s = r.length),
            r.stop().fadeOut(o.fadeOut, function () {
              0 === --s && n(r, d, o, t);
            }))
          : n(r, d, o, t);
    }
    function n(t, o, n, i) {
      var s = e(i);
      if (!s.data("blockUI.isBlocked")) {
        t.each(function (e, t) {
          this.parentNode && this.parentNode.removeChild(this);
        }),
          o &&
            o.el &&
            ((o.el.style.display = o.display),
            (o.el.style.position = o.position),
            (o.el.style.cursor = "default"),
            o.parent && o.parent.appendChild(o.el),
            s.removeData("blockUI.history")),
          s.data("blockUI.static") && s.css("position", "static"),
          "function" == typeof n.onUnblock && n.onUnblock(i, n);
        var l = e(document.body),
          a = l.width(),
          d = l[0].style.width;
        l.width(a - 1).width(a), (l[0].style.width = d);
      }
    }
    function i(t, o, n) {
      var i = o == window,
        l = e(o);
      if (
        (t || ((!i || p) && (i || l.data("blockUI.isBlocked")))) &&
        (l.data("blockUI.isBlocked", t),
        i && n.bindEvents && (!t || n.showOverlay))
      ) {
        var a =
          "mousedown mouseup keydown keypress keyup touchstart touchend touchmove";
        t ? e(document).bind(a, n, s) : e(document).unbind(a, s);
      }
    }
    function s(t) {
      if (
        "keydown" === t.type &&
        t.keyCode &&
        9 == t.keyCode &&
        p &&
        t.data.constrainTabKey
      ) {
        var o = b,
          n = !t.shiftKey && t.target === o[o.length - 1],
          i = t.shiftKey && t.target === o[0];
        if (n || i)
          return (
            setTimeout(function () {
              l(i);
            }, 10),
            !1
          );
      }
      var s = t.data,
        a = e(t.target);
      return (
        a.hasClass("blockOverlay") && s.onOverlayClick && s.onOverlayClick(t),
        a.parents("div." + s.blockMsgClass).length > 0
          ? !0
          : 0 === a.parents().children().filter("div.blockUI").length
      );
    }
    function l(e) {
      if (b) {
        var t = b[e === !0 ? b.length - 1 : 0];
        t && t.focus();
      }
    }
    function a(e, t, o) {
      var n = e.parentNode,
        i = e.style,
        s = (n.offsetWidth - e.offsetWidth) / 2 - d(n, "borderLeftWidth"),
        l = (n.offsetHeight - e.offsetHeight) / 2 - d(n, "borderTopWidth");
      t && (i.left = s > 0 ? s + "px" : "0"),
        o && (i.top = l > 0 ? l + "px" : "0");
    }
    function d(t, o) {
      return parseInt(e.css(t, o), 10) || 0;
    }
    e.fn._fadeIn = e.fn.fadeIn;
    var c = e.noop || function () {},
      r = /MSIE/.test(navigator.userAgent),
      u =
        /MSIE 6.0/.test(navigator.userAgent) &&
        !/MSIE 8.0/.test(navigator.userAgent),
      f =
        (document.documentMode || 0,
        e.isFunction(document.createElement("div").style.setExpression));
    (e.blockUI = function (e) {
      t(window, e);
    }),
      (e.unblockUI = function (e) {
        o(window, e);
      }),
      (e.growlUI = function (t, o, n, i) {
        var s = e('<div class="growlUI"></div>');
        t && s.append("<h1>" + t + "</h1>"),
          o && s.append("<h2>" + o + "</h2>"),
          void 0 === n && (n = 3e3);
        var l = function (t) {
          (t = t || {}),
            e.blockUI({
              message: s,
              fadeIn: "undefined" != typeof t.fadeIn ? t.fadeIn : 700,
              fadeOut: "undefined" != typeof t.fadeOut ? t.fadeOut : 1e3,
              timeout: "undefined" != typeof t.timeout ? t.timeout : n,
              centerY: !1,
              showOverlay: !1,
              onUnblock: i,
              css: e.blockUI.defaults.growlCSS,
            });
        };
        l();
        s.css("opacity");
        s.mouseover(function () {
          l({ fadeIn: 0, timeout: 3e4 });
          var t = e(".blockMsg");
          t.stop(), t.fadeTo(300, 1);
        }).mouseout(function () {
          e(".blockMsg").fadeOut(1e3);
        });
      }),
      (e.fn.block = function (o) {
        if (this[0] === window) return e.blockUI(o), this;
        var n = e.extend({}, e.blockUI.defaults, o || {});
        return (
          this.each(function () {
            var t = e(this);
            (n.ignoreIfBlocked && t.data("blockUI.isBlocked")) ||
              t.unblock({ fadeOut: 0 });
          }),
          this.each(function () {
            "static" == e.css(this, "position") &&
              ((this.style.position = "relative"),
              e(this).data("blockUI.static", !0)),
              (this.style.zoom = 1),
              t(this, o);
          })
        );
      }),
      (e.fn.unblock = function (t) {
        return this[0] === window
          ? (e.unblockUI(t), this)
          : this.each(function () {
              o(this, t);
            });
      }),
      (e.blockUI.version = 2.7),
      (e.blockUI.defaults = {
        message: "<h1>Please wait...</h1>",
        title: null,
        draggable: !0,
        theme: !1,
        css: {
          padding: 0,
          margin: 0,
          width: "30%",
          top: "40%",
          left: "35%",
          textAlign: "center",
          color: "#000",
          border: "3px solid #aaa",
          backgroundColor: "#fff",
          cursor: "wait",
        },
        themedCSS: { width: "30%", top: "40%", left: "35%" },
        overlayCSS: { backgroundColor: "#000", opacity: 0.6, cursor: "wait" },
        cursorReset: "default",
        growlCSS: {
          width: "350px",
          top: "10px",
          left: "",
          right: "10px",
          border: "none",
          padding: "5px",
          opacity: 0.6,
          cursor: "default",
          color: "#fff",
          backgroundColor: "#000",
          "-webkit-border-radius": "10px",
          "-moz-border-radius": "10px",
          "border-radius": "10px",
        },
        iframeSrc: /^https/i.test(window.location.href || "")
          ? "javascript:false"
          : "about:blank",
        forceIframe: !1,
        baseZ: 1e3,
        centerX: !0,
        centerY: !0,
        allowBodyStretch: !0,
        bindEvents: !0,
        constrainTabKey: !0,
        fadeIn: 200,
        fadeOut: 400,
        timeout: 0,
        showOverlay: !0,
        focusInput: !0,
        focusableElements: ":input:enabled:visible",
        onBlock: null,
        onUnblock: null,
        onOverlayClick: null,
        quirksmodeOffsetHack: 4,
        blockMsgClass: "blockMsg",
        ignoreIfBlocked: !1,
      });
    var p = null,
      b = [];
  }
  "function" == typeof define && define.amd && define.amd.jQuery
    ? define(["jquery"], e)
    : e(jQuery);
})();

/*
 * jquery-match-height 0.7.2 by @liabru
 * http://brm.io/jquery-match-height/
 * License MIT
 */
!(function (t) {
  "use strict";
  "function" == typeof define && define.amd
    ? define(["jquery"], t)
    : "undefined" != typeof module && module.exports
    ? (module.exports = t(require("jquery")))
    : t(jQuery);
})(function (t) {
  var e = -1,
    o = -1,
    n = function (t) {
      return parseFloat(t) || 0;
    },
    a = function (e) {
      var o = 1,
        a = t(e),
        i = null,
        r = [];
      return (
        a.each(function () {
          var e = t(this),
            a = e.offset().top - n(e.css("margin-top")),
            s = r.length > 0 ? r[r.length - 1] : null;
          null === s
            ? r.push(e)
            : Math.floor(Math.abs(i - a)) <= o
            ? (r[r.length - 1] = s.add(e))
            : r.push(e),
            (i = a);
        }),
        r
      );
    },
    i = function (e) {
      var o = {
        byRow: !0,
        property: "height",
        target: null,
        remove: !1,
      };
      return "object" == typeof e
        ? t.extend(o, e)
        : ("boolean" == typeof e
            ? (o.byRow = e)
            : "remove" === e && (o.remove = !0),
          o);
    },
    r = (t.fn.matchHeight = function (e) {
      var o = i(e);
      if (o.remove) {
        var n = this;
        return (
          this.css(o.property, ""),
          t.each(r._groups, function (t, e) {
            e.elements = e.elements.not(n);
          }),
          this
        );
      }
      return this.length <= 1 && !o.target
        ? this
        : (r._groups.push({ elements: this, options: o }),
          r._apply(this, o),
          this);
    });
  (r.version = "0.7.2"),
    (r._groups = []),
    (r._throttle = 80),
    (r._maintainScroll = !1),
    (r._beforeUpdate = null),
    (r._afterUpdate = null),
    (r._rows = a),
    (r._parse = n),
    (r._parseOptions = i),
    (r._apply = function (e, o) {
      var s = i(o),
        h = t(e),
        l = [h],
        c = t(window).scrollTop(),
        p = t("html").outerHeight(!0),
        u = h.parents().filter(":hidden");
      return (
        u.each(function () {
          var e = t(this);
          e.data("style-cache", e.attr("style"));
        }),
        u.css("display", "block"),
        s.byRow &&
          !s.target &&
          (h.each(function () {
            var e = t(this),
              o = e.css("display");
            "inline-block" !== o &&
              "flex" !== o &&
              "inline-flex" !== o &&
              (o = "block"),
              e.data("style-cache", e.attr("style")),
              e.css({
                display: o,
                "padding-top": "0",
                "padding-bottom": "0",
                "margin-top": "0",
                "margin-bottom": "0",
                "border-top-width": "0",
                "border-bottom-width": "0",
                height: "100px",
                overflow: "hidden",
              });
          }),
          (l = a(h)),
          h.each(function () {
            var e = t(this);
            e.attr("style", e.data("style-cache") || "");
          })),
        t.each(l, function (e, o) {
          var a = t(o),
            i = 0;
          if (s.target) i = s.target.outerHeight(!1);
          else {
            if (s.byRow && a.length <= 1) return void a.css(s.property, "");
            a.each(function () {
              var e = t(this),
                o = e.attr("style"),
                n = e.css("display");
              "inline-block" !== n &&
                "flex" !== n &&
                "inline-flex" !== n &&
                (n = "block");
              var a = {
                display: n,
              };
              (a[s.property] = ""),
                e.css(a),
                e.outerHeight(!1) > i && (i = e.outerHeight(!1)),
                o ? e.attr("style", o) : e.css("display", "");
            });
          }
          a.each(function () {
            var e = t(this),
              o = 0;
            (s.target && e.is(s.target)) ||
              ("border-box" !== e.css("box-sizing") &&
                ((o +=
                  n(e.css("border-top-width")) +
                  n(e.css("border-bottom-width"))),
                (o += n(e.css("padding-top")) + n(e.css("padding-bottom")))),
              e.css(s.property, i - o + "px"));
          });
        }),
        u.each(function () {
          var e = t(this);
          e.attr("style", e.data("style-cache") || null);
        }),
        r._maintainScroll &&
          t(window).scrollTop((c / p) * t("html").outerHeight(!0)),
        this
      );
    }),
    (r._applyDataApi = function () {
      var e = {};
      t("[data-match-height], [data-mh]").each(function () {
        var o = t(this),
          n = o.attr("data-mh") || o.attr("data-match-height");
        n in e ? (e[n] = e[n].add(o)) : (e[n] = o);
      }),
        t.each(e, function () {
          this.matchHeight(!0);
        });
    });
  var s = function (e) {
    r._beforeUpdate && r._beforeUpdate(e, r._groups),
      t.each(r._groups, function () {
        r._apply(this.elements, this.options);
      }),
      r._afterUpdate && r._afterUpdate(e, r._groups);
  };
  (r._update = function (n, a) {
    if (a && "resize" === a.type) {
      var i = t(window).width();
      if (i === e) return;
      e = i;
    }
    n
      ? o === -1 &&
        (o = setTimeout(function () {
          s(a), (o = -1);
        }, r._throttle))
      : s(a);
  }),
    t(r._applyDataApi);
  var h = t.fn.on ? "on" : "bind";
  t(window)[h]("load", function (t) {
    r._update(!1, t);
  }),
    t(window)[h]("resize orientationchange", function (t) {
      r._update(!0, t);
    });
});
/*
 *	jQuery Sliding Menu Plugin
 *	Mobile app list-style navigation in the browser
 *
 *	Written by Ali Zahid
 *	http://designplox.com/jquery-sliding-menu
 */
!(function (a) {
  var e = [];
  a.fn.slidingMenu = function (t) {
    function n(e) {
      var t = a("ul", e),
        n = [];
      return (
        a(t).each(function (e, t) {
          var r = a(t),
            s = r.prev(),
            l = i();
          if (
            (1 == s.length &&
              (s
                .addClass("nav-has-children dropdown-item")
                .attr("href", "#menu-panel-" + l),
              s.append('<i class="ft-arrow-right children-in"></i>')),
            r.attr("id", "menu-panel-" + l),
            0 == e)
          )
            r.addClass("menu-panel-root");
          else {
            r.addClass("menu-panel");
            var d =
              (a("<li></li>"),
              a("<a></a>")
                .addClass("nav-has-parent back primary dropdown-item")
                .attr("href", "#menu-panel-back"));
            r.prepend(d);
          }
          n.push(t);
        }),
        n
      );
    }
    function r(e, t) {
      var n = { id: "menu-panel-" + i(), children: [], root: t ? !1 : !0 },
        s = [];
      return (
        t && n.children.push({ styleClass: "back", href: "#" + t.id }),
        a(e).each(function (a, e) {
          if ((n.children.push(e), e.children)) {
            var t = r(e.children, n);
            (e.href = "#" + t[0].id), (e.styleClass = "nav"), (s = s.concat(t));
          }
        }),
        [n].concat(s)
      );
    }
    function i() {
      var a;
      do a = Math.random().toString(36).substring(3, 8);
      while (e.indexOf(a) >= 0);
      return e.push(a), a;
    }
    function s() {
      var e = a(".sliding-menu-wrapper"),
        t = a(".sliding-menu-wrapper ul");
      t.length &&
        setTimeout(function () {
          var n = a(l).width();
          e.width(t.length * n),
            t.each(function (e, t) {
              var r = a(t);
              r.width(n);
            }),
            e.css("margin-left", "");
        }, 300);
    }
    var l = this.selector,
      d = !1;
    "rtl" == a("html").data("textdirection") && (d = !0);
    var h = a.extend({ dataJSON: !1, backLabel: "Back" }, t);
    return this.each(function () {
      var e,
        t = this,
        i = a(t);
      if (i.hasClass("sliding-menu")) return void s();
      var l = i.outerWidth();
      (e = h.dataJSON ? r(h.dataJSON) : n(i)),
        i.empty().addClass("sliding-menu");
      var p;
      h.dataJSON
        ? a(e).each(function (e, t) {
            var n = a("<ul></ul>");
            t.root && (p = "#" + t.id),
              n.attr("id", t.id),
              n.addClass("menu-panel"),
              n.width(l),
              a(t.children).each(function (e, t) {
                var r = a("<a></a>");
                r.attr("class", t.styleClass),
                  r.attr("href", t.href),
                  r.text(t.label);
                var i = a("<li></li>");
                i.append(r), n.append(i);
              }),
              i.append(n);
          })
        : a(e).each(function (e, t) {
            var n = a(t);
            n.hasClass("menu-panel-root") && (p = "#" + n.attr("id")),
              n.width(l),
              i.append(t);
          }),
        (p = a(p)),
        p.addClass("menu-panel-root");
      var c = p;
      i.height(p.height());
      var u = a("<div></div>")
        .addClass("sliding-menu-wrapper")
        .width(e.length * l);
      return (
        i.wrapInner(u),
        (u = a(".sliding-menu-wrapper", i)),
        a("a", t).on("click", function (e) {
          var t = a(this).attr("href"),
            n = a(this).text();
          if (u.is(":animated")) return void e.preventDefault();
          if ("#" == t) e.preventDefault();
          else if (0 == t.indexOf("#menu-panel")) {
            var r,
              s,
              l = a(t),
              o = a(this).hasClass("back");
            d === !0
              ? (s = parseInt(u.css("margin-right")))
              : (r = parseInt(u.css("margin-left")));
            var f = i.width();
            a(this).closest("ul").hasClass("menu-panel-root") && (c = p),
              o
                ? ("#menu-panel-back" == t && (l = c.prev()),
                  d === !0
                    ? (properties = { marginRight: s + f })
                    : (properties = { marginLeft: r + f }),
                  u.stop(!0, !0).animate(properties, "fast"))
                : (l.insertAfter(c),
                  h.backLabel === !0
                    ? a(".back", l).html(
                        '<i class="fa fa-arrow-circle-o-left back-in"></i>' + n
                      )
                    : a(".back", l).text(h.backLabel),
                  d === !0
                    ? (properties = { marginRight: s - f })
                    : (properties = { marginLeft: r - f }),
                  u.stop(!0, !0).animate(properties, "fast")),
              (c = l),
              i.stop(!0, !0).animate({ height: l.height() }, "fast"),
              e.preventDefault();
          }
        }),
        this
      );
    });
  };
})(jQuery);

/*!
 * screenfull
 * v3.3.2 - 2017-10-27
 * (c) Sindre Sorhus; MIT License
 */

!(function () {
  "use strict";
  var a =
      "undefined" != typeof window && void 0 !== window.document
        ? window.document
        : {},
    b = "undefined" != typeof module && module.exports,
    c = "undefined" != typeof Element && "ALLOW_KEYBOARD_INPUT" in Element,
    d = (function () {
      for (
        var b,
          c = [
            [
              "requestFullscreen",
              "exitFullscreen",
              "fullscreenElement",
              "fullscreenEnabled",
              "fullscreenchange",
              "fullscreenerror",
            ],
            [
              "webkitRequestFullscreen",
              "webkitExitFullscreen",
              "webkitFullscreenElement",
              "webkitFullscreenEnabled",
              "webkitfullscreenchange",
              "webkitfullscreenerror",
            ],
            [
              "webkitRequestFullScreen",
              "webkitCancelFullScreen",
              "webkitCurrentFullScreenElement",
              "webkitCancelFullScreen",
              "webkitfullscreenchange",
              "webkitfullscreenerror",
            ],
            [
              "mozRequestFullScreen",
              "mozCancelFullScreen",
              "mozFullScreenElement",
              "mozFullScreenEnabled",
              "mozfullscreenchange",
              "mozfullscreenerror",
            ],
            [
              "msRequestFullscreen",
              "msExitFullscreen",
              "msFullscreenElement",
              "msFullscreenEnabled",
              "MSFullscreenChange",
              "MSFullscreenError",
            ],
          ],
          d = 0,
          e = c.length,
          f = {};
        d < e;
        d++
      )
        if ((b = c[d]) && b[1] in a) {
          for (d = 0; d < b.length; d++) f[c[0][d]] = b[d];
          return f;
        }
      return !1;
    })(),
    e = { change: d.fullscreenchange, error: d.fullscreenerror },
    f = {
      request: function (b) {
        var e = d.requestFullscreen;
        (b = b || a.documentElement),
          / Version\/5\.1(?:\.\d+)? Safari\//.test(navigator.userAgent)
            ? b[e]()
            : b[e](c && Element.ALLOW_KEYBOARD_INPUT);
      },
      exit: function () {
        a[d.exitFullscreen]();
      },
      toggle: function (a) {
        this.isFullscreen ? this.exit() : this.request(a);
      },
      onchange: function (a) {
        this.on("change", a);
      },
      onerror: function (a) {
        this.on("error", a);
      },
      on: function (b, c) {
        var d = e[b];
        d && a.addEventListener(d, c, !1);
      },
      off: function (b, c) {
        var d = e[b];
        d && a.removeEventListener(d, c, !1);
      },
      raw: d,
    };
  if (!d) return void (b ? (module.exports = !1) : (window.screenfull = !1));
  Object.defineProperties(f, {
    isFullscreen: {
      get: function () {
        return Boolean(a[d.fullscreenElement]);
      },
    },
    element: {
      enumerable: !0,
      get: function () {
        return a[d.fullscreenElement];
      },
    },
    enabled: {
      enumerable: !0,
      get: function () {
        return Boolean(a[d.fullscreenEnabled]);
      },
    },
  }),
    b ? (module.exports = f) : (window.screenfull = f);
})();

/*! pace 1.0.0 */
(function () {
  var a,
    b,
    c,
    d,
    e,
    f,
    g,
    h,
    i,
    j,
    k,
    l,
    m,
    n,
    o,
    p,
    q,
    r,
    s,
    t,
    u,
    v,
    w,
    x,
    y,
    z,
    A,
    B,
    C,
    D,
    E,
    F,
    G,
    H,
    I,
    J,
    K,
    L,
    M,
    N,
    O,
    P,
    Q,
    R,
    S,
    T,
    U,
    V,
    W,
    X = [].slice,
    Y = {}.hasOwnProperty,
    Z = function (a, b) {
      function c() {
        this.constructor = a;
      }
      for (var d in b) Y.call(b, d) && (a[d] = b[d]);
      return (
        (c.prototype = b.prototype),
        (a.prototype = new c()),
        (a.__super__ = b.prototype),
        a
      );
    },
    $ =
      [].indexOf ||
      function (a) {
        for (var b = 0, c = this.length; c > b; b++)
          if (b in this && this[b] === a) return b;
        return -1;
      };
  for (
    u = {
      catchupTime: 100,
      initialRate: 0.03,
      minTime: 250,
      ghostTime: 100,
      maxProgressPerFrame: 20,
      easeFactor: 1.25,
      startOnPageLoad: !0,
      restartOnPushState: !0,
      restartOnRequestAfter: 500,
      target: "body",
      elements: { checkInterval: 100, selectors: ["body"] },
      eventLag: { minSamples: 10, sampleCount: 3, lagThreshold: 3 },
      ajax: { trackMethods: ["GET"], trackWebSockets: !0, ignoreURLs: [] },
    },
      C = function () {
        var a;
        return null !=
          (a =
            "undefined" != typeof performance &&
            null !== performance &&
            "function" == typeof performance.now
              ? performance.now()
              : void 0)
          ? a
          : +new Date();
      },
      E =
        window.requestAnimationFrame ||
        window.mozRequestAnimationFrame ||
        window.webkitRequestAnimationFrame ||
        window.msRequestAnimationFrame,
      t = window.cancelAnimationFrame || window.mozCancelAnimationFrame,
      null == E &&
        ((E = function (a) {
          return setTimeout(a, 50);
        }),
        (t = function (a) {
          return clearTimeout(a);
        })),
      G = function (a) {
        var b, c;
        return (
          (b = C()),
          (c = function () {
            var d;
            return (
              (d = C() - b),
              d >= 33
                ? ((b = C()),
                  a(d, function () {
                    return E(c);
                  }))
                : setTimeout(c, 33 - d)
            );
          })()
        );
      },
      F = function () {
        var a, b, c;
        return (
          (c = arguments[0]),
          (b = arguments[1]),
          (a = 3 <= arguments.length ? X.call(arguments, 2) : []),
          "function" == typeof c[b] ? c[b].apply(c, a) : c[b]
        );
      },
      v = function () {
        var a, b, c, d, e, f, g;
        for (
          b = arguments[0],
            d = 2 <= arguments.length ? X.call(arguments, 1) : [],
            f = 0,
            g = d.length;
          g > f;
          f++
        )
          if ((c = d[f]))
            for (a in c)
              Y.call(c, a) &&
                ((e = c[a]),
                null != b[a] &&
                "object" == typeof b[a] &&
                null != e &&
                "object" == typeof e
                  ? v(b[a], e)
                  : (b[a] = e));
        return b;
      },
      q = function (a) {
        var b, c, d, e, f;
        for (c = b = 0, e = 0, f = a.length; f > e; e++)
          (d = a[e]), (c += Math.abs(d)), b++;
        return c / b;
      },
      x = function (a, b) {
        var c, d, e;
        if (
          (null == a && (a = "options"),
          null == b && (b = !0),
          (e = document.querySelector("[data-pace-" + a + "]")))
        ) {
          if (((c = e.getAttribute("data-pace-" + a)), !b)) return c;
          try {
            return JSON.parse(c);
          } catch (f) {
            return (
              (d = f),
              "undefined" != typeof console && null !== console
                ? console.error("Error parsing inline pace options", d)
                : void 0
            );
          }
        }
      },
      g = (function () {
        function a() {}
        return (
          (a.prototype.on = function (a, b, c, d) {
            var e;
            return (
              null == d && (d = !1),
              null == this.bindings && (this.bindings = {}),
              null == (e = this.bindings)[a] && (e[a] = []),
              this.bindings[a].push({ handler: b, ctx: c, once: d })
            );
          }),
          (a.prototype.once = function (a, b, c) {
            return this.on(a, b, c, !0);
          }),
          (a.prototype.off = function (a, b) {
            var c, d, e;
            if (null != (null != (d = this.bindings) ? d[a] : void 0)) {
              if (null == b) return delete this.bindings[a];
              for (c = 0, e = []; c < this.bindings[a].length; )
                e.push(
                  this.bindings[a][c].handler === b
                    ? this.bindings[a].splice(c, 1)
                    : c++
                );
              return e;
            }
          }),
          (a.prototype.trigger = function () {
            var a, b, c, d, e, f, g, h, i;
            if (
              ((c = arguments[0]),
              (a = 2 <= arguments.length ? X.call(arguments, 1) : []),
              null != (g = this.bindings) ? g[c] : void 0)
            ) {
              for (e = 0, i = []; e < this.bindings[c].length; )
                (h = this.bindings[c][e]),
                  (d = h.handler),
                  (b = h.ctx),
                  (f = h.once),
                  d.apply(null != b ? b : this, a),
                  i.push(f ? this.bindings[c].splice(e, 1) : e++);
              return i;
            }
          }),
          a
        );
      })(),
      j = window.Pace || {},
      window.Pace = j,
      v(j, g.prototype),
      D = j.options = v({}, u, window.paceOptions, x()),
      U = ["ajax", "document", "eventLag", "elements"],
      Q = 0,
      S = U.length;
    S > Q;
    Q++
  )
    (K = U[Q]), D[K] === !0 && (D[K] = u[K]);
  (i = (function (a) {
    function b() {
      return (V = b.__super__.constructor.apply(this, arguments));
    }
    return Z(b, a), b;
  })(Error)),
    (b = (function () {
      function a() {
        this.progress = 0;
      }
      return (
        (a.prototype.getElement = function () {
          var a;
          if (null == this.el) {
            if (((a = document.querySelector(D.target)), !a)) throw new i();
            (this.el = document.createElement("div")),
              (this.el.className = "pace pace-active"),
              (document.body.className = document.body.className.replace(
                /pace-done/g,
                ""
              )),
              (document.body.className += " pace-running"),
              (this.el.innerHTML =
                '<div class="pace-progress">\n  <div class="pace-progress-inner"></div>\n</div>\n<div class="pace-activity"></div>'),
              null != a.firstChild
                ? a.insertBefore(this.el, a.firstChild)
                : a.appendChild(this.el);
          }
          return this.el;
        }),
        (a.prototype.finish = function () {
          var a;
          return (
            (a = this.getElement()),
            (a.className = a.className.replace("pace-active", "")),
            (a.className += " pace-inactive"),
            (document.body.className = document.body.className.replace(
              "pace-running",
              ""
            )),
            (document.body.className += " pace-done")
          );
        }),
        (a.prototype.update = function (a) {
          return (this.progress = a), this.render();
        }),
        (a.prototype.destroy = function () {
          try {
            this.getElement().parentNode.removeChild(this.getElement());
          } catch (a) {
            i = a;
          }
          return (this.el = void 0);
        }),
        (a.prototype.render = function () {
          var a, b, c, d, e, f, g;
          if (null == document.querySelector(D.target)) return !1;
          for (
            a = this.getElement(),
              d = "translate3d(" + this.progress + "%, 0, 0)",
              g = ["webkitTransform", "msTransform", "transform"],
              e = 0,
              f = g.length;
            f > e;
            e++
          )
            (b = g[e]), (a.children[0].style[b] = d);
          return (
            (!this.lastRenderedProgress ||
              this.lastRenderedProgress | (0 !== this.progress) | 0) &&
              (a.children[0].setAttribute(
                "data-progress-text",
                "" + (0 | this.progress) + "%"
              ),
              this.progress >= 100
                ? (c = "99")
                : ((c = this.progress < 10 ? "0" : ""),
                  (c += 0 | this.progress)),
              a.children[0].setAttribute("data-progress", "" + c)),
            (this.lastRenderedProgress = this.progress)
          );
        }),
        (a.prototype.done = function () {
          return this.progress >= 100;
        }),
        a
      );
    })()),
    (h = (function () {
      function a() {
        this.bindings = {};
      }
      return (
        (a.prototype.trigger = function (a, b) {
          var c, d, e, f, g;
          if (null != this.bindings[a]) {
            for (f = this.bindings[a], g = [], d = 0, e = f.length; e > d; d++)
              (c = f[d]), g.push(c.call(this, b));
            return g;
          }
        }),
        (a.prototype.on = function (a, b) {
          var c;
          return (
            null == (c = this.bindings)[a] && (c[a] = []),
            this.bindings[a].push(b)
          );
        }),
        a
      );
    })()),
    (P = window.XMLHttpRequest),
    (O = window.XDomainRequest),
    (N = window.WebSocket),
    (w = function (a, b) {
      var c, d, e, f;
      f = [];
      for (d in b.prototype)
        try {
          (e = b.prototype[d]),
            f.push(
              null == a[d] && "function" != typeof e ? (a[d] = e) : void 0
            );
        } catch (g) {
          c = g;
        }
      return f;
    }),
    (A = []),
    (j.ignore = function () {
      var a, b, c;
      return (
        (b = arguments[0]),
        (a = 2 <= arguments.length ? X.call(arguments, 1) : []),
        A.unshift("ignore"),
        (c = b.apply(null, a)),
        A.shift(),
        c
      );
    }),
    (j.track = function () {
      var a, b, c;
      return (
        (b = arguments[0]),
        (a = 2 <= arguments.length ? X.call(arguments, 1) : []),
        A.unshift("track"),
        (c = b.apply(null, a)),
        A.shift(),
        c
      );
    }),
    (J = function (a) {
      var b;
      if ((null == a && (a = "GET"), "track" === A[0])) return "force";
      if (!A.length && D.ajax) {
        if ("socket" === a && D.ajax.trackWebSockets) return !0;
        if (((b = a.toUpperCase()), $.call(D.ajax.trackMethods, b) >= 0))
          return !0;
      }
      return !1;
    }),
    (k = (function (a) {
      function b() {
        var a,
          c = this;
        b.__super__.constructor.apply(this, arguments),
          (a = function (a) {
            var b;
            return (
              (b = a.open),
              (a.open = function (d, e) {
                return (
                  J(d) && c.trigger("request", { type: d, url: e, request: a }),
                  b.apply(a, arguments)
                );
              })
            );
          }),
          (window.XMLHttpRequest = function (b) {
            var c;
            return (c = new P(b)), a(c), c;
          });
        try {
          w(window.XMLHttpRequest, P);
        } catch (d) {}
        if (null != O) {
          window.XDomainRequest = function () {
            var b;
            return (b = new O()), a(b), b;
          };
          try {
            w(window.XDomainRequest, O);
          } catch (d) {}
        }
        if (null != N && D.ajax.trackWebSockets) {
          window.WebSocket = function (a, b) {
            var d;
            return (
              (d = null != b ? new N(a, b) : new N(a)),
              J("socket") &&
                c.trigger("request", {
                  type: "socket",
                  url: a,
                  protocols: b,
                  request: d,
                }),
              d
            );
          };
          try {
            w(window.WebSocket, N);
          } catch (d) {}
        }
      }
      return Z(b, a), b;
    })(h)),
    (R = null),
    (y = function () {
      return null == R && (R = new k()), R;
    }),
    (I = function (a) {
      var b, c, d, e;
      for (e = D.ajax.ignoreURLs, c = 0, d = e.length; d > c; c++)
        if (((b = e[c]), "string" == typeof b)) {
          if (-1 !== a.indexOf(b)) return !0;
        } else if (b.test(a)) return !0;
      return !1;
    }),
    y().on("request", function (b) {
      var c, d, e, f, g;
      return (
        (f = b.type),
        (e = b.request),
        (g = b.url),
        I(g)
          ? void 0
          : j.running || (D.restartOnRequestAfter === !1 && "force" !== J(f))
          ? void 0
          : ((d = arguments),
            (c = D.restartOnRequestAfter || 0),
            "boolean" == typeof c && (c = 0),
            setTimeout(function () {
              var b, c, g, h, i, k;
              if (
                (b =
                  "socket" === f
                    ? e.readyState < 2
                    : 0 < (h = e.readyState) && 4 > h)
              ) {
                for (
                  j.restart(), i = j.sources, k = [], c = 0, g = i.length;
                  g > c;
                  c++
                ) {
                  if (((K = i[c]), K instanceof a)) {
                    K.watch.apply(K, d);
                    break;
                  }
                  k.push(void 0);
                }
                return k;
              }
            }, c))
      );
    }),
    (a = (function () {
      function a() {
        var a = this;
        (this.elements = []),
          y().on("request", function () {
            return a.watch.apply(a, arguments);
          });
      }
      return (
        (a.prototype.watch = function (a) {
          var b, c, d, e;
          return (
            (d = a.type),
            (b = a.request),
            (e = a.url),
            I(e)
              ? void 0
              : ((c = "socket" === d ? new n(b) : new o(b)),
                this.elements.push(c))
          );
        }),
        a
      );
    })()),
    (o = (function () {
      function a(a) {
        var b,
          c,
          d,
          e,
          f,
          g,
          h = this;
        if (((this.progress = 0), null != window.ProgressEvent))
          for (
            c = null,
              a.addEventListener(
                "progress",
                function (a) {
                  return (h.progress = a.lengthComputable
                    ? (100 * a.loaded) / a.total
                    : h.progress + (100 - h.progress) / 2);
                },
                !1
              ),
              g = ["load", "abort", "timeout", "error"],
              d = 0,
              e = g.length;
            e > d;
            d++
          )
            (b = g[d]),
              a.addEventListener(
                b,
                function () {
                  return (h.progress = 100);
                },
                !1
              );
        else
          (f = a.onreadystatechange),
            (a.onreadystatechange = function () {
              var b;
              return (
                0 === (b = a.readyState) || 4 === b
                  ? (h.progress = 100)
                  : 3 === a.readyState && (h.progress = 50),
                "function" == typeof f ? f.apply(null, arguments) : void 0
              );
            });
      }
      return a;
    })()),
    (n = (function () {
      function a(a) {
        var b,
          c,
          d,
          e,
          f = this;
        for (
          this.progress = 0, e = ["error", "open"], c = 0, d = e.length;
          d > c;
          c++
        )
          (b = e[c]),
            a.addEventListener(
              b,
              function () {
                return (f.progress = 100);
              },
              !1
            );
      }
      return a;
    })()),
    (d = (function () {
      function a(a) {
        var b, c, d, f;
        for (
          null == a && (a = {}),
            this.elements = [],
            null == a.selectors && (a.selectors = []),
            f = a.selectors,
            c = 0,
            d = f.length;
          d > c;
          c++
        )
          (b = f[c]), this.elements.push(new e(b));
      }
      return a;
    })()),
    (e = (function () {
      function a(a) {
        (this.selector = a), (this.progress = 0), this.check();
      }
      return (
        (a.prototype.check = function () {
          var a = this;
          return document.querySelector(this.selector)
            ? this.done()
            : setTimeout(function () {
                return a.check();
              }, D.elements.checkInterval);
        }),
        (a.prototype.done = function () {
          return (this.progress = 100);
        }),
        a
      );
    })()),
    (c = (function () {
      function a() {
        var a,
          b,
          c = this;
        (this.progress =
          null != (b = this.states[document.readyState]) ? b : 100),
          (a = document.onreadystatechange),
          (document.onreadystatechange = function () {
            return (
              null != c.states[document.readyState] &&
                (c.progress = c.states[document.readyState]),
              "function" == typeof a ? a.apply(null, arguments) : void 0
            );
          });
      }
      return (
        (a.prototype.states = { loading: 0, interactive: 50, complete: 100 }), a
      );
    })()),
    (f = (function () {
      function a() {
        var a,
          b,
          c,
          d,
          e,
          f = this;
        (this.progress = 0),
          (a = 0),
          (e = []),
          (d = 0),
          (c = C()),
          (b = setInterval(function () {
            var g;
            return (
              (g = C() - c - 50),
              (c = C()),
              e.push(g),
              e.length > D.eventLag.sampleCount && e.shift(),
              (a = q(e)),
              ++d >= D.eventLag.minSamples && a < D.eventLag.lagThreshold
                ? ((f.progress = 100), clearInterval(b))
                : (f.progress = 100 * (3 / (a + 3)))
            );
          }, 50));
      }
      return a;
    })()),
    (m = (function () {
      function a(a) {
        (this.source = a),
          (this.last = this.sinceLastUpdate = 0),
          (this.rate = D.initialRate),
          (this.catchup = 0),
          (this.progress = this.lastProgress = 0),
          null != this.source && (this.progress = F(this.source, "progress"));
      }
      return (
        (a.prototype.tick = function (a, b) {
          var c;
          return (
            null == b && (b = F(this.source, "progress")),
            b >= 100 && (this.done = !0),
            b === this.last
              ? (this.sinceLastUpdate += a)
              : (this.sinceLastUpdate &&
                  (this.rate = (b - this.last) / this.sinceLastUpdate),
                (this.catchup = (b - this.progress) / D.catchupTime),
                (this.sinceLastUpdate = 0),
                (this.last = b)),
            b > this.progress && (this.progress += this.catchup * a),
            (c = 1 - Math.pow(this.progress / 100, D.easeFactor)),
            (this.progress += c * this.rate * a),
            (this.progress = Math.min(
              this.lastProgress + D.maxProgressPerFrame,
              this.progress
            )),
            (this.progress = Math.max(0, this.progress)),
            (this.progress = Math.min(100, this.progress)),
            (this.lastProgress = this.progress),
            this.progress
          );
        }),
        a
      );
    })()),
    (L = null),
    (H = null),
    (r = null),
    (M = null),
    (p = null),
    (s = null),
    (j.running = !1),
    (z = function () {
      return D.restartOnPushState ? j.restart() : void 0;
    }),
    null != window.history.pushState &&
      ((T = window.history.pushState),
      (window.history.pushState = function () {
        return z(), T.apply(window.history, arguments);
      })),
    null != window.history.replaceState &&
      ((W = window.history.replaceState),
      (window.history.replaceState = function () {
        return z(), W.apply(window.history, arguments);
      })),
    (l = { ajax: a, elements: d, document: c, eventLag: f }),
    (B = function () {
      var a, c, d, e, f, g, h, i;
      for (
        j.sources = L = [],
          g = ["ajax", "elements", "document", "eventLag"],
          c = 0,
          e = g.length;
        e > c;
        c++
      )
        (a = g[c]), D[a] !== !1 && L.push(new l[a](D[a]));
      for (
        i = null != (h = D.extraSources) ? h : [], d = 0, f = i.length;
        f > d;
        d++
      )
        (K = i[d]), L.push(new K(D));
      return (j.bar = r = new b()), (H = []), (M = new m());
    })(),
    (j.stop = function () {
      return (
        j.trigger("stop"),
        (j.running = !1),
        r.destroy(),
        (s = !0),
        null != p && ("function" == typeof t && t(p), (p = null)),
        B()
      );
    }),
    (j.restart = function () {
      return j.trigger("restart"), j.stop(), j.start();
    }),
    (j.go = function () {
      var a;
      return (
        (j.running = !0),
        r.render(),
        (a = C()),
        (s = !1),
        (p = G(function (b, c) {
          var d, e, f, g, h, i, k, l, n, o, p, q, t, u, v, w;
          for (
            l = 100 - r.progress, e = p = 0, f = !0, i = q = 0, u = L.length;
            u > q;
            i = ++q
          )
            for (
              K = L[i],
                o = null != H[i] ? H[i] : (H[i] = []),
                h = null != (w = K.elements) ? w : [K],
                k = t = 0,
                v = h.length;
              v > t;
              k = ++t
            )
              (g = h[k]),
                (n = null != o[k] ? o[k] : (o[k] = new m(g))),
                (f &= n.done),
                n.done || (e++, (p += n.tick(b)));
          return (
            (d = p / e),
            r.update(M.tick(b, d)),
            r.done() || f || s
              ? (r.update(100),
                j.trigger("done"),
                setTimeout(function () {
                  return r.finish(), (j.running = !1), j.trigger("hide");
                }, Math.max(D.ghostTime, Math.max(D.minTime - (C() - a), 0))))
              : c()
          );
        }))
      );
    }),
    (j.start = function (a) {
      v(D, a), (j.running = !0);
      try {
        r.render();
      } catch (b) {
        i = b;
      }
      return document.querySelector(".pace")
        ? (j.trigger("start"), j.go())
        : setTimeout(j.start, 50);
    }),
    "function" == typeof define && define.amd
      ? define(function () {
          return j;
        })
      : "object" == typeof exports
      ? (module.exports = j)
      : D.startOnPageLoad && j.start();
}.call(this));
