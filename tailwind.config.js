const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    content: [ // 表示生成 css 时，只扫描以下范围的文件，新版中，purge 参数已经取消
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
