<?php
namespace Konversation\Theme;

use InvalidArgumentException;

use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;

use Konversation\Theme\PrependableViewFinderInterface as ViewFinderInterface;
use Konversation\Theme\ThemeProviderInterface;

class ThemeManager extends Collection
{
    /**
     * The application.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * The viewfinder to use for prepending paths (must implement PrependableViewFinderInterface).
     *
     * @var \Konversation\Theme\PrependableViewFinderInterface
     */
    protected $viewFinder;

    /**
     * The currently used (active) theme.
     *
     * @var string
     */
    protected $theme;

    /**
     * The path where all the magic takes place. Eh... the themes lay.
     *
     * @var string
     */
    protected $basePath;

    /**
     * The path to look in for views (%s as placeholder for the themes name).
     *
     * @var string
     */
    protected $viewPath;

    /**
     * The public path to look in for assets (%s as placeholder for the themes name).
     *
     * @var string
     */
    protected $publicPath;

    /**
     * Create a new instance of the manager.
     *
     * @param   \Illuminate\Foundation\Application $app
     * @param   string $viewPath
     * @param   string $publicPath
     * @return  void
     */
    public function __construct(Application $app,
        $basePath = null, $viewPath = null, $publicPath = null)
    {
        $this->app          = $app;
        $this->viewFinder   = $app->make('view.finder');

        if ($basePath) {
            $this->basePath = rtrim($basePath, '/');
        } else {
            $this->basePath = app_path() . '/themes/%s';
        }

        if ($viewPath) {
            $this->viewPath = rtrim($viewPath, '/');
        } else {
            $this->viewPath = $this->basePath . '/views';
        }

        if ($publicPath) {
            $this->publicPath = trim($publicPath, '/');
        } else {
            $this->publicPath = 'themes/%s';
        }
    }

    /**
     * Publish assets to public directory for the specified or current theme.
     *
     * @param   string|\Konversation\Theme\ThemeProviderInterface $theme
     * @param   string $from
     * @return  void
     */
    public function publishAssets($theme = null, $from = 'assets')
    {
        if (!$theme) {
            $theme = $this->getTheme();
        }

        $fileSystem     = $this->app->make('files');
        $directory      = $this->getBasePath() . '/' . $from;
        $destination    = public_path() . '/' . $this->getPublicPath($theme);

        $fileSystem->copyDirectory($directory, $destination);
    }

    /**
     * Delete assets from public directory for the specified or current theme.
     *
     * @param   string|\Konversation\Theme\ThemeProviderInterface $theme
     * @return  void
     */
    public function unpublishAssets($theme = null)
    {
        if (!$theme) {
            $theme = $this->getTheme();
        }

        $fileSystem     = $this->app->make('files');
        $directory      = public_path() . '/' . $this->getPublicPath($theme);

        $fileSystem->deleteDirectory($directory, false);
    }

    /**
     * Register a new theme.
     *
     * @param   \Konversation\Theme\ThemeProviderInterface $theme
     * @return  void
     */
    public function register(ThemeProviderInterface $theme)
    {
        $this->put($theme->getIdentifier(), new Theme($theme));
    }

    /**
     * Create an array of all registered themes (alias for \Illuminate\Support\Collection::all()).
     *
     * @return  array
     */
    public function getThemes()
    {
        return $this->all();
    }

    /**
     * Parse the given path and replace '%s' with the themes name.
     *
     * @param   string   $path
     * @param   string   $theme
     * @return  string
     */
    protected function parsePath($path, $theme = null)
    {
        if (!$theme) {
            $theme = $this->getTheme();
        }

        return sprintf($path, $theme);
    }

    /**
     * Get the public theme path.
     *
     * @param   string   $theme
     * @return  string
     */
    public function getPublicPath($theme = null)
    {
        if (!$theme) {
            $theme = $this->getTheme();
        }

        return $this->parsePath($this->publicPath, $theme);
    }

    /**
     * Generate a URL to a theme asset.
     *
     * @param   string   $file
     * @param   bool     $secure
     * @param   string   $theme
     * @return  string
     */
    public function asset($file, $secure = false, $theme = null)
    {
        if (!$theme) {
            $theme = $this->getTheme();
        }

        return asset($this->getPublicPath() . '/' . ltrim($file, '/'), $secure);
    }

    /**
     * Get the base path for the theme.
     *
     * @param   string   $theme
     * @return  string
     */
    public function getBasePath($theme = null)
    {
        if (!$theme) {
            $theme = $this->getTheme();
        }

        return rtrim($this->parsePath($this->basePath, $theme), '/');
    }

    /**
     * Get the view path for the theme.
     *
     * @param   string   $theme
     * @return  string
     */
    public function getViewPath($theme = null)
    {
        if (!$theme) {
            $theme = $this->getTheme();
        }

        return $this->parsePath($this->viewPath, $theme);
    }

    /**
     * Set the theme specified by name or instance of Konversation\Theme\ThemeProviderInterface as active.
     *
     * @param   string|\Konversation\Theme\ThemeProviderInterface   $theme
     * @return  void
     */
    public function setTheme($theme)
    {
        if ($theme instanceof ThemeProviderInterface) {
            $theme = $theme->getIdentifier();
        }

        if (!is_string($theme)) {
            throw new InvalidArgumentException('Theme must be a string (name) or an instance of ThemeProviderInterface.');
        }

        $path = $this->getViewPath($theme);

        foreach ($this->viewFinder->getHints() as $namespace => $hints) {
            $this->viewFinder->prependNamespace($namespace, $path . '/' . $namespace);
        }

        $this->viewFinder->prependLocation($path);

        $this->theme = $theme;
    }

    /**
     * Alias for \Konversation\Theme\ThemeManager::setTheme($theme).
     *
     * @param   string|\Konversation\Theme\ThemeProviderInterface   $theme
     * @return  void
     */
    public function activate($theme)
    {
        $this->setTheme($theme);
    }

    /**
     * Get the currently active theme.
     *
     * @return  string
     */
    public function getTheme()
    {
        return $this->theme;
    }
}

