<?php

namespace Cocorico\UserBundle\Admin;

use Sonata\UserBundle\Admin\Model\GroupAdmin as SonataGroupAdmin;

class GroupAdmin extends SonataGroupAdmin
{
    protected $baseRoutePattern = 'group';
}
