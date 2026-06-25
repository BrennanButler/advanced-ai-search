import { numericMenu } from "instantsearch.js/es/widgets";

import { registerSearchWidget } from "../../Search/search-widgets";


const numericMenuWidget = (node, search) => {
	return numericMenu({
		container: node,
		attribute: 'vote_count',
		/**
		 * TODO: We should fetch via the server what values should be used here
		 */
		items: [
			{ label: "Default" },
			{ label: "Less than 500", end: 500 },
			{ label: "Between 500 - 1000", start: 500, end: 1000 },
			{ label: "More than 1000", start: 1000 },
		]
	})
}

registerSearchWidget({
	name: 'Numeric menu',
	widgetClass: '.wp-block-advanced-ai-search-numeric-menu',
	widgetFunc: numericMenuWidget,
});