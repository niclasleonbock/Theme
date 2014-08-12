<?php
namespace Konversation\Theme;

use Illuminate\View\FileViewFinder;

class ViewFinder extends FileViewFinder implements PrependableViewFinderInterface
{
    /**
     * {@inheritDoc}
     */
    public function prependLocation($path)
    {
        array_unshift($this->paths, $path);
    }

    /**
     * {@inheritDoc}
     */
    public function prependNamespace($namespace, $hints)
    {
        $hints = (array) $hints;

        if (in_array($namespace, $this->hints)) {
            $this->hints[$namespace] = array_merge($hints, $this->hints[$namespace]);
        } else {
            $this->addNamespace($namespace, $hints);
        }
    }
}

