import {
	BlockContextProvider,
	useInnerBlocksProps,
	__experimentalUseBlockPreview as useBlockPreview
} from "@wordpress/block-editor";

import { memo, useMemo, useState } from "@wordpress/element";


const BlockRecordTemplateProvider = ({
	data,
	contextFn,
	recordBlockTemplate,
	childBlocks,
	className = "",
}) => {

	const [activeBlockContextId, setActiveBlockContextId] = useState();


	// Setup the context for each block preview in the template
	const blockContexts = useMemo(
		() =>
			data?.map((record: any) => contextFn(record)),
		[data]
	);

	console.log(blockContexts);

	return (
		<ul className={className}>
			{blockContexts && blockContexts.map(blockContext => (
				<BlockContextProvider
					key={blockContext.key}
					value={blockContext}
				>
					{blockContext.key === (activeBlockContextId || blockContexts[0]?.key) ? (
						<ActiveContextBlockInnerBlocks template={recordBlockTemplate} />
					) : null}

					{blockContext.key !== (activeBlockContextId || blockContexts[0]?.key) && (
						<MemoizedPreviewContextBlock
							blocks={childBlocks}
							blockContextId={blockContext.key}
							setActiveBlockContextId={setActiveBlockContextId}
							isHidden={
								blockContext.recordId == (activeBlockContextId || blockContexts[0]?.key)
							}
						/>
					)}

				</BlockContextProvider>
			))}
		</ul>
	)
};


const ActiveContextBlockInnerBlocks = ({
	className = "",
	template
}) => {

	const innerBlocksProps = useInnerBlocksProps(
		{
			className
		},
		{
			template
		}
	)

	return <li {...innerBlocksProps} />
}


const PreviewContextBlock = ({
	blocks,
	blockContextId,
	isHidden,
	setActiveBlockContextId,
	className = ""
}) => {

	const previewBlockProps = useBlockPreview({
		blocks,
		props: {
			className
		}
	});

	const style = {
		display: isHidden ? "none" : ""
	};

	return <li
		{...previewBlockProps}
		tabIndex={0}
		onClick={() => setActiveBlockContextId(blockContextId)}
		style={style}
	/>
}

const MemoizedPreviewContextBlock = memo(PreviewContextBlock);

export default BlockRecordTemplateProvider;