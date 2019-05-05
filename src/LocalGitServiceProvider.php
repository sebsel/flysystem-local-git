<?php

namespace Sebsel\Flysystem\LocalGit;

use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;

class LocalGitServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     * 
     * @return void
     */
    public function boot()
    {
        $factory = $this->app->make('filesystem');

        $factory->extend('local-git', function ($app, $config) {
            return new Filesystem(new LocalGitAdapter($config['root']));
        });
    }
}
