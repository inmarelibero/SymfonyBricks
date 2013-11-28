<?php

namespace Bricks\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Bricks\SiteBundle\Entity\Tag
 *
 * @ORM\Entity(repositoryClass="Bricks\SiteBundle\Entity\TagRepository")
 * @ORM\Table(name="tag")
 */
class Tag
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var text $title
     *
     * @ORM\Column(type="text")
     */
    private $title;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;
    
    /**
     * @ORM\OneToMany(targetEntity="Bricks\SiteBundle\Entity\BrickHasTag", mappedBy="tag", cascade={"persist"})
     */
    private $brickHasTags;
    
    /**************************************************************************************************
     *	custom functions
    **************************************************************************************************/
    public function __toString()
    {
        return $this->title;
    }
    
    /**************************************************************************************************
     *	getters and setters
    **************************************************************************************************/
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->brickHasTags = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
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
     * Set title
     *
     * @param string $title
     * @return Tag
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Tag
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Add brickHasTags
     *
     * @param \Bricks\SiteBundle\Entity\BrickHasTag $brickHasTags
     * @return Tag
     */
    public function addBrickHasTag(\Bricks\SiteBundle\Entity\BrickHasTag $brickHasTags)
    {
        $this->brickHasTags[] = $brickHasTags;
    
        return $this;
    }

    /**
     * Remove brickHasTags
     *
     * @param \Bricks\SiteBundle\Entity\BrickHasTag $brickHasTags
     */
    public function removeBrickHasTag(\Bricks\SiteBundle\Entity\BrickHasTag $brickHasTags)
    {
        $this->brickHasTags->removeElement($brickHasTags);
    }

    /**
     * Get brickHasTags
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBrickHasTags()
    {
        return $this->brickHasTags;
    }
}