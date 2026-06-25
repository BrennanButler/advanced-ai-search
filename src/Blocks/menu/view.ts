import { menu } from "instantsearch.js/es/widgets";

import { registerSearchWidget } from "../../Search/search-widgets";


const menuWidget = ( node, search ) => {
	return menu({
		container: node,
		attribute: 'genres'
	})
}

registerSearchWidget({
	name: 'Menu',
	widgetClass: '.wp-block-advanced-ai-search-menu',
	widgetFunc: menuWidget,
});