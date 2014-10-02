<?php

namespace Bricks\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

/**
 * ExternalResource controller.
 *
 * @Route("/externalresource")
 */
class ExternalResourceController extends Controller
{
    /**
     * Show an external resource
     *
     * @Route("/{slug}", name="external_resource_show")
     * @Template()
     */
    public function showAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BricksSiteBundle:ExternalResource')->findOneBy(array(
            'slug' => $slug
        ));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ExternalResource entity.');
        }

        /*
         * if the external resource is not published, return a temporary redirection
         */
        if (!$entity->getPublished()) {
            /**
             * //@TODO: redirect to a new rout: "resource_not_published"
             */
            return $this->redirect($this->generateUrl('brick_not_published'), 307);
        }

        $tagManager = $this->get('fpn_tag.tag_manager');
        $tagManager->loadTagging($entity);

        return array(
            'entity' => $entity
        );
    }
}