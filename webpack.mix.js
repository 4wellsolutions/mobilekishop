const mix = require('laravel-mix');

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

mix.css(['/css/merged-css.min.css','https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.1/css/all.min.css'], 'public/css');
