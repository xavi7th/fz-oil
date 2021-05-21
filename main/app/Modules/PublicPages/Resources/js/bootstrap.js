import { App } from '@inertiajs/inertia-svelte'
import { InertiaProgress } from '@inertiajs/progress'
import { Inertia } from "@inertiajs/inertia";
import {
	initialiseDatatable,
	initialiseSwiper,
	initialiseBasicDataTable,
	initialiseDonutChart,
	initialiseLineChart
} from "@public-shared/actions";
import { getErrorString, mediaHandler } from "@public-shared/helpers";

window.swal = require('sweetalert2')
window._ = {
	compact: require('lodash/compact'),
	debounce: require('lodash/debounce'),
	each: require('lodash/each'),
	endsWith: require('lodash/endsWith'),
	every: require('lodash/every'),
	filter: require('lodash/filter'),
	find: require('lodash/find'),
	forEach: require('lodash/forEach'),
	isEmpty: require('lodash/isEmpty'),
	isEqual: require('lodash/isEqual'),
	isString: require('lodash/isString'),
	map: require('lodash/map'),
	omit: require('lodash/omit'),
	pick: require('lodash/pick'),
	pullAt: require('lodash/pullAt'),
	reduce: require('lodash/reduce'),
	size: require('lodash/size'),
	split: require('lodash/split'),
	startsWith: require('lodash/startsWith'),
	takeRight: require('lodash/takeRight'),
	truncate: require('lodash/truncate'),
}
window.initialiseDatatable = initialiseDatatable;
window.initialiseBasicDataTable = initialiseBasicDataTable;
window.initialiseSwiper = initialiseSwiper;
window.initialiseDonutChart = initialiseDonutChart;
window.initialiseLineChart = initialiseLineChart;
window.Toast = swal.mixin({
	toast: true,
	position: 'top-end',
	showConfirmButton: false,
  timerProgressBar: true,
	timer: 2000,
	icon: "success",
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', swal.stopTimer)
    toast.addEventListener('mouseleave', swal.resumeTimer)
  }
});

window.ToastLarge = swal.mixin({
	icon: "success",
	title: 'To be implemented!',
	html: 'I will close in <b></b> milliseconds.',
	timer: 3000,
  timerProgressBar: true,
	didOpen: () => {
		swal.showLoading()
	},
	// onClose: () => {}
})

window.BlockToast = swal.mixin({
  didOpen: () => {
    swal.showLoading()
	},
  showConfirmButton: false,
	showCloseButton: false,
	allowOutsideClick: false,
	allowEscapeKey: false
});

window.swalPreconfirm = swal.mixin({
	title: 'Are you sure?',
	text: "Implement this when you call the mixin",
	icon: 'question',
	showCloseButton: false,
	allowOutsideClick: () => !swal.isLoading(),
	allowEscapeKey: false,
	showCancelButton: true,
	focusCancel: true,
	cancelButtonColor: '#d33',
	confirmButtonColor: '#3085d6',
	confirmButtonText: 'To be implemented',
	showLoaderOnConfirm: true,
	preConfirm: () => {
		/** Implement this when you call the mixin */
	},
})

InertiaProgress.init({
  // The delay after which the progress bar will
  // appear during navigation, in milliseconds.
  delay: 250,

  // The color of the progress bar.
  color: '#29d',

  // Whether to include the default NProgress styles.
  includeCSS: true,

  // Whether the NProgress spinner will be shown.
  showSpinner: true,
})

Inertia.on('start', (event) => {
	console.log(event);
	jQuery('#page-loader')
		.fadeIn()
})

Inertia.on('progress', (event) => {
  console.log(event);
})

Inertia.on('success', (e) => {
  if (e.detail.page.props.flash.success) {
    ToastLarge.fire( {
      title: "Success",
      html: e.detail.page.props.flash.success,
      icon: "success",
      timer: 1000,
      allowEscapeKey: true
    } );
  }
  else {
    swal.close();
  }
  jQuery('#page-loader')
  	.fadeOut()
})

Inertia.on('error', (e) => {
  console.log(`There were errors on your visit`)
  console.log(e)
  jQuery('#page-loader')
  	.fadeOut()
  ToastLarge.fire( {
    title: "Error",
    html: getErrorString( e.detail.errors ),
    icon: "error",
    timer:10000,
    footer: `If your issues persists contact support for assistance`,
  } );
})

Inertia.on('invalid', (event) => {
  console.log(`An invalid Inertia response was received.`)
  console.log(event);
  event.preventDefault()

  Toast.fire({
    position: 'top',
    title: 'Oops! ' + event.detail.response.statusText,
    icon:'error'
  })
})

Inertia.on('exception', (event) => {
  console.log(event);
  console.log(`An unexpected error occurred during an Inertia visit.`)
  console.log(event.detail.error)
})

Inertia.on('finish', (e) => {
  // console.log(e);
})

/**
 * This should solve everyone's problem. The code below only executes the server side search once a user has stopped typing
 * and 1 second has transpired. Courtesy goes to https://stackoverflow.com/a/1909508 for the delay function. Just as an FYI,
 * I elected on using the older delay function in my example below, as the new function from 2019-05-16 does not support Internet Explorer.
 *
 * I took pjdarch 's function and turned it into a feature plugin.
 * Then, after you 've instantiated your DataTable, you need to create a new instance of the feature on a given table:
 *
 * var table = $("#table_selector").DataTable();
 * var debounce = new $.fn.dataTable.Debounce(table);
 *
 * I am using this thus in my actions
 *
 * @param {*} table
 * @param {Object} options
 */
window.$ && $.fn.dataTable && ($.fn.dataTable.Debounce = function(table, options) {
	var tableId = table.settings()[0].sTableId;
	$('.dataTables_filter input[aria-controls="' + tableId + '"]') // select the correct input field
		.off() // Unbind previous default bindings
		.on('input', (delay(function(e) { // Bind our desired behavior
			table.search($(this)
					.val())
				.draw();
			return;
		}, options.delay || 1000))); // Set delay in milliseconds
})

function delay(callback, ms) {
	var timer = 0;
	return function() {
		var context = this,
			args = arguments;
		clearTimeout(timer);
		timer = setTimeout(function() {
			callback.apply(context, args);
		}, ms || 0);
	};
}

let { isMobile, isDesktop } = mediaHandler();

const app = document.getElementById('app')
new App({
	target: app,
	props: {
		initialPage: JSON.parse(app.dataset.page),
		resolveComponent: str => {
			let [section, module] = _.split(str, '::');

			return import(
					/* webpackChunkName: "js/[request]" */
					/* webpackPrefetch: true */
					`../../../${section}/Resources/js/Pages/${module}.svelte`)
		},
    resolveErrors: page => ((page.props.flash.error || page.props.errors) || {}),
		transformProps: props => {
			return {
				...props,
				isMobile,
				isDesktop
			}
		}
	},
})

/**
 *! Cause back() and forward() buttons of the browser to refresh the browser state
 */
// if (!('onpopstate' in window)) {
// window.addEventListener('popstate', () => {
// 	Inertia.reload({ preserveScroll: true, preserveState: false })
// })
// }
