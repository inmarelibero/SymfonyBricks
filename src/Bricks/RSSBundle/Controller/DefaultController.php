<?php

namespace Bricks\RSSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * Generate the article feed
     *
     * @Route("rss.xml", name="rss_feed")
     * @return Response XML Feed
     */
    public function feedAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BricksSiteBundle:Brick')->findPublished();

        $feed = $this->get('eko_feed.feed.manager')->get('brick');
        $feed->addFromArray($entities);

        return new Response($feed->render('rss')); // or 'atom'
    }
}
