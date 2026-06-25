import { currentRefinements } from "instantsearch.js/es/widgets";

import { registerSearchWidget } from "../../Search/search-widgets";


const currentRefinementsWidget = (node, search) => {
	return currentRefinements({
		container: node,
	})
}

registerSearchWidget({
	name: 'Current refinements',
	widgetClass: '.wp-block-advanced-ai-search-current-refinements',
	widgetFunc: currentRefinementsWidget,
});