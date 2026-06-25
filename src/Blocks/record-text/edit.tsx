import {
	InspectorControls,
	useBlockProps
} from "@wordpress/block-editor";

import {
	useSelect
} from "@wordpress/data";

import { useMemo } from '@wordpress/element';

import {
	PanelBody,
	PanelRow,
	SelectControl,
	Spinner
} from '@wordpress/components';

import "./index.scss";

const Edit = (props) => {
	const {
		context,
		setAttributes,
		attributes: { field }
	} = props;

	const indexSlug = context['advanced-ai-search/search-index'];

	const { index = {}, hasIndexResolved } = useSelect(
		(select) => {
			return {
				index: select(
					'woo-search-indicies'
				).getIndex(indexSlug),
				hasIndexResolved: select(
					'woo-search-indicies'
				).hasFinishedResolution('getIndex'),
			}
		}
	);

	
	console.log("here are the index attributes");
	console.log(index?.attributes);

	const defaultOptions = [{ label: 'Choose an attribute', value: '' }];
	const indexAttributeOptions = defaultOptions.concat(index?.attributes ?? []);

	const recordTitle = context["title"];

	return (
		<>
			<InspectorControls>
				<PanelBody title="Attribute">
					<PanelRow>
						{hasIndexResolved ? (
							<SelectControl
								label="Attribute to display"
								help="Choose an attribute from your data to display"
								value={field}
								options={indexAttributeOptions}
								onChange={(change) =>
									setAttributes({ field: change })
								}
							/>
						) : (
							<Spinner />
						)}
					</PanelRow>
				</PanelBody>
			</InspectorControls>
			<div {...useBlockProps()}>{recordTitle}</div>
		</>
	)
}

export default Edit;