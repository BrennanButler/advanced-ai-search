import { createReduxStore, register } from '@wordpress/data';
import apiFetch from '@wordpress/api-fetch';

const DEFAULT_STATE = {
	services: null
};

const actions = {

	setRecordServices(services) {
		return {
			type: "SET_SERVICES",
			services
		}
	},

};


const store = createReduxStore("woo-search-record-services", {

	reducer(state = DEFAULT_STATE, action) {

		switch (action.type) {

			case "SET_SERVICES":
				return {
					...state,
					services: [
						...action.services
					]
				}
		}

		return state;
	},

	actions,

	selectors: {
		getService( state, name ) {

			const { services } = state;

            const service = services.filter((element) => {
                return name === element.name;
            });

            
			return service;
		},
		getServices( state ) {
			const { services } = state;

			return services;
		}
	},

	resolvers: {
		
        getService: ( ) => async ({ dispatch }) => { {
			const path = '/woo-search/v1/record-services/';
            const services = await apiFetch( { path } );
            dispatch.setRecordServices( services );
		}},

		getServices: ( ) => async ({ dispatch }) => { {
			const path = '/woo-search/v1/record-services/';
            const services = await apiFetch( { path } );
            dispatch.setRecordServices( services );
		}},
	}
});

register(store);