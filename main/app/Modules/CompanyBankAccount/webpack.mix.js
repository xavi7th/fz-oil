const mix = require('laravel-mix');

mix.webpackConfig({
	resolve: {
		extensions: ['.js', '.svelte', '.json'],
		alias: {
			'@companybankaccount-pages': __dirname + '/Resources/js/Pages',
			'@companybankaccount-shared': __dirname + '/Resources/js/Shared',
			'@companybankaccount-assets': __dirname + '/Resources'
		},
	},
})

// mix.js(__dirname + '/Resources/js/app.js', 'js/companybankaccount.js')
// mix.sass(__dirname + '/Resources/sass/app.scss', 'css/companybankaccount-app.css')
