var primary = localStorage.getItem("primary") || '#6362E7';
var secondary = localStorage.getItem("secondary") || '#FFC500';
var success = localStorage.getItem("success") || '#7DC006';
var info = localStorage.getItem("info") || '#1D97FF';
var warning = localStorage.getItem("warning") || '#FF8819';
var danger = localStorage.getItem("danger") || '#E52727';
var light_1 = localStorage.getItem("light-1") || '#8F97B2';


window.zetaAdminConfig = {
	// Theme Primary Color
	primary: primary,
	// theme secondary color
	secondary: secondary,
	success: success,
	info: info,
	warning: warning,
	danger: danger ,
	light_1: light_1
};




"use strict";
let config = {
    colors: {
      primary: "#7367f0",
      secondary: "#a8aaae",
      success: "#28c76f",
      info: "#00cfe8",
      warning: "#ff9f43",
      danger: "#ea5455",
      dark: "#4b4b4b",
      black: "#000",
      white: "#fff",
      cardColor: "#fff",
      bodyBg: "#f8f7fa",
      bodyColor: "#6f6b7d",
      headingColor: "#5d596c",
      textMuted: "#a5a3ae",
      borderColor: "#dbdade",
    },
    colors_label: {
      primary: "#7367f029",
      secondary: "#a8aaae29",
      success: "#28c76f29",
      info: "#00cfe829",
      warning: "#ff9f4329",
      danger: "#ea545529",
      dark: "#4b4b4b29",
    },
    colors_dark: {
      cardColor: "#2f3349",
      bodyBg: "#25293c",
      bodyColor: "#b6bee3",
      headingColor: "#cfd3ec",
      textMuted: "#7983bb",
      borderColor: "#434968",
    },
    enableMenuLocalStorage: !0,
  },
  assetsPath = document.documentElement.getAttribute("data-assets-path"),
  templateName = document.documentElement.getAttribute("data-template"),
  rtlSupport = !0;
"undefined" != typeof TemplateCustomizer &&
  (window.templateCustomizer = new TemplateCustomizer({
    cssPath: assetsPath + "vendor/css" + (rtlSupport ? "/rtl" : "") + "/",
    themesPath: assetsPath + "vendor/css" + (rtlSupport ? "/rtl" : "") + "/",
    displayCustomizer: !0,
    defaultShowDropdownOnHover: !0,
  }));
