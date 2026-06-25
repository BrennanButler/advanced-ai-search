import { pagination } from "instantsearch.js/es/widgets";

import { registerSearchWidget } from "../../Search/search-widgets";


const paginationWidget = (node, search) => {
	return pagination({
		container: node
	})
}

registerSearchWidget({
	name: 'Toggle refinement',
	widgetClass: '.wp-block-advanced-ai-search-pagination',
	widgetFunc: paginationWidget,
});