<?php

namespace Cocorico\CoreBundle\Routing;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\RouteCollection;

class ExtraBundleLoader extends Loader
{
    /**
     * Add routing from extra bundles
     *
     * @param mixed $resource
     * @param null  $type
     * @return RouteCollection
     */
    public function load($resource, $type = null)
    {
        $collection = new RouteCollection();

        return $collection;
    }


    public function supports($resource, $type = null)
    {
        return 'extra_bundle' === $type;
    }
}