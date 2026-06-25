import { clearRefinements } from "instantsearch.js/es/widgets";

import { registerSearchWidget } from "../../Search/search-widgets";


const clearRefinementsWidget = ( node, search ) => {
	return clearRefinements({
		container: node
	})
}

registerSearchWidget({
	name: 'Clear refinements',
	widgetClass: '.wp-block-advanced-ai-search-clear-refinements',
	widgetFunc: clearRefinementsWidget,
});