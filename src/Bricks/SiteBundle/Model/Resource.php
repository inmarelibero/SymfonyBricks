<?php

namespace Bricks\SiteBundle\Model;

interface Resource
{
    const TYPE_BRICK                = 'BRICK';
    const TYPE_EXTERNAL_RESOURCE    = 'EXTERNAL_RESOURCE';

    /**
     * Return resource type
     *
     * @return mixed
     */
    public function getResourceType();
} 