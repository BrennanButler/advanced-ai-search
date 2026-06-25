import { BlockConfiguration, registerBlockType } from '@wordpress/blocks';

import Edit from './edit';
import metadata from './block.json';
import Save from './save';

import type { Attributes } from './types';

import './style.scss';

const blockMetadata = metadata as BlockConfiguration<Attributes>;

registerBlockType(blockMetadata, {
	edit: Edit,
	save: Save,
});
