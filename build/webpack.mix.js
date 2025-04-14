let mix = require('laravel-mix');
const path = require("path");

mix.webpackConfig({
    externals: {
        jquery: "jQuery",
        bootstrap: true,
        vue: "Vue",
        moment: "moment",
    }
});

mix.setResourceRoot('./');
mix.setPublicPath('../themes/login_pro');

mix
    .sass('../themes/login_pro/css/presets/default/main.scss', '../themes/login_pro/css/skins/default.css', {
        sassOptions: {
            includePaths: [
                path.resolve(__dirname, './node_modules/')
            ]
        }
    })
    .copyDirectory('node_modules/@fontsource/open-sans/files', '../themes/login_pro/css/fonts')
