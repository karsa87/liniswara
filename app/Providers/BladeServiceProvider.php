<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        \Blade::directive('hasPermission', function ($permission) {
            $permission = str_replace("'", '"', $permission);
            $permissionArr = json_decode($permission, true);
            if ($permissionArr && is_array($permissionArr)) {
                return "<?php if ( auth()->user()->has('$permission') ) { ?>";
            }

            $permission = str_replace("'", '', $permission);
            $permission = str_replace('"', '', $permission);

            return '<?php if (auth()->user()->has("'.$permission.'")) { ?>';
        });

        \Blade::directive('elsehasPermission', function () {
            return '<?php } else { ?>';
        });

        \Blade::directive('endhasPermission', function () {
            return '<?php } ?>';
        });
    }
}
