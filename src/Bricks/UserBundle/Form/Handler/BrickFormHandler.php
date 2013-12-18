<?php
namespace Bricks\UserBundle\Form\Handler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactory;

use Bricks\SiteBundle\Entity\Brick;

class BrickFormHandler
{
    protected $form_factory;
    protected $request;
    protected $em;
    
    protected $originalBrickHasTags = array();

    public function __construct(FormFactory $form_factory, Request $request, $em)
    {
        $this->form_factory = $form_factory;
        $this->request = $request;
        $this->em = $em;
    }

    public function process($form)
    {
        if ('POST' === $this->request->getMethod()) {

            $brick = $form->getData();
            
            // BrickHasTag (array) before binding request
            $this->originalBrickHasTags = $brick->getBrickHasTags()->toArray();
            
            $form->handleRequest($this->request);

            if ($form->isValid()) {
                $this->onSuccess($brick);

                return true;
            }
        }

        return false;
    }

    protected function onSuccess(Brick $brick)
    {
        $this->manageTags($brick);
        
        $this->em->persist($brick);
        $this->em->flush();
    }
    
    protected function manageTags($brick) {
        
        // BrickHasTag array related to $brick
        $brickHasTags = $brick->getBrickHasTags();
        
        // array of BrickHasTag id after associating request
        $brickHasTagsArrayIds = (count($brickHasTags) > 0) ? array_map(function($value) {return $value->getId();}, $brickHasTags->toArray()) : array();
        
        // filter $this->originalBrickHasTags to contain BrickHasTag no longer present
        foreach ($this->originalBrickHasTags as $k => $brickHasTag) {
            if (in_array($brickHasTag->getId(), $brickHasTagsArrayIds)) {
                unset($this->originalBrickHasTags[$k]);
            }
        }
        
        // remove no longer associated BrickHasTags
        foreach ($this->originalBrickHasTags as $brickHasTag) {
            $this->em->remove($brickHasTag);
        }
        
        /*
        // save new BrickHasTags
        foreach ($brick->getBrickHasTags() as $brickHasTag) {
            
            if (is_null($brickHasTag->getId())) {
                
                // title of the tag (lowered and trimmed)
                $tagTitle = strtolower(trim($brickHasTag->getTag()->getTitle()));
                
                // try to find existing tag; if not found, create a new one
                $tag = $this->em->getRepository('BricksSiteBundle:Tag')->findOneBy(array(
                    'title' => $tagTitle
                ));
                
                // existing tag found: associate it to $brick
                if ($tag) {
                    $brickHasTag->setTag($tag);
                
                // create a new tag
                } else {
                    $tag = new Tag();
                    $tag->setTitle($tagTitle);
                }
            }
        }
        */
        
    }
}