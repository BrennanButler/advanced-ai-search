const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const path = require( 'path' );

module.exports = {
	...defaultConfig,

	entry: async () => {
		const defaultEntry =
			typeof defaultConfig.entry === 'function'
				? await defaultConfig.entry()
				: defaultConfig.entry;

		return {
			...defaultEntry,
			'admin': path.resolve( process.cwd(), 'src/admin.ts' ),
		};
	},
};