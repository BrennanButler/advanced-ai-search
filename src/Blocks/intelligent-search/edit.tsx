import {
	useBlockProps,
	useInnerBlocksProps,
	InspectorControls,
} from '@wordpress/block-editor';

import { PanelBody, PanelRow, SelectControl, Spinner } from '@wordpress/components';

import type { BlockEditProps } from '@wordpress/blocks';

import { useMemo } from '@wordpress/element';

import { select, useSelect } from '@wordpress/data';

import type { Attributes } from './types';

import './index.scss';

type InnerBlocksOptions = Parameters<typeof useInnerBlocksProps>[1];
type BlockTemplate = NonNullable<InnerBlocksOptions>['template'];

const BLOCK_TEMPLATE = [['advanced-ai-search/hits']] satisfies BlockTemplate;

const Edit = ({ setAttributes, attributes }: BlockEditProps<Attributes>) => {
	const { searchIndex } = attributes;

	const { indexIntegrations = [], hasIndexIntegrationResolved } = useSelect(
		(select) => {
			return {
				indexIntegrations: select(
					'woo-search-plugin-integrations'
				).getPluginIndexIntegrations(),
				hasIndexIntegrationResolved: select(
					'woo-search-plugin-integrations'
				).hasFinishedResolution('getPluginIndexIntegrations'),
			}
		}
	);

	// We will get options from a selector later
	const searchIndexOptions = useMemo(
		() =>
			indexIntegrations.map((index) => {
				return {
					label: index.name,
					value: index.slug,
				};
			}),
		[ indexIntegrations ]
	);

	const blockProps = useBlockProps();

	const innerBlocksProps = useInnerBlocksProps(
		{
			className: 'search-interface',
		},
		{
			template: BLOCK_TEMPLATE,
		}
	);

	const defaultOptions = [{ label: 'Choose an index to search', value: '' }];
	const indexSelectOptions = defaultOptions.concat(searchIndexOptions);

	return (
		<>
			<InspectorControls>
				<PanelBody title="Index settings">
					<PanelRow>
						{hasIndexIntegrationResolved ? (
							<SelectControl
								label="Select an index to search"
								help="Choose an index to search through"
								value={searchIndex ?? ''}
								options={indexSelectOptions}
								onChange={(change) =>
									setAttributes({ searchIndex: change })
								}
							/>
						) : (
							<Spinner />
						)}
					</PanelRow>
				</PanelBody>
			</InspectorControls>
			<div {...blockProps}>
				{searchIndex ? (
					<div {...innerBlocksProps} />
				) : (
					<p>Select a index to search</p>
				)}
			</div>
		</>
	);
};

export default Edit;
