declare module '*.scss';

declare global {
	interface Window {
		advancedSearchWidgets?: Array<Promise<void>>
	}
}

export {};