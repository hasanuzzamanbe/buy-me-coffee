let mix = require('laravel-mix');
const AutoImport = require("unplugin-auto-import/webpack");
const {ElementPlusResolver} = require("unplugin-vue-components/resolvers");
const Components = require("unplugin-vue-components/webpack");
var path = require('path');

mix.webpackConfig({
    module: {
        rules: [{
            test: /\.mjs$/,
            resolve: {fullySpecified: false},
            include: /node_modules/,
            type: "javascript/auto"
        }]

    },
    plugins: [
        AutoImport({
            resolvers: [ElementPlusResolver()],
        }),
        Components({
            resolvers: [ElementPlusResolver()],
            directives: false
        }),
    ],
    resolve: {
        extensions: ['.js', '.vue', '.json'],
        alias: {
            '@': path.resolve(__dirname, 'resources/admin')
        }
    }
});

mix
    .js('src/js/boot.js', 'assets/js/boot.js').vue({ version: 3 })
    .js('src/js/main.js', 'assets/js/plugin-main-js-file.js')
    .js('src/js/BmcPublic.js', 'assets/js/BmcPublic.js')
    .js('src/js/BmcFormHandler.js', 'assets/js/BmcFormHandler.js')
    .js('src/js/PaymentMethods/paypal-checkout.js', 'assets/js/PaymentMethods/paypal-checkout.js')
    .copy('src/images', 'assets/images')
    .sass('src/scss/admin/app.scss', 'assets/css/element.css')
    .sass('src/scss/public/BasicTemplate.scss', 'assets/css/BasicTemplate.css')
    .sass('src/scss/public/public-style.scss', 'assets/css/public-style.css');
