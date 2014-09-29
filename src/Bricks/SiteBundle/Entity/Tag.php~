<?php

namespace Bricks\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use FPN\TagBundle\Entity\Tag as BaseTag;
use FPN\TagBundle\Entity\Tagging as BaseTagging;
/**
 * Bricks\SiteBundle\Entity\Tag
 *
 * @ORM\Entity(repositoryClass="Bricks\SiteBundle\Entity\TagRepository")
 * @ORM\Table(name="tag")
 */
class Tag extends BaseTag
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Bricks\SiteBundle\Entity\Tagging", mappedBy="tag", fetch="EAGER")
     */
    protected $tagging;
    
    /**************************************************************************************************
     *	custom functions
    **************************************************************************************************/
    public function __toString()
    {
        return $this->name;
    }

    /**
     * Constructor
     */
    public function __construct($name=null)
    {
        $this->tagging = new \Doctrine\Common\Collections\ArrayCollection();

        parent::__construct($name);
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
     * Add tagging
     *
     * @param \Bricks\SiteBundle\Entity\Tagging $tagging
     * @return Tag
     */
    public function addTagging(\Bricks\SiteBundle\Entity\Tagging $tagging)
    {
        $this->tagging[] = $tagging;

        return $this;
    }

    /**
     * Remove tagging
     *
     * @param \Bricks\SiteBundle\Entity\Tagging $tagging
     */
    public function removeTagging(BaseTagging $tagging)
    {
        $this->tagging->removeElement($tagging);
    }

    /**
     * Get tagging
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTagging()
    {
        return $this->tagging;
    }
    
    /**************************************************************************************************
     *	getters and setters
    **************************************************************************************************/
}
