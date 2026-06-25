import { rangeInput } from "instantsearch.js/es/widgets";

import { registerSearchWidget } from "../../Search/search-widgets";


const rangeInputWidget = (node, search) => {
	return rangeInput({
		container: node,
		attribute: 'vote_count',
		min:10,
		max:500
	})
}

registerSearchWidget({
	name: 'Range input',
	widgetClass: '.wp-block-advanced-ai-search-range-input',
	widgetFunc: rangeInputWidget,
});