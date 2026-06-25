import {
    useBlockProps
} from "@wordpress/block-editor";

import "./index.scss";

const Edit = ( props ) => {
    
    
    return (
        <div {...useBlockProps()}>
            Hello from Numeric menu
        </div>
    )
}

export default Edit;