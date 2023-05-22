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
 


import {ServerComponent} from '../server-component.js';

class MenuComponent extends ServerComponent{
	constructor () {
		super();

		this.name = "Menu";
		this.attributes = ["data-v-component-menu"],
		//this.userServerTemplate = true,

		this.image ="icons/menu.svg";
		this.html = '<div class="mb-3"><label>Your response:</label><textarea class="form-control"></textarea></div>';
		
		this.properties = [{
			name: "Menu to display",
			group:"automatic",
			key: "order",
			col:12,
			inline:false,
			htmlAttr:"data-v-menu_id",
			inputtype: SelectInput,
			data: {
				options: [{
					value: "1",
					text: "Default"
				}, {
					value: "2",
					text: "Date added 1"
				}, {
					value: "3",
					text: "Date added"
				}, {
					value: "4",
					text: "Date modified"
				}, {
					value: "5",
					text: "Sales"
				}]
			}
		}];
	}

    init(node)
	{
		$('.mb-3[data-group]').attr('style','display:none !important');
		
		let source = node.dataset.vSource;
		if (!source) {
			source = "automatic";
		} 
		
		$('.mb-3[data-group="'+ source + '"]').attr('style','');
	}
}

let menuComponent = new MenuComponent;

export {
  menuComponent
};
