<?php
namespace Bricks\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Bricks\SiteBundle\Entity\BrickHasTag
 * 
 * @ORM\Entity
 * @ORM\Table(name="brick_has_tag")
 * @ORM\Entity(repositoryClass="Bricks\SiteBundle\Entity\BrickHasTagRepository")
 */
class BrickHasTag
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var object $brick
     *
     * @ORM\ManyToOne(targetEntity="Bricks\SiteBundle\Entity\Brick", cascade={"persist"})
     * @ORM\JoinColumn(name="brick_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    private $brick;

    /**
     * @var object $tag
     *
     * @ORM\ManyToOne(targetEntity="Bricks\SiteBundle\Entity\Tag", cascade={"persist"})
     * @ORM\JoinColumn(name="tag_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    private $tag;
    
    /**************************************************************************************************
     *	custom functions
    **************************************************************************************************/
    
    /**************************************************************************************************
     *	getters and setters
    **************************************************************************************************/

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set brick
     *
     * @param \Bricks\SiteBundle\Entity\Brick $brick
     * @return BrickHasTag
     */
    public function setBrick(\Bricks\SiteBundle\Entity\Brick $brick = null)
    {
        $this->brick = $brick;
    
        return $this;
    }

    /**
     * Get brick
     *
     * @return \Bricks\SiteBundle\Entity\Brick 
     */
    public function getBrick()
    {
        return $this->brick;
    }

    /**
     * Set tag
     *
     * @param \Bricks\SiteBundle\Entity\Tag $tag
     * @return BrickHasTag
     */
    public function setTag(\Bricks\SiteBundle\Entity\Tag $tag = null)
    {
        $this->tag = $tag;
    
        return $this;
    }

    /**
     * Get tag
     *
     * @return \Bricks\SiteBundle\Entity\Tag 
     */
    public function getTag()
    {
        return $this->tag;
    }
}