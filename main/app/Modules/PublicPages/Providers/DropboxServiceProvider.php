<?php

namespace App\Modules\PublicPages\Providers;

use League\Flysystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Spatie\Dropbox\Client as DropboxClient;
use Spatie\FlysystemDropbox\DropboxAdapter;

class DropboxServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function boot()
    {
      Storage::extend('dropbox', function ($app, $config) {
          $client = new DropboxClient(
              $config['authorization_token']
          );

          return new Filesystem(new DropboxAdapter($client), ['case_sensitive' => false]);
      });
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
}
