import { createReduxStore, register } from '@wordpress/data';
import apiFetch from '@wordpress/api-fetch';

const DEFAULT_STATE = {
	indicies: [],
	indexTypes: []
};

const actions = {

	setIndicies(indicies) {
		return {
			type: "SET_INDICIES",
			indicies
		}
	},

	setIndexTypes( types ) {
		return {
			type: "SET_INDEX_TYPES",
			types
		}
	},

	addIndex(index) {
		return {
			type: "ADD_INDEX",
			index
		}
	},

    async createIndex(index) {

        const path = '/woo-search/v1/indicies/';

        const response = await apiFetch({
            path,
            method: 'POST',
            data: index
        });

        console.log(response);

        return {
            type: "CREATE_INDEX",
            index
        }
    }
};


const store = createReduxStore("woo-search-indicies", {

	reducer(state = DEFAULT_STATE, action) {

		switch (action.type) {

			case "SET_INDICIES":
				return {
					...state,
					indicies: [
						...action.indicies
					]
				}
			case "SET_INDEX_TYPES":
				return {
					...state,
					indexTypes: action.types
				};
			case "ADD_INDEX":
				return {
					...state,
					indicies: [
                        ...state.indicies,
						...action.index
					]
				}
            case "CREATE_INDEX":
                return {
                    ...state,
                    indicies: [
                        ...state.indicies,
                        action.index
                    ]
                }
		}

		return state;
	},

	actions,

	selectors: {
		getIndexTypes( state ) {
			const { indexTypes } = state;

			return indexTypes;
		},
		getIndex( state, slug ) {

			const { indicies } = state;

            const index = indicies.filter((element) => {
                return slug === element.slug;
            });

            
			return 0 === index.length ? null : index[0];
		},
		getIndicies( state ) {
			const { indicies } = state;

			return indicies;
		}
	},

	resolvers: {
		
        getIndex: ( ) => async ({ dispatch }) => { {
			const path = '/woo-search/v1/index-types/';
            const indicies = await apiFetch( { path } );
			console.log("here is the response from the server");
			console.log(indicies);
            dispatch.setIndicies( indicies );
		}},

		getIndexTypes: ( ) => async ({ dispatch }) => { {
			const path = '/woo-search/v1/index-types/';
            const indexTypes = await apiFetch( { path } );
			console.log("here is the response from the server");
			console.log(indexTypes);
            dispatch.setIndexTypes( indexTypes );
		}},

		getIndicies: ( ) => async ({ dispatch }) => { {
			const path = '/woo-search/v1/indicies/';
            const indicies = await apiFetch( { path } );
			console.log("here is the response from the server");
			console.log(indicies);
            dispatch.setIndicies( indicies );
		}},
	}
});

register(store);