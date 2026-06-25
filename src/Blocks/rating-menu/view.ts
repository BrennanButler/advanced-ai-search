import { ratingMenu } from "instantsearch.js/es/widgets";

import { registerSearchWidget } from "../../Search/search-widgets";


const ratingMenuWidget = (node, search) => {
	return ratingMenu({
		container: node,
		attribute: 'vote_average',
	})
}

registerSearchWidget({
	name: 'Rating menu',
	widgetClass: '.wp-block-advanced-ai-search-rating-menu',
	widgetFunc: ratingMenuWidget,
});