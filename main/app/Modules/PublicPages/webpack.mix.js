const mix = require('laravel-mix');

mix.webpackConfig({
	resolve: {
		extensions: ['.js', '.svelte', '.json'],
		alias: {
			'@public-pages': __dirname + '/Resources/js/Pages',
			'@public-assets': __dirname + '/Resources',
			'@public-shared': __dirname + '/Resources/js/Shared'
		},
	},
})

	// mix.copyDirectory(__dirname + '/Resources/img', 'public_html/img');
	mix.copyDirectory(__dirname + '/Resources/fonts', 'public_html/fonts');

	// mix.sass(__dirname + '/Resources/sass/app.scss', 'css/app.css')

	mix.scripts([
      __dirname + '/Resources/js/vendor/jquery.min.js',
      __dirname + '/Resources/js/vendor/bootstrap.bundle.min.js',
      __dirname + '/Resources/js/vendor/owl.carousel.min.js',
      __dirname + '/Resources/js/vendor/nouislider.min.js',
      __dirname + '/Resources/js/vendor/photoswipe.min.js',
      __dirname + '/Resources/js/vendor/photoswipe-ui-default.min.js',
      __dirname + '/Resources/js/vendor/select2.min.js',
      __dirname + '/Resources/js/vendor/number.js',
      __dirname + '/Resources/js/vendor/svg4everybody.min.js',
  ], 'public_html/js/public-vendor.js');

	mix.scripts([
      __dirname + '/Resources/js/vendor/main.js',
      __dirname + '/Resources/js/vendor/header.js'
  ], 'public_html/js/public-init.js');

	mix.js(__dirname + '/Resources/js/app.js', 'js/app.js')
mix.sass(__dirname + '/Resources/sass/app.scss', 'css/app.css')
