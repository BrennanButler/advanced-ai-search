import {
    useBlockProps
} from "@wordpress/block-editor";

import "./index.scss";

const Edit = ( props ) => {
    
    
    return (
        <div {...useBlockProps()}>
            Hello from search stats
        </div>
    )
}

export default Edit;