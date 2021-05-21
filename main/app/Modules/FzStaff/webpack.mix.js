const mix = require('laravel-mix');

mix.webpackConfig({
	resolve: {
		extensions: ['.js', '.svelte', '.json'],
		alias: {
			'@fzstaff-pages': __dirname + '/Resources/js/Pages',
			'@fzstaff-shared': __dirname + '/Resources/js/Shared',
			'@fzstaff-assets': __dirname + '/Resources'
		},
	},
})

mix.copyDirectory(__dirname + '/Resources/img', 'public_html/img');

mix.scripts([
  __dirname + '/Resources/sass/vendor/bower_components/jquery/dist/jquery.min.js',
  __dirname + '/Resources/sass/vendor/bower_components/popper.js/dist/umd/popper.min.js',
  __dirname + '/Resources/sass/vendor/bower_components/moment/moment.js',
  __dirname + '/Resources/sass/vendor/bower_components/chart.js/dist/Chart.min.js',
  __dirname + '/Resources/sass/vendor/bower_components/select2/dist/js/select2.full.min.js',
  __dirname + '/Resources/sass/vendor/bower_components/jquery-bar-rating/dist/jquery.barrating.min.js',
  __dirname + '/Resources/sass/vendor/bower_components/ckeditor/ckeditor.js',
  __dirname + '/Resources/sass/vendor/bower_components/bootstrap-validator/dist/validator.min.js',
  __dirname + '/Resources/sass/vendor/bower_components/bootstrap-daterangepicker/daterangepicker.js',
  __dirname + '/Resources/sass/vendor/bower_components/ion.rangeSlider/js/ion.rangeSlider.min.js',
  __dirname + '/Resources/sass/vendor/bower_components/dropzone/dist/dropzone.js',
  __dirname + '/Resources/sass/vendor/bower_components/editable-table/mindmup-editabletable.js',
  __dirname + '/Resources/sass/vendor/bower_components/datatables.net/js/jquery.dataTables.min.js',
  __dirname + '/Resources/sass/vendor/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
  __dirname + '/Resources/sass/vendor/bower_components/fullcalendar/dist/fullcalendar.min.js',
  __dirname + '/Resources/sass/vendor/bower_components/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js',
  __dirname + '/Resources/sass/vendor/bower_components/tether/dist/js/tether.min.js',
  __dirname + '/Resources/sass/vendor/bower_components/slick-carousel/slick/slick.min.js',
  __dirname + '/Resources/sass/vendor/bower_components/bootstrap/js/dist/util.js',
  __dirname + '/Resources/sass/vendor/bower_components/bootstrap/js/dist/alert.js',
  __dirname + '/Resources/sass/vendor/bower_components/bootstrap/js/dist/button.js',
  __dirname + '/Resources/sass/vendor/bower_components/bootstrap/js/dist/carousel.js',
  __dirname + '/Resources/sass/vendor/bower_components/bootstrap/js/dist/collapse.js',
  __dirname + '/Resources/sass/vendor/bower_components/bootstrap/js/dist/dropdown.js',
  __dirname + '/Resources/sass/vendor/bower_components/bootstrap/js/dist/modal.js',
  __dirname + '/Resources/sass/vendor/bower_components/bootstrap/js/dist/tab.js',
  __dirname + '/Resources/sass/vendor/bower_components/bootstrap/js/dist/tooltip.js',
  __dirname + '/Resources/sass/vendor/bower_components/bootstrap/js/dist/popover.js',
  __dirname + '/Resources/js/vendor/demo_customizer.js',
  __dirname + '/Resources/js/vendor/dataTables.bootstrap4.min.js',
], 'public_html/js/dashboard-app-vendor.js');

mix.copy([
  __dirname + '/Resources/js/vendor/html2pdf.js',
], 'public_html/js/html2pdf.js');

mix.scripts([
  __dirname + '/Resources/js/vendor/main.js',
], 'public_html/js/user-dashboard-init.js');

mix.js(__dirname + '/Resources/js/app.js', 'js/dashboard-app.js')
mix.sass(__dirname + '/Resources/sass/app.scss', 'css/dashboard-app.css')
