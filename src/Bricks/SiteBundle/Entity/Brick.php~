<?php

namespace Bricks\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Eko\FeedBundle\Item\Writer\RoutedItemInterface;

/**
 * Bricks\SiteBundle\Entity\Brick
 *
 * @ORM\Table(name="brick")
 * @ORM\Entity(repositoryClass="Bricks\SiteBundle\Entity\BrickRepository")
 * 
 * @Gedmo\Loggable(logEntryClass="Bricks\SiteBundle\Entity\BrickLogEntry")
 */
class Brick implements RoutedItemInterface
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
     * @Gedmo\Versioned
     * @ORM\Column(type="text")
     */
    private $title;

    /**
     * @var text $description
     *
     * @Gedmo\Versioned
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var text $content
     *
     * @Gedmo\Versioned
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;
    
    /**
     * @var boolean $published
     *
     * @Gedmo\Versioned
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $published;

    /**
     * @var datetime $published_at
     *
     * @ORM\Column(name="published_at", type="datetime", nullable=true)
     */
    private $publishedAt;

    /**
     * @var text $canonicalUrl
     *
     * @Gedmo\Versioned
     * @ORM\Column(type="text", name="canonical_url", nullable=true)
     */
    private $canonicalUrl;

    /**
     * @var object $user
     *
     * @ORM\ManyToOne(targetEntity="Bricks\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="Bricks\SiteBundle\Entity\BrickHasTag", mappedBy="brick", cascade={"persist"})
     */
    public $brickHasTags;

    /**
     * @ORM\OneToMany(targetEntity="Bricks\SiteBundle\Entity\UserStarsBrick", mappedBy="user", cascade={"persist"})
     */
    private $userStarsBricks;

    /**
     * @var object $brickLicense
     *
     * @ORM\ManyToOne(targetEntity="Bricks\SiteBundle\Entity\BrickLicense")
     * @ORM\JoinColumn(name="brick_license_id", referencedColumnName="id", nullable=true)
     */
    private $brickLicense;

    /**
     * @var datetime $updated_at
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @var datetime $created_at
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    /**************************************************************************************************
     *	custom functions
    **************************************************************************************************/
    /**
     * return if the object is new by checking the field 'id'
     */
    public function isNew()
    {
        return !$this->getId();
    }

    /**
     * Set published
     *
     * @param boolean $published
     * @return Brick
     */
    public function setPublished($published)
    {
        $this->published = $published;

        if ($published == true) {
            $this->setPublishedAt(new \Datetime());
        }

        return $this;
    }

    /**
     * this method returns entity item title
     */
    public function getFeedItemTitle()
    {
        return $this->getTitle();
    }

    /**
     * this method returns entity item description (or content)
     */
    public function getFeedItemDescription()
    {
        return "A new brick has been published on SymfonyBricks.com: \"{$this->getTitle()}\"";
    }

    /**
     * this method returns entity item publication date
     */
    public function getFeedItemPubDate()
    {
        return $this->getPublishedAt();
    }

    /**
     * this method returns the name of the route
     */
    public function getFeedItemRouteName()
    {
        return 'brick_show';
    }

    /**
     * this method must return an array with the parameters that are required for the route
     */
    public function getFeedItemRouteParameters()
    {
        return array(
            'slug' => $this->getSlug()
        );
    }

    /**
     * this method returns the anchor that will be appended to the router-generated url. Note: can be an empty string
     */
    public function getFeedItemUrlAnchor()
    {
        return '';
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
        $this->userStarsBricks = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Brick
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
     * Set description
     *
     * @param string $description
     * @return Brick
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Brick
     */
    public function setContent($content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Brick
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
     * Get published
     *
     * @return boolean 
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set canonicalUrl
     *
     * @param string $canonicalUrl
     * @return Brick
     */
    public function setCanonicalUrl($canonicalUrl)
    {
        $this->canonicalUrl = $canonicalUrl;
    
        return $this;
    }

    /**
     * Get canonicalUrl
     *
     * @return string 
     */
    public function getCanonicalUrl()
    {
        return $this->canonicalUrl;
    }

    /**
     * Set updated_at
     *
     * @param \DateTime $updatedAt
     * @return Brick
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;
    
        return $this;
    }

    /**
     * Get updated_at
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Brick
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
    
        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set user
     *
     * @param \Bricks\UserBundle\Entity\User $user
     * @return Brick
     */
    public function setUser(\Bricks\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Bricks\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add brickHasTags
     *
     * @param \Bricks\SiteBundle\Entity\BrickHasTag $brickHasTags
     * @return Brick
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

    /**
     * Add userStarsBricks
     *
     * @param \Bricks\SiteBundle\Entity\UserStarsBrick $userStarsBricks
     * @return Brick
     */
    public function addUserStarsBrick(\Bricks\SiteBundle\Entity\UserStarsBrick $userStarsBricks)
    {
        $this->userStarsBricks[] = $userStarsBricks;
    
        return $this;
    }

    /**
     * Remove userStarsBricks
     *
     * @param \Bricks\SiteBundle\Entity\UserStarsBrick $userStarsBricks
     */
    public function removeUserStarsBrick(\Bricks\SiteBundle\Entity\UserStarsBrick $userStarsBricks)
    {
        $this->userStarsBricks->removeElement($userStarsBricks);
    }

    /**
     * Get userStarsBricks
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUserStarsBricks()
    {
        return $this->userStarsBricks;
    }

    /**
     * Set brickLicense
     *
     * @param \Bricks\SiteBundle\Entity\BrickLicense $brickLicense
     * @return Brick
     */
    public function setBrickLicense(\Bricks\SiteBundle\Entity\BrickLicense $brickLicense = null)
    {
        $this->brickLicense = $brickLicense;
    
        return $this;
    }

    /**
     * Get brickLicense
     *
     * @return \Bricks\SiteBundle\Entity\BrickLicense 
     */
    public function getBrickLicense()
    {
        return $this->brickLicense;
    }

    /**
     * Set publishedAt
     *
     * @param \DateTime $publishedAt
     * @return Brick
     */
    public function setPublishedAt($publishedAt)
    {
        $this->publishedAt = $publishedAt;
    
        return $this;
    }

    /**
     * Get publishedAt
     *
     * @return \DateTime 
     */
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }
}