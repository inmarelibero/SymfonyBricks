<?php
namespace Bricks\SiteBundle\Extension;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Bricks\SiteBundle\Entity\Brick;

/**
 * Twig extensions related to the Brick object
 */
class BrickExtension extends \Twig_Extension
{
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }
    
    public function getFunctions()
    {
        return array(
            'brick_formatted_tags' => new \Twig_Function_Method($this, 'brickFormattedTags')
        );
    }
    
    /**
     * print a string of Brick tag titles separated by $separator
     * 
     * @param Brick $brick Brick object
     * @param string $separator string to separate tag titles
     * @return string
     */
    public function brickFormattedTags(Brick $brick, $separator = '&nbsp;', $printIcons = true)
    {
        $output = '';
        
        // number of $brick->getBrickHasTags() array elements
        $brickHasTagsLength = count($brick->getTags());
        
        foreach ($brick->getTags() as $k => $tag) {
            // add tag title
            $output .= '<a href="' . $this->router->generate('brick_search', array('tag' => $tag->getName())) . '">';
            $output .=      ($printIcons) ? '<span class="glyphicon glyphicon-tag"></span>&nbsp;' : '';
            $output .=      htmlspecialchars($tag->getName(), ENT_QUOTES, 'UTF-8');
            $output .= '</a>';

            // if not last iteration
            if ($k < $brickHasTagsLength-1) {
                // add separator
                $output .= $separator;
            }
        }

        return $output;
    }

    public function getName()
    {
        return 'brick_extension';
    }
}
