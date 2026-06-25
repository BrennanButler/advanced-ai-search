import { stats } from "instantsearch.js/es/widgets";

import { registerSearchWidget } from "../../Search/search-widgets";


const statsWidget = ( node, search ) => {
	return stats({
		container: node
	})
}

registerSearchWidget({
	name: 'Query stats',
	widgetClass: '.wp-block-advanced-ai-search-query-stats',
	widgetFunc: statsWidget,
});