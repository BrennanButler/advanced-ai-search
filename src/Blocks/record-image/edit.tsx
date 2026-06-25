import {
	useBlockProps,
	useInnerBlocksProps,
	RichText
} from "@wordpress/block-editor";

import {
	useEffect
} from "@wordpress/element";

import "./index.scss";

const BLOCK_TEMPLATE = [
	["core/paragraph"]
];

const Edit = (props) => {
	const {
		context,
		setAttributes,
		attributes: { title, figCaption }
	} = props;

	useEffect(() => {
		if (!title) {
			setAttributes({ title: context["advanced-ai-search/search-id"] })
		}
	}, [])

	const recordImages = context["images"];

	const { style } = useBlockProps();

	const innerBlocksProps = useInnerBlocksProps(
		{
			className: "wp-block-advanced-ai-search-record-image__content-container",
			style
		},
		{
			template: BLOCK_TEMPLATE
		}
	)

	const rootStyle = {
		padding: "0", // only add padding on child block container
	}

	return (
		<div {...useBlockProps()} style={ rootStyle }>
			<figure className="wp-block-advanced-ai-search-record-image__figure">
				<picture className="wp-block-advanced-ai-search-record-image__picture">
					<img src="http://localhost:8032/wp-content/uploads/woocommerce-placeholder.webp" alt="" />
				</picture>
				<figcaption>
					<RichText
						tagName="span"
						value={ figCaption }
						onChange={ ( change ) => setAttributes({ figCaption: change })}
						placeholder="Image caption"
					/>
				</figcaption>
			</figure>

			<div {...innerBlocksProps} />
		</div>
	)
}

export default Edit;