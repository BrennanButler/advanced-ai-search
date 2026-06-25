import { toggleRefinement } from "instantsearch.js/es/widgets";

import { registerSearchWidget } from "../../Search/search-widgets";


const toggleRefinementWidget = (node, search) => {
	return toggleRefinement({
		container: node,
		attribute: 'genres',
	})
}

registerSearchWidget({
	name: 'Toggle refinement',
	widgetClass: '.wp-block-advanced-ai-search-toggle-refinement',
	widgetFunc: toggleRefinementWidget,
});