<?php

namespace Bricks\UserBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as BaseController;

class SecurityController extends BaseController
{
    /**
     * print the code for the modal login box
     */
    public function _modalLoginAction($targetPath = null)
    {
        $csrfToken = $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate');
        
        return $this->container->get('templating')->renderResponse('BricksUserBundle:Security:_modalLogin.html.twig', array(
            'csrf_token' => $csrfToken,
            'targetPath' => $targetPath
        ));
    }
}
