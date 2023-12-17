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

mix.combine('resources/theme/admin/js/custom/components/region/list.js', 'public/assets/js/custom/components/region/list.js');

mix.combine('resources/theme/admin/js/custom/authentication/sign-in/general.js', 'public/assets/js/custom/authentication/sign-in/general.js');

mix.combine('resources/theme/admin/js/custom/apps/user-management/permissions/list.js', 'public/assets/js/custom/apps/user-management/permissions/list.js');
mix.combine('resources/theme/admin/js/custom/apps/user-management/permissions/add-permission.js', 'public/assets/js/custom/apps/user-management/permissions/add-permission.js');
mix.combine('resources/theme/admin/js/custom/apps/user-management/permissions/update-permission.js', 'public/assets/js/custom/apps/user-management/permissions/update-permission.js');

mix.combine('resources/theme/admin/js/custom/apps/user-management/roles/list/add.js', 'public/assets/js/custom/apps/user-management/roles/list/add.js');
mix.combine('resources/theme/admin/js/custom/apps/user-management/roles/list/update-role.js', 'public/assets/js/custom/apps/user-management/roles/list/update-role.js');

mix.combine('resources/theme/admin/js/custom/apps/user-management/users/list/table.js', 'public/assets/js/custom/apps/user-management/users/list/table.js');
mix.combine('resources/theme/admin/js/custom/apps/user-management/users/list/add.js', 'public/assets/js/custom/apps/user-management/users/list/add.js');
mix.combine('resources/theme/admin/js/custom/apps/user-management/users/list/update.js', 'public/assets/js/custom/apps/user-management/users/list/update.js');

mix.combine('resources/theme/admin/js/custom/apps/master/supplier/list/table.js', 'public/assets/js/custom/apps/master/supplier/list/table.js');
mix.combine('resources/theme/admin/js/custom/apps/master/supplier/list/add.js', 'public/assets/js/custom/apps/master/supplier/list/add.js');

mix.combine('resources/theme/admin/js/custom/apps/master/expedition/list/table.js', 'public/assets/js/custom/apps/master/expedition/list/table.js');
mix.combine('resources/theme/admin/js/custom/apps/master/expedition/list/add.js', 'public/assets/js/custom/apps/master/expedition/list/add.js');

mix.combine('resources/theme/admin/js/custom/apps/master/collector/list/table.js', 'public/assets/js/custom/apps/master/collector/list/table.js');
mix.combine('resources/theme/admin/js/custom/apps/master/collector/list/add.js', 'public/assets/js/custom/apps/master/collector/list/add.js');

mix.combine('resources/theme/admin/js/custom/apps/master/category/list/table.js', 'public/assets/js/custom/apps/master/category/list/table.js');
mix.combine('resources/theme/admin/js/custom/apps/master/category/list/add.js', 'public/assets/js/custom/apps/master/category/list/add.js');

mix.combine('resources/theme/admin/js/custom/apps/transaction/restock/list/table.js', 'public/assets/js/custom/apps/transaction/restock/list/table.js');
mix.combine('resources/theme/admin/js/custom/apps/transaction/restock/list/add.js', 'public/assets/js/custom/apps/transaction/restock/list/add.js');

mix.combine('resources/theme/admin/js/custom/apps/transaction/preorder/list/table.js', 'public/assets/js/custom/apps/transaction/preorder/list/table.js');
mix.combine('resources/theme/admin/js/custom/apps/transaction/preorder/list/add.js', 'public/assets/js/custom/apps/transaction/preorder/list/add.js');
mix.combine('resources/theme/admin/js/custom/apps/transaction/preorder/list/update_discount.js', 'public/assets/js/custom/apps/transaction/preorder/list/update_discount.js');
mix.combine('resources/theme/admin/js/custom/apps/transaction/preorder/list/update_status.js', 'public/assets/js/custom/apps/transaction/preorder/list/update_status.js');

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
