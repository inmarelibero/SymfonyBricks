<?php

namespace Bricks\SiteBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use DoctrineExtensions\Taggable\Taggable;
use Gedmo\Mapping\Annotation as Gedmo;
use Eko\FeedBundle\Item\Writer\RoutedItemInterface;

use Bricks\SiteBundle\Model\Resource;

/**
 * Bricks\SiteBundle\Entity\ExternalResource
 *
 * @ORM\Entity(repositoryClass="Bricks\SiteBundle\Entity\ExternalResourceRepository")
 * @ORM\Table(name="external_resource")
 *
 * @Gedmo\Loggable(logEntryClass="Bricks\SiteBundle\Entity\ExternalResourceLogEntry")
 */
class ExternalResource implements RoutedItemInterface, Taggable, Resource
{
    private $tags;

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
     * @var text $url
     *
     * @Gedmo\Versioned
     * @ORM\Column(type="text", name="url", nullable=true)
     */
    private $url;

    /**
     * The owner of the resource (eg. a user that submits a link to his own blog)
     *
     * @var object $user
     *
     * @ORM\ManyToOne(targetEntity="Bricks\UserBundle\Entity\User", inversedBy="externalResources")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    private $user;

    /**
     * The user who reported
     *
     * @var object $user
     *
     * @ORM\ManyToOne(targetEntity="Bricks\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="reporter_id", referencedColumnName="id", nullable=true)
     */
    private $reporter;

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

        if ($published == true && $this->getPublishedAt() == null) {
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
        return "A new external resource has been published on SymfonyBricks.com: \"{$this->getTitle()}\"";
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
        return 'external_resource_show';
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

    public function getTags()
    {
        $this->tags = $this->tags ?: new ArrayCollection();

        return $this->tags;
    }

    public function getTaggableType()
    {
        return 'external_resource_tag';
    }

    public function getTaggableId()
    {
        return $this->getId();
    }

    /**
     * Return resource type
     *
     * @return mixed
     */
    public function getResourceType()
    {
        return self::TYPE_EXTERNAL_RESOURCE;
    }

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
     * Set title
     *
     * @param string $title
     * @return ExternalResource
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
     * @return ExternalResource
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
     * Set slug
     *
     * @param string $slug
     * @return ExternalResource
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
     * Set publishedAt
     *
     * @param \DateTime $publishedAt
     * @return ExternalResource
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

    /**
     * Set url
     *
     * @param string $url
     * @return ExternalResource
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set updated_at
     *
     * @param \DateTime $updatedAt
     * @return ExternalResource
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
     * @return ExternalResource
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
     * @return ExternalResource
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
     * Set reporter
     *
     * @param \Bricks\UserBundle\Entity\User $reporter
     * @return ExternalResource
     */
    public function setReporter(\Bricks\UserBundle\Entity\User $reporter = null)
    {
        $this->reporter = $reporter;

        return $this;
    }

    /**
     * Get reporter
     *
     * @return \Bricks\UserBundle\Entity\User 
     */
    public function getReporter()
    {
        return $this->reporter;
    }
}
