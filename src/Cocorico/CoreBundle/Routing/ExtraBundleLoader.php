<?php

/*
 * This file is part of the Cocorico package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cocorico\CoreBundle\Routing;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\RouteCollection;

class ExtraBundleLoader extends Loader
{
    protected $bundles;
    protected $env;

    public function __construct(array $bundles, $env)
    {
        $this->bundles = $bundles;
        $this->env = $env;
    }

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