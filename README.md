# Konversation/Theme
> simple theme support for laravel

### Features

- Automatic view resolving to override templates in themes
- Asset management (publish assets to public or remove them, e.g. via artisan)
- Configurable paths for base, views and public

### Usage
```php
<?php
use Konversation\Theme\ThemeProviderInterface;

class KonversationThemeProvider implements ThemeProviderInterface
{
    public function getIdentifier()
    {
        return 'niclasleonbock/konversation';
    }
    public function getName()
    {
        return 'Konversation';
    }

    public function getVersion()
    {
        return 'first one';
    }

    public function getAuthorName()
    {
        return 'niclasleonbock';
    }
    public function getAuthorEmail()
    {
        return 'me@bock.ga';
    }
    public function getAuthorWebsite()
    {
        return 'https://bock.ga/';
    }
}

Theme::register(new KonversationThemeProvider());
Theme::setTheme('niclasleonbock/konversation');
```

You can simply override views by placing them in `app/themes/{vendor}/{name}/views` (default path).

By default, assets will be copied from `app/themes/{vendor}/{name}/assets/*` to `public/themes/{vendor}/{name}` using the artisan command `theme:publish-assets`.

More documentation to come, so long, please refer to the source code.

