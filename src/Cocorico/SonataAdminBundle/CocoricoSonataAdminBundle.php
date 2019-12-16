<?php

namespace Cocorico\SonataAdminBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CocoricoSonataAdminBundle extends Bundle
{

    public function getParent()
    {
        return 'SonataAdminBundle';
    }

}
