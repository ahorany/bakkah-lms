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

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css');

mix.js([
    'public/front-dist/js/popper.min.js',
    'public/front-dist/js/owl.carousel.min.js',
    // 'public/front-dist/js/jquery.cookie.js',
    // 'public/front-dist/js/slick.min.js'
], 'public/front-dist/js/all.js');

// purgecss --css public/front-dist/css/bootstrap.css --content *.blade.php --output public/front-dist/css/purgecss
// purgecss --css public/front-dist/css/owl.carousel.min.css --content *.blade.php --output public/front-dist/css/purgecss
// purgecss --css public/front-dist/css/owl.theme.default.css --content *.blade.php --output public/front-dist/css/purgecss
// purgecss --css public/front-dist/css/animate.css --content *.blade.php --output public/front-dist/css/purgecss
// purgecss --css public/front-dist/css/slick.css --content *.blade.php --output public/front-dist/css/purgecss
// purgecss --css public/front-dist/css/all.min.css --content *.blade.php --output public/front-dist/css/purgecss
mix.styles([
    // 'public/front-dist/css/bootstrap.min.css',
    'public/front-dist/css/bootstrap-grid.min.css',
    // 'public/front-dist/css/owl.carousel.min.css',
    // 'public/front-dist/css/owl.theme.default.css',
    // 'public/front-dist/css/animate.css',
    // 'public/front-dist/css/slick.css',
    // 'public/front-dist/css/all.min.css',
    // 'public/front-dist/style.css'
], 'public/front-dist/css/all.css');

// mix.styles('public/front-dist/style.css', 'public/front-dist/all.css');
mix.styles('public/front-dist/rtl.css', 'public/front-dist/rtl.min.css');

//Consulting
// mix.styles([
//     'public/front-dist/css/bootstrap.min.css',
//     'public/front-dist/css/owl.carousel.min.css',
//     'public/front-dist/css/owl.theme.default.css',
//     'public/front-dist/css/animate.css',
//     'public/front-dist/css/all.min.css',
// ], 'public/front-dist/consulting/css/all.css');

// mix.js([
//     'public/front-dist/js/popper.min.js',
//     'public/front-dist/js/owl.carousel.min.js',
//     'public/front-dist/js/sliders.js',
//     'public/front-dist/js/jquery.cookie.js',
// ], 'public/front-dist/consulting/js/all.js');

//Admin
mix.styles([
    'public/admin-dist/css/admin.css',
], 'public/admin-dist/css/all.css');
