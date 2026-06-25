import { hierarchicalMenu } from "instantsearch.js/es/widgets";

import { registerSearchWidget } from "../../Search/search-widgets";


const hierarchicalMenuWidget = ( node, search ) => {
	return hierarchicalMenu({
		container: node,
		attributes: ['genres'] // TODO
	})
}

registerSearchWidget({
	name: 'Hierarchical menu',
	widgetClass: '.wp-block-advanced-ai-search-hierarchical-menu',
	widgetFunc: hierarchicalMenuWidget,
});