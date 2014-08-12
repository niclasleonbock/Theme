<?php
namespace Konversation\Theme;

use Illuminate\Support\ServiceProvider;

use Konversation\Theme\ThemeManager;
use Konversation\Theme\ViewFinder;

use Konversation\Theme\Command\AssetsPublishCommand;
use Konversation\Theme\Command\AssetsUnpublishCommand;
use Konversation\Theme\Command\MakeCommand;

class ThemeServiceProvider extends ServiceProvider
{
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

    public function boot()
    {
		$this->package('konversation/theme');
    }

	/**
	 * Register the service provider.
	 *
	 * @return  void
	 */
	public function register()
	{
        $this->app->bindShared('view.finder', function ($app) {
            return new ViewFinder(
                $app->files,
                $app->config->get('view.paths')
            );
        });

        $this->app->singleton('theme', function ($app) {
            return new ThemeManager(
                $app,
                $app->config->get('theme::config.base_path'),
                $app->config->get('theme::config.view_path'),
                $app->config->get('theme::config.public_path')
            );
        });

        $this->registerCommands();
	}

    protected function registerCommands()
    {
        $this->app->bindShared('theme.command.assets.publish', function() {
            return new AssetsPublishCommand();
        });

        $this->app->bindShared('theme.command.assets.unpublish', function() {
            return new AssetsUnpublishCommand();
        });

        $this->app->bindShared('theme.command.make', function() {
            return new MakeCommand();
        });

        $this->commands([
            'theme.command.assets.publish',
            'theme.command.assets.unpublish',
            'theme.command.make',
        ]);
    }

	/**
	 * Get the services provided by the provider.
	 *
	 * @return  array
	 */
	public function provides()
	{
		return [
            'view.finder',
            'theme',
            'theme.command.assets.publish',
            'theme.command.assets.unpublish',
            'theme.command.make',
        ];
	}
}

