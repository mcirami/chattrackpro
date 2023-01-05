const mix = require('laravel-mix');
require('core-js');
require('laravel-mix-polyfill');
const path = require('path');

const BrowserSyncPlugin = require('browser-sync-webpack-plugin');
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

module.exports = {
    resolve: {
        alias: {
            myApp: path.resolve(__dirname, 'resources/js')
        }
    },
    plugins: [
        new BrowserSyncPlugin({
            files: [
                '**/*.css',
                '**/*.js'
            ]
        }, {reload: false})
    ]
}

mix.js('resources/assets/js/app.js', 'public/js/compiled/built.js')
    .sass('resources/assets/sass/app.scss', 'public/css/compiled/app.css')
.minify('public/js/compiled/built.js', 'public/js/compiled/built.min.js')
.minify('public/css/compiled/app.css', 'public/css/compiled/app.min.css')
    .polyfill({
        enabled: true,
        useBuiltIns: 'entry',
        targets: false,
        entryPoints: "stable",
        corejs: 3,
    })
    .browserSync('chattrackpro.test');
