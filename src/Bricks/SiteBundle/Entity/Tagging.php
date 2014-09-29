<?php

namespace Bricks\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Gedmo\Mapping\Annotation as Gedmo;
use FPN\TagBundle\Entity\Tagging as BaseTagging;
use DoctrineExtensions\Taggable\Entity\Tag as BaseTag;

/**
 * Bricks\SiteBundle\Entity\Tagging
 *
 * @ORM\Entity()
 * @ORM\Table(name="tagging", uniqueConstraints={@UniqueConstraint(name="tagging_idx", columns={"tag_id", "resource_type", "resource_id"})})
 */
class Tagging extends BaseTagging
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
     * @var object $user
     *
     * @ORM\ManyToOne(targetEntity="Bricks\SiteBundle\Entity\Tag")
     * @ORM\JoinColumn(name="tag_id", referencedColumnName="id")
     */
    protected $tag;

    /**************************************************************************************************
     *	custom functions
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
     * Set tag
     *
     * @param \Bricks\SiteBundle\Entity\Tag $tag
     * @return Tagging
     */
    public function setTag(BaseTag $tag = null)
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

    /**************************************************************************************************
     *	getters and setters
     **************************************************************************************************/
}
