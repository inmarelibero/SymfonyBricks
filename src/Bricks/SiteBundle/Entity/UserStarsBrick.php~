<?php
namespace Bricks\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_stars_brick")
 * @ORM\Entity(repositoryClass="Bricks\SiteBundle\Entity\UserStarsBrickRepository")
 */
class UserStarsBrick
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

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

    /**
     * @var object $user
     *
     * @ORM\ManyToOne(targetEntity="Bricks\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    private $user;

    /**
     * @var object $brick
     *
     * @ORM\ManyToOne(targetEntity="Bricks\SiteBundle\Entity\Brick", cascade={"persist"})
     * @ORM\JoinColumn(name="brick_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    private $brick;
    
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
     * Set updated_at
     *
     * @param \DateTime $updatedAt
     * @return UserStarsBrick
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
     * @return UserStarsBrick
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
     * @param Bricks\UserBundle\Entity\User $user
     * @return UserStarsBrick
     */
    public function setUser(\Bricks\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return Bricks\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set brick
     *
     * @param Bricks\SiteBundle\Entity\Brick $brick
     * @return UserStarsBrick
     */
    public function setBrick(\Bricks\SiteBundle\Entity\Brick $brick = null)
    {
        $this->brick = $brick;
    
        return $this;
    }

    /**
     * Get brick
     *
     * @return Bricks\SiteBundle\Entity\Brick 
     */
    public function getBrick()
    {
        return $this->brick;
    }
}