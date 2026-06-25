import { liteClient as algoliasearch } from 'algoliasearch/lite';
import instantsearch from 'instantsearch.js';

document.addEventListener('DOMContentLoaded', () => {
	const advancedSearchBlocks = document.querySelectorAll(
		'.wp-block-advanced-ai-search-intelligent-search'
	);

	if (advancedSearchBlocks.length < 1) {
		return;
	}

	const searchClient = algoliasearch(
		'PAVX1J59EN',
		'425e4220cf65787406a6b8303c4a701c'
	);

	advancedSearchBlocks.forEach((block, idx) => {
		const searchId = idx;

		const search = instantsearch({
			indexName: 'algolia_movie_sample_dataset',
			searchClient,
		});

		const searchReadyEvent = new CustomEvent('advanced-search:ready', {
			bubbles: true,
			detail: {
				search,
				searchId,
			},
		});

		console.log("registering search interface " + idx);
		console.log("dispatching event...")
		block.dispatchEvent(searchReadyEvent);

		if (window.advancedSearchWidgets) {
			Promise.all(window.advancedSearchWidgets ?? []).then(() => {
				search.start();
			});
		}
	});
});
