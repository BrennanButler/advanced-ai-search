import { hits } from "instantsearch.js/es/widgets";

import { registerSearchWidget } from "../../Search/search-widgets";


const hitsWidget = ( node, search ) => {
	return hits({
		container: node,
		templates: {
			item: node.querySelector('.hit-template').innerHTML,
		}
	})
}

registerSearchWidget({
	name: 'Hits',
	widgetClass: '.wp-block-advanced-ai-search-hits',
	widgetFunc: hitsWidget,
});
