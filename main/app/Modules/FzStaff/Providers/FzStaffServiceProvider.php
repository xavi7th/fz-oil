<?php

namespace App\Modules\FzStaff\Providers;

use Illuminate\Support\ServiceProvider;

class FzStaffServiceProvider extends ServiceProvider
{
  /**
   * @var string $moduleName
   */
  protected $moduleName = 'FzStaff';

  /**
   * @var string $moduleNameLower
   */
  protected $moduleNameLower = 'fzstaff';

  /**
   * Boot the application events.
   *
   * @return void
   */
  public function boot()
  {
    $this->registerTranslations();
    $this->registerConfig();
    $this->registerViews();
    $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));


    /**** Register the modules middlewares *****/
    // app()->make('router')->aliasMiddleware('fz_staff', OnlyFzStafs::class);
    // app()->make('router')->aliasMiddleware('verified_fz_staff', OnlyVerifiedFzStaff::class);
    // app()->make('router')->aliasMiddleware('unverified_fz_staff', OnlyUnverifiedFzStaff::class);
  }

  /**
   * Register the service provider.
   *
   * @return void
   */
  public function register()
  {
    $this->app->register(RouteServiceProvider::class);
  }

  /**
   * Register config.
   *
   * @return void
   */
  protected function registerConfig()
  {
    $this->publishes([
      module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
    ], 'config');
    $this->mergeConfigFrom(
      module_path($this->moduleName, 'Config/config.php'),
      $this->moduleNameLower
    );
  }

  /**
   * Register views.
   *
   * @return void
   */
  public function registerViews()
  {
    $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

    $sourcePath = module_path($this->moduleName, 'Resources/views');

    $this->publishes([
      $sourcePath => $viewPath
    ], ['views', $this->moduleNameLower . '-module-views']);

    $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
  }

  /**
   * Register translations.
   *
   * @return void
   */
  public function registerTranslations()
  {
    $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

    if (is_dir($langPath)) {
      $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
    } else {
      $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
    }
  }

  /**
   * Get the services provided by the provider.
   *
   * @return array
   */
  public function provides()
  {
    return [];
  }

  private function getPublishableViewPaths(): array
  {
    $paths = [];
    foreach (\Config::get('view.paths') as $path) {
      if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
        $paths[] = $path . '/modules/' . $this->moduleNameLower;
      }
    }
    return $paths;
  }
}
