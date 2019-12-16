<?php

namespace Cocorico\SonataUserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CocoricoSonataUserBundle extends Bundle
{

    public function getParent()
    {
        return 'SonataUserBundle';
    }

}
