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
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
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
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports) {

var registerBlockType = wp.blocks.registerBlockType;


(function ($) {
	'use strict';

	registerBlockType("openai/blog-outlines", {
		title: "Write Blog outlines",
		description: "To generate blog outlines based on your title",
		icon: 'format-image',
		category: 'text',
		attributes: {
			topic: {
				type: 'string'
			},
			outlines: {
				type: 'string'
			},
			outlinesDisplay: {
				type: 'boolean',
				default: false
			}
		},
		edit: function edit(_ref) {
			var attributes = _ref.attributes,
			    setAttributes = _ref.setAttributes;


			function updateTopic(e) {
				setAttributes({ topic: e.target.value });
			}
			function fetchOutlines(e) {
				e.preventDefault();
				var data = {
					action: 'fetch_outlines',
					topic: attributes.topic
				};
				$.ajax({
					url: admin.ajax,
					type: 'post',
					data: data,
					success: function success(response) {
						if (response.status == undefined) {
							response = response.replace(/\"/g, "");
							response = response.replace(/\n/g, "&#13;&#10");
							$(".textarea_block").html('<textarea cols="80" rows="20">' + response.replace(/\"/g, "") + '</textarea>');
							// setAttributes({outlines: response});
							// setAttributes({outlinesDisplay: true});
						}
					}
				});
			}
			return wp.element.createElement(
				"div",
				null,
				wp.element.createElement("input", { placeholder: "Enter your Topic", onChange: updateTopic, type: "text", value: attributes.topic }),
				wp.element.createElement(
					"button",
					{ onClick: fetchOutlines, "class": "btn btn-primary" },
					"Fetch"
				),
				wp.element.createElement("br", null),
				wp.element.createElement("div", { "class": "textarea_block" })
			);
		},
		save: function save() {}
	});
})(jQuery);

/***/ })
/******/ ]);