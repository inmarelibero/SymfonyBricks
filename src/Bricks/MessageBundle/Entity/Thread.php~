<?php
namespace Bricks\MessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use FOS\MessageBundle\Entity\Thread as BaseThread;
use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Model\MessageInterface;
use FOS\MessageBundle\Model\ThreadMetadata as ModelThreadMetadata;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="message_thread")
 */
class Thread extends BaseThread
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\generatedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Bricks\UserBundle\Entity\User")
     */
    protected $createdBy;

    /**
     * @ORM\OneToMany(targetEntity="Bricks\MessageBundle\Entity\Message", mappedBy="thread")
     */
    protected $messages;

    /**
     * @ORM\OneToMany(targetEntity="Bricks\MessageBundle\Entity\ThreadMetadata", mappedBy="thread", cascade={"all"})
     */
    protected $metadata;

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

    public function __construct()
    {
        parent::__construct();

        $this->messages = new ArrayCollection();
    }

    public function setCreatedBy(ParticipantInterface $participant) {
        $this->createdBy = $participant;
        return $this;
    }

    function addMessage(MessageInterface $message) {
        $this->messages->add($message);
    }
    public function getMessages()
    {
        return parent::getMessages();
    }

    public function addMetadata(ModelThreadMetadata $meta) {
        $meta->setThread($this);
        parent::addMetadata($meta);
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
     * Get createdBy
     *
     * @return \Bricks\UserBundle\Entity\User 
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Remove messages
     *
     * @param \Bricks\MessageBundle\Entity\Message $messages
     */
    public function removeMessage(\Bricks\MessageBundle\Entity\Message $messages)
    {
        $this->messages->removeElement($messages);
    }

    /**
     * Remove metadata
     *
     * @param \Bricks\MessageBundle\Entity\ThreadMetadata $metadata
     */
    public function removeMetadata(\Bricks\MessageBundle\Entity\ThreadMetadata $metadata)
    {
        $this->metadata->removeElement($metadata);
    }

    /**
     * Get metadata
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * Set brick
     *
     * @param \Bricks\SiteBundle\Entity\Brick $brick
     * @return Thread
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
}