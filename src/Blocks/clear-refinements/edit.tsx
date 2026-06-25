import {
    useBlockProps
} from "@wordpress/block-editor";

import "./index.scss";

const Edit = ( props ) => {
    
    
    return (
        <div {...useBlockProps()}>
            Hello from clear refinements
        </div>
    )
}

export default Edit;