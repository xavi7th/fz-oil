const mix = require('laravel-mix');

mix.webpackConfig({
	resolve: {
		extensions: ['.js', '.svelte', '.json'],
		alias: {
			'@fzcustomer-pages': __dirname + '/Resources/js/Pages',
			'@fzcustomer-shared': __dirname + '/Resources/js/Shared',
			'@fzcustomer-assets': __dirname + '/Resources'
		},
	},
})

// mix.js(__dirname + '/Resources/js/app.js', 'js/fzcustomer.js')
// mix.sass(__dirname + '/Resources/sass/app.scss', 'css/fzcustomer-app.css')
