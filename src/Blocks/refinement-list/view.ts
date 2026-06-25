import { refinementList } from "instantsearch.js/es/widgets";

import { registerSearchWidget } from "../../Search/search-widgets";


const refinementListWidget = ( node, search ) => {
	return refinementList({
		container: node,
		attribute: 'genres'
	})
}

registerSearchWidget({
	name: 'Refinement list',
	widgetClass: '.wp-block-advanced-ai-search-refinement-list',
	widgetFunc: refinementListWidget,
});