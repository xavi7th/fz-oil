const mix = require('laravel-mix');

mix.webpackConfig({
	resolve: {
		extensions: ['.js', '.svelte', '.json'],
		alias: {
			'@supervisor-pages': __dirname + '/Resources/js/Pages',
			'@supervisor-shared': __dirname + '/Resources/js/Shared',
			'@supervisor-assets': __dirname + '/Resources'
		},
	},
})

// mix.js(__dirname + '/Resources/js/app.js', 'js/supervisor.js')
// mix.sass(__dirname + '/Resources/sass/app.scss', 'css/supervisor-app.css')
