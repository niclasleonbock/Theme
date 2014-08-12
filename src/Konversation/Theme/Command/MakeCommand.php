<?php
namespace Konversation\Theme\Command;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class MakeCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'theme:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a theme.';

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
        $author     = [
            'name'      => $this->argument('author-name'),
            'email'     => $this->argument('author-email'),
            'website'   => $this->option('author-website'),
        ];

        $theme      = $this->argument('name');
        $namespace  = $this->option('namespace');
        $identifier = $this->generateIdentifier($theme, $author['name']);

        $fileSystem = $this->laravel->make('files');
        $path       = $this->laravel->make('theme')->getBasePath($identifier);
        $file       = $path . '/' . ucfirst($theme) . 'ThemeProvider.php';

        $contents   = $this->laravel->make('view')
            ->make('theme::bootstrap-generator', compact('identifier', 'theme', 'author', 'namespace'));

        $fileSystem->makeDirectory($path, 0755, true, true);
        $fileSystem->makeDirectory($path . '/views', 0755, true, true);
        $fileSystem->makeDirectory($path . '/assets', 0755, true, true);

        $fileSystem->put($file, $contents);

        $this->info('Successfully created theme "' . $theme . '" in "' . $path . '".');
    }

    /**
     * Generate identifier from author + theme name.
     *
     * @return  string
     */
    protected function generateIdentifier($name, $author)
    {
        return snake_case($author) . '/' . snake_case($name);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            [ 'name', InputArgument::REQUIRED, 'The name of your new theme.' ],
            [ 'author-name', InputArgument::REQUIRED, 'Your name.' ],
            [ 'author-email', InputArgument::REQUIRED, 'Your email address.' ],
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
            [ 'author-website', null, InputOption::VALUE_OPTIONAL, 'Your website (if you have one).', '' ],
            [ 'namespace', null, InputOption::VALUE_OPTIONAL, 'Namespace of the themes directory, if there is one.', null ],
        ];
    }
}

