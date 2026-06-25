import {
	Placeholder
} from "@wordpress/components";

const BlockPlaceholder = ({
	icon = "",
	instructions,
	label,
	children
}) => {

	const defaultIcon = null; // TODO create a default icon

	return (
		<Placeholder
			icon={ defaultIcon ?? icon }
			instructions={ instructions }
			label={ label }
		>
			{ children }
		</Placeholder>
	);
}

export default BlockPlaceholder;