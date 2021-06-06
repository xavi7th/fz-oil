const mix = require('laravel-mix');

mix.webpackConfig({
	resolve: {
		extensions: ['.js', '.svelte', '.json'],
		alias: {
			'@officeexpense-pages': __dirname + '/Resources/js/Pages',
			'@officeexpense-shared': __dirname + '/Resources/js/Shared',
			'@officeexpense-assets': __dirname + '/Resources'
		},
	},
})

// mix.js(__dirname + '/Resources/js/app.js', 'js/officeexpense.js')
// mix.sass(__dirname + '/Resources/sass/app.scss', 'css/officeexpense-app.css')
