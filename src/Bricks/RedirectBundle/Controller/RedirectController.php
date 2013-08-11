<?php

namespace Bricks\RedirectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class RedirectController
 *
 * Permant redirects are hosted in this class
 */
class RedirectController extends Controller
{
    /**
     * Redirections form urls with the syntax:
     *      /brick/{id}/{slug}
     *
     * to urls with the syntax (route "brick_show"):
     *      /brick/{slug}
     *
     * @Route("/{_locale}/brick/{id}/{slug}", requirements={"_locale": "%route_locale_requirements%", "id": "\d+"})
     */
    public function brickAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BricksSiteBundle:Brick')->findOneBy(array(
            'slug' => $slug
        ));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Brick entity.');
        }

        return $this->redirect($this->generateUrl('brick_show', array('slug' => $slug)), 301);
    }
}
