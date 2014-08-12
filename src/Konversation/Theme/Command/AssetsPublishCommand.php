<?php
namespace Konversation\Theme\Command;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class AssetsPublishCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'theme:publish-assets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish assets to themes public directory.';

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
        $theme  = $this->argument('theme');
        $from   = $this->option('from');

        $this->laravel->make('theme')->publishAssets($theme, $from);

        $this->info('Successfully published assets for theme "' . $theme . '" from "' . $from . '".');
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
        return [
            [ 'from', null, InputOption::VALUE_OPTIONAL, 'Directory to get the assets from (defaults to "assets").', 'assets' ],
        ];
    }
}

