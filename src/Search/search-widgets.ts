export type RegisterSearchWidgetParams = {
	name: string;
	widgetClass: string;
	widgetFunc: CallableFunction;
};

export const registerSearchWidget = (
	widgetObject: RegisterSearchWidgetParams
) => {
	if (!window.advancedSearchWidgets) {
		window.advancedSearchWidgets = [];
	}

	const widgetPromise = new Promise<void>((resolve) => {
		const searchBlocks = document.querySelectorAll(
			'.wp-block-advanced-ai-search-intelligent-search'
		);

		searchBlocks.forEach((block) => {
			block.addEventListener('advanced-search:ready', (event) => {
				const { search, searchId } = event.detail;

				console.log("Registering advanced search widget " + widgetObject.name + "...");

				const targetWidgets = document.querySelectorAll(
					widgetObject.widgetClass
				);

				if (!targetWidgets) {
					console.log("No " + widgetObject.name + " widgets found");
				}

				console.log(targetWidgets.length + " " + widgetObject.name + " widgets found");

				targetWidgets.forEach((widget) => {
					search.addWidgets([
						widgetObject.widgetFunc(widget, search),
					]);
				});

				resolve();
			});
		});
	});

	window.advancedSearchWidgets.push(widgetPromise);
};
