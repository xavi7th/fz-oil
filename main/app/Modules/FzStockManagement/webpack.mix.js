const mix = require('laravel-mix');

mix.webpackConfig({
	resolve: {
		extensions: ['.js', '.svelte', '.json'],
		alias: {
			'@fzstockmanagement-pages': __dirname + '/Resources/js/Pages',
			'@fzstockmanagement-shared': __dirname + '/Resources/js/Shared',
			'@fzstockmanagement-assets': __dirname + '/Resources'
		},
	},
})

// mix.js(__dirname + '/Resources/js/app.js', 'js/fzstockmanagement.js')
// mix.sass(__dirname + '/Resources/sass/app.scss', 'css/fzstockmanagement-app.css')
