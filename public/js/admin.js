/**
 * Vvveb
 *
 * Copyright (C) 2021  Ziadin Givan
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 */
 
 
import {Router} from './common/router.js';
import {Themes} from './admin/controller/themes.js';
import {Plugins} from './admin/controller/plugins.js';
import {Table} from './admin/controller/table.js';
import {HeartBeat} from './admin/heartbeat.js';

if (Vvveb === undefined) var Vvveb = {};

window.themes = Themes;
window.plugins = Plugins;
window.table = Table;

window.delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();

//let theme = localStorage.getItem("theme", "dark");
let theme = $("html").attr("data-bs-theme");
if (theme) {
	if (theme == "dark") {
		$("#color-theme-switch i").removeClass("la-sun").addClass("la-moon");
	}
	//$("html").attr("data-bs-theme", theme);
}

let smallNav = localStorage.getItem("sidebar-size", "small-nav");
if (smallNav) {
	$("#container").addClass(smallNav);
}

//$.ajax(window.location.pathname + '?action=heartbeat');

function toggleClass(container, className) {
}

jQuery(document).ready(function() {
	Router.init();
	
	jQuery(".menu-toggle").click(function() {  
		if ($("#container").hasClass("small-nav")) {
			$("#container").removeClass("small-nav");
			smallNav = ""; 
		} else {
			smallNav = "small-nav";
			$("#container").addClass(smallNav);
		}
		
		localStorage.setItem('sidebar-size', smallNav);
	});
	
	$("#color-theme-switch").click(function () {
		
		let theme = $("html").attr("data-bs-theme");
		
		if (theme == "dark") {
			theme = "light";
			$("i",this).removeClass("la-moon").addClass("la-sun");
		} else if (theme == "light" || theme == "auto" || !theme) {
			theme = "dark";
			$("i", this).removeClass("la-sun").addClass("la-moon");
		} else {
			theme = "auto";
		}
		
		$("html").attr("data-bs-theme", theme);
		//localStorage.setItem("theme", theme);
		document.cookie = "theme=" + theme + ";path=/;";
		//serverStorage.setItem();
	});
});