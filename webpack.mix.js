const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
        require('autoprefixer'),
    ])
    .sourceMaps(process.env.MIX_APP_ENV == 'production', 'source-map')
    .extract(['axios'])
    // .browserSync('laravel8study.cc')
    .version();

// 正确的用法，只有线上时才有必要使用版本化
// if (mix.inProduction()) {
//     mix.version();
// }

// 这个参数就是给 webpack 配置的，规则参考 webpack
// mix.webpackConfig({
//     resolve: {
//         modules: [
//             path.resolve(__dirname, 'vendor/laravel/spark/resources/assets/js')
//         ]
//     }
// });
