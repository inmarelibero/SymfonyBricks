<?php

namespace Bricks\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\HttpException;

use Symfony\Component\Finder\Finder;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction()
    {
        $entities = $this->get('bricks.resource.service')->search(array(
            'published' => true,
            'max_results' => 12
        ));

        // load tags
        $tagManager = $this->get('fpn_tag.tag_manager');
        foreach ($entities as &$entity) {
            $tagManager->loadTagging($entity);
        }

        return array(
            'resources' => $entities
        );
    }
    
    /**
     * @Route("/changelog", name="changelog")
     * @Template()
     */
    public function changelogAction()
    {
        $finder = new Finder();
        $finder->files()
            ->in($this->container->getParameter('kernel.root_dir').'/../')
            ->depth('== 0')
            ->name('CHANGELOG.md')
        ;
        
        foreach ($finder as $file) {
            $changelog_content = file_get_contents($file->getRealPath());
        }
        
        return array(
            'changelog_content' => $changelog_content
        );
    }
    
    /**
     * @Route("/contribute", name="contribute")
     * @Template()
     */
    public function contributeAction()
    {
        return array();
    }
    
    /**
     * @Route("/developers", name="developers")
     * @Template()
     */
    public function developersAction()
    {
        return array();
    }


    /**
     * @Route("/_latest-posts-on-symfonybricks-blog", name="_latestsPostsOnSymfonybricksBlog", options={"ci_metatags_expose"=false})
     * @Template()
     */
    public function _latestsPostsOnSymfonybricksBlogAction()
    {
        if (!$this->getRequest()->isXmlHttpRequest()) {
            throw new HttpException(500);
        }

        $reader = $this->get('eko_feed.feed.reader');
        $feeds = $reader->load('http://blog.symfonybricks.com/feed/')->get();

        return array(
            'feeds' => $feeds
        );
    }

    /**
     * Search resources
     *
     * @Route("/search", name="resource_search")
     * @Template()
     */
    public function searchAction()
    {
        $entities = $this->get('bricks.resource.service')->search(array(
            'q' => $this->getRequest()->get('q'),
            'tag_name' => $this->getRequest()->get('tag'),
            'published' => true
        ));

        // load tags
        $tagManager = $this->get('fpn_tag.tag_manager');
        foreach ($entities as &$entity) {
            $tagManager->loadTagging($entity);
        }

        return array(
            'resources' => $entities
        );
    }
}
