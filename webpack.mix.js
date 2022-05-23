let mix = require('laravel-mix');
mix.setPublicPath('assets');

mix.setResourceRoot('../');
mix
    .js('src/js/boot.js', 'assets/js/boot.js')
    .js('src/js/main.js', 'assets/js/plugin-main-js-file.js')
    .js('src/js/BmcPublic.js', 'assets/js/BmcPublic.js')
    .js('src/js/BmcFormHandler.js', 'assets/js/BmcFormHandler.js')
    .copy('src/images', 'assets/images')
    .sass('src/scss/admin/app.scss', 'assets/css/element.css')
    .sass('src/scss/public/public-style.scss', 'assets/css/public-style.css');
