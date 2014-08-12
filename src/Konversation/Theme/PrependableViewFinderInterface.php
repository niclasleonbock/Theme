<?php
namespace Konversation\Theme;

interface PrependableViewFinderInterface
{
    /**
     * Prepend a location to the view finder.
     *
     * @param   string  $path
     * @return  void
     */
    public function prependLocation($path);

    /**
     * Prepend a namespace to the view finder.
     *
     * @param   string  $namespace
     * @param   array   $hints
     * @return  void
     */
    public function prependNamespace($namespace, $hints);
}

