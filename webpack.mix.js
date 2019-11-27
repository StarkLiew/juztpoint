const mix = require('laravel-mix');
const mixSsr = require('laravel-mix');
const VuetifyLoaderPlugin = require('vuetify-loader/lib/plugin')
const SWPrecacheWebpackPlugin = require('sw-precache-webpack-plugin')
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


mix.js('resources/pos/app.js', 'public/pos')
    .js('resources/backoffice/app.js', 'public/backoffice')
    .js('resources/receipt/app-client.js', 'public/receipt')
    .js('resources/receipt/app-server.js', 'public/receipt')
    .sass('resources/styles/app.sass', 'public/css')
    .options({
        extractVueStyles: 'public/css/app.css',
    })
    .webpackConfig({
        resolve: {
            extensions: ['.js', '.json', '.vue'],
            alias: {
                '~': path.join(__dirname, './resources/pos'),
                '~~': path.join(__dirname, './resources/backoffice'),
                '$pos': path.join(__dirname, './resources/pos/components'),
                '$backoffice': path.join(__dirname, './resources/backoffice/components'),
                '~~~': path.join(__dirname, './resources/receipt'),
                '$receipt': path.join(__dirname, './resources/receipt'),
            }
        },
        plugins: [
            new VuetifyLoaderPlugin(),
            new SWPrecacheWebpackPlugin({
                cacheId: 'pwa',
                filename: 'service-worker.js',
                staticFileGlobs: ['public/**/*.{css,eot,svg,ttf,woff,woff2,js,html}'],
                minify: true,
                stripPrefix: 'public',
                handleFetch: true,
                dynamicUrlToDependencies: {
                    '/': ['resources/views/pos.blade.php'],
                },
                staticFileGlobsIgnorePatterns: [/\.map$/, /mix-manifest\.json$/, /manifest\.json$/, /service-worker\.js$/],
                navigateFallback: 'https://app.juztpoint.com/',
                runtimeCaching: [{
                        urlPattern: /^https:\/\/fonts\.googleapis\.com\//,
                        handler: 'cacheFirst'
                    },
                    {
                        urlPattern: /^https:\/\/www\.thecocktaildb\.com\/images\/media\/drink\/(\w+)\.jpg/,
                        handler: 'cacheFirst'
                    }
                ],
            })
        ]
    })
    .browserSync(process.env.PWA_APP_URL)
