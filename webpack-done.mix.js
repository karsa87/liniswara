// webpack.mix.js
let mix = require('laravel-mix');

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

// copy images folder into laravel public folder
mix.copyDirectory('resources/theme/admin', 'public/assets');
mix.combine('resources/theme/admin/js/custom/authentication/sign-in/general.js', 'public/assets/js/custom/authentication/sign-in/general.js');

/**
* plugins specific issue workaround for webpack
* @see https://github.com/morrisjs/morris.js/issues/697
* @see https://stackoverflow.com/questions/33998262/jquery-ui-and-webpack-how-to-manage-it-into-module
*/
mix.webpackConfig({
   resolve: {
       alias: {
           'morris.js': 'morris.js/morris.js',
           'jquery-ui': 'jquery-ui',
       },
   },
});

if (mix.inProduction()) {
    mix.version();
    mix.then(async () => {
          const convertToFileHash = require("laravel-mix-make-file-hash");
          convertToFileHash({
              publicPath: "public",
              manifestFilePath: "public/mix-manifest.json",
              blacklist: ["assets/plugins/global/fonts/*"],
              keepBlacklistedEntries: true,
              delOptions: { force: false },
              debug: false
          });
    });
}
