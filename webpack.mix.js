const mix = require('laravel-mix')
var tailwindcss = require('tailwindcss')

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
	.js('resources/js/app.js', 'public')
	.sass('resources/sass/app.scss', 'public')
	.options({
		processCssUrls: false,
		postCss: [tailwindcss('./tailwind.config.js')],
	})
