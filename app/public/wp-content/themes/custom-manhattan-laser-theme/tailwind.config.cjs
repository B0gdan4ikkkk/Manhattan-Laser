/** @type {import('tailwindcss').Config} */
module.exports = {
	content: [
		'./**/*.php'
	],
	theme: {
		extend: {
			colors: {
				manhattan: {
					blue: '#0ea5e9',
					dark: '#020617'
				}
			},
			fontFamily: {
				sans: ['SegoeUI', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'sans-serif'],
				display: ['ManhattanDisplay', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'sans-serif']
			}
		}
	},
	plugins: []
};

