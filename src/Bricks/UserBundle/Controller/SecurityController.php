<?php

namespace Bricks\UserBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class SecurityController extends BaseController
{
    /**
     * Print the code for the modal login box
     * @Template
     */
    public function _modalLoginAction($targetPath = null)
    {
        $csrfToken = $this->getCsrfToken();

        return array(
            'csrf_token' => $csrfToken,
            'targetPath' => $targetPath
        );
    }

    /**
     * Print the code for the login form in homepage
     *
     * @Template
     */
    public function _homepageLoginAction($targetPath = null)
    {
        $csrfToken = $this->getCsrfToken();

        return array(
            'csrf_token' => $csrfToken,
            'targetPath' => $targetPath,
            'targetPath' => $this->container->get('router')->generate('homepage', array(), true)
        );
    }

    private function getCsrfToken()
    {
        return $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate');
    }
}
