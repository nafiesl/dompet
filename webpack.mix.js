let mix = require('laravel-mix');

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

var paths = {
    'bootstrap': './node_modules/bootstrap-sass/assets/'
}

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css', {
        sassOptions: {
            includePaths: [
                paths.bootstrap + 'stylesheets/'
            ]
        }
    }).options({
        processCssUrls: false
    }).copyDirectory( paths.bootstrap + 'fonts/bootstrap/', 'public/fonts/bootstrap' )
    .postCss('resources/css/style.css', 'public/css', [
        require('tailwindcss'),
    ]);
