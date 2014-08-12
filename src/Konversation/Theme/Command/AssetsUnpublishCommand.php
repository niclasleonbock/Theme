<?php
namespace Konversation\Theme\Command;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class AssetsUnpublishCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'theme:unpublish-assets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove assets from themes public directory.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $theme = $this->argument('theme');

        if ($this->confirm('Do you really want to remove all assets for theme "' . $theme . '"? [yes|no]')) {
            $this->laravel->make('theme')->unpublishAssets($theme);

            $this->info('Successfully removed assets for theme "' . $theme . '" from public directory.');
        } else {
            $this->info('Cancelled unpublishing assets for theme "' . $theme . '"!');
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            [ 'theme', InputArgument::REQUIRED, 'The theme of which the assets should be published.' ],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [ ];
    }
}

