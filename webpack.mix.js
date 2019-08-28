const mix = require('laravel-mix');
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

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/styles/app.sass', 'public/css')




mix.webpackConfig({
  resolve: {
    extensions: ['.js', '.json', '.vue'],
    alias: {
      '~': path.join(__dirname , './resources/js'),
      '$comp': path.join(__dirname, './resources/js/components')
    }
  },
  plugins: [
    new VuetifyLoaderPlugin(),
    new SWPrecacheWebpackPlugin({
        cacheId: 'juxtpoint',
        filename: 'service-worker.js',
        staticFileGlobs: ['public/**/*.{css,eot,svg,ttf,woff,woff2,js,html}'],
        minify: true,
        stripPrefix: 'public',
        handleFetch: true,
        dynamicUrlToDependencies: {
            '/': ['resources/views/pos.blade.php'],
            '/pos': ['resources/views/pos.blade.php']
        },
        staticFileGlobsIgnorePatterns: [/\.map$/, /mix-manifest\.json$/, /manifest\.json$/, /service-worker\.js$/],
        runtimeCaching: [
            {
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

mix.browserSync(process.env.PWA_APP_URL)


