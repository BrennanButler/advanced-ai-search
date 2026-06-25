import { rangeSlider } from "instantsearch.js/es/widgets";

import { registerSearchWidget } from "../../Search/search-widgets";


const rangeSliderWidget = (node, search) => {
	return rangeSlider({
		container: node,
		attribute: 'vote_average',
		min:1,
		max:10
	})
}

registerSearchWidget({
	name: 'Range slider',
	widgetClass: '.wp-block-advanced-ai-search-range-slider',
	widgetFunc: rangeSliderWidget,
});