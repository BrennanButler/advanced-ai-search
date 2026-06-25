import { useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';

const Save = () => {
	const blockProps = useBlockProps.save();
	const innerBlocksProps = useInnerBlocksProps.save({
		className: 'search-interface',
	});

	return (
		<div {...blockProps}>
			<div {...innerBlocksProps} />
		</div>
	);
};

export default Save;
