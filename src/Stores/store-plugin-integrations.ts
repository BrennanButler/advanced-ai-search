import { createReduxStore, register } from '@wordpress/data';
import apiFetch from '@wordpress/api-fetch';

const DEFAULT_STATE = {
	indexIntegrations: [],
	recordServiceIntegrations: []
};

const actions = {

	setIndexIntegrations(integrations) {
		return {
			type: "SET_INDEX_INTEGRATIONS",
			integrations
		}
	},

	setRecordServiceIntegrations(integrations) {
		return {
			type: "SET_RECORD_SERVICE_INTEGRATIONS",
			integrations
		}
	}
};


const store = createReduxStore("woo-search-plugin-integrations", {

	reducer(state = DEFAULT_STATE, action) {

		switch (action.type) {

			case "SET_INDEX_INTEGRATIONS":
				return {
					...state,
					indexIntegrations: [
						...action.integrations
					]
				}
			case "SET_RECORD_SERVICE_INTEGRATIONS":
				return {
					...state,
					recordServiceIntegrations: [
						...action.integrations
					]
				}
		}

		return state;
	},

	actions,

	selectors: {
		getPluginIndexIntegrations( state ) {

			const { indexIntegrations } = state;

			return indexIntegrations;
		},
		getPluginRecordServiceIntegrations( state ) {
			const { recordServiceIntegrations } = state;

			return recordServiceIntegrations;
		}
	},

	resolvers: {
		
		getPluginIndexIntegrations: ( ) => async ({ dispatch }) => { {
			const path = '/woo-search/v1/index-types/';
            const integrations = await apiFetch( { path } );
			console.log("here is the response from the server");
			console.log(integrations);
            dispatch.setIndexIntegrations( integrations );
		}},

		getPluginRecordServiceIntegrations: ( ) => async ({ dispatch }) => { {
			const path = '/woo-search/v1/record-services/';
            const integrations = await apiFetch( { path } );
			
            dispatch.setRecordServiceIntegrations( integrations );
		}}
	}
});

register(store);