import { infiniteHits } from "instantsearch.js/es/widgets";

import { registerSearchWidget } from "../../Search/search-widgets";


const infiniteHitsWidget = ( node, search ) => {
	return infiniteHits({
		container: node
	})
}

registerSearchWidget({
	name: 'Infinite hits',
	widgetClass: '.wp-block-advanced-ai-search-infinite-hits',
	widgetFunc: infiniteHitsWidget,
});