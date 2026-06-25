import {
	useBlockProps,
	InspectorControls
} from "@wordpress/block-editor";

import {
	PanelBody,
	PanelRow,
	SelectControl
} from "@wordpress/components";


import "./index.scss";

const Edit = (props) => {
	return (
		<>
			<InspectorControls>
				<PanelBody title="Hits settings">
					<PanelRow>
						<p>Hits settings</p>
					</PanelRow>
				</PanelBody>
			</InspectorControls>
			<div { ...useBlockProps() }>Hello from hits</div>
		</>
	)
}

export default Edit;