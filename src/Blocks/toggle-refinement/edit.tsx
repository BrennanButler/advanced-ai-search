import {
    useBlockProps
} from "@wordpress/block-editor";

import "./index.scss";

const Edit = ( props ) => {
    
    
    return (
        <div {...useBlockProps()}>
            Hello from toggle refinement
        </div>
    )
}

export default Edit;