<?php
namespace Konversation\Theme;

interface ThemeProviderInterface
{
    /**
     * Get the theme identifier which is also
     * used for locating templates and assets.
     * Should usually be in format {author}/{name}.
     *
     * @return  string
     */
    public function getIdentifier();

    /**
     * Get the theme name.
     *
     * @return  string
     */
    public function getName();

    /**
     * Get the theme version.
     *
     * @return  int|float|string
     */
    public function getVersion();

    /**
     * Get the themes author name.
     *
     * @return  string
     */
    public function getAuthorName();

    /**
     * Get the themes author email address.
     *
     * @return  string
     */
    public function getAuthorEmail();

    /**
     * Get the themes author website.
     *
     * @return  string
     */
    public function getAuthorWebsite();
}

