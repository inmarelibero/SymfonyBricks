<?php

namespace Bricks\MetaTagsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class BricksMetaTagsBundle extends Bundle
{
    /**
     * extends FOSUserBundle
     */
    public function getParent()
    {
        return 'CopiaincollaMetaTagsBundle';
    }
}
