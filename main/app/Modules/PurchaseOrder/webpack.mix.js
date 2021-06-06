const mix = require('laravel-mix');

mix.webpackConfig({
	resolve: {
		extensions: ['.js', '.svelte', '.json'],
		alias: {
			'@purchaseorder-pages': __dirname + '/Resources/js/Pages',
			'@purchaseorder-shared': __dirname + '/Resources/js/Shared',
			'@purchaseorder-assets': __dirname + '/Resources'
		},
	},
})

// mix.js(__dirname + '/Resources/js/app.js', 'js/purchaseorder.js')
// mix.sass(__dirname + '/Resources/sass/app.scss', 'css/purchaseorder-app.css')
