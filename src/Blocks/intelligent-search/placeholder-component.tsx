import {
	SelectControl,
	Button
} from "@wordpress/components";

import {
	useState
} from "@wordpress/element";

import BlockPlaceholder from "../../Components/BlockPlaceholder";

const PlaceholderComponent = props => {
	const {
		setAttributes
	} = props;

	const [ searchIndexSelected, setSearchIndexSelected ] = useState("algolia_movie_sample_dataset");

	return (
		<BlockPlaceholder label="My block placeholder" instructions="My instructions">
			<SearchIndexSelector
				value={ searchIndexSelected }
				onChange={ ( change ) => setSearchIndexSelected( change ) }
			/>
			<Button
				variant="primary"
				onClick={ () => setAttributes( { searchIndex: searchIndexSelected } ) }
			>
				Select index
			</Button>
		</BlockPlaceholder>
	);
};


const SearchIndexSelector = ({
	value,
	onChange
}) => {

	
	return (
		<SelectControl
			label="Search index"
			help="Select an index to perform search operations on"
			options={ [ { label: 'Movies index', value: 'algolia_movie_sample_dataset' } ] }
			value={ value }
			onChange={ onChange }
		/>
	)
}

export default PlaceholderComponent;