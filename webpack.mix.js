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
        require('tailwindcss'), // 请理解 tailwindcss 是 postcss 的插件！
        require('autoprefixer'),
    ])
    .sourceMaps(process.env.MIX_APP_ENV == 'production', 'source-map')
    .extract(['axios'])
    .options({
        // processCssUrls: false, // 禁用自动整理资源路径
    })
    // .browserSync('laravel8study.cc')
    .version();

// 载入其它模块
mix.autoload({
    jquery: ['$', 'window.jQuery', 'jQuery'], // more than one
    moment: 'moment' // only one
});

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

// 如果你没有使用 Laravel，你的 mix-manifest.json 文件会被放到项目根目录下，如果你不喜欢的话，可以调用 mix.setPublicPath('dist/');，然后 manifest 文件就会被放到 dist 目录下。
// mix.setPublicPath('dis/');
