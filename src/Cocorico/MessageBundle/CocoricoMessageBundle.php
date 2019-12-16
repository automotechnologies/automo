<?php

namespace Cocorico\MessageBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * FOS Message bundle (extended)
 *
 */
class CocoricoMessageBundle extends Bundle
{

    public function getParent()
    {
        return 'FOSMessageBundle';
    }

}
