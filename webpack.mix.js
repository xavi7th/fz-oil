const mix = require('laravel-mix');
const autoPreprocess = require('svelte-preprocess');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const dotenvExpand = require('dotenv-expand');
const WebpackBar = require('webpackbar');
var path = require('path');

require('laravel-mix-imagemin');
require('laravel-mix-svelte');
require('laravel-mix-bundle-analyzer');
require('laravel-mix-purgecss');

let fs = require('fs-extra');
let modules = fs.readdirSync('./main/app/Modules');

// dotenvExpand(require('dotenv')
// 	.config({
// 		path: __dirname + '/main/.env',
// 		debug: true
// 	}));
// let siteUrl = process.env.APP_URL.replace(/(^\w+:|^)\/\//, '');

// console.log(process.env);

if (modules && modules.length > 0) {
	modules.forEach((module) => {
		let path = `./main/app/Modules/${module}/webpack.mix.js`;
		if (fs.existsSync(path)) {
			require(path);
		}
	});
}

mix.setPublicPath('./public_html');

mix.babelConfig({
	plugins: ['@babel/plugin-syntax-dynamic-import'],
});

mix.webpackConfig({
	output: {
		filename: mix.inProduction() ? "[name].[contenthash].js" : "[name].[hash].js",
		chunkFilename:mix.inProduction() ? "[name].[contenthash].js" : "[name].[hash].js",
	},
	plugins: [
    new WebpackBar(),
    new CleanWebpackPlugin({
			dry: false,
			cleanOnceBeforeBuildPatterns: ['js/*', './*.js', 'css/*', 'fonts/*', '/img/*', './mix-manifest.json']
		}),
  ]
});

mix
  .options( {
    watchOptions: {
        ignored: /node_modules/
        // ignored: [/node_modules/, ]
    },
    processCssUrls: false,
		fileLoaderDirs: {
			images: 'img'
		},
		postCss: [
		  require('postcss-fixes')(),
		  require('cssnano')({
				discardComments: {
					removeAll: true
				},
				calc: false,
				cssDeclarationSorter: true
			})
    ],
    //  hmrOptions: {
    //  	host: siteUrl,
    //  	port: 8081 // Can't use 443 here because address already in use
    //  }
	})
	.svelte({
		dev: !mix.inProduction(),
		css: true,
		preprocess: autoPreprocess({
			sourceMap: !mix.inProduction()
		}),
		onwarn: (warning, handler) => {
			const {
				code,
				frame
			} = warning;

			if (code == "anchor-is-valid" || code == "a11y-invalid-attribute") {
				return
			}
			if (code == "css-unused-selector") {
				return
			}
			if (code == 'css-unused-selector' && frame.includes('sweetalert')) {
				return
			}
			if (code == 'missing-declaration' && frame.includes('route')) {
				return
			}

			console.log(
				'\x1b[41m%s\x1b[0m',
				code
			)

			handler(warning);
		}
	})
	.extract()
	// .imagemin(
	// 	[
	// 			{
	// 				from: '**/img/**/**.*',
	// 				to: 'img/[folder]/[name].[ext]',
	// 				toType: 'template',
	//    },
	// 			{
	// 				from: '**/img/**.*',
	// 				to: 'img/[name].[ext]',
	// 				toType: 'template',
	//    }
	//   ], {
	// 			context: './main/app/Modules',
	// 		}, {
	// 			// optipng: null,
	// 			optipng: {
	// 				optimizationLevel: 7
	// 			},
	// 			jpegtran: null,
	// 			plugins: [
	//       require('imagemin-mozjpeg')({
	// 					quality: 75,
	// 					progressive: true,
	// 				}),
	//       // require('imagemin-webp')({
	// 			// 	quality: 75,
	// 			// }),
  //     ],
	// 		}
	// 	)
	.purgeCss({
		enabled: true,
		extend: {
			content: [
        path.join(__dirname, "main/app/Modules/**/*.php"),
        // path.join(__dirname, "main/app/Modules/**/*.html"),
        path.join(__dirname, "main/app/Modules/**/*.js"),
        path.join(__dirname, "main/app/Modules/**/*.svelte"),
      ],
			safelist: {
				standard: [/[pP]aginat(e|ion)/, /active/, /page/, /disabled/, /^dt-/],
				deep: [/[dD]ata[tT]able/],
				greedy: [/^dt/, /yay/, /fancy/, /modal/, /rui-chartist-donut/, /rui-widget/]
			},
			rejected: true,
			variables: true
		}
	})
	.then(() => {
		const _ = require('lodash');

		let oldManifestData = JSON.parse(fs.readFileSync('./public_html/mix-manifest.json', 'utf-8'))
		let newManifestData = {};

		_.map(oldManifestData, (actualFilename, mixKeyName) => {
			if (_.startsWith(mixKeyName, '/css')) {
				/** Exclude CSS files from renaming for now till we start cache busting them */
				newManifestData[mixKeyName] = actualFilename;
			} else {
				let newMixKeyName = _.split(mixKeyName, '.')
					.tap(o => {
						_.pullAt(o, 1);
					})
					.join('.')

				/** If the js extension has been stripped we add it back */
				newMixKeyName = _.endsWith(newMixKeyName, '.js') ? newMixKeyName : newMixKeyName + '.js'

				newManifestData[newMixKeyName] = actualFilename;
			}

		});

		let data = JSON.stringify(newManifestData, null, 2);
		fs.writeFileSync('./public_html/mix-manifest.json', data);
	})

if (!mix.inProduction()) {
	mix.sourceMaps();
}

if (mix.inProduction()) {
	// mix.bundleAnalyzer();
}

// mix.browserSync({
//   proxy:'romzynew.test/login',
//   // Disable UI completely
//   // ui: false,
//   // files: [
//   //   "wp-content/themes/**/*.css",
//   //   {
//   //       match: ["wp-content/themes/**/*.php"],
//   //       fn:    function (event, file) {
//   //           /** Custom event handler **/
//   //       }
//   //   }
//   // ],
//   // ghostMode: {
//   //   clicks: true,
//   //   forms: true,
//   //   scroll: false
//   // },
//   // notify: false,
//   // reloadDelay: 2000,
//   // // Don't append timestamps to injected files
//   // timestamps: false
// })
