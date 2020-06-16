/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./js/admin-general.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./js/admin-general.js":
/*!*****************************!*\
  !*** ./js/admin-general.js ***!
  \*****************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* WEBPACK VAR INJECTION */(function(global) {/* harmony import */ var _modules_tooltips__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./modules/tooltips */ "./js/modules/tooltips.js");
/* harmony import */ var _modules_ac_section__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./modules/ac-section */ "./js/modules/ac-section.js");
/* harmony import */ var _modules_ac_pointer__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./modules/ac-pointer */ "./js/modules/ac-pointer.js");



global.AdminColumns = typeof AdminColumns !== "undefined" ? AdminColumns : {};
jQuery(document).ready(function ($) {
  if ($('#cpac').length === 0) {
    return false;
  }

  ac_pointers();
  ac_help($);
  document.querySelectorAll('.ac-section').forEach(function (el) {
    new _modules_ac_section__WEBPACK_IMPORTED_MODULE_1__["default"](el);
  });
});
/*
 * WP Pointer
 *
 */

global.ac_pointers = function () {
  var $ = jQuery;
  document.querySelectorAll('.ac-pointer').forEach(function (element) {
    new _modules_ac_pointer__WEBPACK_IMPORTED_MODULE_2__["default"](element);
  });
  $('.ac-wp-pointer').hover(function () {
    $(this).addClass('hover');
  }, function () {
    $(this).removeClass('hover');
    $('.ac-pointer').trigger('close');
  }).on('click', '.close', function () {
    $('.ac-pointer').removeClass('open');
  });
  new _modules_tooltips__WEBPACK_IMPORTED_MODULE_0__["default"]();
};

global.ac_pointer = function (el) {
  new _modules_ac_pointer__WEBPACK_IMPORTED_MODULE_2__["default"](el);
};
/*
 * Help
 *
 * usage: <a href="javascript:;" class="help" data-help="tab-2"></a>
 */


function ac_help($) {
  $('a.help').click(function (e) {
    e.preventDefault();
    var panel = $('#contextual-help-wrap');
    panel.parent().show();
    $('a[href="#tab-panel-cpac-' + $(this).attr('data-help') + '"]', panel).trigger('click');
    panel.slideDown('fast', function () {
      panel.focus();
    });
  });
}
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../node_modules/webpack/buildin/global.js */ "./node_modules/webpack/buildin/global.js")))

/***/ }),

/***/ "./js/modules/ac-pointer.js":
/*!**********************************!*\
  !*** ./js/modules/ac-pointer.js ***!
  \**********************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return Pointer; });
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var Pointer = /*#__PURE__*/function () {
  function Pointer(el) {
    _classCallCheck(this, Pointer);

    this.el = el;
    this.settings = this.getDefaults();
    this.init();
    this.setInitialized();
  }

  _createClass(Pointer, [{
    key: "setInitialized",
    value: function setInitialized() {
      this.el.dataset.ac_pointer_initialized = 1;
    }
  }, {
    key: "getDefaults",
    value: function getDefaults() {
      return {
        width: this.el.getAttribute('data-width') ? this.el.getAttribute('data-width') : 250,
        noclick: this.el.getAttribute('data-noclick') ? this.el.getAttribute('data-noclick') : false,
        position: this.getPosition()
      };
    }
  }, {
    key: "isInitialized",
    value: function isInitialized() {
      return this.el.dataset.hasOwnProperty('ac_pointer_initialized');
    }
  }, {
    key: "init",
    value: function init() {
      if (this.isInitialized()) {
        return;
      } // create pointer


      jQuery(this.el).pointer({
        content: this.getRelatedHTML(),
        position: this.settings.position,
        pointerWidth: this.settings.width,
        pointerClass: this.getPointerClass()
      });
      this.initEvents();
    }
  }, {
    key: "getPosition",
    value: function getPosition() {
      var position = {
        at: 'left top',
        // position of wp-pointer relative to the element which triggers the pointer event
        my: 'right top',
        // position of wp-pointer relative to the at-coordinates
        edge: 'right' // position of arrow

      };
      var pos = this.el.getAttribute('data-pos');
      var edge = this.el.getAttribute('data-pos_edge');

      if ('right' === pos) {
        position = {
          at: 'right middle',
          my: 'left middle',
          edge: 'left'
        };
      }

      if ('right_bottom' === pos) {
        position = {
          at: 'right middle',
          my: 'left bottom',
          edge: 'none'
        };
      }

      if ('left' === pos) {
        position = {
          at: 'left middle',
          my: 'right middle',
          edge: 'right'
        };
      }

      if (edge) {
        position.edge = edge;
      }

      return position;
    }
  }, {
    key: "getPointerClass",
    value: function getPointerClass() {
      var classes = ['ac-wp-pointer', 'wp-pointer', 'wp-pointer-' + this.settings.position.edge];

      if (this.settings.noclick) {
        classes.push('noclick');
      }

      return classes.join(' ');
    }
  }, {
    key: "getRelatedHTML",
    value: function getRelatedHTML() {
      var related_element = document.getElementById(this.el.getAttribute('rel'));
      return related_element ? related_element.innerHTML : '';
    }
  }, {
    key: "initEvents",
    value: function initEvents() {
      var el = jQuery(this.el); // click

      if (!this.settings.noclick) {
        el.click(function () {
          if (el.hasClass('open')) {
            el.removeClass('open');
          } else {
            el.addClass('open');
          }
        });
      }

      el.click(function () {
        el.pointer('open');
      });
      el.mouseenter(function () {
        el.pointer('open');
        setTimeout(function () {
          el.pointer('open');
        }, 2);
      });
      el.mouseleave(function () {
        setTimeout(function () {
          if (!el.hasClass('open') && jQuery('.ac-wp-pointer.hover').length === 0) {
            el.pointer('close');
          }
        }, 1);
      });
      el.on('close', function () {
        setTimeout(function () {
          if (!el.hasClass('open')) {
            el.pointer('close');
          }
        });
      });
    }
  }]);

  return Pointer;
}();



/***/ }),

/***/ "./js/modules/ac-section.js":
/*!**********************************!*\
  !*** ./js/modules/ac-section.js ***!
  \**********************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return AcSection; });
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var Cookies = __webpack_require__(/*! js-cookie */ "./node_modules/js-cookie/src/js.cookie.js");

var AcSection = /*#__PURE__*/function () {
  function AcSection(el) {
    _classCallCheck(this, AcSection);

    this.element = el;
    this.init();
  }

  _createClass(AcSection, [{
    key: "init",
    value: function init() {
      var _this = this;

      if (this.element.classList.contains('-closable')) {
        var header = this.element.querySelector('.ac-section__header');

        if (header) {
          header.addEventListener('click', function () {
            _this.toggle();
          });
        }

        if (this.isStorable()) {
          var setting = Cookies.get(this.getCookieKey());

          if (setting !== undefined) {
            parseInt(setting) === 1 ? this.open : this.close();
          }
        }
      }
    }
  }, {
    key: "getCookieKey",
    value: function getCookieKey() {
      return "ac-section_".concat(this.getSectionId());
    }
  }, {
    key: "getSectionId",
    value: function getSectionId() {
      return this.element.dataset.section;
    }
  }, {
    key: "isStorable",
    value: function isStorable() {
      return typeof this.element.dataset.section !== 'undefined';
    }
  }, {
    key: "toggle",
    value: function toggle() {
      this.isOpen() ? this.close() : this.open();
    }
  }, {
    key: "isOpen",
    value: function isOpen() {
      return !this.element.classList.contains('-closed');
    }
  }, {
    key: "open",
    value: function open() {
      this.element.classList.remove('-closed');

      if (this.isStorable()) {
        Cookies.set(this.getCookieKey(), 1);
      }
    }
  }, {
    key: "close",
    value: function close() {
      this.element.classList.add('-closed');

      if (this.isStorable()) {
        Cookies.set(this.getCookieKey(), 0);
      }
    }
  }]);

  return AcSection;
}();



/***/ }),

/***/ "./js/modules/tooltips.js":
/*!********************************!*\
  !*** ./js/modules/tooltips.js ***!
  \********************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var Tooltips = /*#__PURE__*/function () {
  function Tooltips() {
    _classCallCheck(this, Tooltips);

    this.isEnabled = typeof jQuery.fn.qtip !== 'undefined';
    this.init();
  }

  _createClass(Tooltips, [{
    key: "init",
    value: function init() {
      if (!this.isEnabled) {
        console.log('Tooltips not loaded!');
        return;
      }

      jQuery('[data-ac-tip]').qtip({
        content: {
          attr: 'data-ac-tip'
        },
        position: {
          my: 'top center',
          at: 'bottom center'
        },
        style: {
          tip: true,
          classes: 'qtip-tipsy'
        }
      });
    }
  }]);

  return Tooltips;
}();

/* harmony default export */ __webpack_exports__["default"] = (Tooltips);

/***/ }),

/***/ "./node_modules/js-cookie/src/js.cookie.js":
/*!*************************************************!*\
  !*** ./node_modules/js-cookie/src/js.cookie.js ***!
  \*************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_RESULT__;/*!
 * JavaScript Cookie v2.2.1
 * https://github.com/js-cookie/js-cookie
 *
 * Copyright 2006, 2015 Klaus Hartl & Fagner Brack
 * Released under the MIT license
 */
;(function (factory) {
	var registeredInModuleLoader;
	if (true) {
		!(__WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.call(exports, __webpack_require__, exports, module)) :
				__WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
		registeredInModuleLoader = true;
	}
	if (true) {
		module.exports = factory();
		registeredInModuleLoader = true;
	}
	if (!registeredInModuleLoader) {
		var OldCookies = window.Cookies;
		var api = window.Cookies = factory();
		api.noConflict = function () {
			window.Cookies = OldCookies;
			return api;
		};
	}
}(function () {
	function extend () {
		var i = 0;
		var result = {};
		for (; i < arguments.length; i++) {
			var attributes = arguments[ i ];
			for (var key in attributes) {
				result[key] = attributes[key];
			}
		}
		return result;
	}

	function decode (s) {
		return s.replace(/(%[0-9A-Z]{2})+/g, decodeURIComponent);
	}

	function init (converter) {
		function api() {}

		function set (key, value, attributes) {
			if (typeof document === 'undefined') {
				return;
			}

			attributes = extend({
				path: '/'
			}, api.defaults, attributes);

			if (typeof attributes.expires === 'number') {
				attributes.expires = new Date(new Date() * 1 + attributes.expires * 864e+5);
			}

			// We're using "expires" because "max-age" is not supported by IE
			attributes.expires = attributes.expires ? attributes.expires.toUTCString() : '';

			try {
				var result = JSON.stringify(value);
				if (/^[\{\[]/.test(result)) {
					value = result;
				}
			} catch (e) {}

			value = converter.write ?
				converter.write(value, key) :
				encodeURIComponent(String(value))
					.replace(/%(23|24|26|2B|3A|3C|3E|3D|2F|3F|40|5B|5D|5E|60|7B|7D|7C)/g, decodeURIComponent);

			key = encodeURIComponent(String(key))
				.replace(/%(23|24|26|2B|5E|60|7C)/g, decodeURIComponent)
				.replace(/[\(\)]/g, escape);

			var stringifiedAttributes = '';
			for (var attributeName in attributes) {
				if (!attributes[attributeName]) {
					continue;
				}
				stringifiedAttributes += '; ' + attributeName;
				if (attributes[attributeName] === true) {
					continue;
				}

				// Considers RFC 6265 section 5.2:
				// ...
				// 3.  If the remaining unparsed-attributes contains a %x3B (";")
				//     character:
				// Consume the characters of the unparsed-attributes up to,
				// not including, the first %x3B (";") character.
				// ...
				stringifiedAttributes += '=' + attributes[attributeName].split(';')[0];
			}

			return (document.cookie = key + '=' + value + stringifiedAttributes);
		}

		function get (key, json) {
			if (typeof document === 'undefined') {
				return;
			}

			var jar = {};
			// To prevent the for loop in the first place assign an empty array
			// in case there are no cookies at all.
			var cookies = document.cookie ? document.cookie.split('; ') : [];
			var i = 0;

			for (; i < cookies.length; i++) {
				var parts = cookies[i].split('=');
				var cookie = parts.slice(1).join('=');

				if (!json && cookie.charAt(0) === '"') {
					cookie = cookie.slice(1, -1);
				}

				try {
					var name = decode(parts[0]);
					cookie = (converter.read || converter)(cookie, name) ||
						decode(cookie);

					if (json) {
						try {
							cookie = JSON.parse(cookie);
						} catch (e) {}
					}

					jar[name] = cookie;

					if (key === name) {
						break;
					}
				} catch (e) {}
			}

			return key ? jar[key] : jar;
		}

		api.set = set;
		api.get = function (key) {
			return get(key, false /* read as raw */);
		};
		api.getJSON = function (key) {
			return get(key, true /* read as json */);
		};
		api.remove = function (key, attributes) {
			set(key, '', extend(attributes, {
				expires: -1
			}));
		};

		api.defaults = {};

		api.withConverter = init;

		return api;
	}

	return init(function () {});
}));


/***/ }),

/***/ "./node_modules/webpack/buildin/global.js":
/*!***********************************!*\
  !*** (webpack)/buildin/global.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports) {

var g;

// This works in non-strict mode
g = (function() {
	return this;
})();

try {
	// This works if eval is allowed (see CSP)
	g = g || new Function("return this")();
} catch (e) {
	// This works if the window reference is available
	if (typeof window === "object") g = window;
}

// g can still be undefined, but nothing to do about it...
// We return undefined, instead of nothing here, so it's
// easier to handle this case. if(!global) { ...}

module.exports = g;


/***/ })

/******/ });
//# sourceMappingURL=admin-general.js.map