<?php

namespace Bricks\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * ExternalResource controller.
 *
 * @Route("/externalresource")
 */
class ExternalResourceController extends Controller
{
    /**
     * Lists all ExternalResource entities
     *
     * @Route("/", name="admin_external_resource")
     * @Template("BricksUserBundle:ExternalResource:index.html.twig")
     */
    public function indexAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BricksSiteBundle:ExternalResource')->findBy(
            array(),
            array('title' => 'ASC')
        );

        return array(
            'entities' => $entities,
        );
    }
}