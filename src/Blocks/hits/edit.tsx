import {
	useBlockProps
} from "@wordpress/block-editor";

import {
	Spinner
} from "@wordpress/components";

import {
	useSelect
} from "@wordpress/data";

import "./index.scss";
import BlockRecordTemplateProvider from "../../Components/BlockRecordTemplateProvider";

const RECORD_TEMPLATE = [
	["advanced-ai-search/record-text"]
]

const Edit = (props) => {

	const {
		records,
		isRecordsResolving,
		childBlocks
	} = useSelect(select => {
		return {
			records: select("core").getEntityRecords("postType", "post", { per_page: 6 }),
			isRecordsResolving: select("core").isResolving("getEntityRecords"),
			childBlocks: select("core/block-editor").getBlocks(props.clientId)
		}
	});


	/** todo: move somewhere else as used in infinite hits as well */
	const recordContext = (record) => {
		console.log("here is the record");
		console.log(record);
		return {
			key: record.id,
			title: record.title.raw,
			images: record.featured_media,
		}
	};

	return (
		<div {...useBlockProps()}>
			{(!records && isRecordsResolving) ? (
				<Spinner />
			) : (
				<BlockRecordTemplateProvider
					data={records}
					recordBlockTemplate={RECORD_TEMPLATE}
					childBlocks={childBlocks}
					contextFn={recordContext}
				/>
			)}
		</div>
	)
}

export default Edit;