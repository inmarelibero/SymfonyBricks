<?php

namespace Bricks\HWIOAuthBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class BricksHWIOAuthBundle extends Bundle
{
    /**
     * extends HWIOAuthBundle
     */
    public function getParent()
    {
        return 'HWIOAuthBundle';
    }
}
