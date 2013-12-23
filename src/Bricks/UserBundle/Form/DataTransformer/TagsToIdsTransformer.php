<?php
namespace Bricks\UserBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Bricks\SiteBundle\Entity\Brick;
use Bricks\SiteBundle\Entity\Tag;
use Bricks\SiteBundle\Entity\BrickHasTag;

/**
 * 
 */
class TagsToIdsTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;
    
    private $brick;

    /**
     * @param ObjectManager $om
     */
    public function __construct($om, Brick $brick)
    {
        $this->om = $om;
        $this->brick = $brick;
    }

    /**
     * Transform a collection of Tag objects into a string
     *
     * @param  DoctrineCollection|null $collection
     * @return string $output a string of Tag titles separated by ","
     */
    public function transform($collection)
    {
        if (null === $collection) {
            return "";
        }
        
        $output = '';
        
        foreach ($collection as $k => $item) {
            // add separator
            if ($k != 0) {
                $output .= ',';
            }
             // add tag title
            $output .= $item->getTag()->getTitle();
        }

        return $output;
    }

    /**
     * Transform a string (Tag ids separated by ",") to an array of BrickHasTag objects
     *
     * @param  string $tags
     * @return array|null
     */
    public function reverseTransform($tags)
    {
        if (!$tags) {
            return null;
        }
        
        // array of BrickHasTag objects
        $output = array();
        
        // explode the $tags string; tag titles separated by ","
        $array = explode(',', $tags);
        
        // iterate on tag titles
        foreach ($array as $tagTitle) {
            
            // title of the tag (lowered and trimmed)
            $tagTitle = strtolower(trim($tagTitle));
            
            // check if tag title is empty
            if ($tagTitle == '') {
                continue;
            }
            
            // try to find existing Tag; if not found, create a new one
            $tag = $this->om->getRepository('BricksSiteBundle:Tag')->findOneBy(array('title' => $tagTitle));
            
            // if not found, create a new Tag
            if (!$tag) {
                $tag = new Tag();
                $tag->setTitle($tagTitle);
            }
            
            // try to find existing BrickHasTag
            $brickHasTag = $this->om->getRepository('BricksSiteBundle:BrickHasTag')->findOneBy(array(
                'tag'    => $tag->getId(),
                'brick'  => $this->brick->getId()
            ));
            
            // if $brickHasTag is not found, create a new one
            if (!$brickHasTag) {
                $brickHasTag = new BrickHasTag();
                $brickHasTag->setTag($tag);
                $brickHasTag->setBrick($this->brick);
            }
            
            // add $brickHasTag to returning array
            $output[] = $brickHasTag;
        }
        
        return $output;
    }
}
