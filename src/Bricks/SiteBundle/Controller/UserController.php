<?php

namespace Bricks\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * User controller.
 * 
 * Actions to provide public info about a User
 */
class UserController extends Controller
{
    /**
     * Lists usernames for javascript autocompletion
     *
     * @Route("/ajax-autocomplete-users", name="ajax_autocomplete_users", options={"expose"=true, "ci_metatags_expose"=false})
     */
    public function indexAction()
    {
        // check ajax request
        if (!$this->getRequest()->isXmlHttpRequest()) {
            throw new \Exception('Only Ajax request type is accepted');
        }
        
        $q = $this->getRequest()->get('q');
        
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('BricksUserBundle:User');

        
        // query builder
        $qb = $repository->createQueryBuilder('e');
        
        // filter user by username beginning by $q
        $qb->where($qb->expr()->like('e.username', ':q'))
            ->setParameter('q', $q.'%')
        ;
        
        $qb->select('e.username');
        $qb->orderBy('e.username', 'ASC');
        
        // fetch results
        $users = $qb->getQuery()->getResult();

        return new Response(json_encode($users), 200, array('Content-Type'=>'application/json'));
    }
}
