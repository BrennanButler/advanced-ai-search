import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';
import metadata from './block.json';
import Save from './save';

import "./style.scss";

registerBlockType(metadata.name, {
    edit: Edit,
    save: Save,
    __experimentalLabel(attributes, { context }) {
        const { field } = attributes;

        if (!field) {
            return metadata.title + ' - Select a field to display';
        }

        return metadata.title + ' - Displaying ' + field;
    }
});