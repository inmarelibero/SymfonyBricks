<?php

namespace Bricks\SiteBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\DomCrawler\Crawler;

use Bricks\SiteBundle\Entity\Brick;
use Bricks\UserBundle\Form\Type\BrickType;
use Bricks\SiteBundle\Entity\UserStarsBrick;
use Bricks\UserBundle\Form\Type\UserMessageType;

/**
 * Brick controller.
 *
 * @Route("/brick")
 */
class BrickController extends Controller
{
    /**
     * Lists all Brick entities.
     *
     * @Route("/", name="brick")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BricksSiteBundle:Brick')->findBy(array('published' => true));

        return array(
            'bricks' => $entities,
        );
    }
    
    /**
     * Display a page to inform that a brick is not published
     *
     * @Route("/not-published", name="brick_not_published")
     * @Template()
     */
    public function notPublishedAction()
    {
        return array();
    }

    /**
     * Search bricks
     *
     * @Route("/search", name="brick_search")
     * @Template()
     */
    public function searchAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        // search for bricks
        $entities = $em->getRepository('BricksSiteBundle:Brick')->search(array(
            'q' => $this->getRequest()->get('q'),
            'tag_slug' => $this->getRequest()->get('tag')
        ));
        
        return array(
            'entities' => $entities
        );
    }
    
    /**
     * Toggle the "star" of a brick from a user
     * 
     * With this action, a user can "star" or "un-star" a brick (like marking it as favorite)
     * 
     * @Route("/toggle-star/{brick_id}", name="user_brick_toggle_star", options={"expose"=true})
     * #TODO: security access
     *
     * @param unknown_type $id
     */
    public function toggleStarAction($brick_id)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BricksSiteBundle:Brick')->find($brick_id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Brick entity.');
        }
        
        // toggle "star" state
        if ($user->isStarringBrick($entity)) {
            
            $userStarsBrick = $em->getRepository('BricksSiteBundle:UserStarsBrick')->findOneBy(array(
                'brick' =>    $entity->getId(),
                'user' =>     $user->getId()
            ));
            
            $em->remove($userStarsBrick);
            $em->flush();
            
            $jsonResponse = array('action' => 'unstarred');
            
        } else {
            $userStarsBrick = new UserStarsBrick();
            
            $userStarsBrick->setUser($user);
            $userStarsBrick->setBrick($entity);
            
            $user->addUserStarsBrick($userStarsBrick);
            
            $userManager = $this->container->get('fos_user.user_manager');
            $userManager->updateUser($user);
            
            $jsonResponse = array('action' => 'starred');
        }
        
        
        /*
        if ($entity->getPublished()) {
            $this->get('session')->getFlashBag()->add('success', 'alert.brick.togglePublished.published');
        } else {
            $this->get('session')->getFlashBag()->add('information', 'alert.brick.togglePublished.unpublished');
        }
        */
        
        return new Response(json_encode($jsonResponse), 200, array('Content-Type'=>'application/json'));
    }

    /**
     * Fetch the content of a url by curl and retrieve the markdown content
     *
     * @Route("/fetchurlcontent", name="fetch_url_content", options={"expose"=true})
     */
    public function fetchLinkAction()
    {
        $url = $this->getRequest()->get('url');

        if (is_null($url)) {
            return new Response('', 500);
        }

        $options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => true,

            /*
             * TODO: fix
             * Warning: curl_setopt_array(): CURLOPT_FOLLOWLOCATION cannot be activated
             * when safe_mode is enabled or an open_basedir is set in
             */
            //CURLOPT_FOLLOWLOCATION => true,

            CURLOPT_ENCODING       => "",
            CURLOPT_USERAGENT      => "spider",
            CURLOPT_AUTOREFERER    => true,
            CURLOPT_CONNECTTIMEOUT => 120,
            CURLOPT_TIMEOUT        => 120,
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        );

        $ch      = curl_init( $url );
        curl_setopt_array( $ch, $options );
        $content = curl_exec( $ch );
        //$err     = curl_errno( $ch );
        //$errmsg  = curl_error( $ch );
        $header  = curl_getinfo( $ch );
        curl_close( $ch );


        // use PKMarkdownifyBundle to convert html to markdown
        $markdownify = @$this->container->get('pk.markdownify');
        $markdownContent = @$markdownify->parseString($content);

        // html render from markup content
        $htmlContent = $this->container->get('markdown.parser')->transformMarkdown($markdownContent);

        $jsonResponse = array(
            'markdown' => $markdownContent,
            'html' => $htmlContent
        );

        return new Response(json_encode($jsonResponse), $header['http_code'], array('Content-Type'=>'application/json'));
    }

    /**
     * Show a brick
     *
     * @Route("/{slug}", name="brick_show")
     * @Template()
     */
    public function showAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BricksSiteBundle:Brick')->findOneBy(array(
            'slug' => $slug
        ));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Brick entity.');
        }

        /*
         * if the brick is not published, return a temporary redirection
         */
        if (!$entity->getPublished()) {
            return $this->redirect($this->generateUrl('brick_not_published'), 307);
        }

        return array(
            'brick' => $entity
        );
    }
}