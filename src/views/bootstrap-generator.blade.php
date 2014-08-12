<?php echo "<?php\n" ?>
@if ($namespace)
namespace {{ $namespace }};

@endif
use Konversation\Theme\ThemeProviderInterface;

class KonversationThemeProvider implements ThemeProviderInterface
{
    public function getIdentifier()
    {
        return '{{ $identifier }}';
    }
    public function getName()
    {
        return '{{ $theme }}';
    }

    public function getVersion()
    {
        return '0.0.1';
    }

    public function getAuthorName()
    {
        return '{{ $author['name'] }}';
    }
    public function getAuthorEmail()
    {
        return '{{ $author['email'] }}';
    }
    public function getAuthorWebsite()
    {
        return '{{ $author['website'] }}';
    }
}

