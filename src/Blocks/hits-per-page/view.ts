import { hitsPerPage } from "instantsearch.js/es/widgets";

import { registerSearchWidget } from "../../Search/search-widgets";


const hitsPerPageWidget = (node, search) => {
	return hitsPerPage({
		container: node,
		items: [
			{ label: "8 hits per page", value: 8, default: true },
			{ label: "16 hits per page", value: 16 },
		]
	})
}

registerSearchWidget({
	name: 'Hits per page',
	widgetClass: '.wp-block-advanced-ai-search-hits-per-page',
	widgetFunc: hitsPerPageWidget,
});