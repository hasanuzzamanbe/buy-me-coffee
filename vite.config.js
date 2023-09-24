import {defineConfig} from 'vite'
import {viteStaticCopy} from 'vite-plugin-static-copy'
import vue from '@vitejs/plugin-vue'
import react from '@vitejs/plugin-react'
import liveReload from 'vite-plugin-live-reload';
import path from "path";
import AutoImport from 'unplugin-auto-import/vite'

const {ElementPlusResolver} = require("unplugin-vue-components/resolvers");
const Components = require("unplugin-vue-components/vite");
// https://vitejs.dev/config/

//Add All css and js here
//Important: Key must be output filepath without extension, and value will be the file source
const inputs = {
    'js/boot': 'src/js/boot.js',
    'js/plugin-main-js-file' : 'src/js/main.js',
    'js/BmcPublic' : 'src/js/BmcPublic.js',
    'js/BmcFormHandler' : 'src/js/BmcFormHandler.js',
    'js/PaymentMethods/paypal-checkout' : 'src/js/PaymentMethods/paypal-checkout.js',

    //styles
    // 'css/element' : 'src/scss/admin/app.scss',
    'css/BasicTemplate' : 'src/scss/public/BasicTemplate.scss',
    'css/public-style' : 'src/scss/public/public-style.scss',

    //Block Editor assets
    'js/gutenBlock' : 'src/js/Editor/gutenBlock.jsx'
}
export default defineConfig({
    css: {
        preprocessorOptions: {
            scss: {
                additionalData: `@use "./src/scss/admin/app.scss" as *;`,
            },
        },
    },
    plugins:
        [
            vue(),
            react(),
            liveReload([
                `${__dirname}/**/*\.php`,
            ]),
            viteStaticCopy({
                targets: [
                    {src: 'src/images', dest: ''},
                ]
            }),
            AutoImport({
                resolvers: [ElementPlusResolver()],
            }),
            Components({
                resolvers: [ElementPlusResolver()],
                directives: false
            }),
        ],

    build: {
        manifest: true,
        outDir: 'assets',
        //assetsDir: '',
        publicDir: 'assets',
        //root: '/',
        emptyOutDir: true, // delete the contents of the output directory before each build

        // https://rollupjs.org/guide/en/#big-list-of-options
        rollupOptions: {
            input: inputs,
            output: {
                chunkFileNames: '[name].js',
                entryFileNames: '[name].js',


            },
        },
    },

    resolve: {
        alias: {
            'vue': 'vue/dist/vue.esm-bundler.js',
            '@': path.resolve(__dirname, 'resources/admin'),
        },
    },

    server: {
        port: 2222,
        strictPort: true,
        hmr: {
            port: 2222,
            host: 'localhost',
            protocol: 'ws',
        }
    }
})
